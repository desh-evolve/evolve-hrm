<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PayStubController extends Controller
{
    private $common = null;

    public function __construct()
    {
        //$this->middleware('permission:view pay stub account', ['only' => ['']]);
        $this->common = new CommonModel();
    }

    public function getByPayPeriodId( $id ){

        if ( $id == '' ) {
			return FALSE;
		}

        $table = 'pay_stub';
        $fields = '*';
        $joinArr = [];

        $whereArr = [['pay_stub.pay_period_id', '=', $id]];

        $exceptDel = true;
        $connections = [];
        $groupBy = null;
        $orderBy = null;

        $res = $this->common->commonGetAll( $table, $fields, $joinArr, $whereArr, $exceptDel, $connections, $groupBy, $orderBy );

        return $res;
    }

    public function getByPayperiodsIdAndUserId($pay_period_id,$user_id){
        
        if ( $pay_period_id == '') {
            return FALSE;
        }
            
        if ( $user_id == '') {
            return FALSE;
        }
            
        $table = 'pay_stub';
        $fields = 'pay_stub.*';
        $joinArr = [
            'emp_employees' => ['emp_employees.user_id', '=', 'pay_stub.user_id']
        ];
        $whereArr = [
            ['pay_period_id', '=', $pay_period_id],
            ['user_id', '=', $user_id],
            ['emp_employees.status', '!=', 'delete']
        ];
        $exceptDel = true;
        $groupBy = null;
        $orderBy = null;

        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel, $groupBy, $orderBy);
        
        return $res;
            
            
    }

    public function getByUserIdAndPayPeriodId(){
        print_r('PayStubController->getByUserIdAndPayPeriodId');exit;
    }

    public function loadPreviousPayStub(){
        print_r('PayStubController->loadPreviousPayStub');exit;
    }

    public function save($data){
        print_r('PayStubController->save');exit;
    }

    function loadPreviousPayStub() {
        print_r('PayStubController->loadPreviousPayStub');exit;
        
		if ( $this->getUser() == FALSE OR $this->getStartDate() == FALSE ) {

			return FALSE;

		}



		//Grab last pay stub so we can use it for YTD calculations on this pay stub.

		$pslf = TTnew( 'PayStubListFactory' );

		$pslf->getLastPayStubByUserIdAndStartDate( $this->getUser(), $this->getStartDate() );

		if ( $pslf->getRecordCount() > 0 ) {

			$ps_obj = $pslf->getCurrent();

			Debug::text('Loading Data from Pay Stub ID: '. $ps_obj->getId() , __FILE__, __LINE__, __METHOD__,10);



			$retarr = array(

							'id' => $ps_obj->getId(),

							'start_date' => $ps_obj->getStartDate(),

							'end_date' => $ps_obj->getEndDate(),

							'transaction_date' => $ps_obj->getTransactionDate(),

							'entries' => NULL,

							);



			//

			//If previous pay stub is in a different year, only carry forward the accrual accounts.

			//

			$new_year = FALSE;

			if ( TTDate::getYear( $this->getTransactionDate() ) != TTDate::getYear( $ps_obj->getTransactionDate() ) ) {

				Debug::text('Pay Stub Years dont match!...' , __FILE__, __LINE__, __METHOD__,10);

				$new_year = TRUE;

			}



			//Get pay stub entries

			$pself = TTnew( 'PayStubEntryListFactory' );

			$pself->getByPayStubId( $ps_obj->getID() );

			if ( $pself->getRecordCount() > 0 ) {

				foreach( $pself as $pse_obj ) {

					//Get PSE account type, group by that.

					$psea_arr = $this->getPayStubEntryAccountArray( $pse_obj->getPayStubEntryNameId() );

					if ( is_array( $psea_arr) ) {

						$type_id = $psea_arr['type_id'];

					} else {

						$type_id = NULL;

					}



					//If we're just starting a new year, only carry over

					//accrual balances, reset all YTD entries.

					if ( $new_year == FALSE OR $type_id == 50 ) {

						$pse_arr[] = array(

							'id' => $pse_obj->getId(),

							'pay_stub_entry_type_id' => $type_id,

							'pay_stub_entry_account_id' => $pse_obj->getPayStubEntryNameId(),

							'pay_stub_amendment_id' => $pse_obj->getPayStubAmendment(),

							'rate' => $pse_obj->getRate(),

							'units' => $pse_obj->getUnits(),

							'amount' => $pse_obj->getAmount(),

							'ytd_units' => $pse_obj->getYTDUnits(),

							'ytd_amount' => $pse_obj->getYTDAmount(),

							);

					}

					unset($type_id, $psea_obj);

				}



				if ( isset( $pse_arr ) ) {

					$retarr['entries'] = $pse_arr;



					$this->tmp_data['previous_pay_stub'] = $retarr;



					return TRUE;

				}

			}

		}



		Debug::text('Returning FALSE...' , __FILE__, __LINE__, __METHOD__,10);

		return FALSE;

	}

    function addEntry( $pay_stub_entry_account_id, $amount, $units = NULL, $rate = NULL, $description = NULL, $ps_amendment_id = NULL, $ytd_amount = NULL, $ytd_units = NULL, $ytd_adjustment = FALSE ) {
        print_r('PayStubController->addEntry');exit;
        
		Debug::text('Add Entry: PSE Account ID: '. $pay_stub_entry_account_id .' Amount: '. $amount .' YTD Amount: '. $ytd_amount .' Pay Stub Amendment Id: '. $ps_amendment_id, __FILE__, __LINE__, __METHOD__,10);

		if ( $pay_stub_entry_account_id == '' ) {

			return FALSE;

		}



		//Round amount to 2 decimal places.

		//So any totaling is proper after this point, because it gets rounded to two decimal places

		//in PayStubEntryFactory too.

		$amount = round( $amount, 2 );

		$ytd_amount = round( $ytd_amount, 2 );



		if ( is_numeric( $amount ) ) {

			$psea_arr = $this->getPayStubEntryAccountArray( $pay_stub_entry_account_id );

			if ( is_array( $psea_arr) ) {

				$type_id = $psea_arr['type_id'];

			} else {

				$type_id = NULL;

			}



			$retarr = array(

				'pay_stub_entry_type_id' => $type_id,

				'pay_stub_entry_account_id' => $pay_stub_entry_account_id,

				'pay_stub_amendment_id' => $ps_amendment_id,

				'rate' => $rate,

				'units' => $units,

				'amount' => $amount, //PHP v5.3.5 has a bug that it converts large values with 0's on the end into scientific notation.

				'ytd_units' => $ytd_units,

				'ytd_amount' => $ytd_amount,

				'description' => $description,

				'ytd_adjustment' => $ytd_adjustment,

				);



			$this->tmp_data['current_pay_stub'][] = $retarr;



			//Check if this pay stub account is linked to an accrual account.

			//Make sure the PSE account does not match the PSE Accrual account,

			//because we don't want to get in to an infinite loop.

			//Also don't touch the accrual account if the amount is 0.

			//This happens mostly when AddUnUsedEntries is called.

			if ( $this->getEnableLinkedAccruals() == TRUE

					AND $amount > 0

					AND $psea_arr['accrual_pay_stub_entry_account_id'] != ''

					AND $psea_arr['accrual_pay_stub_entry_account_id'] != 0

					AND $psea_arr['accrual_pay_stub_entry_account_id'] != $pay_stub_entry_account_id

					AND $ytd_adjustment == FALSE ) {



				Debug::text('Add Entry: PSE Account Links to Accrual Account!: '. $pay_stub_entry_account_id .' Accrual Account ID: '. $psea_arr['accrual_pay_stub_entry_account_id'] .' Amount: '. $amount, __FILE__, __LINE__, __METHOD__,10);



				if ( $type_id == 10 ) {

					$tmp_amount = $amount*-1; //This is an earning... Reduce accrual

				} elseif ( $type_id == 20 ) {

					$tmp_amount = $amount; //This is a employee deduction, add to accrual.

				} else {

					$tmp_amount = 0;

				}

				Debug::text('Amount: '. $tmp_amount , __FILE__, __LINE__, __METHOD__,10);



				return $this->addEntry( $psea_arr['accrual_pay_stub_entry_account_id'], $tmp_amount, NULL, NULL, NULL, NULL, NULL, NULL);

			}



			return TRUE;

		}



		Debug::text('Returning FALSE', __FILE__, __LINE__, __METHOD__,10);



		$this->Validator->isTrue(		'entry',

										FALSE,

										TTi18n::gettext('Invalid Pay Stub entry'));



		return FALSE;

	}
}
?>