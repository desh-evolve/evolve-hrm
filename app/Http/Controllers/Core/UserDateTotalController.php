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

    public function getDayReportByCompanyIdAndArrayCriteria( $company_id, $filter_data ){
		$order = array( 'tmp.pay_period_id' => 'asc','z.last_name' => 'asc', 'tmp.date_stamp' => 'asc' );

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

		$ulf = new UserListFactory();
		$udf = new UserDateFactory();
		$uwf = new UserWageFactory();
		$pcf = new PunchControlFactory();
		$pf = new PunchFactory();
		$otpf = new OverTimePolicyFactory();
		$apf = new AbsencePolicyFactory();
		$ppf = new PremiumPolicyFactory();

		$ph = array();

		//Make it so employees with 0 hours still show up!! Very important!
		//Order dock hours first, so it can be deducted from regular time.
		$query = '
				select z.id, tmp.*
				from users as z
				LEFT JOIN
					( select
							b.user_id,
							b.pay_period_id as pay_period_id,
							b.date_stamp as date_stamp,
							a.branch_id as branch_id,
							a.department_id as department_id,
							a.status_id as status_id,
							a.type_id as type_id,

							a.over_time_policy_id as over_time_policy_id,
							n.id as over_time_policy_wage_id,
							n.effective_date as over_time_policy_wage_effective_date,

							a.absence_policy_id as absence_policy_id,
							p.id as absence_policy_wage_id,
							p.effective_date as absence_policy_wage_effective_date,

							a.premium_policy_id as premium_policy_id,
							r.id as premium_policy_wage_id,
							r.effective_date as premium_policy_wage_effective_date,

							z.id as user_wage_id,
							z.effective_date as user_wage_effective_date,
							tmp2.min_punch_time_stamp as min_punch_time_stamp,
							tmp2.max_punch_time_stamp as max_punch_time_stamp,
							sum(total_Time) as total_time,
							sum(actual_total_Time) as actual_total_time
					from user_date_total as a
					LEFT JOIN user_date as b ON a.user_date_id = b.id
					LEFT JOIN overtime_policy as m ON a.over_time_policy_id = m.id
					LEFT JOIN emp_wage as n ON n.id = (select n.id
																		from emp_wage as n
																		where n.user_id = b.user_id
																			and n.wage_group_id = m.wage_group_id
																			and n.effective_date <= b.date_stamp
																			and n.deleted = 0
																			order by n.effective_date desc limit 1)

					LEFT JOIN absence_policy as o ON a.absence_policy_id = o.id
					LEFT JOIN user_wage as p ON p.id = (select p.id
																		from '. $uwf->getTable() .' as p
																		where p.user_id = b.user_id
																			and p.wage_group_id = o.wage_group_id
																			and p.effective_date <= b.date_stamp
																			and p.deleted = 0
																			order by p.effective_date desc limit 1)

					LEFT JOIN '. $ppf->getTable() .' as q ON a.premium_policy_id = q.id
					LEFT JOIN '. $uwf->getTable() .' as r ON r.id = (select r.id
																		from '. $uwf->getTable() .' as r
																		where r.user_id = b.user_id
																			and r.wage_group_id = q.wage_group_id
																			and r.effective_date <= b.date_stamp
																			and r.deleted = 0
																			order by r.effective_date desc limit 1)

					LEFT JOIN '. $uwf->getTable() .' as z ON z.id = (select z.id
																		from '. $uwf->getTable() .' as z
																		where z.user_id = b.user_id
																			and z.effective_date <= b.date_stamp
																			and z.wage_group_id = 0
																			and z.deleted = 0
																			order by z.effective_date desc limit 1)
					LEFT JOIN (
						select tmp3.id, min(tmp3.min_punch_time_stamp) as min_punch_time_stamp, max(tmp3.max_punch_time_stamp) as max_punch_time_stamp from (
							select tmp2_a.id,
								CASE WHEN tmp2_c.status_id = 10 THEN min(tmp2_c.time_stamp) ELSE NULL END as min_punch_time_stamp,
								CASE WHEN tmp2_c.status_id = 20 THEN max(tmp2_c.time_stamp) ELSE NULL END as max_punch_time_stamp
								from '. $udf->getTable() .' as tmp2_a
								LEFT JOIN '. $pcf->getTable() .' as tmp2_b ON tmp2_a.id = tmp2_b.user_date_id
								LEFT JOIN '. $pf->getTable() .' as tmp2_c ON tmp2_b.id = tmp2_c.punch_control_id
								WHERE 1=1 ';
								if ( isset($filter_data['user_id']) AND isset($filter_data['user_id'][0]) AND !in_array(-1, (array)$filter_data['user_id']) ) {
									$query  .=	' AND tmp2_a.user_id in ('. $this->getListSQL($filter_data['user_id'], $ph) .') ';
								}

								if ( isset($filter_data['pay_period_ids']) AND isset($filter_data['pay_period_ids'][0]) AND !in_array(-1, (array)$filter_data['pay_period_ids']) ) {
									$query .= 	' AND tmp2_a.pay_period_id in ('. $this->getListSQL($filter_data['pay_period_ids'], $ph) .') ';
								}

								if ( isset($filter_data['start_date']) AND trim($filter_data['start_date']) != '' ) {
									$ph[] = $this->db->BindDate($filter_data['start_date']);
									$query  .=	' AND tmp2_a.date_stamp >= ?';
								}
								if ( isset($filter_data['end_date']) AND trim($filter_data['end_date']) != '' ) {
									$ph[] = $this->db->BindDate($filter_data['end_date']);
									$query  .=	' AND tmp2_a.date_stamp <= ?';
								}

								$query .= '
									AND tmp2_c.time_stamp is not null
									AND ( tmp2_a.deleted = 0 AND tmp2_b.deleted = 0 AND tmp2_c.deleted = 0 )
								group by tmp2_a.id, tmp2_c.status_id
							) as tmp3 group by tmp3.id
					) as tmp2 ON b.id = tmp2.id

					where 	1=1 ';

					if ( isset($filter_data['user_id']) AND isset($filter_data['user_id'][0]) AND !in_array(-1, (array)$filter_data['user_id']) ) {
						$query  .=	' AND b.user_id in ('. $this->getListSQL($filter_data['user_id'], $ph) .') ';
					}

		if ( isset($filter_data['pay_period_ids']) AND isset($filter_data['pay_period_ids'][0]) AND !in_array(-1, (array)$filter_data['pay_period_ids']) ) {
			$query .= 	' AND b.pay_period_id in ('. $this->getListSQL($filter_data['pay_period_ids'], $ph) .') ';
		}

 		if ( isset($filter_data['punch_branch_id']) AND isset($filter_data['punch_branch_id'][0]) AND !in_array(-1, (array)$filter_data['punch_branch_id']) ) {
			$query .= 	' AND a.branch_id in ('. $this->getListSQL($filter_data['punch_branch_id'], $ph) .') ';
		}
 		if ( isset($filter_data['punch_department_id']) AND isset($filter_data['punch_department_id'][0]) AND !in_array(-1, (array)$filter_data['punch_department_id']) ) {
			$query .= 	' AND a.department_id in ('. $this->getListSQL($filter_data['punch_department_id'], $ph) .') ';
		}

		if ( isset($filter_data['start_date']) AND trim($filter_data['start_date']) != '' ) {
			$ph[] = $this->db->BindDate($filter_data['start_date']);
			$query  .=	' AND b.date_stamp >= ?';
		}
		if ( isset($filter_data['end_date']) AND trim($filter_data['end_date']) != '' ) {
			$ph[] = $this->db->BindDate($filter_data['end_date']);
			$query  .=	' AND b.date_stamp <= ?';
		}

		$ph[] = $company_id;
		$query .= '
						AND a.status_id in (10,20,30)
						AND ( a.deleted = 0 AND b.deleted = 0 )
					group by b.user_id, b.pay_period_id, a.branch_id, a.department_id, b.date_stamp, user_wage_id, user_wage_effective_date, over_time_policy_wage_id, over_time_policy_wage_effective_date, absence_policy_wage_id, absence_policy_wage_effective_date, premium_policy_wage_id, premium_policy_wage_effective_date, a.status_id, a.type_id, a.over_time_policy_id, a.absence_policy_id, a.premium_policy_id, tmp2.min_punch_time_stamp, tmp2.max_punch_time_stamp
					) as tmp ON z.id = tmp.user_id
				WHERE z.company_id = ? ';

		if ( isset($filter_data['user_id']) AND isset($filter_data['user_id'][0]) AND !in_array(-1, (array)$filter_data['user_id']) ) {
			$query  .=	' AND z.id in ('. $this->getListSQL($filter_data['user_id'], $ph) .') ';
		}

		$query .= ' AND z.deleted = 0 ';

		$query .= $this->getSortSQL( $order, FALSE );

		$this->rs = $this->db->Execute($query, $ph);
               // print_r($this->rs);
		return $this;
    }

}

?>