<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

use App\Http\Controllers\Payroll\PayStubController;
use App\Http\Controllers\Core\UserDateController;

class CalculatePayStubController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->common = new CommonModel();
    }

    function removeTerminatePayStub($pay_period_id, $user_id){
        $id = $pay_period_id.'-'.$user_id;
        $whereArr = [
            ['pay_period_id', '=', $pay_period_id],
            ['user_id', '=', $user_id],
        ];
        $title = '';
        $table = '';
        $res = $this->common->commonDelete($id, $whereArr, $title, $table, $returnMsg = false, $deletedBy = true, $recordLog = true);
        return $res;
    }

    function calculateAllowance($pay_period_id, $user_id){
        
        //$udlf = new UserDateController();
        
        $filter_data['pay_period_ids'] = array($pay_period_id);
        $filter_data['include_user_ids'] = array($user_id);
        $filter_data['user_id'] = array($user_id);
        
        //echo $this->getUserObject()->getCompany(); 
        
        $udtlf = new UserDateTotalController();
        $udtlf->getDayReportByCompanyIdAndArrayCriteria( $this->getUserObject()->getCompany(), $filter_data );
        
                    
        $slf = new ScheduleListFactory();
        $slf->getSearchByCompanyIdAndArrayCriteria($this->getUserObject()->getCompany(),$filter_data);
        
        if ( $slf->getRecordCount() > 0 ) {
            foreach($slf as $s_obj) {
                $user_id = $s_obj->getColumn('user_id');
                $status_id = $s_obj->getColumn('status_id');
                $status = strtolower( Option::getByKey($status_id, $s_obj->getOptions('status') ) );
                $pay_period_id = $s_obj->getColumn('pay_period_id');
                $date_stamp = TTDate::strtotime( $s_obj->getColumn('date_stamp') );

                $schedule_rows[$pay_period_id][$user_id][$date_stamp][$status] = $s_obj->getColumn('total_time');  
                $schedule_rows[$pay_period_id][$user_id][$date_stamp]['start_time'] = $s_obj->getColumn('start_time');  
                $schedule_rows[$pay_period_id][$user_id][$date_stamp]['end_time'] = $s_obj->getColumn('end_time');  
                unset($user_id, $status_id, $status, $pay_period_id, $date_stamp);
            }
        }
                    
        foreach ($udtlf as $udt_obj ) {
            $user_id = $udt_obj->getColumn('id');
            $pay_period_id = $udt_obj->getColumn('pay_period_id');
            $date_stamp = TTDate::strtotime( $udt_obj->getColumn('date_stamp') );
                            
            $status_id = $udt_obj->getColumn('status_id');
            $type_id = $udt_obj->getColumn('type_id');
                            
            $tmp_rows[$pay_period_id][$user_id][$date_stamp]['min_punch_time_stamp'] = TTDate::strtotime( $udt_obj->getColumn('min_punch_time_stamp') );
            $tmp_rows[$pay_period_id][$user_id][$date_stamp]['max_punch_time_stamp'] = TTDate::strtotime( $udt_obj->getColumn('max_punch_time_stamp') );
                            
            if ( ($status_id == 20 AND $type_id == 10 ) OR ($status_id == 10 AND $type_id == 100 ) ) {
                $tmp_rows[$pay_period_id][$user_id][$date_stamp]['worked_time'] += (int)$udt_obj->getColumn('total_time');
            }
        }
        
                    
        $worked_days_no = 0;
        $late_days_no = 0;
        $nopay_days_no = 0;
        $full_day_leave_no = 0;
        $half_day_leave_no = 0;
                    
        foreach($tmp_rows as $pp_id=>$user_data) {
            foreach ($user_data as $usr_id => $date_data) {
                foreach ($date_data as $date_stamp => $att_data) {
                                
                    $dt_stamp = new DateTime();
                    $dt_stamp->setTimestamp($date_stamp);
                    $current_date = $dt_stamp->format('Y-m-d');
                                
                    if((isset($schedule_rows[$pp_id][$usr_id][$date_stamp]['start_time']) && $schedule_rows[$pp_id][$usr_id][$date_stamp]['start_time'] !='' )&& (isset($att_data['min_punch_time_stamp'])&& $att_data['min_punch_time_stamp']!='')){
                                    
                        $worked_days_no++;
                                    
                        $late_time = TTDate::strtotime($schedule_rows[$pp_id][$usr_id][$date_stamp]['start_time']) - $att_data['min_punch_time_stamp'];
                                    
                        if($late_time < 0){
                            $alf_a = new AccrualListFactory();
                    
                            $alf_a->getByAccrualByUserIdAndTypeIdAndDate($usr_id,55,$current_date);
                            
                            if($alf_a->getRecordCount() > 0){
                                // echo $late_time.'<br>';
                            } else {
                                $late_days_no++;
                            }
                        }
                    } elseif ((isset($schedule_rows[$pp_id][$usr_id][$date_stamp]['start_time']) && $schedule_rows[$pp_id][$usr_id][$date_stamp]['start_time'] !='' )&& (isset($att_data['min_punch_time_stamp'])&& $att_data['min_punch_time_stamp']=='') && (isset($att_data['max_punch_time_stamp'])&& $att_data['max_punch_time_stamp']=='')){
                                    
                        $hlf = TTnew('HolidayListFactory');
                                    
                        $hlf->getByPolicyGroupUserIdAndDate($usr_id, $current_date);
                        $hday_obj_arr = $hlf->getCurrent()->data;

                        if (!empty($hday_obj_arr)) {
                                            
                        } else {
                            $alf = new AccrualListFactory();
                            $alf->getByAccrualByUserIdAndTypeIdAndDate($usr_id,55,$current_date);
                                                    
                                                    //  echo ' '.$date_stamp;
                                            
                            if($alf->getRecordCount() > 0){
                                $af_obj = $alf->getCurrent();
                                if($af_obj->getAmount()== -28800){
                                    $full_day_leave_no++;
                                } elseif($af_obj->getAmount()==-14400){
                                    $half_day_leave_no++;
                                }
                            } else {
                                // Used to calculate nopay days and place nopay on salary
                                                            
                                $ulf = new UserListFactory();
                                $ulf->getById($usr_id);
                                $user_obj = $ulf->getCurrent();
                                                            
                                // exclude directors from Nopay
                                if($user_obj->getTitle()!= 2){ 
                                    
                                    $udlf = new UserDateListFactory();
                                    $udlf->getByUserIdAndDate($usr_id, $current_date);

                                    if($udlf->getRecordCount() >0){
                                        $ud_obj = $udlf->getCurrent();

                                        $udtlf = new UserDateTotalListFactory();
                                        $udtlf->getByUserDateId($ud_obj->getId());

                                        if($udtlf->getRecordCount() > 0){

                                            foreach($udtlf as $udt_obj){

                                                $udt_obj->setDeleted(TRUE);

                                                if( $udt_obj->isValid()){
                                                    $udt_obj->Save(); 
                                                }
                                            }
                                        }

                                                                
                                        if(($user_obj->getTerminationDate()!='' && $user_obj->getTerminationDate() >= $dt_stamp->getTimestamp() )|| $user_obj->getTerminationDate() == ''){
                                                                
                                        $udt_obj1 = new UserDateTotalFactory();

                                        $udt_obj1->setUserDateID($ud_obj->getId());
                                        $udt_obj1->setStatus(10);
                                        $udt_obj1->setType(10);
                                        $udt_obj1->setTotalTime(0);

                                        if( $udt_obj1->isValid()){
                                                    $udt_obj1->Save(); 
                                        }

                                        $udt_obj2 = new UserDateTotalFactory();


                                        $udt_obj2->setUserDateID($ud_obj->getId());
                                        $udt_obj2->setStatus(30);
                                        $udt_obj2->setType(10);
                                        $udt_obj2->setTotalTime(28800);
                                        $udt_obj2->setAbsencePolicyID(10);
                                        $udt_obj2->setDepartment($user_obj->getDefaultDepartment());
                                        $udt_obj2->setBranch($user_obj->getDefaultBranch());


                                        if( $udt_obj2->isValid()){
                                                    $udt_obj2->Save(); 
                                        }

                                        unset($udt_obj1);
                                        unset($udt_obj2);
                                        unset($ulf);
                                        
                                        }
                                    }
                                }
                                                        
                                $nopay_days_no++;
                            }
                        }
                    } elseif( (isset($att_data['min_punch_time_stamp'])&& $att_data['min_punch_time_stamp']!='')){
                                    
                        if($att_data['worked_time'] >= 14400){
                            
                            // echo "gone";
                            $worked_days_no++;
                        }
                    
                    }
                
            
                }// end foreach datestamp
                
            }// end of user foreach
            
        }// end of payperiods  foreach
                    
                    
        $allf = new AllowanceListFactory();
        $allf->getByUserIdAndPayperiodsId($user_id, $pay_period_id);
        
        if($allf->getRecordCount() >0){
            
            $alf_obj = $allf->getCurrent();
            
            
            $alf_obj->setUser($user_id);
            $alf_obj->setPayPeriod($pay_period_id);
            $alf_obj->setWorkedDays($worked_days_no);
            $alf_obj->setLateDays($late_days_no);
            $alf_obj->setNopayDays($nopay_days_no);
            $alf_obj->setFulldayLeaveDays($full_day_leave_no);
            $alf_obj->setHalfdayLeaveDays($half_day_leave_no);

            if($alf_obj->isValid()){
                $alf_obj->Save();
            }
            
        } else {
        
            $alf = new AllowanceFactory();

            $alf->setUser($user_id);
            $alf->setPayPeriod($pay_period_id);
            $alf->setWorkedDays($worked_days_no);
            $alf->setLateDays($late_days_no);
            $alf->setNopayDays($nopay_days_no);
            $alf->setFulldayLeaveDays($full_day_leave_no);
            $alf->setHalfdayLeaveDays($half_day_leave_no);

            if($alf->isValid()){
                $alf->Save();
            }
        }
    }
        

}

?>