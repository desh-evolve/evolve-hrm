<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class ExceptionPolicyController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view exception policy', ['only' => ['index', 'getAllExceptionPolicies']]);
        $this->middleware('permission:create exception policy', ['only' => ['form', 'createExceptionPolicy', '']]);
        $this->middleware('permission:update exception policy', ['only' => ['form', 'updateExceptionPolicy', 'getExceptionPolicyById']]);
        $this->middleware('permission:delete exception policy', ['only' => ['deleteExceptionPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.exception.index');
    }

    public function form()
    {
        return view('policy.exception.form');
    }

    public function getAllExceptionPolicies(){
        $table = 'exception_policy_control';
        $connections = [
            'policy_group_policies' => [
                'con_fields' => ['*'],
                'con_where' => [
                    'policy_group_policies.policy_table' => $table,
                    'policy_group_policies.policy_id' => 'id',
                    'policy_group.status' => 'active',
                ],
                'con_joins' => [
                    'policy_group' => ['policy_group.id', '=', 'policy_group_policies.policy_group_id']
                ],
                'con_name' => 'policy_groups',
                'except_deleted' => false,
            ],
        ];
        $exceptions = $this->common->commonGetAll($table, '*', [], [], false, $connections);
        return response()->json(['data' => $exceptions], 200);
    }

    public function getExceptionPolicyById($id){
        $connections = [
            'exception_policy' => [
                'con_fields' => ['*'],  // Fields to select from connected table
                'con_where' => ['exception_policy.exception_policy_control_id' => 'id'],  // Link to the main table
                'con_joins' => [],
                'con_name' => 'exceptions',  // Alias to store connected data in the result
                'except_deleted' => true,  // Filter out soft-deleted records
            ],
        ];
        $exceptions = $this->common->commonGetById($id, 'id', 'exception_policy_control', '*', [], [], false, $connections);
        return response()->json(['data' => $exceptions], 200);
    }

    public function deleteExceptionPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Exception Policy';
        $table = 'exception_policy_control';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createExceptionPolicy(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                // Validate the request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'policy_data' => 'required|string',
                ]);

                $policyData = json_decode($request->policy_data, true);

                // Insert into exception_policy_control
                $controlData = [
                    'company_id' => 1, // Replace with dynamic company ID if necessary
                    'name' => $request->name,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                $exceptionPolicyControlId = $this->common->commonSave('exception_policy_control', $controlData);

                // Insert policy data into exception_policy
                foreach ($policyData as $pol) {
                    // Prepare the policy array with all the necessary fields
                    $policy = [
                        'exception_policy_control_id' => $exceptionPolicyControlId,
                        'type_id' => $pol['code'] ?? '',
                        'grace' => isset($pol['grace']) ? $pol['grace'] : null,
                        'watch_window' => isset($pol['watch_window']) ? $pol['watch_window'] : null,
                        'demerit' => isset($pol['demerit']) ? $pol['demerit'] : null,
                        'active' => isset($pol['active']) ? $pol['active'] : 0,
                        'severity' => isset($pol['severity']) ? $pol['severity'] : 'low',
                        'email_notification' => isset($pol['email_notification']) ? $pol['email_notification'] : 'both',
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id
                    ];

                    // Save the policy
                    $this->common->commonSave('exception_policy', $policy);

                }

                if($exceptionPolicyControlId){
                    return response()->json(['status' => 'success', 'message' => 'Exception policy created successfully', 'data' => ['id' => $exceptionPolicyControlId]], 200);
                }else{
                    return response()->json(['status' => 'error', 'message' => 'Something went wrong', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }


    public function updateExceptionPolicy(Request $request, $id)
    {

        try {
            return DB::transaction(function () use ($request, $id) {

                // Validate the request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'policy_data' => 'required|string',
                ]);

                $policyData = json_decode($request->policy_data, true);

                // Insert into exception_policy_control
                $controlData = [
                    'name' => $request->name,
                    'updated_by' => Auth::user()->id,
                ];
                $exceptionPolicyControlId = $this->common->commonSave('exception_policy_control', $controlData, $id, 'id');

                // Delete existing policies tied to this control ID before adding new ones
                DB::table('exception_policy')->where('exception_policy_control_id', $id)->delete();

                // Insert policy data into exception_policy
                foreach ($policyData as $pol) {
                    // Prepare the policy array with all the necessary fields
                    $policy = [
                        'exception_policy_control_id' => $id,
                        'type_id' => $pol['code'] ?? '',
                        'grace' => isset($pol['grace']) ? $pol['grace'] : null,
                        'watch_window' => isset($pol['watch_window']) ? $pol['watch_window'] : null,
                        'demerit' => isset($pol['demerit']) ? $pol['demerit'] : null,
                        'active' => isset($pol['active']) ? $pol['active'] : 0,
                        'severity' => isset($pol['severity']) ? $pol['severity'] : 'low',
                        'email_notification' => isset($pol['email_notification']) ? $pol['email_notification'] : 'both',
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id
                    ];

                    // Save the policy
                    $this->common->commonSave('exception_policy', $policy);

                }

                if($exceptionPolicyControlId){
                    return response()->json(['status' => 'success', 'message' => 'Exception policy updated successfully', 'data' => ['id' => $exceptionPolicyControlId]], 200);
                }else{
                    return response()->json(['status' => 'error', 'message' => 'Something went wrong', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }



}
