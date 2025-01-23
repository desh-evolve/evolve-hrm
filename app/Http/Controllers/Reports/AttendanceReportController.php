<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class AttendanceReportController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view attendance report', ['only' => [
            'index',
            'form',
            'getDropdownData',
            'getAllEmployeeDetail',
        ]]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('reports.attendance_report.filter');
    }

    public function form(Request $request)
    {
        // Get the 'data' query parameter
        $dataList = json_decode($request->query('data'), true);

        // Pass it to the view
        return view('reports.attendance_report.report', compact('dataList'));
    }

    public function getDropdownData()
    {

        $com_employee_groups = $this->common->commonGetAll('com_employee_groups', '*');
        $com_branches = $this->common->commonGetAll('com_branches', '*');
        $com_departments = $this->common->commonGetAll('com_departments', '*');
        $com_user_designations = $this->common->commonGetAll('com_user_designations', '*');
        $users = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);

        //type => create table
        $emp_status = [
            ['id' => 1, 'name' => 'Active', 'value' => 'active'],
            ['id' => 2, 'name' => 'Leave - Illness/Injury', 'value' => 'illness'],
            ['id' => 3, 'name' => 'Leave - Maternity/Parental', 'value' => 'maternity'],
            ['id' => 4, 'name' => 'Leave - Other', 'value' => 'other'],
            ['id' => 5, 'name' => 'Terminated', 'value' => 'terminated'],
        ];

        return response()->json([
            'data' => [
                'users' => $users,
                'emp_status' => $emp_status,
                'com_employee_groups' => $com_employee_groups,
                'com_branches' => $com_branches,
                'com_departments' => $com_departments,
                'com_user_designations' => $com_user_designations,
            ]
        ], 200);
    }

    public function getReportData(Request $request)
    {
        // dd($request);
        // Extract parameters and process them if necessary
        $excludeUserIds = $request->exclude_user_ids ? explode(',', $request->exclude_user_ids) : [];
        $includeUserIds = $request->include_user_ids ? explode(',', $request->include_user_ids) : [];
        $userStatusIds = $request->user_status_ids ? explode(',', $request->user_status_ids) : [];
        $groupIds = $request->group_ids ? explode(',', $request->group_ids) : [];
        $branchIds = $request->branch_ids ? explode(',', $request->branch_ids) : [];
        $departmentsIds = $request->department_ids ? explode(',', $request->department_ids) : [];
        $userTitleIds = $request->user_title_ids ? explode(',', $request->user_title_ids) : [];


        $query  = DB::table('emp_employees')
        ->join('com_branch_department_users', 'emp_employees.user_id', '=', 'com_branch_department_users.user_id')
        ->join('loc_cities', 'emp_employees.city_id', '=', 'loc_cities.id')
        ->join('loc_provinces', 'emp_employees.province_id', '=', 'loc_provinces.id')
        ->select(
                'emp_employees.id as id',
                'emp_employees.user_id',
                'emp_employees.full_name as name',
                'emp_employees.first_name',
                'emp_employees.last_name',
                'emp_employees.nic',
                'emp_employees.title',
                'emp_employees.work_contact',
                'emp_employees.home_contact',
                'emp_employees.address_1',
                'emp_employees.city_id',
                'emp_employees.user_status',
                'emp_employees.province_id',
                'emp_employees.postal_code',
                'emp_employees.status',
                'loc_cities.city_name',
                'loc_provinces.province_name',
            )
            // ->where('emp_employees.status', $request->pay_stub_amendment_status)

            ->when(!empty($excludeUserIds), function ($query) use ($excludeUserIds) {
                return $query->whereNotIn('emp_employees.user_id', $excludeUserIds);
            })
            ->when(!empty($includeUserIds), function ($query) use ($includeUserIds) {
                return $query->whereIn('emp_employees.user_id', $includeUserIds);
            })
            ->when(!empty($userTitleIds), function ($query) use ($userTitleIds) {
                return $query->whereIn('emp_employees.title', $userTitleIds);
            })
            ->when(!empty($userStatusIds), function ($query) use ($userStatusIds) {
                return $query->whereIn('emp_employees.user_status', $userStatusIds);
            })
            ->when(!empty($groupIds), function ($query) use ($groupIds) {
                return $query->whereIn('emp_employees.user_group_id', $groupIds);
            })
            ->when(!empty($branchIds), function ($query) use ($branchIds) {
                return $query->whereIn('com_branch_department_users.branch_id', $branchIds);
            })
            ->when(!empty($departmentsIds), function ($query) use ($departmentsIds) {
                return $query->whereIn('com_branch_department_users.department_id', $departmentsIds);
            });

        $data = $query->get();
        return response()->json(['data' => $data], 200);
    }
}
