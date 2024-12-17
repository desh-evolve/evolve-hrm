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
        $currentUser = Auth::user();
        $filter_data = $request->input('filter_data', [
            'company_id' => 1,
            'employee_id' => $currentUser->id,
            'date' => '2024-12-11',
            'group_ids' => -1,
            'branch_ids' => -1,
            'department_ids' => -1,
        ]);

        $data = $this->getTimesheetData($filter_data);

        return view('attendance.timesheet.index', [
            'payPeriod' => $data['payPeriod'],
            'filter_data' => $filter_data
        ]);
    }

    private function getTimesheetData($filter_data)
    {
        $payPeriod = $this->getCurrentPayPeriod($filter_data);
        $punchList = $this->getRenderingData($filter_data);

        return [
            'payPeriod' => $payPeriod
        ];
    }

    private function getCurrentPayPeriod($filter_data)
    {
        $employee_id = $filter_data['employee_id'];
        $date = $filter_data['date'];

        $fields = ['*'];
        $joinArr = [
            'pay_period_schedule' => ['pay_period_schedule.id', '=', 'pay_period.pay_period_schedule_id'],
            'pay_period_schedule_employee' => ['pay_period_schedule_employee.pay_period_schedule_id', '=', 'pay_period_schedule.id']
        ];

        $whereArr = [
            ['DATE(pay_period.start_date)', '<=', '"'.$date.'"'],
            ['DATE(pay_period.end_date)', '>=', '"'.$date.'"'],
            ['pay_period_schedule_employee.employee_id', '=', $employee_id],
            ['pay_period_schedule.status', '=', '"active"'],
        ];

        // Fetch the pay period data
        $pp = $this->common->commonGetAll('pay_period', $fields, $joinArr, $whereArr, true);

        // Return the result (can be empty if no records are found)
        return $pp;
    }

    private function getRenderingData($filter_data) {
        $epc = new EmployeePreferencesController();
        $cdc = new CommonDateController();
        $current_user_prefs = $epc->getEmployeePreferencesByEmployeeId(Auth::user()->id);

        // Get the start day of the week, defaulting to Monday
        $start_date = $cdc->getBeginWeekEpoch( $filter_data['date'], $current_user_prefs[0]->start_week_day );
		$end_date = $cdc->getEndWeekEpoch( $filter_data['date'], $current_user_prefs[0]->start_week_day );

        $calendar_array = $cdc->getCalendarArray( $start_date, $end_date, $current_user_prefs[0]->start_week_day );

        //print_r($calendar_array);exit;

        // Fetch the punch list for the given week range
        $pc = new PunchController();
        $punchList = $pc->getPunchesByEmployeeIdAndStartDateAndEndDate($filter_data['employee_id'], $start_date, $end_date);


        if(count($punchList) > 0){
            foreach($punchList as $punch_obj){
                $user_date_stamp = $punch_obj->user_date_stamp;

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
				if ($punch_obj->punch_type !== 'normal') {

                    $userDateType =& $tmp_date_break_totals[$user_date_stamp][$punch_obj->punch_type];
                
                    if ($punch_obj->punch_status === 'out') {
                        $userDateType['prev'] = $punch_obj->time_stamp;
                    } elseif (isset($userDateType['prev'])) {
                
                        // Ensure both values are well-formed numeric strings
                        $current_time = (string)(float)$punch_obj->time_stamp;
                        $previous_time = (string)(float)$userDateType['prev'];
                
                        $userDateType['total_time'] = isset($userDateType['total_time']) 
                            ? bcadd($userDateType['total_time'], bcsub($current_time, $previous_time)) 
                            : bcsub($current_time, $previous_time);
                
                        $userDateType['total_breaks'] = isset($userDateType['total_breaks']) 
                            ? $userDateType['total_breaks'] + 1 
                            : 1;
                
                        if ($userDateType['total_time'] > 0) {
                            $break_name = ($punch_obj->punch_type == 20) ? 'Lunch Time' : 'Break Time';
                
                            $date_break_totals[$user_date_stamp][$punch_obj->punch_type] = [
                                'break_name' => $break_name,
                                'total_time' => $userDateType['total_time'],
                                'total_breaks' => $userDateType['total_breaks'],
                            ];

                        }
                    }
                    
                    $date_total_break_ids[] = (int)$punch_obj->punch_type;
                }
            }
            
            //Process meal/break total time so it can be properly formatted on the timesheet.
            if ( isset($date_break_totals) ) {
                $date_total_break_ids = array_unique($date_total_break_ids);
                rsort($date_total_break_ids); //Put break time first, then lunch.

                $date_break_total_rows = TimeSheetFormatArrayByDate( $date_break_totals, $date_total_break_ids, $calendar_array, 'break_name');
            }

            $x=0;
            $stop = FALSE;
            $max_no_punch_count = count($calendar_array)*2;
            $punch_day_counter=array();
            $last_punch_control_id=array();
            $no_punch_count=0;
            $max_punch_day_counter=0;
            
            while ( $stop == FALSE ) {
                if ($x % 2 == 0) {
                    $status = 'in'; //In
                    $status_name = 'In';
                } else {
                    $status = 'in'; //Out
                    $status_name = 'Out';
                }
    
                foreach( $calendar_array as $cal_arr ) {
                    $cal_day_epoch = $cal_arr['epoch'];
                    if ( !isset($punch_day_counter[$cal_day_epoch]) ) {
                        $punch_day_counter[$cal_day_epoch] = 0;
                    }
                    if ( !isset($last_punch_control_id[$cal_day_epoch]) ) {
                        $last_punch_control_id[$cal_day_epoch] = 0;
                    }
    
                    if ( isset($punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]])
                            && $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]['status_id'] == $status
                            && $status == 'in' ) {
                        $punch_arr = $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]];
    
                        $last_punch_control_id[$cal_day_epoch] = $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]['punch_control_id'];
    
                        $punch_day_counter[$cal_day_epoch]++;
    
                        $no_punch_count=0;
                    } elseif ( isset($punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]])
                                && $punches[$cal_day_epoch][$punch_day_counter[$cal_day_epoch]]['status_id'] == $status
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
                    $rows[$x]['status_id'] = $status;
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
        $edc = new EmployeeDateController();

        $meal_policy_options = $this->common->commonGetById($filter_data['company_id'], 'company_id', 'meal_policy', '*');

        $employee_date_total = $edc->getByCompanyIDAndUserIdAndStatusAndTypeAndStartDateAndEndDate( $filter_data['company_id'], $filter_data['employee_id'], 'in', 'normal', $start_date, $end_date);

        if ( count($employee_date_total) > 0 ){
            foreach($employee_date_total as $udt_obj) {
                $user_date_stamp = strtotime( $udt_obj->user_date_stamp );

                if ( $udt_obj->meal_policy_id !== FALSE AND isset($meal_policy_options[$udt_obj->meal_policy_id]) ) {
                    $meal_policy = $meal_policy_options[$udt_obj->meal_policy_id];
                } else {
                    $meal_policy = 'No Meal Policy';
                }

                $date_meal_totals[$user_date_stamp][] = array(
                    'date_stamp' => $udt_obj->user_date_stamp,
                    'id' => $udt_obj->id,
                    'user_date_id' => $udt_obj->employee_date_id,
                    'status_id' => $udt_obj->punch_status,
                    'type_id' => $udt_obj->punch_type,
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
			$date_meal_policy_total_rows = TimeSheetFormatArrayByDate( $date_meal_total_group, $date_total_meal_ids, $calendar_array, 'meal_policy');
		}

        $break_policy_options = $this->common->commonGetById($filter_data['company_id'], 'company_id', 'break_policy', '*');

		if ( count($employee_date_total) > 0 ) {
			foreach($employee_date_total as $udt_obj) {
				$user_date_stamp = strtotime( $udt_obj->user_date_stamp );

				if ( $udt_obj->id !== FALSE AND isset($break_policy_options[$udt_obj->id]) ) {
					$break_policy = $break_policy_options[$udt_obj->id];
				} else {
					$break_policy = 'No Break Policy';
				}

				$date_break_policy_totals[$user_date_stamp][] = array(
                    'date_stamp' => $udt_obj->user_date_stamp,
                    'id' => $udt_obj->id,
                    'user_date_id' => $udt_obj->employee_date_id,
                    'status_id' => $udt_obj->punch_status,
                    'type_id' => $udt_obj->punch_type,
                    'over_time_policy_id' => $udt_obj->over_time_policy_id,
                    'break_policy_id' => $udt_obj->break_policy_id,
                    'break_policy' => $break_policy,
                    'department_id' => $udt_obj->departme,
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
					}

					$date_data['total_time'] = $date_data['total_time'] + $prev_total_time;
					$date_break_policy_total_group[$user_date_stamp][$date_data['break_policy_id']] = $date_data;
				}
			}

			$date_total_break_policy_ids = array_unique($date_total_break_policy_ids);
			sort($date_total_break_policy_ids);

			$date_break_policy_total_rows = TimeSheetFormatArrayByDate( $date_break_policy_total_group, $date_total_break_policy_ids, $calendar_array, 'break_policy');
		}

        //Get only system totals.
		if ( count($employee_date_total) > 0 ) {
			foreach($employee_date_total as $udt_obj) {
				$user_date_stamp = strtotime( $udt_obj->user_date_stamp );

				$type_and_policy_id = $udt_obj->punch_type.(int)$udt_obj->over_time_policy_id;

				$date_totals[$user_date_stamp][] = array(
                    'date_stamp' => $udt_obj->user_date_stamp,
                    'id' => $udt_obj->id,
                    'user_date_id' => $udt_obj->employee_date_id,
                    'status_id' => $udt_obj->punch_status,
                    'type_id' => $udt_obj->punch_type,
                    'over_time_policy_id' => $udt_obj->over_time_policy_id,
                    'type_and_policy_id' => $type_and_policy_id,
                    'branch_id' => (int)$udt_obj->branch_id,
                    'department_id' => $udt_obj->department_id,
                    'total_time' => $udt_obj->total_time,
                    'name' => $udt_obj->name,
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

			$date_total_rows = TimeSheetFormatArrayByDate( $date_total_group, $date_total_type_ids, $calendar_array, 'name');
		}

        // ==================================================================================================================
        // job part removed (add if needed later) - no table columns either. you have to add those.
        // ==================================================================================================================
        
        
        // ==================================================================================================================
        // Get Premium Time
        // ==================================================================================================================

        $premium_policy_options = $this->common->commonGetById($filter_data['company_id'], 'company_id', 'premuim_policy', '*');

		$udtlf = TTnew( 'UserDateTotalListFactory' );
		//Get only worked totals.
		if ( count($employee_date_total) > 0 ) {
			foreach($employee_date_total as $udt_obj) {
				$user_date_stamp = strtotime($udt_obj->user_date_stamp);

				if ( $udt_obj->id !== FALSE AND isset($premium_policy_options[$udt_obj->premium_policy_id]) ) {
					$premium_policy = $premium_policy_options[$udt_obj->premium_policy_id];
				} else {
					$premium_policy = 'No Policy';
				}

				$date_premium_totals[$user_date_stamp][] = array(
                    'date_stamp' => $udt_obj->user_date_stamp,
                    'id' => $udt_obj->id,
                    'user_date_id' => $udt_obj->employee_date_id,
                    'status_id' => $udt_obj->punch_status,
                    'type_id' => $udt_obj->punch_type,
                    'over_time_policy_id' => $udt_obj->over_time_policy_id,
                    'premium_policy_id' => $udt_obj->premium_policy_id,
                    'premium_policy' => $premium_policy,
                    'department_id' => $udt_obj->departme,
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

			$date_premium_total_rows = TimeSheetFormatArrayByDate( $date_premium_total_group, $date_total_premium_ids, $calendar_array, 'premium_policy');
			//var_dump($date_premium_total_rows);
		}


        // ==================================================================================================================
        // Get Absences
        // ==================================================================================================================
        
        $absence_policy_options = $this->common->commonGetById($filter_data['company_id'], 'company_id', 'absence_policy', '*');
                
		//Get only worked totals.
        if ( count($employee_date_total) > 0 ) {
			foreach($employee_date_total as $udt_obj) {
				$user_date_stamp = strtotime( $udt_obj->user_date_stamp );

				if ( $udt_obj->absence_policy_id !== FALSE ) {
					$absence_policy = $absence_policy_options[$udt_obj->absence_policy_id];
				} else {
					$absence_policy = 'No Policy';
				}

				$date_absence_totals[$user_date_stamp][] = array(
                    'date_stamp' => $udt_obj->user_date_stamp,
                    'id' => $udt_obj->id,
                    'user_date_id' => $udt_obj->employee_date_id,
                    'status_id' => $udt_obj->punch_status,
                    'type_id' => $udt_obj->punch_type,
                    'over_time_policy_id' => $udt_obj->over_time_policy_id,
                    'absence_policy_id' => $udt_obj->absence_policy_id,
                    'absence_policy' => $absence_policy,
                    'department_id' => $udt_obj->departme,
                    'total_time' => $udt_obj->total_time,
                    //'name' => $udt_obj->getName(),
                    'override' => $udt_obj->override
				);

				$date_absence_total_policy_ids[] = (int)$udt_obj->absence_policy_id;
				$date_total_absence_ids[] = (int)$udt_obj->absence_policy_id;
			}
		} 
		//                die;

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

			$date_absence_total_rows = TimeSheetFormatArrayByDate( $date_absence_total_group, $date_total_absence_ids, $calendar_array, 'absence_policy');
			//var_dump($date_absence_total_rows);
		}

        // ==================================================================================================================
        // Get Exceptions
        // ==================================================================================================================

        $cc = new CommonController();

		$exceptions = $cc->getExceptionsByCompanyIDAndUserIdAndStartDateAndEndDate( $current_company->getID(), $filter_data['employee_id'], $start_date, $end_date);

		$punch_exceptions = array();

		if ( count($exceptions) > 0 ) {
			foreach( $exceptions as $e_obj ) {

				$user_date_stamp = strtotime( $e_obj->user_date_stamp );


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
				if ( !isset($unique_exceptions[$e_obj->getColumn('exception_policy_type_id')])
						OR ( $unique_exceptions[$e_obj->getColumn('exception_policy_type_id')]['severity_id'] < $exception_data_arr['severity_id']) ) {
					$unique_exceptions[$e_obj->getColumn('exception_policy_type_id')] = $exception_data_arr;
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
            $exception_options = $this->common->commonGetAll('exception_policy', '*');
            
			foreach( $unique_exceptions as $unique_exception ) {
				$unique_exceptions[$unique_exception['exception_policy_type_id']]['name'] = $exception_options[$unique_exception['exception_policy_type_id']];
			}

			sort($unique_exceptions);
		}

        // ==================================================================================================================
        // Get Pending Requests
        // ==================================================================================================================

		$requests = $cc->getRequestsByCompanyIDAndUserIdAndStatusAndStartDateAndEndDate( $current_company->getID(), $filter_data['employee_id'], 'pending', $start_date, $end_date);
		if ( count($requests) > 0 ) {
			foreach( $requests as $r_obj ) {
				$user_date_stamp = strtotime( $r_obj->date_stamp );

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

        // ==================================================================================================================
        // Get pay period locked days
        // ==================================================================================================================

        if ( isset($pay_period_obj) AND is_object($pay_period_obj) ) {
			foreach( $calendar_array as $cal_arr ) {
				if ( $cal_arr['epoch'] >= $pay_period_obj->getStartDate()
						AND $cal_arr['epoch'] <= $pay_period_obj->getEndDate() ) {
					//Debug::text('Current Pay Period: '. TTDate::getDate('DATE+TIME', $cal_arr['epoch'] ), __FILE__, __LINE__, __METHOD__,10);
					$pay_period_locked_rows[$cal_arr['epoch']] = $pay_period_obj->getIsLocked();
				} else {
					//Debug::text('Diff Pay Period...', __FILE__, __LINE__, __METHOD__,10);
					//FIXME: Add some caching here perhaps?
					$pplf->getByUserIdAndEndDate( $user_id, $cal_arr['epoch'] );
					if ( $pplf->getRecordCount() > 0 ) {
						$tmp_pay_period_obj = $pplf->getCurrent();
						$pay_period_locked_rows[$cal_arr['epoch']] = $tmp_pay_period_obj->getIsLocked();
					} else {
						//Debug::text('  Did not Found rows...', __FILE__, __LINE__, __METHOD__,10);
						//Allow them to edit payperiods in future.
						$pay_period_locked_rows[$cal_arr['epoch']] = FALSE;
					}
				}

			}
			unset($tmp_pay_period_obj);
		}

        // ==================================================================================================================
        // Get TimeSheet verification
        // ==================================================================================================================

        if ( isset($pay_period_obj) AND is_object($pay_period_obj) ) {
			$is_timesheet_superior = FALSE;
			$pptsvlf = TTnew( 'PayPeriodTimeSheetVerifyListFactory' );
			$pptsvlf->getByPayPeriodIdAndUserId( $pay_period_obj->getId(), $user_id );

			if ( $pptsvlf->getRecordCount() > 0 ) {
				$pptsv_obj = $pptsvlf->getCurrent();
				$pptsv_obj->setCurrentUser( $current_user->getId() );
			} else {
				$pptsv_obj = $pptsvlf;
				$pptsv_obj->setCurrentUser( $current_user->getId() );
				$pptsv_obj->setUser( $user_id );
				$pptsv_obj->setPayPeriod( $pay_period_obj->getId() );
				//$pptsv_obj->setStatus( 45 ); //Pending Verification
			}

			$time_sheet_verify = array(
                'id' => $pptsv_obj->getId(),
                'user_verified' => $pptsv_obj->getUserVerified(),
                'user_verified_date' => $pptsv_obj->getUserVerifiedDate(),
                'status_id' => $pptsv_obj->getStatus(),
                'status' => Option::getByKey( $pptsv_obj->getStatus(), $pptsv_obj->getOptions('status') ),
                'pay_period_id' => $pptsv_obj->getPayPeriod(),
                'user_id' => $pptsv_obj->getUser(),
                'authorized' => $pptsv_obj->getAuthorized(),
                'authorized_users' => $pptsv_obj->getAuthorizedUsers(),
                'is_hierarchy_superior' => $pptsv_obj->isHierarchySuperior(),
                'display_verify_button' => $pptsv_obj->displayVerifyButton(),
                'verification_box_color' => $pptsv_obj->getVerificationBoxColor(),
                'verification_status_display' => $pptsv_obj->getVerificationStatusDisplay(),
                'previous_pay_period_verification_display' => $pptsv_obj->displayPreviousPayPeriodVerificationNotice(),
                'created_date' => $pptsv_obj->getCreatedDate(),
                'created_by' => $pptsv_obj->getCreatedBy(),
                'updated_date' => $pptsv_obj->getUpdatedDate(),
                'updated_by' => $pptsv_obj->getUpdatedBy(),
                'deleted_date' => $pptsv_obj->getDeletedDate(),
                'deleted_by' => $pptsv_obj->getDeletedBy()
            );
		}


        //Get pay period totals
		//Sum all Worked Hours
		//Sum all Paid Absences
		//Sum all Dock Absences
		//Sum all Regular/OverTime hours
		$udtlf = TTnew( 'UserDateTotalListFactory' );
		$worked_total_time = (int)$udtlf->getWorkedTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id );
		Debug::text('Worked Total Time: '. $worked_total_time, __FILE__, __LINE__, __METHOD__,10);

		$paid_absence_total_time = $udtlf->getPaidAbsenceTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id );
		Debug::text('Paid Absence Total Time: '. $paid_absence_total_time, __FILE__, __LINE__, __METHOD__,10);

		$dock_absence_total_time = $udtlf->getDockAbsenceTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id );
		Debug::text('Dock Absence Total Time: '. $dock_absence_total_time, __FILE__, __LINE__, __METHOD__,10);

		$udtlf->getRegularAndOverTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id );
		if ( $udtlf->getRecordCount() > 0 ) {
			//Get overtime policy names
			$otplf = TTnew( 'OverTimePolicyListFactory' );
			$over_time_policy_options = $otplf->getByCompanyIdArray( $current_company->getId(), FALSE );

			foreach($udtlf as $udt_obj ) {
				Debug::text('Type ID: '. $udt_obj->getColumn('type_id') .' OverTime Policy ID: '. $udt_obj->getColumn('over_time_policy_id') .' Total Time: '. $udt_obj->getColumn('total_time'), __FILE__, __LINE__, __METHOD__,10);

				if ( $udt_obj->getColumn('type_id') == 20 ) {
					$name = TTi18n::gettext('Regular Time');
				} else {
					if ( isset($over_time_policy_options[$udt_obj->getColumn('over_time_policy_id')]) ) {
						$name = $over_time_policy_options[$udt_obj->getColumn('over_time_policy_id')];
					} else {
						$name = TTi18n::gettext('N/A');
					}
				}

				if ( $udt_obj->getColumn('type_id') == 20 ) {
					$total_time = $udt_obj->getColumn('total_time') + $paid_absence_total_time;
				} else {
					$total_time = $udt_obj->getColumn('total_time');
				}

				$pay_period_total_rows[] = array( 'name' => $name, 'total_time' => $total_time );
			}
			//var_dump($pay_period_total_rows);
		}

        //==========================================================================
        //print_r($employee_date_total);
        //exit;
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
                $type_id = $type_arr[$x];
            } else {
                $type_id = null;
            }

            $no_punch_count = 0;

            foreach ($calendar_array as $cal_arr) {
                if (isset($input_arr[$cal_arr['epoch']][$type_id])) {
                    $total_arr = $input_arr[$cal_arr['epoch']][$type_id];

                    if ($total_arr[$name_key] == '') {
                        $total_rows[$x]['name'] = __('N/A'); // Localization for "N/A"
                    } else {
                        $total_rows[$x]['name'] = $total_arr[$name_key];
                    }
                    $total_rows[$x]['type_and_policy_id'] = $type_id;
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
        $employee_groups = $this->common->commonGetAll('com_employee_groups', '*');

        $employees = $this->common->commonGetAll('emp_employees', '*');

        return response()->json([
            'data' => [
                'branches' => $branches,
                'departments' => $departments,
                'employee_groups' => $employee_groups,
                'employees' => $employees, //should be filtered by hierarchy
            ]
        ], 200);
    }
    
}