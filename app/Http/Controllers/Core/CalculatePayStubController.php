<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Carbon\Carbon;

use App\Http\Controllers\Payroll\PayStubController;
use App\Http\Controllers\Core\UserDateController;
use App\Http\Controllers\Schedule\ScheduleController;
use App\Http\Controllers\Accrual\AccrualController;
use App\Http\Controllers\Company\AllowanceController;
use App\Http\Controllers\Holiday\HolidayController;
use App\Http\Controllers\Payroll\PayStubAmendmentController;
use App\Http\Controllers\Policy\PremiumPolicyController;
use App\Http\Controllers\UserController;

class CalculatePayStubController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function removeTerminatePayStub($pay_period_id, $user_id){
        $id = $pay_period_id.'-'.$user_id;
        $whereArr = [
            ['pay_period_id', '=', $pay_period_id],
            ['user_id', '=', $user_id],
        ];
        $title = 'Remove Paystub';
        $table = 'pay_stub';
        $returnMsg = false;
        $res = $this->common->commonDelete($id, $whereArr, $title, $table, $returnMsg);
        return $res;
    }

    public function calculateAllowance($pay_period_id, $user_id, $com_id){
        //$udlf = new UserDateController();
        
        $filter_data['pay_period_ids'] = array($pay_period_id);
        $filter_data['include_user_ids'] = array($user_id);
        $filter_data['user_id'] = array($user_id);
        
        //echo $com_id; 
        
        $udtc = new UserDateTotalController();
        $udtlf = $udtc->getDayReportByCompanyIdAndArrayCriteria( $com_id, $filter_data );
                    
        $sc = new ScheduleController();
        $slf = $sc->getSearchByCompanyIdAndArrayCriteria($com_id,$filter_data);

        //check here
        if ( count($slf) > 0 ) {
            foreach($slf as $s_obj) {
                $user_id = $s_obj->user_id;
                $status = $s_obj->status;
                $pay_period_id = $s_obj->pay_period_id;
                $date_stamp = Carbon::parse($s_obj->date_stamp)->format('Y-m-d');

                $schedule_rows[$pay_period_id][$user_id][$date_stamp][$status] = $s_obj->total_time;
                $schedule_rows[$pay_period_id][$user_id][$date_stamp]['start_time'] = $s_obj->start_time;
                $schedule_rows[$pay_period_id][$user_id][$date_stamp]['end_time'] = $s_obj->end_time;
            }
        }
                    
        foreach ($udtlf as $udt_obj ) {
            $user_id = $udt_obj->id;
            $pay_period_id = $udt_obj->pay_period_id;
            $date_stamp = Carbon::parse($udt_obj->date_stamp)->format('Y-m-d');
                            
            $status = $udt_obj->status;
            $type = $udt_obj->type;
                            
            $tmp_rows[$pay_period_id][$user_id][$date_stamp]['min_punch_time_stamp'] = Carbon::parse($udt_obj->min_punch_time_stamp)->format('Y-m-d');
            $tmp_rows[$pay_period_id][$user_id][$date_stamp]['max_punch_time_stamp'] = Carbon::parse($udt_obj->max_punch_time_stamp)->format('Y-m-d');
                            
            // check here
            if ( ($status == 'worked' AND $type == 'total' ) OR ($status == 'system' AND $type == 'lunch' ) ) {
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
                            $ac = new AccrualController();
                    
                            $alf_a = $ac->getByAccrualByUserIdAndTypeIdAndDate($usr_id,55,$current_date);
                            
                            if(count($alf_a) > 0){
                                // echo $late_time.'<br>';
                            } else {
                                $late_days_no++;
                            }
                        }
                    } elseif ((isset($schedule_rows[$pp_id][$usr_id][$date_stamp]['start_time']) && $schedule_rows[$pp_id][$usr_id][$date_stamp]['start_time'] !='' )&& (isset($att_data['min_punch_time_stamp'])&& $att_data['min_punch_time_stamp']=='') && (isset($att_data['max_punch_time_stamp'])&& $att_data['max_punch_time_stamp']=='')){
                                    
                        $hlf = new HolidayController();
                                    
                        $hlf->getByPolicyGroupUserIdAndDate($usr_id, $current_date);
                        $hday_obj_arr = $hlf->getCurrent()->data;

                        if (!empty($hday_obj_arr)) {
                                            
                        } else {
                            $alf = new AccrualController();
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
                                                            
                                $ulf = new UserController();
                                $ulf->getById($usr_id);
                                $user_obj = $ulf->getCurrent();
                                                            
                                // exclude directors from Nopay
                                if($user_obj->getTitle()!= 2){ 
                                    
                                    $udlf = new UserDateController();
                                    $udlf->getByUserIdAndDate($usr_id, $current_date);

                                    if($udlf->getRecordCount() >0){
                                        $ud_obj = $udlf->getCurrent();

                                        $udtlf = new UserDateTotalController();
                                        $udtlf->getByUserDateId($ud_obj->getId());

                                        if($udtlf->getRecordCount() > 0){

                                            foreach($udtlf as $udt_obj){

                                                $udt_obj->setDeleted(TRUE);

                                                if( $udt_obj->isValid()){
                                                    $udt_obj->Save(); 
                                                }
                                            }
                                        }

                                                                
                                        if(($user_obj->temination_date!='' && $user_obj->temination_date >= $dt_stamp->getTimestamp() )|| $user_obj->temination_date == ''){
                                                                
                                        $udt_obj1 = new UserDateTotalController();

                                        $udt_obj1->setUserDateID($ud_obj->getId());
                                        $udt_obj1->setStatus(10);
                                        $udt_obj1->setType(10);
                                        $udt_obj1->setTotalTime(0);

                                        if( $udt_obj1->isValid()){
                                                    $udt_obj1->Save(); 
                                        }

                                        $udt_obj2 = new UserDateTotalController();


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
                    
                    
        $allf = new AllowanceController();
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
        
            $alf = new AllowanceController();

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
        
    public function calculate($epoch = NULL, $userObject, $payPeriodObject) {
        print_r('CalculatePayStubController->calculate');exit;

		if ( $userObject == FALSE OR $userObject->status !== 'active' ) {
			return FALSE;
		}

		$generic_queue_status_label = $userObject->full_name.' - Pay Stub';

		$epoch = $epoch ?? Carbon::now()->timestamp;

		if (  $payPeriodObject == FALSE ) {
			return FALSE;
		}

		echo 'bbUser Id: '. $userObject->user_id .' Pay Period End Date: DATE+TIME'. $payPeriodObject->end_date;

		//echo '<pre>';

		//$pay_stub = new PayStubController();
        $pay_stub = [];
		//$pay_stub->StartTransaction();

		$old_pay_stub_id = NULL;
		//if ( $this->getEnableCorrection() == TRUE ) {
			echo 'Correction Enabled!';
			$pay_stub['temp'] = true;

			//Check for current pay stub ID so we can compare against it.
			$psc = new PayStubController;
			$pslf = $psc->getByUserIdAndPayPeriodId( $userObject->user_id, $payPeriodObject->id );
			if ( count($pslf) > 0 ) {
				$old_pay_stub_id = $pslf[0]->id;
				echo 'Comparing Against Pay Stub ID: '. $old_pay_stub_id;
			}
		//}
		$pay_stub['user_id'] = $userObject->user_id;
		$pay_stub['pay_period_id'] = $payPeriodObject->id;
		$pay_stub['currency_id'] = $userObject->currency_id;
		$pay_stub['status'] = 'new';

		//Use User Termination Date instead of ROE.
		if ( $userObject->temination_date != ''
				AND $userObject->temination_date >= $payPeriodObject->start_date
				AND $userObject->temination_date <= $payPeriodObject->end_date ) {
			echo 'User has been terminated in this pay period!';

			$is_terminated = TRUE;
		} else {
			$is_terminated = FALSE;
		}

		if ( $is_terminated == TRUE ) {
			echo 'User is Terminated, assuming final pay, setting End Date to terminated date: '. $userObject->temination_date ;

			$pay_stub['start_date'] = $payPeriodObject->start_date ;
			$pay_stub['end_date'] = $userObject->temination_date;

			//Use the PS generation date instead of terminated date...
			//Unlikely they would pay someone before the pay stub is generated.
			//Perhaps still use the pay period transaction date for this too?
			//Anything we set won't be correct for everyone. Maybe a later date is better though?
			//Perhaps add to the user factory under Termination Date a: "Final Transaction Date" for this purpose?
			//Use the end of the current date for the transaction date, as if the employee is terminated
			//on the same day they are generating the pay stub, the transaction date could be before the end date
			//as the end date is at 11:59PM

			//For now make sure that the transaction date for a terminated employee is never before their termination date.
			if ( TTDate::getEndDayEpoch( TTDate::getTime() ) < $userObject->temination_date ) {
				$pay_stub->setTransactionDate( $userObject->temination_date );
			} else {
				$pay_stub->setTransactionDate( TTDate::getEndDayEpoch( TTDate::getTime() ) );
			}

		} else {
			echo ('User Termination Date is NOT set, assuming normal pay.', __FILE__, __LINE__, __METHOD__,10);
			$pay_stub->setDefaultDates();
		}

		//This must go after setting advance
		if ( $this->getEnableCorrection() == FALSE AND $pay_stub->IsUniquePayStub() == FALSE ) {
			echo ('Pay Stub already exists', __FILE__, __LINE__, __METHOD__,10);
			$this->CommitTransaction();

			UserGenericStatusFactory::queueGenericStatus( $generic_queue_status_label, 20, TTi18n::gettext('Pay Stub for this employee already exists, skipping...'), NULL );

			return FALSE;
		}

		if ( $pay_stub->isValid() == TRUE ) {
			$pay_stub->Save(FALSE);
			$pay_stub->setStatus('Open');
		} else {
			echo ('Pay Stub isValid failed!', __FILE__, __LINE__, __METHOD__,10);

			UserGenericStatusFactory::queueGenericStatus( $generic_queue_status_label, 10, $pay_stub->Validator->getTextErrors(), NULL );

			$this->FailTransaction();
			$this->CommitTransaction();
			return FALSE;
		}

		$pay_stub->loadPreviousPayStub();

		$user_date_total_arr = $this->getWageObject()->getUserDateTotalArray();
                
		if ( isset($user_date_total_arr['entries']) AND is_array( $user_date_total_arr['entries'] ) ) {
			foreach( $user_date_total_arr['entries'] as $udt_arr ) {
				//Allow negative amounts so flat rate premium policies can reduce an employees wage if need be.
				if ( $udt_arr['amount'] != 0 ) {
					echo ('Adding Pay Stub Entry: '. $udt_arr['pay_stub_entry'] .' Amount: '. $udt_arr['amount'], __FILE__, __LINE__, __METHOD__,10);
					$pay_stub->addEntry( $udt_arr['pay_stub_entry'], $udt_arr['amount'], TTDate::getHours( $udt_arr['total_time'] ), $udt_arr['rate'] );
				} else {
					echo ('NOT Adding ($0 amount) Pay Stub Entry: '. $udt_arr['pay_stub_entry'] .' Amount: '. $udt_arr['amount'], __FILE__, __LINE__, __METHOD__,10);
				}
			}
		} else {
			//No Earnings, CHECK FOR PS AMENDMENTS next for earnings.
			echo ('NO TimeSheet EARNINGS ON PAY STUB... Checking for PS amendments', __FILE__, __LINE__, __METHOD__,10);
		}
                /////////////////////////////////////////Added by Thusitha start//////////////////////////////////////////////
                $pgplf = new PremiumPolicyController();
               // $pslf = TTnew( 'PayStubListFactory' );
                //echo $userObject->user_id;                exit();
                $pgplf->getByPolicyGroupUserId($userObject->user_id);
                
                if($pgplf->getRecordCount() > 0){
                    
                    foreach($pgplf as $ppf_obj){

                        //$ppf_obj = $pgplf->getCurrent();

                        $allf = new AllowanceController();
                        $allf->getByUserIdAndPayperiodsId($userObject->user_id, $payPeriodObject->id);
                      
                         if($allf->getRecordCount()>0){
                          
                             $amount = 0;
                             $alf_obj = $allf->getCurrent();
                             
                             if($ppf_obj->getId() == 1){
                                 
                                 $amount = ( $alf_obj->getWorkedDays() - $alf_obj->getLateDays())*120;
                             }
                             elseif ($ppf_obj->getId() == 3) {
                                 $amount = ( $alf_obj->getWorkedDays() - $alf_obj->getLateDays())*160;
                             }
                             elseif ($ppf_obj->getId() == 2) {
                                 
                                 $nopay_days = $alf_obj->getNopayDays();
                                 $full_day = $alf_obj->getFulldayLeaveDays();
                                 $half_day = $alf_obj->getHalfdayLeaveDays();
                                 
                                 $allowance = 3000;
                                 
                                 $amount =  $allowance - (($nopay_days*1000) + ($full_day*500)+ ($half_day*250));
                                 
                             }
                             
                         }
                             if($amount > 0){
                                 $pay_stub->addEntry( $ppf_obj->getPayStubEntryAccountId(), $amount, 2, 1 );
                             }
                         
                    
                    }    
                }
		/////////////////////////////////////////Added by Thusitha end//////////////////////////////////////////////
		//Get all PS amendments and Tax / Deductions so we can determine the proper order to calculate them in.
		$psalf = new PayStubAmendmentController();
		$psalf->getByUserIdAndAuthorizedAndStartDateAndEndDate( $userObject->user_id, TRUE, $payPeriodObject->start_date, $payPeriodObject->end_date );

		//                echo '<br>';
		//                print_r($psalf->getRecordCount());
		$udlf = TTnew( 'UserDeductionListFactory' );
		$udlf->getByCompanyIdAndUserId( $userObject->getCompany(), $userObject->getId() );

		//                echo '<br>';
		//                print_r($udlf->getRecordCount());
                
		$deduction_order_arr = $this->getOrderedDeductionAndPSAmendment( $udlf, $psalf );
               // print_r($deduction_order_arr); exit;
		if ( is_array($deduction_order_arr) AND count($deduction_order_arr) > 0 ) {
                    
                      $deduction_slary_advance = 0;
                      
			foreach($deduction_order_arr as $calculation_order => $data_arr ) {

				echo ('Found PS Amendment/Deduction: Type: '. $data_arr['type'] .' Name: '. $data_arr['name'] .' Order: '. $calculation_order, __FILE__, __LINE__, __METHOD__,10);

				if ( isset($data_arr['obj']) AND is_object($data_arr['obj']) ) {

					if ( $data_arr['type'] == 'UserDeductionListFactory' ) {

						$ud_obj = $data_arr['obj'];

						//Determine if this deduction is valid based on start/end dates.
						//Determine if this deduction is valid based on min/max length of service.
						//Determine if this deduction is valid based on min/max user age.
						if ( $ud_obj->getCompanyDeductionObject()->isActiveDate( $pay_stub->getPayPeriodObject()->getEndDate() ) == TRUE
								AND $ud_obj->getCompanyDeductionObject()->isActiveLengthOfService( $userObject, $pay_stub->getPayPeriodObject()->getEndDate() ) == TRUE
								AND $ud_obj->getCompanyDeductionObject()->isActiveUserAge( $userObject, $pay_stub->getPayPeriodObject()->getEndDate() ) == TRUE ) {

								$amount = $ud_obj->getDeductionAmount( $userObject->getId(), $pay_stub, $payPeriodObject );
								echo ('User Deduction: '. $ud_obj->getCompanyDeductionObject()->getName() .' Amount: '. $amount .' Calculation Order: '. $ud_obj->getCompanyDeductionObject()->getCalculationOrder(), __FILE__, __LINE__, __METHOD__,10);

                                                                if($ud_obj->getCompanyDeduction()==3){
                                                                    
                                                                    $wage_obj = $this->getWageObject();
                                                                    
                                                                    $date_now = new DateTime();
                                                                    
                                                                    $user_wage_list = new UserWageListFactory();
                                                                    $user_wage_list->getLastWageByUserIdAndDate($userObject->getId(),$date_now->getTimestamp());
                                                                    
                                                                    if($user_wage_list->getRecordCount() > 0){
                                                                        
                                                                       $uw_obj =  $user_wage_list->getCurrent();
                                                                       
                                                                       $total_pay_period_days = ceil( TTDate::getDayDifference( $pay_stub->getPayPeriodObject()->start_date, $pay_stub->getPayPeriodObject()->getEndDate()) );
                                                                      
                                                                        
                                                                        $wage_effective_date = new DateTime($uw_obj->getColumn('effective_date'));
                                                                        $prev_wage_effective_date = $pay_stub->getPayPeriodObject()->getEndDate();
                                                                        
                                                                        $total_wage_effective_days = ceil( TTDate::getDayDifference( $wage_effective_date->getTimestamp(), $prev_wage_effective_date ) );
                                                                        
                                                                         
                                                                        
                                                                        if($total_pay_period_days > $total_wage_effective_days){
                                                                            
                                                                            $total_pay_period_days = 30;
                                                                            
                                                                           // if($userObject->getId()==971){
                                                                            
                                                                            //echo $total_wage_effective_days.' '.$uw_obj->getColumn('effective_date').'<br>';
                                                                           // echo $total_pay_period_days.' ';
                                                                            
                                                                           $amount = abs(bcmul( $amount, bcdiv($total_wage_effective_days, $total_pay_period_days) ));
                                                                           
                                                                          // exit();
                                                                          //  }
                                                                            
                                                                        }
                                                                    }
                                                                  
                                                                }
                                                                
                                                                if($ud_obj->getCompanyDeduction()==10){//no pay
                                                                    $amount = $user_date_total_arr['other']['dock_absence_amount'];
                                                                }
                                                                
                                                                
                                                                $deduction_slary_advance++;
                                                                //}
								//Allow negative amounts, so they can reduce previously calculated deductions or something. getEmpBasisType()
                                                                // added by thusitha 2017/08/10
								if ( isset($amount) AND $amount != 0 ) {
                                                                   
                                                                       $pay_stub->addEntry( $ud_obj->getCompanyDeductionObject()->getPayStubEntryAccount(), $amount );
                                                                   
								} else {
									echo ('Amount is 0, skipping...', __FILE__, __LINE__, __METHOD__,10);
								}
						}
						unset($amount, $ud_obj);
					} elseif ( $data_arr['type'] == 'PayStubAmendmentListFactory' ) {
						$psa_obj = $data_arr['obj'];

						echo ('Found Pay Stub Amendment: ID: '. $psa_obj->getID() .' Entry Name ID: '. $psa_obj->getPayStubEntryNameId() .' Type: '. $psa_obj->getType() , __FILE__, __LINE__, __METHOD__,10);

						$amount = $psa_obj->getCalculatedAmount( $pay_stub );
                                                
                                               

						if ( isset($amount) AND $amount != 0 ) {
							echo ('Pay Stub Amendment Amount: '. $amount , __FILE__, __LINE__, __METHOD__,10);

							$pay_stub->addEntry( $psa_obj->getPayStubEntryNameId(), $amount, $psa_obj->getUnits(), $psa_obj->getRate(), $psa_obj->getDescription(), $psa_obj->getID(), NULL, NULL, $psa_obj->getYTDAdjustment() );

							//Keep in mind this causes pay stubs to be re-generated every time, as this modifies the updated time
							//to slightly more then the pay stub creation time.
							$psa_obj->setStatus('IN USE');
							$psa_obj->Save();

						} else {
							echo ('bPay Stub Amendment Amount is not set...', __FILE__, __LINE__, __METHOD__,10);
						}
						unset($amount, $psa_obj);

					}
				}

			}
		//                        die;
                        
                       

		}
		unset($deduction_order_arr, $calculation_order, $data_arr);

		$pay_stub_id = $pay_stub->getId();

		$pay_stub->setEnableProcessEntries(TRUE);
		$pay_stub->processEntries();
		if ( $pay_stub->isValid() == TRUE ) {
			echo ('Pay Stub is valid, final save.', __FILE__, __LINE__, __METHOD__,10);
			$pay_stub->Save();

			if ( $this->getEnableCorrection() == TRUE ) {
				if ( isset($old_pay_stub_id) ) {
					echo ('bCorrection Enabled - Doing Comparison here', __FILE__, __LINE__, __METHOD__,10);
					PayStubFactory::CalcDifferences( $pay_stub_id, $old_pay_stub_id );
				}

				//Delete newly created temp paystub.
				//This used to be in the above IF block that depended on $old_pay_stub_id
				//being set, however in cases where the old pay stub didn't exist
				//TimeTrex wouldn't delete these temporary pay stubs.
				//Moving this code outside that IF statement so it only depends on EnableCorrection()
				//to be TRUE should fix that issue.
				$pslf = TTnew( 'PayStubListFactory' );
				$pslf->getById( $pay_stub_id );
				if ( $pslf->getRecordCount() > 0 ) {
					$tmp_ps_obj = $pslf->getCurrent();
					$tmp_ps_obj->setDeleted(TRUE);
					$tmp_ps_obj->Save();
					unset($tmp_ps_obj);
				}
			}

			$pay_stub->CommitTransaction();

			UserGenericStatusFactory::queueGenericStatus( $generic_queue_status_label, 30, NULL, NULL );

			return TRUE;
		}

		echo ('Pay Stub is NOT valid returning FALSE', __FILE__, __LINE__, __METHOD__,10);

		UserGenericStatusFactory::queueGenericStatus( $generic_queue_status_label, 10, $pay_stub->Validator->getTextErrors(), NULL );

		$pay_stub->FailTransaction(); //Reduce transaction count by one.
		//$pay_stub->FailTransaction(); //Reduce transaction count by one.

		$pay_stub->CommitTransaction();

		return FALSE;
	}


}

?>