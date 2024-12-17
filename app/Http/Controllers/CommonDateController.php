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

    /**
     * Generate a calendar array between start and end dates.
     *
     * @param string $start_date
     * @param string $end_date
     * @param int $start_day_of_week
     * @param bool $force_weeks
     * @return array|false
     */
    public function getCalendarArray($start_date, $end_date, $start_day_of_week = 0, $force_weeks = true)
    {
        if (empty($start_date) || empty($end_date)) {
            return false;
        }

        $cal_start_date = $force_weeks
            ? $this->getBeginWeekEpoch($start_date, $start_day_of_week)
            : Carbon::parse($start_date)->startOfDay()->timestamp;

        $cal_end_date = $force_weeks
            ? $this->getEndWeekEpoch($end_date, $start_day_of_week)
            : Carbon::parse($end_date)->endOfDay()->timestamp;

        $prev_month = null;
        $retarr = [];
        $x = 0;

        for ($i = $cal_start_date; $i <= $cal_end_date; $i += 93600) {
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

    /**
     * Get the beginning of the week timestamp.
     *
     * @param string|null $date
     * @param int $start_day_of_week
     * @return int
     */
    public static function getBeginWeekEpoch($date = null, $start_day_of_week = 0)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $day_of_week = $date->dayOfWeek;
        $offset = $day_of_week < $start_day_of_week
            ? 7 + ($day_of_week - $start_day_of_week)
            : $day_of_week - $start_day_of_week;

        return $date->subDays($offset)->startOfDay()->timestamp;
    }

    /**
     * Get the end of the week timestamp.
     *
     * @param string|null $date
     * @param int $start_day_of_week
     * @return int
     */
    public static function getEndWeekEpoch($date = null, $start_day_of_week = 0)
    {
        $begin_week = self::getBeginWeekEpoch($date, $start_day_of_week);
        return Carbon::createFromTimestamp($begin_week)->addDays(6)->endOfDay()->timestamp;
    }

    /**
     * Get the beginning of the day timestamp.
     *
     * @param int|null $timestamp
     * @return int
     */
    public static function getBeginDayEpoch($timestamp = null)
    {
        return Carbon::createFromTimestamp($timestamp ?? Carbon::now()->timestamp)->startOfDay()->timestamp;
    }

    /**
     * Get the middle of the day timestamp.
     *
     * @param int|null $timestamp
     * @return int
     */
    public static function getMiddleDayEpoch($timestamp = null)
    {
        return Carbon::createFromTimestamp($timestamp ?? Carbon::now()->timestamp)->setTime(12, 0)->timestamp;
    }

    /**
     * Get the end of the day timestamp.
     *
     * @param int|null $timestamp
     * @return int
     */
    public static function getEndDayEpoch($timestamp = null)
    {
        return Carbon::createFromTimestamp($timestamp ?? Carbon::now()->timestamp)->endOfDay()->timestamp;
    }

    /**
     * Get an ISO date stamp from a timestamp.
     *
     * @param int $timestamp
     * @return string
     */
    public static function getISODateStamp($timestamp)
    {
        return Carbon::createFromTimestamp($timestamp)->format('Ymd');
    }


}
