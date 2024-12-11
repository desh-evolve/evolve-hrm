<?php

namespace App\Http\Controllers\Attendance;

use App\Models\CommonModel;
use Illuminate\Http\Request;

class AttendanceRequestsController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view attendance requests', ['only' => [
            'index',
            'getAllAttendenceRequests',
            'getRequestsByControlId',
            'getEmployeeDropdownData',

        ]]);
        $this->middleware('permission:create attendance requests', ['only' => ['createAttendenceRequests']]);
        $this->middleware('permission:delete attendance requests', ['only' => ['deleteAttendenceRequests']]);

        $this->common = new CommonModel();
    }


    //pawanee(2024-12-09)
    public function index()
    {
        return view('attendance.requests.index');
    }


    //pawanee(2024-12-09)
    public function getRequestDropdownData(){
        $employees = $this->common->commonGetAll('emp_employees', '*');
        $types = $this->common->commonGetAll('message_types', '*');
        return response()->json([
            'data' => [
                'employees' => $employees,
                'types' => $types,
            ]
        ], 200);
    }




    //pawanee(2024-12-09)
    public function getAllAttendenceRequests()
    {
       //
    }


    //pawanee(2024-12-09)
    public function getRequestsByControlId($id)
    {
        $idColumn = 'employee_id';
        $table = 'request';
        $fields = ['request.*','branch_name', 'department_name', 'emp_designation_name'];
        $joinArr = [
            'com_branches'=>['com_branches.id', '=', 'emp_job_history.branch_id'],
            'com_departments'=>['com_departments.id', '=', 'emp_job_history.department_id'],
            'com_employee_designations'=>['com_employee_designations.id', '=', 'emp_job_history.designation_id'],

        ];
        $request = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr);
        return response()->json(['data' => $request], 200);
    }


    //pawanee(2024-12-09)
    public function createAttendenceRequests()
    {
       //
    }


    //pawanee(2024-12-09)
    public function deleteAttendenceRequests($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Attendance Request';
        $table = 'request';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }




}
