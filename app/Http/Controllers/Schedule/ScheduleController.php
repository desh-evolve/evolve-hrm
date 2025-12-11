<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Company\BranchController;
use App\Http\Controllers\Company\DepartmentController;
use App\Http\Controllers\Company\EmployeeDesignationController;
use App\Http\Controllers\Company\EmployeeGroupController;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Core\Misc;
use App\Http\Controllers\Core\UserDateController;
use App\Http\Controllers\Employee\EmpWageController;
use App\Http\Controllers\Policy\AbsencePolicyController;
use App\Http\Controllers\UserController;

class ScheduleController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function getSearchByCompanyIdAndArrayCriteria($company_id, $filter_data, $limit = null, $page = null, $where = NULL, $order = NULL)
    {
        if ( $company_id == '') {
			return FALSE;
		}

        if ( !is_array($order) ) {
			//Use Filter Data ordering if its set.
			if ( isset($filter_data['sort_column']) AND $filter_data['sort_order']) {
				$order = array(Misc::trimSortPrefix($filter_data['sort_column']) => $filter_data['sort_order']);
			}
		}

        $additional_order_fields = array('pay_period_id', 'user_id', 'last_name');

		$sort_column_aliases = array(
            'pay_period' => 'udf.pay_period',
            'user_id' => 'udf.user_id',
            'status' => 'a.status',
            'last_name' => 'uf.last_name',
            'first_name' => 'uf.first_name',
        );

        $order = $this->getColumnsFromAliases( $order, $sort_column_aliases );
		if ( $order == NULL ) {
			$order = array( 'uf.last_name' => 'asc', 'a.start_time' => 'asc' );
		}

        if ( isset($filter_data['exclude_user_ids']) ) {
			$filter_data['exclude_id'] = $filter_data['exclude_user_ids'];
		}
		if ( isset($filter_data['include_user_ids']) ) {
			$filter_data['id'] = $filter_data['include_user_ids'];
		}
		/*if ( isset($filter_data['user_status']) ) {
			$filter_data['user_status'] = $filter_data['user_status'];
		}*/
		if ( isset($filter_data['user_title_ids']) ) {
			$filter_data['title_id'] = $filter_data['user_title_ids'];
		}
		if ( isset($filter_data['group_ids']) ) {
			$filter_data['group_id'] = $filter_data['group_ids'];
		}
		if ( isset($filter_data['default_branch_ids']) ) {
			$filter_data['default_branch_id'] = $filter_data['default_branch_ids'];
		}
		if ( isset($filter_data['default_department_ids']) ) {
			$filter_data['default_department_id'] = $filter_data['default_department_ids'];
		}
		/*if ( isset($filter_data['status']) ) {
			$filter_data['status'] = $filter_data['status'];
		}*/
		if ( isset($filter_data['branch_ids']) ) {
			$filter_data['schedule_branch_id'] = $filter_data['branch_ids'];
		}
		if ( isset($filter_data['department_ids']) ) {
			$filter_data['schedule_department_id'] = $filter_data['department_ids'];
		}
		if ( isset($filter_data['schedule_branch_ids']) ) {
			$filter_data['schedule_branch_id'] = $filter_data['schedule_branch_ids'];
		}
		if ( isset($filter_data['schedule_department_ids']) ) {
			$filter_data['schedule_department_id'] = $filter_data['schedule_department_ids'];
		}

		if ( isset($filter_data['exclude_job_ids']) ) {
			$filter_data['exclude_id'] = $filter_data['exclude_job_ids'];
		}
		if ( isset($filter_data['include_job_ids']) ) {
			$filter_data['include_job_id'] = $filter_data['include_job_ids'];
		}
		if ( isset($filter_data['job_group_ids']) ) {
			$filter_data['job_group_id'] = $filter_data['job_group_ids'];
		}
		if ( isset($filter_data['job_item_ids']) ) {
			$filter_data['job_item_id'] = $filter_data['job_item_ids'];
		}

        $ph = array(
            'company_id' => $company_id,
        );

        $query = '
            select
                a.id as id,
                a.id as schedule_id,
                a.status as status,
                a.start_time as start_time,
                a.end_time as end_time,

                a.user_date_id as user_date_id,
                a.branch_id as branch_id,
                bfb.branch_name as branch,
                a.department_id as department_id,
                dfb.department_name as department,
                a.total_time as total_time,
                a.schedule_policy_id as schedule_policy_id,
                a.absence_policy_id as absence_policy_id,
                apf.type as absence_policy_type_id,

                bf.branch_name as default_branch,
                df.department_name as default_department,
                ugf.emp_group_name as "group",
                utf.emp_designation_name as title,

                udf.user_id as user_id,
                udf.date_stamp as date_stamp,
                udf.pay_period_id as pay_period_id,

                uf.first_name as first_name,
                uf.last_name as last_name,
                uf.default_branch_id as default_branch_id,
                uf.default_department_id as default_department_id,
                uf.designation_id as title_id,
                uf.employee_group_id as group_id,
                uf.created_by as user_created_by,

                uwf.id as user_wage_id,
                uwf.hourly_rate as user_wage_hourly_rate,
                uwf.effective_date as user_wage_effective_date 
        ';
        
        $query .= '
            from schedule as a
                LEFT JOIN user_date as udf ON a.user_date_id = udf.id
                LEFT JOIN emp_employees as uf ON udf.user_id = uf.user_id
                LEFT JOIN com_branches as bf ON ( uf.default_branch_id = bf.id AND bf.status != "delete")
                LEFT JOIN com_branches as bfb ON ( a.branch_id = bfb.id AND bfb.status != "delete")
                LEFT JOIN com_departments as df ON ( uf.default_department_id = df.id AND df.status != "delete")
                LEFT JOIN com_departments as dfb ON ( a.department_id = dfb.id AND dfb.status != "delete")
                LEFT JOIN com_employee_groups as ugf ON ( uf.employee_group_id = ugf.id AND ugf.status != "delete" )
                LEFT JOIN com_employee_designations as utf ON ( uf.designation_id = utf.id AND utf.status != "delete" )
                LEFT JOIN absence_policy as apf ON a.absence_policy_id = apf.id
                LEFT JOIN emp_wage as uwf ON uwf.id = (select z.id
                    from emp_wage as z
                    where z.user_id = udf.user_id
                        and z.effective_date <= udf.date_stamp
                        and z.status != "delete"
                        order by z.effective_date desc limit 1)
		';

        $query .= '	WHERE uf.company_id = :company_id';
        
        if (isset($filter_data['permission_children_ids']) && !empty($filter_data['permission_children_ids']) && !in_array(-1, (array)$filter_data['permission_children_ids'])) {
            $query .= ' AND uf.id IN (' . implode(',', array_map('intval', (array)$filter_data['permission_children_ids'])) . ') ';
        }
        if (isset($filter_data['id']) && !empty($filter_data['id']) && !in_array(-1, (array)$filter_data['id'])) {
            $query .= ' AND uf.id IN (' . implode(',', array_map('intval', (array)$filter_data['id'])) . ') ';
        }
        if (isset($filter_data['exclude_id']) && !empty($filter_data['exclude_id']) && !in_array(-1, (array)$filter_data['exclude_id'])) {
            $query .= ' AND uf.id NOT IN (' . implode(',', array_map('intval', (array)$filter_data['exclude_id'])) . ') ';
        }
        if (isset($filter_data['user_status']) && !empty($filter_data['user_status']) && !in_array(-1, (array)$filter_data['user_status'])) {
            $query .= ' AND uf.status IN (' . implode(',', array_map('intval', (array)$filter_data['user_status'])) . ') ';
        }

        if (isset($filter_data['group_id']) && !empty($filter_data['group_id']) && !in_array(-1, (array)$filter_data['group_id'])) {
            /*
            if (isset($filter_data['include_subgroups']) && (bool)$filter_data['include_subgroups'] === true) {
                $filter_data['group_id'] = $ugf->getByCompanyIdAndGroupIdAndSubGroupsArray($company_id, $filter_data['group_id'], true);
            }
            */
            $query .= ' AND uf.group_id IN (' . implode(',', array_map('intval', (array)$filter_data['group_id'])) . ') ';
        }
        
        if (isset($filter_data['default_branch_id']) && !empty($filter_data['default_branch_id']) && !in_array(-1, (array)$filter_data['default_branch_id'])) {
            $query .= ' AND uf.default_branch_id IN (' . implode(',', array_map('intval', (array)$filter_data['default_branch_id'])) . ') ';
        }
        if (isset($filter_data['default_department_id']) && !empty($filter_data['default_department_id']) && !in_array(-1, (array)$filter_data['default_department_id'])) {
            $query .= ' AND uf.default_department_id IN (' . implode(',', array_map('intval', (array)$filter_data['default_department_id'])) . ') ';
        }
        if (isset($filter_data['title_id']) && !empty($filter_data['title_id']) && !in_array(-1, (array)$filter_data['title_id'])) {
            $query .= ' AND uf.title_id IN (' . implode(',', array_map('intval', (array)$filter_data['title_id'])) . ') ';
        }
        if (isset($filter_data['schedule_branch_id']) && !empty($filter_data['schedule_branch_id']) && !in_array(-1, (array)$filter_data['schedule_branch_id'])) {
            $query .= ' AND a.branch_id IN (' . implode(',', array_map('intval', (array)$filter_data['schedule_branch_id'])) . ') ';
        }
        if (isset($filter_data['schedule_department_id']) && !empty($filter_data['schedule_department_id']) && !in_array(-1, (array)$filter_data['schedule_department_id'])) {
            $query .= ' AND a.department_id IN (' . implode(',', array_map('intval', (array)$filter_data['schedule_department_id'])) . ') ';
        }
        if (isset($filter_data['status_id']) && !empty($filter_data['status_id']) && !in_array(-1, (array)$filter_data['status_id'])) {
            $query .= ' AND a.status_id IN (' . implode(',', array_map('intval', (array)$filter_data['status_id'])) . ') ';
        }
        if (isset($filter_data['schedule_policy_id']) && !empty($filter_data['schedule_policy_id']) && !in_array(-1, (array)$filter_data['schedule_policy_id'])) {
            $query .= ' AND a.schedule_policy_id IN (' . implode(',', array_map('intval', (array)$filter_data['schedule_policy_id'])) . ') ';
        }
        if (isset($filter_data['pay_period_ids']) && !empty($filter_data['pay_period_ids']) && !in_array(-1, (array)$filter_data['pay_period_ids'])) {
            $query .= ' AND udf.pay_period_id IN (' . implode(',', array_map('intval', (array)$filter_data['pay_period_ids'])) . ') ';
        }
        
        // jobs part removes from here
        
        if (!empty($filter_data['start_date'])) {
            $start_date = $filter_data['start_date'];
            $query .= " AND a.start_time >= '{$start_date}'";
        }
        
        if (!empty($filter_data['end_date'])) {
            $end_date = $filter_data['end_date'];
            $query .= " AND a.start_time <= '{$end_date}'";
        }

        $query .= 	' AND (a.status != "delete" AND udf.status != "delete" AND uf.status != "delete")';

        // Apply additional filters if $where conditions exist
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $query .= " AND {$key} = {$value}";
            }
        }

        // Append order conditions to the query
        $query .= " ORDER BY ";

        $fields = [];

        foreach ($additional_order_fields as $column) {
            $fields[] = "{$column} ASC";
        }

        if (!empty($order)) {
            foreach ($order as $column => $direction) {
                $fields[] = "{$column} {$direction}";
            }
        }

        $query .= implode(", ", $fields);

        if ($limit > 0) {
            $query .= " LIMIT :limit OFFSET :offset";
            $ph['limit'] = $limit;
            $ph['offset'] = ($page - 1) * $limit;
        }

        //print_r($query);exit;
        return DB::select($query, $ph);
    }
}
?>