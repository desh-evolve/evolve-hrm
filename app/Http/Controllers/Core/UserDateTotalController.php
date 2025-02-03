<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class UserDateTotalController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function getWorkedUsersByPayPeriodId( $pay_period_id ){
        if ( $pay_period_id == '' ) {
			return FALSE;
		}

        $table = 'user_date_total';
        $fields = [DB::raw('count(distinct(user_date.user_id)) as count')];
        $joinArr = [
            'user_date' => ['user_date.id', '=', 'user_date_total.user_date_id']
        ];

        $whereArr = [
            ['user_date.pay_period_id', '=', $pay_period_id],
            ['user_date_total.status', '=', '"worked"'],
            ['user_date_total.total_time', '>', 0],
            ['user_date.status', '!=', '"delete"'],
        ];

        $exceptDel = true;
        $connections = [];
        $groupBy = null;
        $orderBy = null;

        $total = $this->common->commonGetAll( $table, $fields, $joinArr, $whereArr, $exceptDel, $connections, $groupBy, $orderBy );
        
        $total = $total[0]->count;

		if ($total === FALSE ) {
			$total = 0;
		}

        return $total;
    }
    
    public function getDayReportByCompanyIdAndArrayCriteria($company_id, $filter_data){
        print_r('getDayReportByCompanyIdAndArrayCriteria');exit;
        
        $order = [
            'tmp.pay_period_id' => 'asc',
            'z.last_name' => 'asc',
            'tmp.date_stamp' => 'asc',
        ];

        // Normalize filter data keys
        if ( isset($filter_data['punch_branch_ids']) ) {
			$filter_data['punch_branch_id'] = $filter_data['punch_branch_ids'];
		}
		if ( isset($filter_data['punch_department_ids']) ) {
			$filter_data['punch_department_id'] = $filter_data['punch_department_ids'];
		}

		if ( isset($filter_data['branch_ids']) ) {
			$filter_data['branch_id'] = $filter_data['branch_ids'];
		}
		if ( isset($filter_data['department_ids']) ) {
			$filter_data['department_id'] = $filter_data['department_ids'];
		}

        $ph = [$company_id]; // Store parameter bindings

        // Base Query
        $query = "
            SELECT z.user_id as id, tmp.*
            FROM emp_employees AS z
            LEFT JOIN (
                SELECT 
                    b.user_id,
                    b.pay_period_id,
                    b.date_stamp,
                    a.branch_id,
                    a.department_id,
                    a.status,
                    a.type,
                    a.over_time_policy_id,
                    n.id AS over_time_policy_wage_id,
                    n.effective_date AS over_time_policy_wage_effective_date,
                    a.absence_policy_id,
                    p.id AS absence_policy_wage_id,
                    p.effective_date AS absence_policy_wage_effective_date,
                    a.premium_policy_id,
                    r.id AS premium_policy_wage_id,
                    r.effective_date AS premium_policy_wage_effective_date,
                    z.user_id AS user_wage_id,
                    z.effective_date AS user_wage_effective_date,
                    tmp2.min_punch_time_stamp,
                    tmp2.max_punch_time_stamp,
                    SUM(total_time) AS total_time,
                    SUM(actual_total_time) AS actual_total_time
                FROM user_date_total AS a
                LEFT JOIN user_date AS b ON a.user_date_id = b.id
                LEFT JOIN overtime_policy AS m ON a.over_time_policy_id = m.id
                LEFT JOIN emp_wage AS n ON n.id = (
                    SELECT n.id FROM emp_wage AS n
                    WHERE n.user_id = b.user_id
                    AND n.wage_group_id = m.wage_group_id
                    AND n.effective_date <= b.date_stamp
                    AND n.status != 'delete'
                    ORDER BY n.effective_date DESC LIMIT 1
                )
                LEFT JOIN absence_policy AS o ON a.absence_policy_id = o.id
                LEFT JOIN emp_wage AS p ON p.id = (
                    SELECT p.id FROM emp_wage AS p
                    WHERE p.user_id = b.user_id
                    AND p.wage_group_id = o.wage_group_id
                    AND p.effective_date <= b.date_stamp
                    AND p.status != 'delete'
                    ORDER BY p.effective_date DESC LIMIT 1
                )
                LEFT JOIN premium_policy AS q ON a.premium_policy_id = q.id
                LEFT JOIN emp_wage AS r ON r.id = (
                    SELECT r.id FROM emp_wage AS r
                    WHERE r.user_id = b.user_id
                    AND r.wage_group_id = q.wage_group_id
                    AND r.effective_date <= b.date_stamp
                    AND r.status != 'delete'
                    ORDER BY r.effective_date DESC LIMIT 1
                )
                LEFT JOIN emp_wage AS z ON z.user_id = (
                    SELECT z.user_id FROM emp_wage AS z
                    WHERE z.user_id = b.user_id
                    AND z.effective_date <= b.date_stamp
                    AND z.wage_group_id = 0
                    AND z.status != 'delete'
                    ORDER BY z.effective_date DESC LIMIT 1
                )
                LEFT JOIN (
                    SELECT tmp3.id, MIN(tmp3.min_punch_time_stamp) AS min_punch_time_stamp, 
                        MAX(tmp3.max_punch_time_stamp) AS max_punch_time_stamp 
                    FROM (
                        SELECT tmp2_a.id,
                            CASE WHEN tmp2_c.punch_status = 'in' THEN MIN(tmp2_c.time_stamp) ELSE NULL END AS min_punch_time_stamp,
                            CASE WHEN tmp2_c.punch_status = 'out' THEN MAX(tmp2_c.time_stamp) ELSE NULL END AS max_punch_time_stamp
                        FROM user_date AS tmp2_a
                        LEFT JOIN punch_control AS tmp2_b ON tmp2_a.id = tmp2_b.user_date_id
                        LEFT JOIN punch AS tmp2_c ON tmp2_b.id = tmp2_c.punch_control_id
                        WHERE 1=1 ";

        // Dynamic Filters for Subquery
        if (!empty($filter_data['user_id']) && !in_array(-1, (array)$filter_data['user_id'])) {
            // Ensure user IDs are integers to prevent SQL injection
            $userIds = array_map('intval', (array)$filter_data['user_id']);
            
            // Create a comma-separated list of user IDs
            $query .= " AND tmp2_a.user_id IN (" . implode(',', $userIds) . ") ";
        }

        if (!empty($filter_data['pay_period_ids']) && !in_array(-1, (array)$filter_data['pay_period_ids'])) {
            // Ensure pay period IDs are integers to prevent SQL injection
            $payPeriodIds = array_map('intval', (array)$filter_data['pay_period_ids']);
            
            // Create a comma-separated list of pay period IDs
            $query .= " AND tmp2_a.pay_period_id IN (" . implode(',', $payPeriodIds) . ") ";
        }

        if (!empty($filter_data['start_date'])) {
            $query .= " AND tmp2_a.date_stamp >= ".$filter_data['start_date'];
        }

        if (!empty($filter_data['end_date'])) {
            $query .= " AND tmp2_a.date_stamp <= ".$filter_data['end_date'];
        }

        $query .= "
                        AND tmp2_c.time_stamp IS NOT NULL
                        AND (tmp2_a.status != 'delete' AND tmp2_b.status != 'delete' AND tmp2_c.punch_status != 'delete')
                        GROUP BY tmp2_a.id, tmp2_c.punch_status
                    ) AS tmp3 GROUP BY tmp3.id
                ) AS tmp2 ON b.id = tmp2.id
                WHERE 1=1 ";

        // More Filters (User ID, Pay Period, Branch, Department)
        $filterKeys = ['user_id', 'pay_period_id', 'punch_branch_id', 'punch_department_id'];

        foreach ($filterKeys as $key) {
            if (!empty($filter_data[$key]) && !in_array(-1, (array)$filter_data[$key])) {
                // Ensure values are integers to prevent SQL injection
                $values = array_map('intval', (array)$filter_data[$key]);
                
                // Create a comma-separated string of values (e.g., 1,2,3)
                $placeholders = implode(',', $values);

                // Append condition to query directly (without placeholders)
                $query .= " AND b.{$key} IN ($placeholders) ";
            }
        }


        // Date Filters
        if (!empty($filter_data['start_date'])) {
            $query .= " AND b.date_stamp >= ".$filter_data['start_date'];
        }
        if (!empty($filter_data['end_date'])) {
            $query .= " AND b.date_stamp <= ".$filter_data['end_date'];
        }

        // Final Conditions
        $query .= "
                AND a.status IN ('system','worked','absence')
                AND (a.status != 'delete' AND b.status != 'delete')
                GROUP BY b.user_id, b.pay_period_id, a.branch_id, a.department_id, 
                        b.date_stamp, user_wage_id, user_wage_effective_date, 
                        over_time_policy_wage_id, over_time_policy_wage_effective_date,
                        absence_policy_wage_id, absence_policy_wage_effective_date, 
                        premium_policy_wage_id, premium_policy_wage_effective_date, 
                        a.status, a.type, a.over_time_policy_id, 
                        a.absence_policy_id, a.premium_policy_id, 
                        tmp2.min_punch_time_stamp, tmp2.max_punch_time_stamp
            ) AS tmp ON z.user_id = tmp.user_id
            WHERE z.company_id = ?";

        // Append order conditions
        $query .= " ORDER BY ";
        $c = 0;
        foreach ($order as $column => $direction) {
            $c > 0 && $query .= ",";
            $query .= " {$column} {$direction}";
            $c++;
        }

        // Execute Query
        return DB::select($query, $ph);
    }

    public function getByUserDateId(){
        print_r('UserDateTotalController->getByUserDateId');exit;
    }

}

?>