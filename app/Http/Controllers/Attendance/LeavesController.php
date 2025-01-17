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
            [ 'com_employee_designations' => ['com_employee_designations.id', '=', 'emp_employees.designation_id']]
        );

        $current_user = $current_user[0]; //get current user
        
        $data['name'] = $current_user->first_name.' '.$current_user->last_name;
        //check here
        $data['title'] = $current_user->title;
        //$data['title_id'] = '0';
        $data['leave_start_date'] = '';

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

        //=========================================================

        $abc = new AccrualBalanceController();
        
        $ablf = $abc->getByUserIdAndAccrualPolicyId($user_id, $data['leave_type']);

        if( count($ablf) > 0){
            $abf = $ablf[0]; //get current accrual balance
            $balance = $abf->balance;
            $amount = $data['no_days'];
            $amount_taken = 0;

            if($data['method_type'] == 1){
                $amount_taken = (($amount*8) * (28800/8));
            } elseif($data['method_type'] == 2){
                
                if($amount<1){
                     $amount_taken = (($amount*8) * (28800/8));
                }
                else{
                    $amount_taken = (($amount*8) * (28800/8));
                }
            } elseif($data['method_type'] == 3){
                $amount_taken = 4320;
                 
                $start_date_stamp = Carbon::parse($data['appt-time']);
                $end_date_stamp = Carbon::parse($data['end-time']);
                
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
                $date_sh_array = explode(',', $data['leave_start_date']);
                //check here    
                $udc = new UserDateListFactory();
                $udtlf_s = $udc->getByUserIdAndDate($user_id, $date_sh_array[0]);
                
                $udf_obj = $udtlf_s[0]; //get current userdate
                $pp_id = $udf_obj->getPayPeriod();
                
                $pplf = new PayPeriodListFactory();
                $pplf->getById($pp_id);
                $pp_obj = $pplf->getCurrent();
                // echo "foo".$pp_obj->getStartDate(TRUE);
                
                $lrlf_s = new LeaveRequestListFactory();
                $row = $lrlf_s->getPayperiodsShortLeaveCount($current_user->getId(), $data['leave_type'], $pp_obj->getStartDate(TRUE), $pp_obj->getEndDate(TRUE));
                $pp_short_leave_count=$row['count'];


            }else{
                $msg = "You don't have sufficent leave";
            }

        }else{
            $msg = "You don't have this leave type";
        }

        /*
        try {
            return DB::transaction(function () use ($request) {
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'designation' => 'required|string|max:250',
                    'leaveType' => 'required|string',
                    'leaveMethod' => 'required|string',
                    'numberOfDays' => 'required|integer|min:1',
                    'startTime' => 'required|date_format:Y-m-d H:i:s',
                    'endTime' => 'required|date_format:Y-m-d H:i:s|after:startTime',
                    'reason' => 'required|string',
                    'contact' => 'nullable|string|max:250',
                    'coverDuties' => 'required|string',
                    'supervisor' => 'required|string',
                    'selectedDates' => 'required|array|min:1',
                    'selectedDates.*' => 'date_format:Y-m-d',
                ]);

                $table = 'leave_requests';
                $inputArr = [
                    'company_id' => 1, // Replace with dynamic company ID if applicable
                    'user_id' => Auth::id(),
                    'name' => $request->name,
                    'designation' => $request->designation,
                    'leave_type' => $request->leaveType,
                    'leave_method' => $request->leaveMethod,
                    'number_of_days' => $request->numberOfDays,
                    'start_time' => $request->startTime,
                    'end_time' => $request->endTime,
                    'reason' => $request->reason,
                    'contact' => $request->contact,
                    'cover_duties' => $request->coverDuties,
                    'supervisor' => $request->supervisor,
                    'selected_dates' => json_encode($request->selectedDates),
                    'status' => 'pending', // Default status
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ];

                $leaveRequestId = $this->common->commonSave($table, $inputArr);

                if ($leaveRequestId) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Leave request created successfully',
                        'data' => ['id' => $leaveRequestId]
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to create leave request',
                        'data' => []
                    ], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
        */
    }


}

?>