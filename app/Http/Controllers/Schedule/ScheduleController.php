<?php

namespace App\Http\Controllers\Schedule;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function getSearchByCompanyIdAndArrayCriteria($company_id, $filter_data, $limit = null, $page = null)
    {
        print_r('getSearchByCompanyIdAndArrayCriteria');exit;
        
        if (empty($company_id)) {
            return false;
        }

        $query = DB::table('schedule as a')
            ->select([
                'a.id as id', 'a.id as schedule_id', 'a.status', 'a.start_time', 'a.end_time',
                'a.user_date_id', 'a.branch_id', 'bfb.branch_name as branch', 'a.department_id',
                'dfb.department_name as department', 'a.total_time',
                'a.schedule_policy_id', 'a.absence_policy_id', 'apf.type as absence_policy_type_id',
                'bf.branch_name as default_branch', 'df.department_name as default_department', 'ugf.emp_group_name as `group`',
                'utf.emp_designation_name as title', 'udf.user_id', 'udf.date_stamp', 'udf.pay_period_id',
                'uf.first_name', 'uf.last_name', 'uf.default_branch_id', 'uf.default_department_id',
                'uf.title_id', 'uf.group_id', 'uf.created_by as user_created_by',
                'uwf.id as user_wage_id', 'uwf.hourly_rate as user_wage_hourly_rate',
                'uwf.effective_date as user_wage_effective_date'
            ])
            ->join('user_date as udf', 'a.user_date_id', '=', 'udf.id')
            ->join('emp_employees as uf', 'udf.user_id', '=', 'uf.user_id')
            ->leftJoin('com_branches as bf', 'uf.default_branch_id', '=', 'bf.id')
            ->leftJoin('com_branches as bfb', 'a.branch_id', '=', 'bfb.id')
            ->leftJoin('com_departments as df', 'uf.default_department_id', '=', 'df.id')
            ->leftJoin('com_departments as dfb', 'a.department_id', '=', 'dfb.id')
            ->leftJoin('com_employee_groups as ugf', 'uf.group_id', '=', 'ugf.id')
            ->leftJoin('com_employee_designations as utf', 'uf.title_id', '=', 'utf.id')
            ->leftJoin('absence_policy as apf', 'a.absence_policy_id', '=', 'apf.id')
            ->leftJoin('emp_wage as uwf', function ($join) {
                $join->on('uwf.user_id', '=', 'udf.user_id')
                    ->whereRaw('uwf.effective_date <= udf.date_stamp')
                    ->orderBy('uwf.effective_date', 'desc')
                    ->limit(1);
            })
            ->where('uf.company_id', $company_id)
            ->where('a.deleted', 0)
            ->where('udf.deleted', 0)
            ->where('uf.deleted', 0);

        // Apply filters
        if (!empty($filter_data['id'])) {
            $query->whereIn('uf.user_id', (array) $filter_data['id']);
        }
        if (!empty($filter_data['exclude_user_ids'])) {
            $query->whereNotIn('uf.user_id', (array) $filter_data['exclude_user_ids']);
        }
        if (!empty($filter_data['user_status_ids'])) {
            $query->whereIn('uf.status', (array) $filter_data['user_status_ids']);
        }
        if (!empty($filter_data['group_ids'])) {
            $query->whereIn('uf.group_id', (array) $filter_data['group_ids']);
        }
        if (!empty($filter_data['default_branch_ids'])) {
            $query->whereIn('uf.default_branch_id', (array) $filter_data['default_branch_ids']);
        }
        if (!empty($filter_data['default_department_ids'])) {
            $query->whereIn('uf.default_department_id', (array) $filter_data['default_department_ids']);
        }
        if (!empty($filter_data['schedule_branch_ids'])) {
            $query->whereIn('a.branch_id', (array) $filter_data['schedule_branch_ids']);
        }
        if (!empty($filter_data['schedule_department_ids'])) {
            $query->whereIn('a.department_id', (array) $filter_data['schedule_department_ids']);
        }
        if (!empty($filter_data['status_ids'])) {
            $query->whereIn('a.status', (array) $filter_data['status_ids']);
        }
        if (!empty($filter_data['pay_period_ids'])) {
            $query->whereIn('udf.pay_period_id', (array) $filter_data['pay_period_ids']);
        }
        if (!empty($filter_data['start_date'])) {
            $query->where('a.start_time', '>=', $filter_data['start_date']);
        }
        if (!empty($filter_data['end_date'])) {
            $query->where('a.start_time', '<=', $filter_data['end_date']);
        }

        // Sorting
        $orderByColumn = $filter_data['sort_column'] ?? 'uf.last_name';
        $orderByDirection = $filter_data['sort_order'] ?? 'asc';
        $query->orderBy($orderByColumn, $orderByDirection);
        $query->orderBy('a.start_time', 'asc');

        // Pagination
        if ($limit !== null) {
            return $query->paginate($limit, ['*'], 'page', $page);
        }

        return $query->get();
    }
}
?>