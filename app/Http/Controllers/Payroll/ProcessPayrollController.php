<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Carbon\Carbon;

use App\Http\Controllers\Payroll\PayPeriodTimeSheetVerifyListController;
use App\Http\Controllers\Payroll\PayPeriodController;
use App\Http\Controllers\Payroll\PayPeriodScheduleController;
use App\Http\Controllers\Payroll\PayStubController;
use App\Http\Controllers\Core\ExceptionController;
use App\Http\Controllers\Core\UserDateTotalController;
use App\Http\Controllers\Request\RequestController;

class ProcessPayrollController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view process payroll', ['only' => ['index']]);

        $this->common = new CommonModel();

        //status => close = 20, lock = 12, open = 10, post_adjustment = 15 (old system)
    }

    public function index(){
        $user_id = Auth::user()->id;
        $com_id = Auth::user()->company_id ?? 1;
        $msg = 'Default Message Here';

        //Step 1, get all open pay periods that have ended and are before the transaction date.

		$ppc = new PayPeriodController();
		$ppsc = new PayPeriodScheduleController();

		$open_pay_periods = FALSE;

		$pplf = $ppc->getByCompanyIdAndStatus( $com_id, ['open', 'locked', 'post_adjustment'] );

		if ( count($pplf) > 0 ) {
            foreach ($pplf as $pay_period_obj) {
				$pay_period_schedule = $ppsc->getPayPeriodScheduleById( $pay_period_obj->pay_period_schedule_id )[0];
				//print_r($pay_period_schedule);exit;

                if($pay_period_schedule){
                    $ec = new ExceptionController();
					$elf = $ec->getSumExceptionsByPayPeriodIdAndBeforeDate($pay_period_obj->id, $pay_period_obj->end_date );

                    $low_severity_exceptions = 0;
					$med_severity_exceptions = 0;
					$high_severity_exceptions = 0;
					$critical_severity_exceptions = 0;

                    if ( count($elf) > 0 ) {
                        //echo 'Found Exceptions:';
						foreach($elf as $e_obj ) {
							if ( $e_obj->severity == 'low' ) {
								$low_severity_exceptions = $e_obj->count;
							}
							if ( $e_obj->severity == 'medium' ) {
								$med_severity_exceptions = $e_obj->count;
							}
							if ( $e_obj->severity == 'high' ) {
								$high_severity_exceptions = $e_obj->count;
							}
							if ( $e_obj->severity == 'critical' ) {
								$critical_severity_exceptions = $e_obj->count;
							}
						}
					} else {
                        //echo 'No Exceptions!';
					}

                    //check here
                    //Get all pending requests
					$pending_requests = 0;

					$rc = new RequestController();
					$rlf = $rc->getSumByPayPeriodIdAndStatus( $pay_period_obj->id, 'pending' );

					if ( count($rlf) > 0 ) {
						$pending_requests = $rlf[0]->total;
					}

					//Get PS Amendments.
					$psac = new PayStubAmendmentController();
					$psalf = $psac->getByUserIdAndAuthorizedAndStartDateAndEndDate( $pay_period_schedule->users[0]->user_id, TRUE, $pay_period_obj->start_date, $pay_period_obj->end_date );
					$total_ps_amendments = 0;
					if ( is_object($psalf) ) {
						$total_ps_amendments = count($psalf);
					}

					//Get verified timesheets
					$pptsvc = new PayPeriodTimeSheetVerifyListController();
					$pptsvlf = $pptsvc->getByPayPeriodIdAndCompanyId( $pay_period_obj->id, $com_id );
					//print_r($pptsvlf);exit;
					$verified_time_sheets = 0;
					$pending_time_sheets = 0;
					if ( count($pptsvlf) > 0 ) {
						foreach( $pptsvlf as $pptsv_obj ) {
							if ( $pptsv_obj->authorized == TRUE ) {
								$verified_time_sheets++;
							} elseif (  $pptsv_obj->status == 'pending_authorization' OR $pptsv_obj->status == 'pending_employee_verification' ) {
								$pending_time_sheets++;
							}
						}
					}

					//Get total employees with time for this pay period.
					$udtc = new UserDateTotalController();
					$total_worked_users = $udtc->getWorkedUsersByPayPeriodId( $pay_period_obj->id );

					//Count how many pay stubs for each pay period.
					$pslf = new PayStubController();
					$pay_stubs = $pslf->getByPayPeriodId( $pay_period_obj->id );
					$total_pay_stubs = count($pay_stubs);
					
					if ( $pay_period_obj->status != 'closed' ) {
						$open_pay_periods = TRUE;
					}

					
					$pay_periods[] = array(
						'id' => $pay_period_obj->id,
						'company_id' => $pay_period_obj->company_id,
						'pay_period_schedule_id' => $pay_period_obj->pay_period_schedule_id,
						'name' => $pay_period_schedule->name,
						'type' => $pay_period_schedule->type,
						'status' => $pay_period_obj->status,
						'start_date' => Carbon::parse($pay_period_obj->start_date)->format('Y-m-d'),
						'end_date' => Carbon::parse($pay_period_obj->end_date)->format('Y-m-d'),
						'transaction_date' => Carbon::parse($pay_period_obj->transaction_date)->format('Y-m-d'),
						'low_severity_exceptions' => $low_severity_exceptions,
						'med_severity_exceptions' => $med_severity_exceptions,
						'high_severity_exceptions' => $high_severity_exceptions,
						'critical_severity_exceptions' => $critical_severity_exceptions,
						'pending_requests' => $pending_requests,
						'verified_time_sheets' => $verified_time_sheets,
						'pending_time_sheets' => $pending_time_sheets,
						'total_worked_users' => $total_worked_users,
						'total_ps_amendments' => $total_ps_amendments,
						'total_pay_stubs' => $total_pay_stubs,
					);
                }
            }
        }else{
            $msg = 'No pay periods pending transaction ';
        }
		$total_pay_periods = count($pay_periods);
		$parse_obj = [
            'open_pay_periods' => $open_pay_periods,
            'pay_periods' => $pay_periods,
            'total_pay_periods' => $total_pay_periods,
            //'sort_column' => $sort_column,
            //'sort_order' => $sort_order,
		];

		//print_r($pay_periods);exit;
        return view('payroll.process_payroll.index', $parse_obj);
    }

	// for close/unlock/lock
	public function changeStatus(){
		/*
		//Lock selected pay periods
				Debug::Text('Lock Selected Pay Periods... Action: '. $action, __FILE__, __LINE__, __METHOD__,10);

				$pplf = TTnew( 'PayPeriodListFactory' );

				$pplf->StartTransaction();
				if ( isset($pay_period_ids) AND count($pay_period_ids) > 0 ) {
					foreach($pay_period_ids as $pay_period_id) {
						$pay_period_obj = $pplf->getById( $pay_period_id )->getCurrent();

						if ( $pay_period_obj->getStatus() != 20 ) {
							if ( $action == 'close' ) {
								$pay_period_obj->setStatus(20);
							} elseif ( $action == 'lock' ) {
								$pay_period_obj->setStatus(12);
							} else {
								$pay_period_obj->setStatus(10);
							}

							$pay_period_obj->Save();
						}
					}
				}
				$pplf->CommitTransaction();

				Redirect::Page( URLBuilder::getURL(NULL, 'ClosePayPeriod.php') );

		*/
	}

	public function generatePayStubs(){
		/*
		Debug::Text('Generate Pay Stubs ', __FILE__, __LINE__, __METHOD__,10);
		//var_dump($pay_stub_pay_period_ids); die;
		Redirect::Page( URLBuilder::getURL( array('action' => 'generate_paystubs', 'pay_period_ids' => $pay_stub_pay_period_ids, 'next_page' => '../payperiod/ClosePayPeriod.php' ), '../progress_bar/ProgressBarControl.php') );

		*/
	}
}

?>