<?php

namespace App\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

use App\Http\Controllers\Accrual\AccrualController;
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
            
            $header_leave[]['name']=$apf->name;
            
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
            '*', 
            [ 'com_employee_designations' => ['com_employee_designations.id', '=', 'emp_employees.designation_id']]
        );
        
        $data['name'] = $current_user->first_name.' '.$current_user->last_name;
        //check here
        $data['title'] = $current_user->title;
        //$data['title_id'] = '0';
        $data['leave_start_date'] = '';

        $parse_obj = [
            'total_asign_leave' => $total_taken_leave,
            'total_taken_leave' => $total_taken_leave,
            'total_balance_leave' => $total_balance_leave,
            'header_leave' => $header_leave,
            'data' => $data,
            'user' => $header_leave,
            'header_leave' => $current_user
        ];

        print_r($parse_obj);exit;
        return view('attendance.leaves.form', $parse_obj);
    }

}

?>