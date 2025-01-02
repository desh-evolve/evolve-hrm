<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Termwind\Components\Dd;
use Carbon\Carbon;

use App\Http\Controllers\Employee\EmployeePreferencesController;
use App\Http\Controllers\Attendance\PunchController;
use App\Http\Controllers\CommonDateController;
use App\Http\Controllers\EmployeeDateController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Policy\MealPolicyController;

class TimeSheetController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view timesheet', ['only' => ['index']]);
        $this->middleware('permission:create timesheet', ['only' => ['getDropdownData']]);
        $this->middleware('permission:update timesheet', ['only' => ['']]);
        $this->middleware('permission:delete timesheet', ['only' => ['']]);

        $this->common = new CommonModel();
    }

    /*
    public function index()
    {

        return view('attendance.timesheet.index');
    }
    */

    public function index(Request $request)
    {

        $epc = new EmployeePreferencesController();
        $cdc = new CommonDateController();
        $cc = new CommonController();
        $pc = new PunchController();
        $edc = new EmployeeDateController();

        $currentUser = Auth::user();
        $filter_data = $request->input('filter_data', [
            'company_id' => 1,
            'user_id' => $currentUser->id,
            'date' => '2024-12-14',
            'group_ids' => -1,
            'branch_ids' => -1,
            'department_ids' => -1,
        ]);

        $user_id = $filter_data['user_id'];

        //Get user date info from filter date.
        $em_date_arr = $edc->getByEmployeeDateUserIdAndDate($user_id, $filter_data['date']);
        //print_r($em_date_arr);exit;
        $pay_period_obj = null;
        if ( count($em_date_arr) > 0 ) {
            $pay_period_id = $em_date_arr[0]->pay_period_id;
        } else {
            $pay_period_arr = $cc->getPayPeriodByUserIdAndDate($user_id, $filter_data['date']);

            if ( count($pay_period_arr) > 0 ) {
                $pay_period_obj = $pay_period_arr[0];
                $pay_period_id = $pay_period_obj->id;
            } else {
                $pay_period_id = FALSE;
            }
        }

        $pay_period_arr =  $this->common->commonGetById($pay_period_id, 'id', 'pay_period', '*', [], [], true);

        if ( count($pay_period_arr) > 0 ) {
            $pay_period_obj = $pay_period_arr[0];
        }

        //print_r($pay_period_obj );exit;

        $current_user_prefs = $epc->getEmployeePreferencesByEmployeeId(Auth::user()->id);

        // Get the start day of the week, defaulting to Monday
        $start_date = $cdc->getBeginWeekEpoch( $filter_data['date'], $current_user_prefs[0]->start_week_day ); // Y-m-d format
		$end_date = $cdc->getEndWeekEpoch( $filter_data['date'], $current_user_prefs[0]->start_week_day ); // Y-m-d format

        $calendar_array = $cdc->getCalendarArray( $start_date, $end_date, $current_user_prefs[0]->start_week_day );

        //print_r($calendar_array);exit;
        
        // Fetch the punch list for the given week range
        $punchList = $pc->getPunchesByEmployeeIdAndStartDateAndEndDate($filter_data['user_id'], $start_date, $end_date);
        
        //print_r($punchList);exit;
        
        if(count($punchList) > 0){
            
            foreach($punchList as $punch_obj){
                
                $user_date_stamp = Carbon::parse($punch_obj->user_date_stamp)->timestamp; //UNIX timestamp

                if ( $punch_obj->note != '' ) {
					$has_note = TRUE;
				} else {
					$has_note = FALSE;
				}

                $punches[$user_date_stamp][] = array(
					'date_stamp' => $user_date_stamp,
					'id' => $punch_obj->id,
					'punch_control_id' => $punch_obj->punch_control_id,
					'time_stamp' => $punch_obj->time_stamp,
					'punch_status' => $punch_obj->punch_status,
					'punch_type' => $punch_obj->punch_type,
					'type_code' => '', //if this is necessary get later
					'has_note' => $has_note,
				);

                //Total up meal and break total time for each day.
				if ($punch_obj->punch_type != 'normal') {
                    
                    $userDateType =& $tmp_date_break_totals[$user_date_stamp][$punch_obj->punch_type];
                    //print_r($punch_obj->time_stamp);

                    if ($punch_obj->punch_status == 'out') {

                        $userDateType['prev'] = Carbon::parse($punch_obj->time_stamp)->timestamp;
                        
                    } elseif ( isset($userDateType['prev']) ) {

                        if ( !isset($userDateType['total_time']) ) {
							$userDateType['total_time'] = 0;
						}

                        $timeStamp = Carbon::parse($punch_obj->time_stamp)->timestamp;
                        $userDateType['total_time'] = bcadd( $userDateType['total_time'], bcsub( $timeStamp, $userDateType['prev']) );

                        if ( !isset($userDateType['total_breaks']) ) {
							$userDateType['total_breaks'] = 0;
						}
						$userDateType['total_breaks']++;

						if ( $userDateType['total_time'] > 0 ) {
                            $break_name = ($punch_obj->punch_type == 'lunch') ? 'Lunch Time' : 'Break Time';

							$date_break_totals[$user_date_stamp][$punch_obj->punch_type] = array(
								'break_name' => $break_name,
								'total_time' => $userDateType['total_time'], //time in seconds
  								'total_breaks' => $userDateType['total_breaks'], //number of breaks
							);
						}

                    }

                    $date_total_break_ids[] = $punch_obj->punch_type;
                }
            }
            
            //Process meal/break total time so it can be properly formatted on the timesheet.
            if ( isset($date_break_totals) ) {
                $date_total_break_ids = array_unique($date_total_break_ids);
                sort($date_total_break_ids); //Put break time first, then lunch.

                $date_break_total_rows = $this->TimeSheetFormatArrayByDate( $date_break_totals, $date_total_break_ids, $calendar_array, 'break_name');
            }

            $x=0;
            $stop = FALSE;
            $max_no_punch_count = count($calendar_array)*2;
            $punch_day_counter = array();
            $last_punch_control_id=array();
            $no_punch_count=0;
            $max_punch_day_counter=0;
            
            while ( $stop == FALSE ) {
                if ($x % 2 == 0) {
                    $status = 'in'; //In
                    $status_name = 'In';
                } else {
                    $status = 'out'; //Out
                    $status_name = 'Out';
                }
    
                //print_r($punches);exit;

                foreach( $calendar_array as $cal_arr ) {
                    $cal_day_epoch = $cal_arr['epoch'];
                    
                    if ( !isset($punch_day_counter[$cal_day_epoch]) ) {
                        $punch_day_counter[$cal_day_epoch] = 0;
                    }
                    if ( !isset($last_punch_control_id[$cal_day_epoch]) ) {
                        $last_punch_control_id[$cal_day_epoch] = 0;
                    }

                    //print_r($x . "=".isset($punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]));

                    if ( isset($punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]])
                            AND $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]['punch_status'] == $status
                            AND $status == 'in' ) {
                        $punch_arr = $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]];
    
                        $last_punch_control_id[$cal_day_epoch] = $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]['punch_control_id'];
    
                        $punch_day_counter[$cal_day_epoch]++;
    
                        $no_punch_count=0;
                    } elseif ( isset($punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]])
                                && $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]['punch_status'] == $status
                                && $status == 20 ) {
                        //Debug::text($x .'Status: 20 Found Punch for Day: '. $cal_arr['day_of_month'] , __FILE__, __LINE__, __METHOD__,10);
    
                        //Make sure the previous IN status punch_control_id matches this one.
                        //Or that it is null.
                        //Debug::text($x .'Last Punch Control ID: '. $last_punch_control_id[$cal_day_epoch], __FILE__, __LINE__, __METHOD__,10);
                        if ( isset($punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]-1])
                                && ( $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]-1]['punch_control_id'] == $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]['punch_control_id']
                                        || $last_punch_control_id[$cal_day_epoch] == NULL ) ) {
                            //Debug::text('Status: 20 -- Punch Control ID DOES match that of In Status! ', __FILE__, __LINE__, __METHOD__,10);
                            $punch_arr = $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]];
    
                            $last_punch_control_id[$cal_day_epoch] = $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]['punch_control_id'];
    
                            $punch_day_counter[$cal_day_epoch]++;
                        } else {
                            //Check to see if the In punch even exists first?
                            if ( !isset($punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]-1] ) ) {
                                //Debug::text('Status: 20 -- In Punch does not exist! ', __FILE__, __LINE__, __METHOD__,10);
                                $punch_arr = $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]];
    
                                $last_punch_control_id[$cal_day_epoch] = $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]['punch_control_id'];
    
                                $punch_day_counter[$cal_day_epoch]++;
                            } else {
                                //Debug::text('Status: 20 -- Punch Control ID DOES NOT match that of In Status! ', __FILE__, __LINE__, __METHOD__,10);
                                $punch_arr = array('punch_control_id' => $last_punch_control_id[$cal_day_epoch]);
    
                                $last_punch_control_id[$cal_day_epoch] = NULL;
                            }
                        }
    
                        $no_punch_count=0;
                    } else {
                        //Debug::text($x .': NO Punch found for Day: '. $cal_arr['day_of_month'] .' Status: '. $status .' Day Counter: '. $punch_day_counter[$cal_day_epoch] .' No Punch Count: '. $no_punch_count, __FILE__, __LINE__, __METHOD__,10);
    
                        $tmp_punch_control_id = NULL;
                        if ( $status == 10 ) {
                            if ( isset($punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]) ) {
                                //Debug::text('aFound Possible Punch Control ID: '.$punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]['punch_control_id'], __FILE__, __LINE__, __METHOD__,10);
                                $tmp_punch_control_id = $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]['punch_control_id'];
                                $no_punch_count=0;
                            } else {
                                //Debug::text('aDID NOT Find Possible Punch Control ID: ', __FILE__, __LINE__, __METHOD__,10);
                                //$last_punch_control_id[$cal_day_epoch] = NULL;
                            }
                        } else {
                            //Check for counter-1 for punch control id
                            if ( isset($punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]-1])
                                    && $last_punch_control_id[$cal_day_epoch] != NULL ) {
                                //Debug::text('bFound Possible Punch Control ID: '.$punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]-1]['punch_control_id'], __FILE__, __LINE__, __METHOD__,10);
                                $tmp_punch_control_id = $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]-1]['punch_control_id'];
                                $no_punch_count=0;
                            }
                        }
                        $last_punch_control_id[$cal_day_epoch] = NULL;
    
                        $punch_arr = array('punch_control_id' => $tmp_punch_control_id);
    
                        $no_punch_count++;
                    }
    
    
                    $rows[$x]['data'][$cal_arr['epoch']] = $punch_arr;
                    $rows[$x]['punch_status'] = $status;
                    $rows[$x]['status'] = $status_name;
                    $rows[$x]['background'] = $x % 2;

                    if ( $punch_day_counter[$cal_day_epoch] > $max_punch_day_counter) {
                        //Debug::text('Updating Max Day Punch Counter: '. $punch_day_counter[$cal_day_epoch], __FILE__, __LINE__, __METHOD__,10);
                        $max_punch_day_counter = $punch_day_counter[$cal_day_epoch];
                    }
                }

                //Debug::text('No Punch Count: '. $no_punch_count .' Max: '. $max_no_punch_count, __FILE__, __LINE__, __METHOD__,10);
                //Only pop off the last row if the rows aren't in pairs. Because if there is only ONE in punch at the first day of the week
                //and no other punches, the Out row doesn't show otherwise.
                if ( $x == 100 || $no_punch_count >= $max_no_punch_count ) {
                    //Debug::text('Stopping Loop at: '. $x, __FILE__, __LINE__, __METHOD__,10);
    
                    //Made this >= 2 so it doesn't show 3 rows if the first day of the week only has the IN punch.
                    //It was > 2.
                    if ( $x >= 2 ) {
                        if ( $x % 2 == 0) {
                            //Clear last 1 rows, as its blank;
                            //Debug::text('Popping Off Last Row: '. $x, __FILE__, __LINE__, __METHOD__,10);
                            array_pop($rows);
                        } else {
                            //Debug::text('Popping Off Last TWO Row: '. $x, __FILE__, __LINE__, __METHOD__,10);
                            array_pop($rows);
                            array_pop($rows);
                        }
                    }
    
                    $stop = TRUE;
                }
                $x++;
            }
        }

        //Get date total rows.
        $meal_policy_options = $this->common->commonGetById($filter_data['company_id'], 'company_id', 'meal_policy', '*');
        
        $user_date_total = $edc->getByCompanyIDAndUserIdAndStatusAndTypeAndStartDateAndEndDate( $filter_data['company_id'], $filter_data['user_id'], 'in', 'normal', $start_date, $end_date);
        
        if ( count($user_date_total) > 0 ){
            foreach($user_date_total as $udt_obj) {
                $user_date_stamp = Carbon::parse($udt_obj->user_date_stamp)->timestamp;

                if ( $udt_obj->meal_policy_id !== FALSE AND isset($meal_policy_options[$udt_obj->meal_policy_id]) ) {
                    $meal_policy = $meal_policy_options[$udt_obj->meal_policy_id];
                } else {
                    $meal_policy = 'No Meal Policy';
                }

                $date_meal_totals[$user_date_stamp][] = array(
                    'date_stamp' => $udt_obj->user_date_stamp,
                    'id' => $udt_obj->id,
                    'user_date_id' => $udt_obj->user_date_id,
                    'status' => $udt_obj->status,
                    'type' => $udt_obj->type,
                    'over_time_policy_id' => $udt_obj->over_time_policy_id,
                    'meal_policy_id' => $udt_obj->meal_policy_id,
                    'meal_policy' => $meal_policy,
                    'department_id' => $udt_obj->department_id,
                    'total_time' => $udt_obj->total_time,
                    'total_time_display' => abs($udt_obj->total_time),
                    //'name' => $udt_obj->getName(),
                    'override' => $udt_obj->override
                );

                $date_meal_total_policy_ids[] = (int)$udt_obj->meal_policy_id;
                $date_total_meal_ids[] = (int)$udt_obj->meal_policy_id;
            }
        }
        
        if ( isset($date_meal_totals) ) {
			foreach( $date_meal_totals as $user_date_stamp => $date_rows ) {
				foreach($date_rows as $date_data) {
					$prev_total_time = 0;
					if ( isset($date_meal_total_group[$user_date_stamp][$date_data['meal_policy_id']]) ) {
						$prev_total_time = $date_meal_total_group[$user_date_stamp][$date_data['meal_policy_id']]['total_time'];
					}

					$date_data['total_time'] = $date_data['total_time'] + $prev_total_time;
					$date_meal_total_group[$user_date_stamp][$date_data['meal_policy_id']] = $date_data;
				}
			}

			// Get unique and sorted meal IDs
            $date_total_meal_ids = array_unique($date_total_meal_ids);
            sort($date_total_meal_ids);

            // Format the array by date
			$date_meal_policy_total_rows = $this->TimeSheetFormatArrayByDate( $date_meal_total_group, $date_total_meal_ids, $calendar_array, 'meal_policy');

            //print_r($date_meal_total_group);
            //exit;
		}

        // check here============================================================================================================================================================================================================================================================================================================================================================================
        // check all the functions working properly.


        $break_policy_options = $this->common->commonGetById($filter_data['company_id'], 'company_id', 'break_policy', '*');

		if ( count($user_date_total) > 0 ) {
			foreach($user_date_total as $udt_obj) {
                $user_date_stamp = Carbon::parse($udt_obj->user_date_stamp)->timestamp;

				if ( $udt_obj->id !== FALSE AND isset($break_policy_options[$udt_obj->id]) ) {
					$break_policy = $break_policy_options[$udt_obj->id];
				} else {
					$break_policy = 'No Break Policy';
				}

				$date_break_policy_totals[$user_date_stamp][] = array(
                    'date_stamp' => $udt_obj->user_date_stamp,
                    'id' => $udt_obj->id,
                    'user_date_id' => $udt_obj->user_date_id,
                    'status' => $udt_obj->status,
                    'type' => $udt_obj->type,
                    'over_time_policy_id' => $udt_obj->over_time_policy_id,
                    'break_policy_id' => $udt_obj->break_policy_id,
                    'break_policy' => $break_policy,
                    'department_id' => $udt_obj->department_id,
                    'total_time' => $udt_obj->total_time,
                    'total_time_display' => abs($udt_obj->total_time),
                    //'name' => $udt_obj->getName(),
                    'override' => $udt_obj->override
				);

				$date_break_policy_total_policy_ids[] = (int)$udt_obj->id;
				$date_total_break_policy_ids[] = (int)$udt_obj->id;
			}
		}

        
        if ( isset($date_break_policy_totals) ) {
            foreach( $date_break_policy_totals as $user_date_stamp => $date_rows ) {
                foreach($date_rows as $date_data) {
                    $prev_total_time = 0;
					if ( isset($date_break_policy_total_group[$user_date_stamp][$date_data['break_policy_id']]) ) {
                        $prev_total_time = $date_break_policy_total_group[$user_date_stamp][$date_data['break_policy_id']]['total_time'];
                        echo 'hi';
					}
                    
					$date_data['total_time'] = $date_data['total_time'] + $prev_total_time;

					$date_break_policy_total_group[$user_date_stamp][$date_data['break_policy_id']] = $date_data;
				}
			}
            
			$date_total_break_policy_ids = array_unique($date_total_break_policy_ids);
			sort($date_total_break_policy_ids);
            
			$date_break_policy_total_rows = $this->TimeSheetFormatArrayByDate( $date_break_policy_total_group, $date_total_break_policy_ids, $calendar_array, 'break_policy');
            
        }

        //Get only system totals.
		if ( count($user_date_total) > 0 ) {
			foreach($user_date_total as $udt_obj) {
                $user_date_stamp = Carbon::parse($udt_obj->user_date_stamp)->timestamp;

				$type_and_policy_id = $udt_obj->type.(int)$udt_obj->over_time_policy_id;

				$date_totals[$user_date_stamp][] = array(
                    'date_stamp' => $udt_obj->user_date_stamp,
                    'id' => $udt_obj->id,
                    'user_date_id' => $udt_obj->user_date_id,
                    'status' => $udt_obj->status,
                    'type' => $udt_obj->type,
                    'over_time_policy_id' => $udt_obj->over_time_policy_id,
                    'type_and_policy_id' => $type_and_policy_id,
                    'branch_id' => (int)$udt_obj->branch_id,
                    'department_id' => $udt_obj->department_id,
                    'total_time' => $udt_obj->total_time,
                    'name' => 'Total Time',
                    //Override only shows for SYSTEM override columns...
                    //FIXME: Need to check Worked overrides too.
                    'tmp_override' => $udt_obj->override
				);

				$date_total_type_ids[$type_and_policy_id] = NULL;
				//$date_total_type_ids[] = $type_and_policy_id;
			}
		} else {
			$date_totals[$start_date][] = array(
                    'date_stamp' => $start_date,
                    'type_and_policy_id' => 100,
                    'total_time' => 0,
                    'name' => 'Total Time',
                    'tmp_override' => FALSE
				);
			$date_total_type_ids[100] = NULL;
		}

        
        if ( isset($date_totals) ) {
            //Group Date Totals
			foreach( $date_totals as $user_date_stamp => $date_rows ) {
                foreach($date_rows as $date_data) {
                    $prev_total_time = 0;
					if ( isset($date_total_group[$user_date_stamp][$date_data['type_and_policy_id']]) ) {
                        $prev_total_time = $date_total_group[$user_date_stamp][$date_data['type_and_policy_id']]['total_time'];
					}
                    
					if ( $date_data['tmp_override'] == TRUE && isset($date_total_group[$user_date_stamp][100]) ) {
                        $date_total_group[$user_date_stamp][100]['override'] = TRUE;
					}
                    
					$date_data['total_time'] = $date_data['total_time'] + $prev_total_time;
					$date_total_group[$user_date_stamp][$date_data['type_and_policy_id']] = $date_data;
				}
			}
            
            
			//We want to keep the order of the SQL query, so use this method instead.
			if ( isset($date_total_type_ids) ) {
				$date_total_type_ids = array_keys($date_total_type_ids);
				sort($date_total_type_ids); //Keep Total, then Regular first.
			}
            
			$date_total_rows = $this->TimeSheetFormatArrayByDate( $date_total_group, $date_total_type_ids, $calendar_array, 'name');

		}

        // ==================================================================================================================
        // job part removed (add if needed later) - no table columns either. you have to add those too. (desh (2024-12-23))
        // ==================================================================================================================
        
        
        // ==================================================================================================================
        // Get Premium Time
        // ==================================================================================================================

        $premium_policy_options = $this->common->commonGetById($filter_data['company_id'], 'company_id', 'premium_policy', '*');

        
		//Get only worked totals.
		if ( count($user_date_total) > 0 ) {
            foreach($user_date_total as $udt_obj) {
                $user_date_stamp = Carbon::parse($udt_obj->user_date_stamp)->timestamp;
                //print_r($user_date_total); exit;
                
				if ( $udt_obj->id !== FALSE AND isset($premium_policy_options[$udt_obj->premium_policy_id]) ) {
					$premium_policy = $premium_policy_options[$udt_obj->premium_policy_id];
				} else {
					$premium_policy = 'No Policy';
				}

				$date_premium_totals[$user_date_stamp][] = array(
                    'date_stamp' => $udt_obj->user_date_stamp,
                    'id' => $udt_obj->id,
                    'user_date_id' => $udt_obj->user_date_id,
                    'status' => $udt_obj->status,
                    'type' => $udt_obj->type,
                    'over_time_policy_id' => $udt_obj->over_time_policy_id,
                    'premium_policy_id' => $udt_obj->premium_policy_id,
                    'premium_policy' => $premium_policy,
                    'department_id' => $udt_obj->department_id,
                    'total_time' => $udt_obj->total_time,
                    //'name' => $udt_obj->getName(),
                    'override' => $udt_obj->override
				);

				$date_premium_total_policy_ids[] = (int)$udt_obj->premium_policy_id;
				$date_total_premium_ids[] = (int)$udt_obj->premium_policy_id;
			}
		}

        
		if ( isset($date_premium_totals) ) {
            foreach( $date_premium_totals as $user_date_stamp => $date_rows ) {
				foreach($date_rows as $date_data) {
					$prev_total_time = 0;
					if ( isset($date_premium_total_group[$user_date_stamp][$date_data['premium_policy_id']]) ) {
						$prev_total_time = $date_premium_total_group[$user_date_stamp][$date_data['premium_policy_id']]['total_time'];
					}
                    
					$date_data['total_time'] = $date_data['total_time'] + $prev_total_time;
					$date_premium_total_group[$user_date_stamp][$date_data['premium_policy_id']] = $date_data;
				}
			}
            
			$date_total_premium_ids = array_unique($date_total_premium_ids);
			sort($date_total_premium_ids);
            
			$date_premium_total_rows = $this->TimeSheetFormatArrayByDate( $date_premium_total_group, $date_total_premium_ids, $calendar_array, 'premium_policy');
			//var_dump($date_premium_total_rows);
		}
        
        
        // ==================================================================================================================
        // Get Absences
        // ==================================================================================================================
        
        $absence_policy_options = $this->common->commonGetById($filter_data['company_id'], 'company_id', 'absence_policy', '*');
       
                
		//Get only worked totals.
        if ( count($user_date_total) > 0 ) {
			foreach($user_date_total as $udt_obj) {
				$user_date_stamp = Carbon::parse($udt_obj->user_date_stamp)->timestamp;

				if ( $udt_obj->absence_policy_id !== FALSE ) {
					$absence_policy = $absence_policy_options[$udt_obj->absence_policy_id];
				} else {
					$absence_policy = 'No Policy';
				}

				$date_absence_totals[$user_date_stamp][] = array(
                    'date_stamp' => $udt_obj->user_date_stamp,
                    'id' => $udt_obj->id,
                    'user_date_id' => $udt_obj->user_date_id,
                    'status' => $udt_obj->status,
                    'type' => $udt_obj->type,
                    'over_time_policy_id' => $udt_obj->over_time_policy_id,
                    'absence_policy_id' => $udt_obj->absence_policy_id,
                    'absence_policy' => $absence_policy,
                    'department_id' => $udt_obj->department_id,
                    'total_time' => $udt_obj->total_time,
                    //'name' => $udt_obj->getName(),
                    'override' => $udt_obj->override
				);

				$date_absence_total_policy_ids[] = (int)$udt_obj->absence_policy_id;
				$date_total_absence_ids[] = (int)$udt_obj->absence_policy_id;
			}
		} 
		
        
        
		if ( isset($date_absence_totals) ) {
            foreach( $date_absence_totals as $user_date_stamp => $date_rows ) {
                foreach($date_rows as $date_data) {
                    $prev_total_time = 0;
					if ( isset($date_absence_total_group[$user_date_stamp][$date_data['absence_policy_id']]) ) {
                        $prev_total_time = $date_absence_total_group[$user_date_stamp][$date_data['absence_policy_id']]['total_time'];
					}
                    
					$date_data['total_time'] = $date_data['total_time'] + $prev_total_time;
					$date_absence_total_group[$user_date_stamp][$date_data['absence_policy_id']] = $date_data;
				}
			}
            
			$date_total_absence_ids = array_unique($date_total_absence_ids);
			sort($date_total_absence_ids);
            
			$date_absence_total_rows = $this->TimeSheetFormatArrayByDate( $date_absence_total_group, $date_total_absence_ids, $calendar_array, 'absence_policy');
			//var_dump($date_absence_total_rows);
		}

        // ==================================================================================================================
        // Get Exceptions
        // ==================================================================================================================
        
		$exceptions = $cc->getExceptionsByCompanyIDAndUserIdAndStartDateAndEndDate( $filter_data['company_id'], $filter_data['user_id'], $start_date, $end_date);
        
		$punch_exceptions = array();

		if ( count($exceptions) > 0 ) {
			foreach( $exceptions as $e_obj ) {

                $user_date_stamp = Carbon::parse($e_obj->user_date_stamp)->timestamp;

				$exception_data_arr = array(
                    'type_id' => $e_obj->type_id,
                    'severity_id' => $e_obj->severity,
                    'exception_policy_type_id' => $e_obj->exception_policy_type_id,
                    'color' => $e_obj->color,
				);

				if ( $e_obj->punch_id != '' ) {
					$punch_exceptions[$e_obj->punch_id][] = $exception_data_arr;
				}
				if ( $e_obj->punch_id == '' AND $e_obj->punch_control_id != '' ) {
					$punch_control_exceptions[$e_obj->punch_control_id][] = $exception_data_arr;
				}

				$date_exceptions[$user_date_stamp][] = $exception_data_arr;
				if ( !isset($unique_exceptions[$e_obj->exception_policy_type_id])
						OR ( $unique_exceptions[$e_obj->exception_policy_type_id]['severity_id'] < $exception_data_arr['severity_id']) ) {
					$unique_exceptions[$e_obj->exception_policy_type_id] = $exception_data_arr;
				}
			}
		}
        
		if ( isset($date_exceptions) ) {
            foreach( $calendar_array as $cal_arr ) {
                if ( isset($date_exceptions[$cal_arr['epoch']])) {
                    $exception_data = $date_exceptions[$cal_arr['epoch']];
				} else {
					$exception_data = NULL;
				}
                
				$date_exception_total_rows[] = $exception_data;
			}
		}
        
        //Get exception names for legend.
		if ( isset($unique_exceptions) ) {

            // check here
            // this is data redundant -> if can add these data to a table and get from it.
            $exception_options = [
                'S1' => 'Unscheduled Absence',
                'S2' => 'Not Scheduled',
                'S3' => 'In Early',
                'S4' => 'In Late',
                'S5' => 'Out Early',
                'S6' => 'Out Late',
                'S7' => 'Over Daily Scheduled Time',
                'S8' => 'Under Daily Scheduled Time',
                'S9' => 'Over Weekly Scheduled Time',
                'O1' => 'Over Daily Time',
                'O2' => 'Over Weekly Time',
                'M1' => 'Missing In Punch',
                'M2' => 'Missing Out Punch',
                'M3' => 'Missing Lunch In/Out Punch',
                'M4' => 'Missing Break In/Out Punch',
                'L1' => 'Long Lunch',
                'L2' => 'Short Lunch',
                'L3' => 'No Lunch',
                'B1' => 'Long Break',
                'B2' => 'Short Break',
                'B3' => 'Too Many Breaks',
                'B4' => 'Too Few Breaks',
                'B5' => 'No Break',
                'V1' => 'TimeSheet Not Verified'
            ];

            
			foreach( $unique_exceptions as $unique_exception ) {
                $unique_exceptions[$unique_exception['exception_policy_type_id']]['name'] = $exception_options[$unique_exception['exception_policy_type_id']];
			}
            
			sort($unique_exceptions);
		}

        // ==================================================================================================================
        // Get Pending Requests
        // ==================================================================================================================
		$requests = $cc->getRequestsByCompanyIDAndUserIdAndStatusAndStartDateAndEndDate( $filter_data['company_id'], $filter_data['user_id'], '"pending"', $start_date, $end_date);

		if ( count($requests) > 0 ) {
			foreach( $requests as $r_obj ) {
                $user_date_stamp = Carbon::parse($r_obj->user_date_stamp)->timestamp;

				$request_data_arr = array(
				    'id' => $r_obj->id
				);

				$date_requests[$user_date_stamp][] = $request_data_arr;
			}
		}

		if ( isset($date_requests) ) {
			foreach( $calendar_array as $cal_arr ) {
				if ( isset($date_requests[$cal_arr['epoch']])) {
					$request_data = $date_requests[$cal_arr['epoch']];
				} else {
					$request_data = NULL;
				}

				$date_request_total_rows[$cal_arr['epoch']] = $request_data;
			}
		}

        
        // ==================================================================================================================
        // Get Holidays
        // ==================================================================================================================
        
        $holiday_array = $cc->getHolidaysByPolicyGroupUserId($user_id, $start_date, $end_date);
        

        // ==================================================================================================================
        // Get pay period locked days
        // ==================================================================================================================
        
        if ( isset($pay_period_obj) AND is_object($pay_period_obj) ) {
			foreach( $calendar_array as $cal_arr ) {
                
                $start_date = Carbon::parse($pay_period_obj->start_date)->startOfDay()->timestamp;;
                $end_date = Carbon::parse($pay_period_obj->end_date)->startOfDay()->timestamp;;

				if ( $cal_arr['epoch'] >= $start_date
						AND $cal_arr['epoch'] <= $end_date ) {
					//Debug::text('Current Pay Period: '. TTDate::getDate('DATE+TIME', $cal_arr['epoch'] ), __FILE__, __LINE__, __METHOD__,10);
					$pay_period_locked_rows[$cal_arr['epoch']] = $pay_period_obj->status == 'locked' ? TRUE : FALSE;
				} else {
					//Debug::text('Diff Pay Period...', __FILE__, __LINE__, __METHOD__,10);
					//FIXME: Add some caching here perhaps?
					$pplf = $cc->getPayPeriodByUserIdAndDate( $user_id, $cal_arr['epoch'] );
					if ( count($pplf) > 0 ) {
						$tmp_pay_period_obj = $pplf[0];
						$pay_period_locked_rows[$cal_arr['epoch']] = $tmp_pay_period_obj->status == 'locked' ? TRUE : FALSE;
					} else {
						//Debug::text('  Did not Found rows...', __FILE__, __LINE__, __METHOD__,10);
						//Allow them to edit payperiods in future.
						$pay_period_locked_rows[$cal_arr['epoch']] = FALSE;
					}
                    
				}

			}
		}

        // ==================================================================================================================
        // Get TimeSheet verification
        // ==================================================================================================================

        if ( isset($pay_period_obj) AND is_object($pay_period_obj) ) {
			$is_timesheet_superior = FALSE;
			$pptsvlf = $cc->getPayPeriodTimeSheetByPayPeriodIdAndUserId( $pay_period_obj->id, $user_id );

			if ( count($pptsvlf) > 0 ) {
				$pptsv_obj = $pptsvlf[0];
			} else {
                $pptsv_obj = $pptsvlf;
				//$pptsv_obj->setStatus( 45 ); //Pending Verification
			}
            
			$time_sheet_verify = array(
                'id' => $pptsv_obj->id,
                'user_verified' => $pptsv_obj->user_verified,
                'user_verified_date' => $pptsv_obj->user_verified_date,
                'status_id' => $pptsv_obj->status_id,
                'status' => $pptsv_obj->status,
                'pay_period_id' => $pptsv_obj->pay_period_id,
                'user_id' => $pptsv_obj->user_id,
                'authorized' => $pptsv_obj->authorized,
                'authorized_users' => null, //get users if need
                'is_hierarchy_superior' => null,
                'display_verify_button' => null,
                'verification_box_color' => null,
                'verification_status_display' => null,
                'previous_pay_period_verification_display' => null,
                'created_at' => $pptsv_obj->created_at,
                'created_by' => $pptsv_obj->created_by,
                'updated_at' => $pptsv_obj->updated_at,
                'updated_by' => $pptsv_obj->updated_by,
            );

		}
        
        //Get pay period totals
		//Sum all Worked Hours
		//Sum all Paid Absences
		//Sum all Dock Absences
		//Sum all Regular/OverTime hours
		$worked_total_time = $edc->getWorkedTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id );
        
		$paid_absence_total_time = $edc->getPaidAbsenceTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id );
        
		$dock_absence_total_time = $edc->getDockAbsenceTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id );
        
		$udtlf = $edc->getRegularAndOverTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id );
        
		if ($udtlf && count($udtlf) > 0 ) {
			//Get overtime policy names
			$over_time_policy_options = $cc->getOverTimePolicyOptions($filter_data['company_id']); 
            
            
			foreach($udtlf as $udt_obj ) {

				if ( $udt_obj->type == 'regular' ) {
					$name = 'Regular Time';
				} else {
                    if ( isset($over_time_policy_options[$udt_obj->over_time_policy_id]) ) {
                        $name = $over_time_policy_options[$udt_obj->over_time_policy_id];
					} else {
                        $name = 'N/A';
					}
				}
                
                
				if ( $udt_obj->type == 'regular' ) {
					$total_time = $udt_obj->total_time + $paid_absence_total_time;
				} else {
                    $total_time = $udt_obj->total_time;
				}
                
				$pay_period_total_rows[] = array( 'name' => $name, 'total_time' => $total_time );
			}
		}

        //==========================================================================
        //print_r($pay_period_obj);
        //exit;

        return view('attendance.timesheet.index', [
            'payPeriod' => $pay_period_obj,
            'filter_data' => $filter_data,
            'calendar_array' => $calendar_array,
            'rows' => $rows,
            'date_break_total_rows' => $date_break_total_rows,
            'date_break_policy_total_rows' => $date_break_policy_total_rows,
            'date_meal_policy_total_rows' => $date_meal_policy_total_rows,
            'date_total_rows' => $date_total_rows,
            //'date_branch_total_rows' => $date_branch_total_rows,
            //'date_department_total_rows' => $date_department_total_rows,
            //'date_job_total_rows' => $date_job_total_rows,
            //'date_job_item_total_rows' => $date_job_item_total_rows,
            'date_premium_total_rows' => $date_premium_total_rows,
            'date_absence_total_rows' => $date_absence_total_rows,
            'punch_exceptions' => $punch_exceptions,
            //'punch_control_exceptions' => $punch_control_exceptions,
            'date_exception_total_rows' => $date_exception_total_rows,
            'date_request_total_rows' => $date_request_total_rows,
            'exception_legend' => $unique_exceptions,
            'pay_period_total_rows' => $pay_period_total_rows,
            'holidays' => $holiday_array,
            'pay_period_locked_rows' => $pay_period_locked_rows,
            'pay_period_worked_total_time' => $worked_total_time,
            'pay_period_paid_absence_total_time' => $paid_absence_total_time,
            'pay_period_dock_absence_total_time' => $dock_absence_total_time,
            'time_sheet_verify' => $time_sheet_verify,
            //'is_assigned_pay_period_schedule' => $is_assigned_pay_period_schedule,
            //'pay_period_id' => optional($pay_period_obj)->getId(),
            //'pay_period_start_date' => optional($pay_period_obj)->getStartDate(),
            //'pay_period_end_date' => optional($pay_period_obj)->getEndDate(),
            //'pay_period_verify_type_id' => optional($pay_period_obj)->getTimeSheetVerifyType(),
            //'pay_period_verify_window_start_date' => optional($pay_period_obj)->getTimeSheetVerifyWindowStartDate(),
            //'pay_period_verify_window_end_date' => optional($pay_period_obj)->getTimeSheetVerifyWindowEndDate(),
            //'pay_period_transaction_date' => optional($pay_period_obj)->getTransactionDate(),
            //'pay_period_is_locked' => optional($pay_period_obj)->getIsLocked(),
            //'pay_period_status_id' => optional($pay_period_obj)->getStatus(),
            //'action_options' => $action_options,
            //'group_options' => $group_options,
            //'branch_options' => $branch_options,
            //'department_options' => $department_options,
            //'user_options' => $user_options,
            //'is_owner' => $is_owner,
            //'is_child' => $is_child,
            //'user_obj' => $user_obj,
            //'start_date' => $start_date,
            //'end_date' => $end_date,
            //'current_time' => $current_time
        ]);
    }

    /**
     * Format the timesheet data array by date.
     *
     * @param array $input_arr
     * @param array $type_arr
     * @param array $calendar_array
     * @param string $name_key
     * @param string|null $id_key
     * @return array
     */
    private function TimeSheetFormatArrayByDate($input_arr, $type_arr, $calendar_array, $name_key, $id_key = null)
    {
        $x = 0;
        $stop = false;
        $max_no_punch_count = count($calendar_array);
        $total_rows = [];

        while ($stop == false) {
            if (isset($type_arr[$x])) {
                $type = $type_arr[$x];
            } else {
                $type = null;
            }
            
            $no_punch_count = 0;

            foreach ($calendar_array as $cal_arr) {
                if (isset($input_arr[$cal_arr['epoch']][$type])) {
                    $total_arr = $input_arr[$cal_arr['epoch']][$type];

                    if ($total_arr[$name_key] == '') {
                        $total_rows[$x]['name'] = __('N/A'); // Localization for "N/A"
                    } else {
                        $total_rows[$x]['name'] = $total_arr[$name_key];
                    }
                    $total_rows[$x]['type_and_policy_id'] = $type;
                    if ($id_key != '') {
                        $total_rows[$x]['id'] = $total_arr[$id_key];
                    }
                } else {
                    $total_arr = null;
                    $no_punch_count++;
                }

                $total_rows[$x]['data'][$cal_arr['epoch']] = $total_arr;
            }

            if ($x == 100 || $no_punch_count == $max_no_punch_count) {
                array_pop($total_rows);
                $stop = true;
            }
            $x++;
        }

        return $total_rows;
    }


    /**
     * Generates a 7-day calendar array starting from the week start day
     *
     * @param string $currentDate The reference date (e.g., "2024-12-11")
     * @param int $startWeekDay The start of the week (1 = Monday, 7 = Sunday)
     * @return array Array of 7 dates for the intended week
     */
 
    public function getDropdownData(){
        $branches = $this->common->commonGetAll('com_branches', '*');

        // Define connections to other tables
        $connections = [
            'com_branch_departments' => [
                'con_fields' => ['branch_id', 'department_id', 'branch_name'],  // Fields to select from connected table
                'con_where' => ['com_branch_departments.department_id' => 'id'],  // Link to the main table (department_id)
                'con_joins' => [
                    'com_branches' => ['com_branches.id', '=', 'com_branch_departments.branch_id'],
                ],
                'con_name' => 'branch_departments',  // Alias to store connected data in the result
                'except_deleted' => false,  // Filter out soft-deleted records
            ],
        ];
    
        // Fetch the department with connections
        $departments = $this->common->commonGetAll('com_departments', ['com_departments.*'], [], [], false, $connections);
        $user_groups = $this->common->commonGetAll('com_employee_groups', '*');

        $users = $this->common->commonGetAll('emp_employees', '*');

        return response()->json([
            'data' => [
                'branches' => $branches,
                'departments' => $departments,
                'user_groups' => $user_groups,
                'users' => $users, //should be filtered by hierarchy
            ]
        ], 200);
    }
    
}