<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommonModel;
use Carbon\Carbon;

class CommonDateController extends Controller
{
    private $common;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function getCalendarArray($start_date, $end_date, $start_day_of_week = 0, $force_weeks = true)
    {
        if (empty($start_date) || empty($end_date)) {
            return false;
        }

        $cal_start_date = $force_weeks
            ? Carbon::parse($this->getBeginWeekEpoch($start_date, $start_day_of_week))->timestamp
            : Carbon::parse($start_date)->startOfDay()->timestamp;

        $cal_end_date = $force_weeks
            ? Carbon::parse($this->getEndWeekEpoch($end_date, $start_day_of_week))->timestamp
            : Carbon::parse($end_date)->endOfDay()->timestamp;

        $prev_month = null;
        $retarr = [];
        $x = 0;

        for ($i = $cal_start_date; $i <= $cal_end_date; $i += 86400) {
            if ($x > 200) {
                break;
            }

            $i = $this->getBeginDayEpoch($i);
            $current_date = Carbon::createFromTimestamp($i);
            $current_month = $current_date->month;
            $current_day_of_week = $current_date->dayOfWeek;

            $isNewMonth = $current_month !== $prev_month && $i >= strtotime($start_date);
            $isNewWeek = $current_day_of_week === $start_day_of_week;

            $day_of_week = $i >= strtotime($start_date) && $i <= strtotime($end_date) ? $current_date->format('D') : null;
            $day_of_month = $i >= strtotime($start_date) && $i <= strtotime($end_date) ? $current_date->day : null;
            $month_name = $i >= strtotime($start_date) && $i <= strtotime($end_date) ? $current_date->format('F') : null;

            $retarr[] = [
                'epoch' => $i,
                'date_stamp' => $current_date->toDateString(),
                'start_day_of_week' => $start_day_of_week,
                'day_of_week' => $day_of_week,
                'day_of_month' => $day_of_month,
                'month_name' => $month_name,
                'month_short_name' => $month_name ? substr($month_name, 0, 3) : null,
                'month' => $current_month,
                'isNewMonth' => $isNewMonth,
                'isNewWeek' => $isNewWeek
            ];

            $prev_month = $current_month;
            $x++;
        }

        return $retarr;
    }

    public static function getBeginWeekEpoch($date = null, $start_day_of_week = 0)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        //print_r($date); echo '<br><br>';
        
        $day_of_week = $date->dayOfWeek;
        
        $offset = $day_of_week < $start_day_of_week
            ? 7 + ($day_of_week - $start_day_of_week)
            : $day_of_week - $start_day_of_week;

        // Get the start of the week date and use the timestamp
        $d = $date->subDays($offset)->startOfDay();

        // Output the date in 'Y-m-d' format
        $formatted_date = $d->format('Y-m-d'); 
 
        return $formatted_date;  // Return the Unix timestamp
    }

    public static function getEndWeekEpoch($date = null, $start_day_of_week = 0)
    {
        // Get the beginning of the week in Y-m-d format
        $begin_week = self::getBeginWeekEpoch($date, $start_day_of_week);

        // Convert the Y-m-d formatted date to a Unix timestamp
        $begin_week_timestamp = Carbon::parse($begin_week)->timestamp;

        // Calculate the end of the week as a timestamp
        $end_of_week_timestamp = Carbon::createFromTimestamp($begin_week_timestamp)
            ->addDays(6)
            ->endOfDay()
            ->timestamp;
            
        // Output the date in 'Y-m-d' format
        $formatted_date = Carbon::createFromTimestamp($end_of_week_timestamp)->format('Y-m-d');

        // Return the Unix timestamp for the end of the week
        return $formatted_date;
    }
    

    public static function getBeginDayEpoch($timestamp = null)
    {
        return Carbon::createFromTimestamp($timestamp ?? Carbon::now()->timestamp)->startOfDay()->timestamp;
    }

    public static function getMiddleDayEpoch($timestamp = null)
    {
        return Carbon::createFromTimestamp($timestamp ?? Carbon::now()->timestamp)->setTime(12, 0)->timestamp;
    }

    public static function getEndDayEpoch($timestamp = null)
    {
        return Carbon::createFromTimestamp($timestamp ?? Carbon::now()->timestamp)->endOfDay()->timestamp;
    }

    public static function getISODateStamp($timestamp)
    {
        return Carbon::createFromTimestamp($timestamp)->format('Ymd');
    }


}
