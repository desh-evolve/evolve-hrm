<?php

namespace App\Http\Controllers\ProgressBar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Carbon\Carbon;

use App\Http\Controllers\Payroll\PayStubController;
use App\Http\Controllers\Payroll\PayPeriodController;
use App\Http\Controllers\Payroll\PayPeriodScheduleController;
use App\Http\Controllers\Payroll\PayPeriodScheduleUserController;
use App\Http\Controllers\Core\CalculatePayStubController;

class ProgressBarController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function initProgressBar($data)
    {
        $user_id = Auth::user()->id;
        $com_id = Auth::user()->company_id ?? 1;

        // Retrieve session data
        $action = $data['action'];
        $next_page = $data['next_page'];
        $pay_period_ids = $data['pay_period_ids'];
        $filter_user_id = $data['filter_user_id'];
        $pay_stub_ids = $data['pay_stub_ids'];
        $data = $data['data'];

        //$action = strtolower($request->query('action', ''));
        $comment = __('Test Progress Bar...');

        switch ($action) {
            case 'recalculate_company':
            case 'recalculate_employee':
            case 'generate_paystubs':
                echo 'Generate Pay Stubs!<br>';
                
                if ( !is_array($pay_period_ids) ) {
                    $pay_period_ids = array($pay_period_ids);
                }

                //add a log record here (check here)
                echo ('Recalculating Company Pay Stubs for Pay Periods:').' '. implode(',', $pay_period_ids).'<br>';

                $init_progress_bar = TRUE;
                foreach($pay_period_ids as $pay_period_id) {
                    echo 'Pay Period ID: '. $pay_period_id.'<br>';
                    $ppc = new PayPeriodController();
			        $pplf = $ppc->getByIdAndCompanyId($pay_period_id, $com_id);

                    //print_r($pplf);exit;

                    foreach ($pplf as $pay_period_obj) {
                        echo 'Pay Period Schedule ID: '. $pay_period_obj->pay_period_schedule_id.'<br>';
                        
                        if ( $init_progress_bar == TRUE ) {
                            //InitProgressBar();
                            $init_progress_bar = FALSE;
                        }

                        //$progress_bar->setValue(0);
				        //$progress_bar->display();

                        //Delete existing pay stub. Make sure we only delete pay stubs that are the same as what we're creating.
                        $psc = new PayStubController();
                        $pslf = $psc->getByPayPeriodId( $pay_period_obj->id );
                        
                        foreach ( $pslf as $pay_stub_obj ) {
                            echo 'Existing Pay Stub: '. $pay_stub_obj->id.'<br>';
                            //Check PS End Date to match with PP End Date So if an ROE was generated, it won't get deleted when they generate all other Pay Stubs later on.
                            
                            if ( ($pay_stub_obj->status == 'new' || $pay_stub_obj->status == 'locked' || $pay_stub_obj->status == 'open') AND $pay_stub_obj->tainted == FALSE AND Carbon::parse($pay_stub_obj->end_date)->format('Y-m-d') == Carbon::parse($pay_period_obj->end_date)->format('Y-m-d') 
                            ){
                                echo 'Pay stub matched advance flag, deleting: '. $pay_stub_obj->id.'<br>';
                                $id = $pay_stub_obj->id;
                                $whereArr = ['id' => $pay_stub_obj->id];
                                $title = 'Pay Stub';
                                $table = 'pay_stub';
                                $returnMsg = false;
                                $this->common->commonDelete($id, $whereArr, $title, $table, $returnMsg);
                            } else {
                                echo 'Pay stub does not need regenerating, or it is LOCKED!<br>';
                            }
                        }
                        $i=1;

                        //Grab all users for pay period
                        $ppsuc = new PayPeriodScheduleUserController();
                        $ppsulf = $ppsuc->getByPayPeriodScheduleId( $pay_period_obj->pay_period_schedule_id );
                        $total_pay_stubs = count($ppsulf);

                        foreach ($ppsulf as $pay_period_schdule_user_obj) {
                            echo 'Pay Period User ID: '. $pay_period_schdule_user_obj->user_id.'<br>';
                            echo 'Total Pay Stubs: '. $total_pay_stubs .' - '. ceil( 1 / (100 / $total_pay_stubs) ).'<br>';
                            /*$profiler->startTimer( 'Calculating Pay Stub' );*/
                            //Calc paystubs.
                            $cpsc = new CalculatePayStubController();
                            $user_id = $pay_period_schdule_user_obj->user_id;
                            $com_id = $com_id;
                            $pay_period_id = $pay_period_obj->id;

                            $cpsc->removeTerminatePayStub( $pay_period_id, $user_id );
                            $cpsc->calculateAllowance( $pay_period_id, $user_id, $com_id ); 
                            echo '<br><br><br>';
                            //check here
                            $cpsc->calculate( $pay_period_id, $user_id );
                            
                            //$profiler->stopTimer( 'Calculating Pay Stub' );
        
                            //$progress_bar->setValue( Misc::calculatePercent( $i, $total_pay_stubs ) );
                            //$progress_bar->display();
        
                            $i++;
                        }

                        $ugsf = TTnew( 'UserGenericStatusFactory' );
                        $ugsf->setUser( $current_user->getId() );
                        $ugsf->setBatchID( $ugsf->getNextBatchId() );
                        $ugsf->setQueue( UserGenericStatusFactory::getStaticQueue() );
                        $ugsf->saveQueue();
                        $next_page = URLBuilder::getURL( array('batch_id' => $ugsf->getBatchID(), 'batch_title' => 'Generating Pay Stubs', 'batch_next_page' => $next_page), '../users/UserGenericStatusList.php');

                        $next_page = [
                            'batch_id' => $ugsf->getBatchID(),
                            'batch_title' => 'Generating Pay Stubs',
                            'batch_next_page' => $next_page
                        ];
                    }
                }

                break;
            case 'generate_paymiddle':
            case 'recalculate_paystub_ytd':
            case 'add_mass_punch':
            case 'add_mass_schedule':
            case 'add_mass_schedule_npvc':
            case 'recalculate_accrual_policy':
            case 'process_late_leave':
            default:
                echo 'Default';
                break;
        }
        
        return $comment;

        $progressData = [
            'comment' => $comment,
            'next_page' => $request->query('next_page', ''),
        ];

    }

}

?>