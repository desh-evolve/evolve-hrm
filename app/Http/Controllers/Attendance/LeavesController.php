<?php

namespace App\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

use App\Http\Controllers\Accrual\AccrualController;
use App\Http\Controllers\Accrual\AccrualBalanceController;
use App\Http\Controllers\Policy\AccrualPolicyController;
use App\Http\Controllers\Attendance\LeavesRequestController;
use App\Http\Controllers\Core\UserDateController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Payroll\PayPeriodController;
use Carbon\Carbon;

class LeavesController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:apply leaves', ['only' => ['form', '']]);

        $this->common = new CommonModel();
    }

    public function form()
    {
        $user_id = Auth::user()->id;
        $com_id = Auth::user()->company_id ?? 1;


        //====================================================

        $lrc = new LeavesRequestController();

        $lrlf = $lrc->getByUserIdAndCompanyId($user_id, $com_id);

        $leave_request = array();

        if(count($lrlf) > 0){
            foreach($lrlf as $lrf){
                $leave = [];


                $leave['name'] = $lrf->fname.' '.$lrf->lname.' (#'.$lrf->emp_id.')';
                $leave['from'] = $lrf->leave_from;
                $leave['to'] = $lrf->leave_to;
                $leave['amount'] = $lrf->amount;
                $leave['leave_type'] = $lrf->accurals_policy_id;
                $leave['leave_method'] = $lrf->method;
                $leave['status'] = "Pending Approvals";

                if($lrf->is_covered_approved && $lrf->status == 'pending'){
                    $leave['status'] = "Pending for Authorization";
                }

                if (!$lrf->is_covered_approved && $lrf->status=='cover_rejected') {
                    $leave['status'] = "Cover Rejected";
                }

                if($lrf->is_covered_approved && $lrf->is_supervisor_approved && $lrf->status == 'pending' ){
                     $leave['status'] = "Supervisor Approved";
                }

                if($lrf->is_covered_approved && !$lrf->is_supervisor_approved && $lrf->status == 'supervisor_rejected' ){
                        $leave['status'] = "Supervisor Rejected";
                }

                if($lrf->is_covered_approved && $lrf->is_supervisor_approved && $lrf->is_hr_approved && $lrf->status == 'pending'){
                     $leave['status'] = "HR Approved";
                }

                if($lrf->is_covered_approved && $lrf->is_supervisor_approved && !$lrf->is_hr_approved && $lrf->status == 'hr_rejected'){
                    $leave['status'] = "HR Rejected";
                }


                $leave_request[]= $leave;

            }
        }

        $ac = new AccrualController();

        $apc = new AccrualPolicyController();

        $aplf = $apc->getAccrualPolicyByCompanyIdAndType($com_id, 'calendar_based');
        //print_r($aplf);exit;
        $header_leave = array();
        $total_asign_leave = array();
        $total_taken_leave = array();
        $total_balance_leave = array();

        foreach($aplf as $apf){
            // if($apf->getId() == 4 || $apf->getId() == 11){
            //     continue;
            // }

            $alf = $ac->getByCompanyIdAndUserIdAndAccrualPolicyIdAndStatusForLeave($com_id, $user_id, $apf->id, 'awarded');

            $header_leave[]['name'] = $apf->name;

            if(count($alf) > 0){
                $af= $alf[0]; //get current accrual
                $total_asign_leave[]['asign'] =  number_format($af->amount/28800,2);
            }else{
                $total_asign_leave[]['asign'] = 0;
            }

            $ttotal =  $ac->getSumByUserIdAndAccrualPolicyId($user_id, $apf->id);

            if(count($alf) > 0)
            {
               $af= $alf[0]; //get current accrual
               $total_taken_leave[]['taken'] = number_format(($af->amount/28800)-($ttotal/28800),2);
               $total_balance_leave[]['balance'] =  number_format(($ttotal/28800),2);
            }
            else{
                $total_taken_leave[]['taken'] = 0;
                $total_balance_leave[]['balance'] = 0;
            }

        }

        $leave_options = array();
        foreach($aplf as $apf){
            $leave_options[$apf->id]=$apf->name;

            //$alf = $ac->getByCompanyIdAndUserIdAndAccrualPolicyIdAndStatus($com_id, $user_id, $apf->id, 'awarded');

        }
        $data['leave_options'] = $leave_options;

        $method_options = $this->common->commonGetAll( 'absence_leave', '*' );
        $data['method_options'] = $method_options;

        $user_options = $this->common->commonGetAll(
            'emp_employees',
            ['user_id', 'id AS emp_id', 'title', 'first_name', 'last_name'],
            [],
            ['user_id' => ['user_id', '!=', $user_id]]
        );

        $data['users_cover_options'] = $user_options;

        $current_user = $this->common->commonGetById(
            $user_id,
            'user_id',
            'emp_employees',
            ['user_id', 'emp_employees.id', 'title', 'first_name', 'last_name', 'emp_designation_name'],
            ['com_user_designations' => ['com_user_designations.id', '=', 'emp_employees.designation_id']]
        );

        $current_user = $current_user[0]; //get current user

        $data['name'] = $current_user->first_name.' '.$current_user->last_name;
        //check here
        $data['title'] = $current_user->title;
        //$data['designation_id'] = '0';
        $data['leave_from'] = '';

        $parse_obj = [
            'total_asign_leave' => $total_asign_leave,
            'total_taken_leave' => $total_taken_leave,
            'total_balance_leave' => $total_balance_leave,
            'header_leave' => $header_leave,
            'leave_request' => $leave_request,
            'data' => $data,
            'user' => $current_user
        ];

        //print_r($parse_obj);exit;
        return view('attendance.leaves.form', $parse_obj);
    }

    public function create(Request $request)
    {

        $user_id = Auth::user()->id;
        $com_id = Auth::user()->company_id ?? 1;

        $request->validate([
            'designation_id' => 'required|integer',
            'accurals_policy_id' => 'required|integer', //leave type
            'amount' => 'required|numeric|min:0',
            'leave_from' => 'required|date|before_or_equal:leave_to',
            'leave_to' => 'required|date|after_or_equal:leave_from',
            'reason' => 'required|string|max:200',
            'address_telephone' => 'required|string|max:200',
            'covered_by' => 'required|integer',
            'supervisor_id' => 'required|integer',
            'method' => 'required|integer|in:1,2,3', // leave method = Absence leave id
            'leave_time' => 'required|string|max:20',
            'leave_end_time' => 'required|string|max:20',
            'leave_dates' => 'required|string|max:2000', // Ensure JSON format if storing multiple dates
        ]);

        //=========================================================

        $abc = new AccrualBalanceController();

        $ablf = $abc->getByUserIdAndAccrualPolicyId($user_id, $request->accurals_policy_id);

        $stt = 'error';
        $msg = 'Something went wrong';

        if( count($ablf) > 0){
            $abf = $ablf[0]; //get current accrual balance
            $balance = $abf->balance;
            $amount = $request->amount;
            $amount_taken = 0;

            if($request->method == 1){ //full day leave
                $amount_taken = (($amount*8) * (28800/8));
            } elseif($request->method == 2){ //half day leave

                if($amount<1){
                     $amount_taken = (($amount*8) * (28800/8));
                }
                else{
                    $amount_taken = (($amount*8) * (28800/8));
                }
            } elseif($request->method == 3){ //short leave
                $amount_taken = 4320;

                $start_date_stamp = Carbon::parse($request->leave_time);
                $end_date_stamp = Carbon::parse($request->leave_end_time);

                $time_diff = $end_date_stamp - $start_date_stamp;

                if($time_diff <= 3600){
                    $time_diff = 3600;
                }


                if($time_diff > 7200){
                    $time_diff = 7200;
                }

                $amount_taken =$time_diff * 0.8;
             }

            $amount_taken = -1 * abs($amount_taken);

            $current_amount = abs($amount_taken);

            if($current_amount <= $balance ){
                $date_sh_array = explode(',', $request->leave_from);
                //check here
                $udc = new UserDateController();
                $udtlf_s = $udc->getByUserIdAndDate($user_id, $date_sh_array[0]);

                $udf_obj = $udtlf_s[0]; //get current userdate
                $pp_id = $udf_obj->pay_period;

                $ppc = new PayPeriodController();
                $pplf = $ppc->getById($pp_id);
                $pp_obj = $pplf[0]; //get current

                $lrc = new LeavesRequestController();
                $row = $lrc->getPayperiodsShortLeaveCount($user_id, $request->accurals_policy_id, $pp_obj->start_date, $pp_obj->end_date);
                $pp_short_leave_count = $row['count'];

                if($pp_short_leave_count >= 2 && $request->accurals_policy_id == 8){ //short leave = 8
                    $stt = 'warning';
                    $msg = "You can apply only two short leaves";
                }else{
                    //save data
                    $table = 'leave_request';

                    $designation_id = $request->designation_id;
                    $accurals_policy_id = $request->accurals_policy_id;
                    $amount = $request->amount;
                    $leave_from = $request->leave_from;
                    $leave_to = $request->leave_to;
                    $reason = $request->reason;
                    $address_telephone = $request->address_telephone;
                    $covered_by = $request->covered_by;
                    $supervisor_id = $request->supervisor_id;
                    $method = $request->method;
                    $is_covered_approved = 1;
                    $is_supervisor_approved = 0;
                    $is_hr_approved = 0;
                    $leave_time = $request->leave_time;
                    $leave_end_time = $request->leave_end_time;
                    $leave_dates = $request->leave_dates;

                    if($request->accurals_policy_id == 3){ //duty leave
                        $is_covered_approved = 1;
                    }

                    if($request->accurals_policy_id == 14){ //director approved leave
                        $is_covered_approved = 1;
                    }

                    $lrc = new LeavesRequestController();
                    $lrlf_b = $lrc->checkUserHasLeaveTypeForDay($current_user->getId(), $from_date->format('Y-m-d'), $request->accurals_policy_id);

                    if(count($lrlf_b) > 0 && $request->accurals_policy_id == 8){ //short leave
                        $stt = 'success';
                        $msg = "You have This leave for the day";
                    }else{
                        $inputArr = [
                            'company_id' => $com_id, // Replace with dynamic company ID if applicable
                            'user_id' => $user_id,
                            'designation_id' => $designation_id,
                            'accurals_policy_id' => $accurals_policy_id,
                            'amount' => $amount,
                            'leave_from' => $leave_from,
                            'leave_to' => $leave_to,
                            'reason' => $reason,
                            'address_telephone' => $address_telephone,
                            'covered_by' => $covered_by,
                            'supervisor_id' => $supervisor_id,
                            'method' => $method,
                            'is_covered_approved' => $is_covered_approved,
                            'is_supervisor_approved' => $is_supervisor_approved,
                            'is_hr_approved' => $is_hr_approved,
                            'leave_time' => $leave_time,
                            'leave_end_time' => $leave_end_time,
                            'leave_dates' => $leave_dates,

                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ];

                        $leaveRequestId = $this->common->commonSave($table, $inputArr);

                        $ec = new EmployeeController();
                        $supervisors = $ec->getEmployeeByUserId(trim($request->supervisor_id));
                        $supervisor_obj = $supervisors[0];


                        $employeeLF = $ec->getEmployeeByUserId(trim($user_id));
                        $employee_obj = $employeeLF->getCurrent();

                        if ( $supervisor_obj->work_email != FALSE ) {
                            $supervisor_primary_email = $supervisor_obj->work_email;
                            if ( $supervisor_obj->home_email != FALSE ) {
                                    $supervisor_secondary_email = $supervisor_obj->home_email;
                            } else {
                                    $supervisor_secondary_email = NULL;
                            }
                        } else {
                                    $supervisor_primary_email = $supervisor_obj->home_email;
                                    $supervisor_secondary_email = NULL;
                        }

                        if ( $employee_obj->work_email != FALSE ) {
                            $employee_primary_email = $employee_obj->work_email;
                            if ( $employee_obj->home_email != FALSE ) {
                                    $employee_secondary_email = $employee_obj->home_email;
                            } else {
                                    $employee_secondary_email = NULL;
                            }
                        } else {
                                    $employee_primary_email = $employee_obj->home_email;
                                    $employee_secondary_email = NULL;
                        }

                        $aplf = new AccrualPolicyListFactory();
                        $aplf->getById($request->accurals_policy_id);

                        $email = new EmailController;

                        $subject = "New leave request by ". $employee_obj->full_name;
                        $body = '';
                        $body .= '<p>Dear '.$supervisor_obj->full_name.'</p>';
                        $body .= '<div style="background: rgb(55,110,55); padding-bottom: 0.1px; padding-top: 0.1px;" align="center"><h2 style="color: #fff">Below leave application is pending for your approval.</h2></div>';
                        $body .= '<br><br><table><tr><td>Emp No </td>'. "<td>".$employee_obj->id."</td></tr>";
                        $body .= '<tr><td>Name </td>'. "<td>".$employee_obj->first_name.' '.$employee_obj->last_name. "</td></tr>";
                        $body .= '<tr><td>Leave Type </td>'. "<td>".  $aplf->leave_type."</td></tr>";
                        $body .= '<tr><td>No. of days </td>'. "<td>".$request->amount."</td></tr>";
                        $body .= '<tr><td>From </td>'. "<td>".$from_date->format('Y-m-d')."</td></tr>";
                        $body .= '<tr><td>To </td>'. "<td>".$to_date->format('Y-m-d')."</td></tr></table>";
                        $body .= '<tr><td>Dates </td>'. "<td>".$request->leave_from."</td></tr></table>";
                        $body .= '<p><b><i><span style="font-family: Helvetica,sans-serif; color:#440062">HR Department</span></i></b></p>"';

                        $from = "careers@aquafresh.lk";
                        $to = $supervisor_primary_email;

                        $email->sendEmail($subject, $body, $from, $to);

                        $stt = 'success';
                        $msg = "You have successfully apply leave";
                    }
                }

            }else{
                $stt = 'warning';
                $msg = "You don't have sufficent leave";
            }

        }else{
            $stt = 'warning';
            $msg = "You don't have this leave type";
        }

        return response()->json([
            'status' => $stt,
            'message' => $msg,
            'data' => [],
        ], 201);
    }


}

?>
