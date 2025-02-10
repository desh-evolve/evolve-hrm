<?php

namespace App\Http\Controllers\Employee;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeDocumentsController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee document', ['only' => [
            'index',
            'getDocumentByEmpId',
            'getAllEmployeeDocument',
            'getdocumentDropdownData',
            'getSingleEmployeeDocument',
            ]]);

        $this->middleware('permission:create employee document', ['only' => ['createEmployeeDocument']]);
        $this->middleware('permission:update employee document', ['only' => ['updateEmployeeDocument']]);
        $this->middleware('permission:delete employee document', ['only' => ['deleteEmployeeDocument']]);

        $this->common = new CommonModel();
    }


    //pawanee(2024-11-08)
    public function index()
    {
        return view('employee_documents.index');
    }


    public function getdocumentDropdownData()
    {
        $users = $this->common->commonGetAll('emp_employees', '*');
        $doc_types = $this->common->commonGetAll('com_employee_doc_types', '*');
        return response()->json([
            'data' => [
                'users' => $users,
                'doc_types' => $doc_types,
            ]
        ], 200);
    }



    public function createEmployeeDocument(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // Validate the request
                $request->validate([
                    'user_id' => 'required|integer',
                    'doc_file' => 'required|array',
                    'doc_file.*' => 'required|file|mimes:pdf,doc,docx,txt,xls,xlsx,ppt,pptx|max:2048',
                    'doc_title' => 'required|array|min:1',
                    'doc_title.*' => 'required|string|max:255',
                    'doc_type_id' => 'required|array|min:1',
                    'doc_type_id.*' => 'required|integer',
                ]);

                // Initialize required variables
                $userId = $request->user_id;
                $uploadPath = 'uploads/employee/documents';
                $insertIds = [];

                // Check if there are uploaded files
                if ($request->hasFile('doc_file') && is_iterable($request->file('doc_file'))) {
                    foreach ($request->file('doc_file') as $key => $docFile) {
                        if (!isset($request->doc_type_id[$key]) || !isset($request->doc_title[$key])) {
                            continue;
                        }

                        // Upload document using the common model function
                        $uploadResponse = $this->common->uploadDocument($userId, $docFile, $uploadPath);

                        if ($uploadResponse['status'] !== 'success') {
                            Log::error("Document upload failed: " . $uploadResponse['message']);
                            continue;
                        }

                        // Prepare data for insertion
                        $inputArr = [
                            'user_id' => $userId,
                            'doc_type_id' => $request->doc_type_id[$key],
                            'title' => $request->doc_title[$key],
                            'file' => $uploadResponse['data']['fileName'],
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ];


                        $insertId = DB::table('emp_documents')->insertGetId($inputArr);

                        if ($insertId) {
                            $insertIds[] = $insertId;
                        }
                    }
                }


                if (!empty($insertIds)) {
                    return response()->json(['status' => 'success', 'message' => 'Documents added successfully', 'data' => ['ids' => $insertIds]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to add documents', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => [] ], 500);
        }
    }



    public function updateEmployeeDocument(Request $request, $documentId)
    {
        try {
            return DB::transaction(function () use ($request, $documentId) {
                // Convert values to expected types
                $request->merge([
                    'doc_type_id' => intval($request->doc_type_id),
                    'doc_title' => strval($request->doc_title)
                ]);

                // Validate the request
                $request->validate([
                    'doc_type_id' => 'required|integer',
                    'doc_title' => 'required|string|max:255',
                    'doc_file' => 'nullable|file|mimes:pdf,doc,docx,txt,xls,xlsx,ppt,pptx|max:2048',
                ]);

                // Fetch the existing document
                $document = DB::table('emp_documents')->where('id', $documentId)->first();

                if (!$document) {
                    return response()->json(['status' => 'error', 'message' => 'Document not found'], 404);
                }

                $updateData = [
                    'doc_type_id' => $request->doc_type_id,
                    'title' => $request->doc_title,
                    'updated_by' => Auth::user()->id,
                ];

                // Handle file upload if a new file is provided
                if ($request->hasFile('doc_file')) {
                    $uploadPath = 'uploads/employee/documents';

                    // Delete old file
                    if (!empty($document->file)) {
                        $oldFilePath = storage_path("app/public/$uploadPath/{$document->file}");
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }

                    // Upload new file
                    $uploadResponse = $this->common->uploadDocument($document->user_id, $request->file('doc_file'), $uploadPath);

                    if ($uploadResponse['status'] !== 'success') {
                        return response()->json(['status' => 'error', 'message' => 'File upload failed'], 500);
                    }

                    $updateData['file'] = $uploadResponse['data']['fileName'];
                }

                // Update the database record
                DB::table('emp_documents')->where('id', $documentId)->update($updateData);

                return response()->json(['status' => 'success', 'message' => 'Document updated successfully']);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }


    public function deleteEmployeeDocument($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Document';
        $table = 'emp_documents';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }



    public function downloadDocument($file)
    {
        $filePath = storage_path("app/public/uploads/employee/documents/" . $file);

        if (!file_exists($filePath)) {
            Log::error("File not found: " . $filePath);
            return response()->json(['status' => 'error', 'message' => 'File not found.'], 404);
        }

        return response()->download($filePath);
    }


    public function getDocumentByEmpId($id)
    {
        $idColumn = 'user_id';
        $table = 'emp_documents';
        $fields = ['emp_documents.*', 'name'];
        $joinArr = [
            'com_employee_doc_types'=>['com_employee_doc_types.id', '=', 'emp_documents.doc_type_id'],
        ];
        $documents = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr);

        return response()->json(['status' => 'success', 'data' => $documents], 200);
    }


    public function getSingleEmployeeDocument($id)
    {
        $idColumn = 'id';
        $table = 'emp_documents';
        $fields = '*';
        $user_document = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $user_document], 200);
    }


    public function getAllEmployeeDocument()
    {
        $table = 'emp_documents';
        $fields = '*';
        $user_document = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $user_document], 200);
    }

}
