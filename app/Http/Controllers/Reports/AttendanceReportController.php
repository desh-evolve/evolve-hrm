<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommonModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceReportController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->common = new CommonModel();
    }
    public function index()
    {
        return view('reports.attendance_report.filter');
    }
    public function getDropdownData()
    {
        $com_employee_groups = $this->common->commonGetAll('com_employee_groups', ['id', 'emp_group_name AS name']);
        $com_branches = $this->common->commonGetAll('com_branches', ['id', 'branch_name AS name']);
        $com_departments = $this->common->commonGetAll('com_departments', ['id', 'department_name AS name']);

        $com_user_designations = $this->common->commonGetAll('com_user_designations',  ['id', 'emp_designation_name AS name']);
        $users = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);
        $pay_periods = DB::table('pay_period')
            ->select('id', DB::raw("CONCAT(DATE_FORMAT(start_date, '%d/%m/%Y'), ' -> ', DATE_FORMAT(end_date, '%d/%m/%Y')) AS name"))
            ->get();
        // var_dump('$pay_periods'.$pay_periods);

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
                'pay_periods' => $pay_periods,
            ]
        ], 200);
    }

    // public function getReportData(Request $request)
    // {
    //     $currentCompany = 1; // Assuming the current company is accessible via the authenticated user
    //     $filterData = $request->all();

    //     // Step 1: Get users based on company and filter criteria
    //     $users = $this->common->commonGetAll('users', '*', [], [
    //         'company_id' => 1,
    //         'id' => $filterData['user_id'] ?? null,
    //         // 'exclude_id' => $filterData['exclude_user_ids'] ?? null,
    //     ]);

    //     if (empty($users)) {
    //         return response()->json(['message' => 'No users found'], 404);
    //     }

    //     // Step 2: Handle pay period logic
    //     if (isset($filterData['date_type']) && $filterData['date_type'] == 'pay_period_ids') {
    //         unset($filterData['start_date'], $filterData['end_date']);
    //     } else {
    //         unset($filterData['pay_period_ids']);
    //     }

    //     // Step 3: Process pay period IDs
    //     if (isset($filterData['pay_period_ids'])) {
    //         $payPeriodIds = array_map(function ($id) {
    //             return trim($id, '-');
    //         }, $filterData['pay_period_ids']);
    //         $filterData['pay_period_ids'] = $payPeriodIds;
    //     }

    //     // Step 4: Get the greatest end date of selected pay periods
    //     $endDate = $this->getEndDate($filterData);

    //     // Step 5: Get user wages
    //     $userWages = $this->getUserWages($users->pluck('id')->toArray(), $endDate);

    //     // Step 6: Get user date totals
    //     $userDateTotals = $this->getUserDateTotals(1, $filterData);

    //     // Step 7: Get schedules
    //     $schedules = $this->getSchedules(1, $filterData);

    //     // Step 8: Get punches (if detailed timesheet is requested)
    //     $punches = [];
    //     if ($request->action == 'display_detailed_timesheet') {
    //         $punches = $this->getPunches(1, $filterData);
    //     }

    //     // Step 9: Get verified timesheets
    //     $verifiedTimeSheets = $this->getVerifiedTimeSheets(1, $filterData);

    //     // Step 10: Compile the final report data
    //     $reportData = $this->compileReportData($users, $userDateTotals, $schedules, $userWages, $verifiedTimeSheets, $punches);

    //     return response()->json(['data' => $reportData], 200);
    // }

    public function getReportData(Request $request)
    {
        try {
            $currentCompany = 1; // Replace with dynamic company ID if needed
            $filterData = $request->all();

            // Step 1: Get users based on company and filter criteria
            $users = $this->common->commonGetAll('emp_employees', '*', [], [
                'company_id' => $currentCompany,
                'id' => $filterData['include_user_ids'] ?? null,
                // 'exclude_id' => $filterData['exclude_user_ids'] ?? null,
            ]);

            if (empty($users)) {
                return response()->json(['message' => 'No users found'], 404);
            }

            // Step 2: Handle pay period logic
            if (isset($filterData['date_type']) && $filterData['date_type'] == 'pay_period') {
                unset($filterData['start_date'], $filterData['end_date']);
            } else {
                unset($filterData['pay_period_ids']);
            }
            if (isset($filterData['pay_period_ids'])) {
                // Ensure it's an array
                $payPeriodIds = is_array($filterData['pay_period_ids'])
                    ? $filterData['pay_period_ids']
                    : explode(',', $filterData['pay_period_ids']); // Convert string to array

                // Process pay period IDs
                $payPeriodIds = array_map(function ($id) {
                    return trim($id, '-');
                }, $payPeriodIds);

                $filterData['pay_period_ids'] = $payPeriodIds;
            }

            // Step 4: Get the greatest end date of selected pay periods
            $endDate = $this->getEndDate($filterData);


            // Step 5: Get user wages
            $userWages = $this->getUserWages($users->pluck('id')->toArray(), $endDate);
            // var_dump('g',$userWages);
            // Step 6: Get user date totals
            $userDateTotals = $this->getUserDateTotals($currentCompany, $filterData);

            // Step 7: Get schedules
            $schedules = $this->getSchedules($currentCompany, $filterData);

            // Step 8: Get punches (if detailed timesheet is requested)
            $punches = [];
            if ($request->action == 'display_detailed_timesheet') {
                $punches = $this->getPunches($currentCompany, $filterData);
            }

            // Step 9: Get verified timesheets
            $verifiedTimeSheets = $this->getVerifiedTimeSheets($currentCompany, $filterData);

            // Step 10: Compile the final report data
            $reportData = $this->compileReportData($users, $userDateTotals, $schedules, $userWages, $verifiedTimeSheets, $punches);

            return response()->json(['data' => $reportData], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    private function getEndDate(array $filterData)
    {
        if (isset($filterData['pay_period_ids']) && !empty($filterData['pay_period_ids'])) {
            if (in_array('-1', $filterData['pay_period_ids'])) {
                return Carbon::now()->timestamp;
            } else {
                // Use common to fetch pay periods
                $payPeriods = DB::table('pay_period')
                    ->whereIn('id', $filterData['pay_period_ids'])
                    ->get();

                // Get the maximum end date
                return collect($payPeriods)->max('end_date');
            }
        } else {
            return $filterData['end_date'];
        }
    }

    private function getUserWages(array $userIds, $endDate)
    {
        // string(19) "2025-02-28 10:00:43"
        return DB::table('user_wage')
            ->whereIn('user_id', $userIds)
            ->where('effective_date', '<=', $endDate)
            ->orderBy('effective_date', 'DESC')
            ->get();
    }

    private function getUserDateTotals($companyId, array $filterData)
    {

        $joinsArr = ['user_date' => ['user_date.id', '=', 'user_date_total.user_date_id']];

        $whereArr = [
            'user_date.user_id' => $filterData['include_user_ids'] ?? null,
            'user_date.pay_period_id' => $filterData['pay_period_ids'] ?? null
        ];
        return $this->common->commonGetAll('user_date_total', '*', $joinsArr, $whereArr);
    }

    private function getSchedules($companyId, array $filterData)
    {
        $joinsArr = ['user_date' => ['user_date.id', '=', 'schedule.user_date_id']];

        $whereArr = [
            'user_date.user_id' => $filterData['include_user_ids'] ?? null,
            'user_date.pay_period_id' => $filterData['pay_period_ids'] ?? null
        ];
        return $this->common->commonGetAll('schedule', '*', $joinsArr, $whereArr);
    }

    private function getPunches($companyId, array $filterData)
    {

        $joinsArr = ['user_date' => ['user_date.id', '=', 'punch.user_date_id']];

        $whereArr = [
            'user_date.user_id' => $filterData['include_user_ids'] ?? null,
            'user_date.pay_period_id' => $filterData['pay_period_ids'] ?? null
        ];
        return $this->common->commonGetAll('punch', '*', $joinsArr, $whereArr);

    }

    private function getVerifiedTimeSheets($companyId, array $filterData)
    {
        if (isset($filterData['pay_period_ids']) && !empty($filterData['pay_period_ids'])) {
            return $this->common->commonGetAll('pay_period_time_sheet_verify', '*', [], [
                'pay_period_id' => $filterData['pay_period_ids'],
            ]);
        }
        return [];
    }

    // private function compileReportData($users, $userDateTotals, $schedules, $userWages, $verifiedTimeSheets, $punches)
    // {
    //     $reportData = [];

    //     foreach ($users as $user) {
    //         $userData = [
    //             'user_id' => $user->id,
    //             'first_name' => $user->first_name,
    //             'last_name' => $user->last_name,
    //             'full_name' => $user->full_name,
    //             'employee_number' => $user->employee_number,
    //             'verified_time_sheet' => $this->getVerifiedTimeSheetStatus($user->id, $verifiedTimeSheets),
    //             'data' => [],
    //         ];

    //         // Add user date totals, schedules, and punches to the report data
    //         // (Logic for compiling this data can be added here)

    //         $reportData[] = $userData;
    //     }

    //     return $reportData;
    // }

    private function compileReportData($users, $userDateTotals, $schedules, $userWages, $verifiedTimeSheets, $punches)
    {
        $reportData = [];

        foreach ($users as $user) {
            $userData = [
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,
                'employee_number' => $user->employee_number,
                'verified_time_sheet' => $this->getVerifiedTimeSheetStatus($user->id, $verifiedTimeSheets),
                'data' => [],
            ];

            // Add user date totals
            $userData['data']['date_totals'] = $this->getUserDateTotalsForReport($user->id, $userDateTotals);

            // Add schedules
            $userData['data']['schedules'] = $this->getSchedulesForReport($user->id, $schedules);

            // Add punches (if detailed timesheet is requested)
            if (!empty($punches)) {
                $userData['data']['punches'] = $this->getPunchesForReport($user->id, $punches);
            }

            // Add user wages
            $userData['data']['wages'] = $this->getUserWagesForReport($user->id, $userWages);

            $reportData[] = $userData;
        }

        return $reportData;
    }
    private function getUserDateTotalsForReport($userId, $userDateTotals)
    {
        return collect($userDateTotals)
            ->where('user_id', $userId)
            ->map(function ($dateTotal) {
                return [
                    'date_stamp' => $dateTotal->date_stamp,
                    'total_time' => $dateTotal->total_time,
                    'status_id' => $dateTotal->status_id,
                    'type_id' => $dateTotal->type_id,
                ];
            })
            ->values()
            ->toArray();
    }
    private function getSchedulesForReport($userId, $schedules)
    {
        return collect($schedules)
            ->where('user_id', $userId)
            ->map(function ($schedule) {
                return [
                    'date_stamp' => $schedule->date_stamp,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'status' => $schedule->status,
                ];
            })
            ->values()
            ->toArray();
    }
    private function getPunchesForReport($userId, $punches)
    {
        return collect($punches)
            ->where('user_id', $userId)
            ->map(function ($punch) {
                return [
                    'date_stamp' => $punch->date_stamp,
                    'time_stamp' => $punch->time_stamp,
                    'status' => $punch->status,
                    'type' => $punch->type,
                ];
            })
            ->values()
            ->toArray();
    }
    private function getUserWagesForReport($userId, $userWages)
    {
        return collect($userWages)
            ->where('user_id', $userId)
            ->map(function ($wage) {
                return [
                    'effective_date' => $wage->effective_date,
                    'hourly_rate' => $wage->hourly_rate,
                ];
            })
            ->values()
            ->toArray();
    }
    private function getVerifiedTimeSheetStatus($userId, $verifiedTimeSheets)
    {
        if (isset($verifiedTimeSheets[$userId])) {
            $status = $verifiedTimeSheets[$userId]->first()->status;
            return $status == 50 ? 'Yes' : ($status == 30 || $status == 45 ? 'Pending' : 'Declined');
        }
        return 'No';
    }
}
