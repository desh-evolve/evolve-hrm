<?php
namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TableSequence;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class CommonModel extends Model
{
    use HasFactory;

    
    function getLastInsertedID($table_name)
    {
        $data = DB::table($table_name)
        ->orderBy('id', 'desc')
        ->limit(1)
        ->get()
        ->pluck('id')
        ->toArray();
        return $data;
    }
    
    function getAllRecords($table_name)
    {
        $data = DB::table($table_name)
            ->select('*')
            ->where('status !=', 'delete')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        return $data;
    }

    function getAllRecordsWithLimit($table_name)
    {
        $data = DB::table($table_name)
            ->select('*')
            ->where('status !=', 'delete')
            ->orderBy('id', 'desc')
            ->limit(100)
            ->get()
            ->toArray();
        return $data;
    }

    function getSingleRecord($table_name, $id)
    {
        $data = DB::table($table_name)
            ->select('*')
            ->where('id', $id)
            ->where('status !=', 'delete')
            ->get()
            ->toArray();
            // var_dump(->get());
        return $data;
    }

    function destroyRecord($table_name, $id, $data)
    {
        return DB::table($table_name)
            ->where('id', $id)
            ->update($data);
    }

    function updateRecord($table_name, $id, $data)
    {
        return DB::table($table_name)
            ->where('id', $id)
            ->update($data);
    }

    function storeRecord($table_name, $data)
    {   
        return DB::table($table_name)
              ->insertGetId($data);  // This will return the last inserted ID     
    }

    function getAllActiveRecords($table_name)
    {
        $data = DB::table($table_name)
            ->select('*')
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        return $data;
    }

    function getAllInActiveRecords($table_name)
    {
        $data = DB::table($table_name)
            ->select('*')
            ->where('status', 'inactive')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        return $data;
    }

    function getSingleActiveRecord($table_name, $id)
    {
        $data = DB::table($table_name)
            ->select('*')
            ->where('id', $id)
            ->where('status', 'active')
            ->get()
            ->toArray();
        return $data;
    }

    function getAllRecordsWithSelectedFields($table_name, $selection_fields_list)
    {
        $data = DB::table($table_name)
            ->select($selection_fields_list)
            ->where('status !=', 'delete')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        return $data;
    }

    function getAllActiveRecordsWithSelectedFields($table_name, $selection_fields_list)
    {
        $data = DB::table($table_name)
            ->select($selection_fields_list)
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        return $data;
    }

    function getSingleRecordWithSelectedFields($table_name, $id, $selection_fields_list)
    {
        $data = DB::table($table_name)
            ->select($selection_fields_list)
            ->where('id', $id)
            ->where('status !=', 'delete')
            ->get()
            ->toArray();
        return $data;
    }
    
    function getSingleActiveRecordWithSelectedFields($table_name, $id, $selection_fields_list)
    {
        $data = DB::table($table_name)
            ->select($selection_fields_list)
            ->where('id', $id)
            ->where('status', 'active')
            ->get()
            ->toArray();
        return $data;
    }

    function getAllPendingRecords($table_name)
    {
        $data = DB::table($table_name)
            ->select('*')
            ->where('status', 'pending')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        return $data;
    }

    function getIdsForSpecificMatchingKey($table_name, $key_column, $key_value)
    {
        $data = DB::table($table_name)
            ->where($key_column, $key_value)
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->toArray();
        return $data;
    }

    function getAllActiveRecordsByGivenColumn($table_name, $given_column_name, $given_column_value)
    {
        $data = DB::table($table_name)
            ->select('*')
            ->where($given_column_name, $given_column_value)
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        return $data;
    }

    function getMatchingValuesForSpecificColumn($table_name, $given_column_name, $given_column_value_array, $select_column_name)
    {
        $data = DB::table($table_name)
            ->where('status', 'active')
            ->whereIn($given_column_name, $given_column_value_array)
            ->pluck($select_column_name)
            ->toArray();
        return $data;
    }

    function getMatchingForSpecificColumn($table_name, $given_column_name, $given_column_value_array, $select_column_name)
    {
        $data = DB::table($table_name)
            ->where('status !=', 'delete')
            ->where($given_column_name, $given_column_value_array)
            ->pluck($select_column_name)
            ->toArray();
        return $data;
    }

    function getAllMatchingForSpecificColumn($table_name, $given_column_name, $given_column_value)
    {
        $data = DB::table($table_name)
            ->select('*')
            ->where($given_column_name, $given_column_value)
            ->where('status !=', 'delete')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        return $data;
    }


    function getSpecificColumnValueByGivenColumnName($table_name, $given_column_name, $select_column_name, $given_column_value, $delete_flag, $delete_status)
    {
        $data = DB::table($table_name)
            ->select($select_column_name)
            ->where($delete_flag, $delete_status)
            ->where([$given_column_name => $given_column_value])
            ->distinct()
            ->get()
            ->toArray();
        return $data;
    }

    //==================================================================================================
    function getBalanceLeaves($val_1, $val_2)
    {
        $data = DB::table('user_leave')
            ->select('*')
            ->where('leave_type_id', $val_1)
            ->where('employee_id', $val_2)
            ->where('is_deleted', 0)
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        return $data;
    }

    function checkIfAllocateAttendance($id, $description)
    {
        $data = DB::table('payroll_summary')
            ->select('*')
            ->where('description', $description)
            ->where('payroll_month_id', $id)
            ->where('is_deleted', 0)
            ->get()
            ->toArray();
        return $data;
    }
    function getAllEmployee($table_name)
    {
        $data = DB::table($table_name)
            ->select('id')
            ->where('active_status', 1)
            ->where('is_deleted', 0)
            ->distinct()
            ->get()
            ->toArray();
        return $data;
    }
    function timeDiffInMinutes($out_time, $in_time) {
        $time_1 = strtotime($in_time);
        $time_2 = strtotime($out_time);
        return round(abs($time_2 - $time_1) / 60, 2);
    }
    function getMatchingForSpecificColumns($table_name, $given_column_name_1, 
    $given_column_value_array_1,$given_column_name_2, 
    $given_column_value_array_2, $select_column_name)
    {
        $data = DB::table($table_name)
            ->where('is_deleted', 0)
            ->where($given_column_name_1, $given_column_value_array_1)
            ->where($given_column_name_2, $given_column_value_array_2)
            ->pluck($select_column_name)
            ->toArray();
        return $data;
    }
    function getAllMatchingForSpecificColumns($table_name, $given_column_name_1, 
    $given_column_value_array_1,$given_column_name_2, 
    $given_column_value_array_2)
    {
        $data = DB::table($table_name)
            ->where('is_deleted', 0)
            ->where($given_column_name_1, $given_column_value_array_1)
            ->where($given_column_name_2, $given_column_value_array_2)
            ->get()
            ->toArray();
        return $data;
    }
    function getDatesBetween($startDate, $endDate) 
    {
        $dates = [];
    
        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
    
        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->toDateString();
            $currentDate->addDay();
        }
    
        return $dates;
    }
    //==================================================================================================
}