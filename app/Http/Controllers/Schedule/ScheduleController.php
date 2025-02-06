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
            'status_id' => 'a.status_id',
            'last_name' => 'uf.last_name',
            'first_name' => 'uf.first_name',
        );

        $order = $this->getColumnsFromAliases( $order, $sort_column_aliases );
		if ( $order == NULL ) {
			$order = array( 'uf.last_name' => 'asc', 'a.start_time' => 'asc' );
			$strict = FALSE;
		} else {
			$strict = TRUE;
		}

        if ( isset($filter_data['exclude_user_ids']) ) {
			$filter_data['exclude_id'] = $filter_data['exclude_user_ids'];
		}
		if ( isset($filter_data['include_user_ids']) ) {
			$filter_data['id'] = $filter_data['include_user_ids'];
		}
		if ( isset($filter_data['user_status_ids']) ) {
			$filter_data['user_status_id'] = $filter_data['user_status_ids'];
		}
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
		if ( isset($filter_data['status_ids']) ) {
			$filter_data['status_id'] = $filter_data['status_ids'];
		}
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

        $uf = new UserController();
		$udf = new UserDateController();
		$uwf = new EmpWageController();
		$apf = new AbsencePolicyController();
		$bf = new BranchController();
		$df = new DepartmentController();
		$ugf = new EmployeeGroupController();
		$utf = new EmployeeDesignationController();

        $ph = array(
            'company_id' => $company_id,
        );

        $query = '
            select
                a.id as id,
                a.id as schedule_id,
                a.status_id as status_id,
                a.start_time as start_time,
                a.end_time as end_time,

                a.user_date_id as user_date_id,
                a.branch_id as branch_id,
                bfb.name as branch,
                a.department_id as department_id,
                dfb.name as department,
                a.job_id as job_id,
                a.job_item_id as job_item_id,
                a.total_time as total_time,
                a.schedule_policy_id as schedule_policy_id,
                a.absence_policy_id as absence_policy_id,
                apf.type_id as absence_policy_type_id,

                bf.name as default_branch,
                df.name as default_department,
                ugf.name as "group",
                utf.name as title,

                udf.user_id as user_id,
                udf.date_stamp as date_stamp,
                udf.pay_period_id as pay_period_id,

                uf.first_name as first_name,
                uf.last_name as last_name,
                uf.default_branch_id as default_branch_id,
                uf.default_department_id as default_department_id,
                uf.title_id as title_id,
                uf.group_id as group_id,
                uf.created_by as user_created_by,

                uwf.id as user_wage_id,
                uwf.hourly_rate as user_wage_hourly_rate,
                uwf.effective_date as user_wage_effective_date 
        ';
        //check here
        $query .= '
            from schedule as a
                LEFT JOIN user_date as udf ON a.user_date_id = udf.id
                LEFT JOIN emp_employees as uf ON udf.user_id = uf.user_id
                LEFT JOIN com_branches as bf ON ( uf.default_branch_id = bf.id AND bf.deleted = 0)
                LEFT JOIN com_branches as bfb ON ( a.branch_id = bfb.id AND bfb.deleted = 0)
                LEFT JOIN com_departments as df ON ( uf.default_department_id = df.id AND df.deleted = 0)
                LEFT JOIN com_departments as dfb ON ( a.department_id = dfb.id AND dfb.deleted = 0)
                LEFT JOIN com_employee_groups as ugf ON ( uf.group_id = ugf.id AND ugf.deleted = 0 )
                LEFT JOIN com_employee_designations as utf ON ( uf.title_id = utf.id AND utf.deleted = 0 )
                LEFT JOIN absence_policy as apf ON a.absence_policy_id = apf.id
                LEFT JOIN emp_wage as uwf ON uwf.id = (select z.id
                    from emp_wage as z
                    where z.user_id = udf.user_id
                        and z.effective_date <= udf.date_stamp
                        and z.deleted = 0
                        order by z.effective_date desc limit 1)
		';

        $query .= '	WHERE uf.company_id = ?';
        
        print_r('getSearchByCompanyIdAndArrayCriteria');exit;
    }
}
?>