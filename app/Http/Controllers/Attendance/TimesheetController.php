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
use Carbon\Carbon;

use App\Http\Controllers\Employee\EmployeePreferencesController;
use App\Http\Controllers\Attendance\PunchController;

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
            'company_id' => 1,
            'employee_id' => $currentUser->id,
            'date' => '2024-12-11',
            'group_ids' => -1,
            'branch_ids' => -1,
            'department_ids' => -1,
        ]);

        $data = $this->getTimesheetData($filterData);

        return view('attendance.timesheet.index', [
            'payPeriod' => $data['payPeriod'],
            'filterData' => $filterData
        ]);
    }

    private function getTimesheetData($filterData)
    {
        $payPeriod = $this->getCurrentPayPeriod($filterData);
        $punchList = $this->getRenderingData($filterData);

        return [
            'payPeriod' => $payPeriod
        ];
    }

    private function getCurrentPayPeriod($filterData)
    {
        $employee_id = $filterData['employee_id'];
        $date = $filterData['date'];

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

    private function getRenderingData($filterData) {
        $epc = new EmployeePreferencesController();
        $empPrefs = $epc->getEmployeePreferencesByEmployeeId(Auth::user()->id);

        // Get the start day of the week, defaulting to Monday
        $startWeekDay = isset($empPrefs[0]->start_week_day) 
                            ? $empPrefs[0]->start_week_day 
                            : 1; // Default to 'Monday'

        // Prepare calendar array for 7 days
        $calendarArr = $this->getCalendarArray($filterData['date'], $startWeekDay);

        // Format the start and end dates to match the database date format (Y-m-d)
        $startDate = Carbon::parse($calendarArr[0])->format('Y-m-d');
        $endDate = Carbon::parse($calendarArr[6])->format('Y-m-d');

        // Fetch the punch list for the given week range
        $pc = new PunchController();
        $punchList = $pc->getPunchesByEmployeeIdAndStartDateAndEndDate($filterData['employee_id'], $startDate, $endDate);

        // check here
        print_r($punchList);
        exit;
    }

    /**
     * Generates a 7-day calendar array starting from the week start day
     *
     * @param string $currentDate The reference date (e.g., "2024-12-11")
     * @param int $startWeekDay The start of the week (1 = Monday, 7 = Sunday)
     * @return array Array of 7 dates for the intended week
     */
    private function getCalendarArray($currentDate, $startWeekDay) {
        // Convert current date to Carbon instance
        $currentDate = Carbon::parse($currentDate);
    
        // Get the current day of the week (1 = Monday, 7 = Sunday)
        $currentDayOfWeek = $currentDate->dayOfWeekIso; // dayOfWeekIso gives 1=Monday to 7=Sunday
    
        // Calculate how many days to subtract to get to the start of the week
        $daysToSubtract = ($currentDayOfWeek - $startWeekDay + 7) % 7;
        $weekStartDate = $currentDate->subDays($daysToSubtract); // Start of the desired week
    
        // Generate the 7-day array
        $calendarArr = [];
        for ($i = 0; $i < 7; $i++) {
            $calendarArr[] = $weekStartDate->copy()->addDays($i)->format('Y-m-d'); // Adding 0 to 6 days
        }
    
        return $calendarArr;
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