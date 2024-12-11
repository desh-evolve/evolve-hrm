<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Termwind\Components\Dd;

class TimeSheetController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view timesheet', ['only' => ['index']]);
        $this->middleware('permission:create timesheet', ['only' => ['getDropdownData']]);
        $this->middleware('permission:update timesheet', ['only' => ['']]);
        $this->middleware('permission:delete timesheet', ['only' => ['']]);

        $this->common = new CommonModel();
    }

    /*
    public function index()
    {

        return view('attendance.timesheet.index');
    }
    */

    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $filterData = $request->input('filter_data', [
            'employee_id' => $currentUser->id,
            'date' => '2024-12-11',
            'group_ids' => -1,
            'branch_ids' => -1,
            'department_ids' => -1,
        ]);


        $payPeriod = $this->getCurrentPayPeriod($filterData['employee_id'], $filterData['date']);
        $empPref = $this->getEmployeePreferences(Auth::user()->id);

        //$punches = $this->getTimesheetData($userId, $startDate, $endDate);
        //$payPeriod = $this->getCurrentPayPeriod($filterData['employee_id'], $filterData['date']);
        //$users = $this->getUserData($filterData['employee_id'], $currentUser->company_id);

        return view('attendance.timesheet.index', compact('payPeriod', 'empPref', 'filterData'));
    }

    private function getCurrentPayPeriod($employee_id, $date)
    {
        $fields = ['*'];
        $joinArr = [
            'pay_period_schedule' => ['pay_period_schedule.id', '=', 'pay_period.pay_period_schedule_id'],
            'pay_period_schedule_employee' => ['pay_period_schedule_employee.pay_period_schedule_id', '=', 'pay_period_schedule.id']
        ];

        $whereArr = [
            ['DATE(pay_period.start_date)', '<=', '"'.$date.'"'],
            ['DATE(pay_period.end_date)', '>=', '"'.$date.'"'],
            ['pay_period_schedule_employee.employee_id', '=', $employee_id],
            ['pay_period_schedule.status', '=', '"active"'],
        ];

        // Fetch the pay period data
        $pp = $this->common->commonGetAll('pay_period', $fields, $joinArr, $whereArr, true);

        // Return the result (can be empty if no records are found)
        return $pp;
    }

    private function getEmployeePreferences($employee_id){
        $ep = $this->common->commonGetById($employee_id, 'employee_id', 'emp_preference', '*');

        return $ep;
    }

    public function getDropdownData(){
        $branches = $this->common->commonGetAll('com_branches', '*');

        // Define connections to other tables
        $connections = [
            'com_branch_departments' => [
                'con_fields' => ['branch_id', 'department_id', 'branch_name'],  // Fields to select from connected table
                'con_where' => ['com_branch_departments.department_id' => 'id'],  // Link to the main table (department_id)
                'con_joins' => [
                    'com_branches' => ['com_branches.id', '=', 'com_branch_departments.branch_id'],
                ],
                'con_name' => 'branch_departments',  // Alias to store connected data in the result
                'except_deleted' => false,  // Filter out soft-deleted records
            ],
        ];
    
        // Fetch the department with connections
        $departments = $this->common->commonGetAll('com_departments', ['com_departments.*'], [], [], false, $connections);
        $employee_groups = $this->common->commonGetAll('com_employee_groups', '*');

        $employees = $this->common->commonGetAll('emp_employees', '*');

        return response()->json([
            'data' => [
                'branches' => $branches,
                'departments' => $departments,
                'employee_groups' => $employee_groups,
                'employees' => $employees, //should be filtered by hierarchy
            ]
        ], 200);
    }
    
}