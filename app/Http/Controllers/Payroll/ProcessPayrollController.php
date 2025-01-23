<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

use App\Http\Controllers\Payroll\PayPeriodController;
use App\Http\Controllers\Payroll\PayPeriodScheduleController;
use App\Http\Controllers\Core\ExceptionController;

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
							if ( $e_obj->getColumn('severity') == 'low' ) {
								$low_severity_exceptions = $e_obj->getColumn('count');
							}
							if ( $e_obj->getColumn('severity') == 'medium' ) {
								$med_severity_exceptions = $e_obj->getColumn('count');
							}
							if ( $e_obj->getColumn('severity') == 'high' ) {
								$high_severity_exceptions = $e_obj->getColumn('count');
							}
							if ( $e_obj->getColumn('severity') == 'critical' ) {
								$critical_severity_exceptions = $e_obj->getColumn('count');
							}
						}
					} else {
                        //echo 'No Exceptions!';
					}

                    //Get all pending requests
					$pending_requests = 0;
					$rlf = TTnew( 'RequestListFactory' );
					$rlf->getSumByPayPeriodIdAndStatus( $pay_period_obj->getId(), 30 );
					if ( $rlf->getRecordCount() > 0 ) {
						$pending_requests = $rlf->getCurrent()->getColumn('total');
					}

                }else{
                    print_r('sfd');
                }
            }
        }else{
            $msg = 'No pay periods pending transaction ';
        }
        exit;
        return view('payroll.process_payroll.index');
    }

}

?>