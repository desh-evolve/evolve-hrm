<?php
namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TableSequence;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class CommonModel extends Model
{
    use HasFactory;

    // desh(2024-10-18)
    public function checkDuplicates($table, $checkColumn, $checkText, $check_where = false, $where = "") {
        $query = DB::table($table)
            ->where($checkColumn, $checkText)
            ->where('status', 'active');
    
        if ($check_where) {
            $query->whereRaw($where);
        }
    
        return $query->exists();
    }
    
    // desh(2024-10-18)
    public function commonGetAll($table, $fields, $joinsArr = [], $whereArr = [], $exceptDel = false, $connections = [], $groupBy = null, $orderBy = null) {
    
        $query = DB::table($table)->select($fields);
    
        foreach ($joinsArr as $joinTable => $on) {
            $query->leftJoin($joinTable, $on[0], $on[1], $on[2]);
        }
    
        foreach ($whereArr as $whereCol => $whereVal) {
            $query->where($whereCol, $whereVal);
        }
    
        if ($exceptDel !== 'all') {
            $statusCondition = $exceptDel ? "$table.status != 'delete'" : "$table.status = 'active'";
            $query->whereRaw($statusCondition);
        }
    
        if ($groupBy) {
            $query->groupBy($groupBy);
        }
    
        if ($orderBy) {
            $query->orderByRaw($orderBy);
        }
    
        $results = $query->get();
    
        foreach ($results as $res) {
            foreach ($connections as $table => $val) {
                $con_query = DB::table($table)->select($val['con_fields']);
    
                if (isset($val['except_deleted']) && $val['except_deleted']) {
                    if ($val['except_deleted'] !== 'all') {
                        $con_query->where("$table.status", '!=', 'delete');
                    }
                } else {
                    $con_query->where("$table.status", 'active');
                }
    
                if (!empty($val['con_joins'])) {
                    foreach ($val['con_joins'] as $conJoinTable => $conJoinOn) {
                        $con_query->leftJoin($conJoinTable, $conJoinOn);
                    }
                }
    
                if (!empty($val['con_where'])) {
                    foreach ($val['con_where'] as $joinCol => $tableCol) {
                        $con_query->where($joinCol, $res->$tableCol);
                    }
                }
    
                $con_name = $val['con_name'];
                $res->$con_name = $con_query->get();
            }
        }
    
        return $results;
    }
    
    // desh(2024-10-18)
    public function commonGetById($id, $idColumn, $table, $fields, $joinsArr = [], $whereArr = [], $exceptDel = false, $connections = [], $groupBy = null, $orderBy = null) {
    
        $query = DB::table($table)->select($fields)->where($idColumn, $id);
    
        foreach ($joinsArr as $joinTable => $on) {
            $query->leftJoin($joinTable, $on[0], $on[1], $on[2]);
        }
    
        foreach ($whereArr as $whereCol => $whereVal) {
            $query->where($whereCol, $whereVal);
        }
    
        if ($exceptDel !== 'all') {
            $statusCondition = $exceptDel ? "$table.status != 'delete'" : "$table.status = 'active'";
            $query->whereRaw($statusCondition);
        }
    
        if ($groupBy) {
            $query->groupBy($groupBy);
        }
    
        if ($orderBy) {
            $query->orderByRaw($orderBy);
        }
    
        $results = $query->get();
    
        foreach ($results as $res) {
            foreach ($connections as $table => $val) {
                $con_query = DB::table($table)->select($val['con_fields']);
    
                if (isset($val['except_deleted']) && $val['except_deleted']) {
                    if ($val['except_deleted'] !== 'all') {
                        $con_query->where("$table.status", '!=', 'delete');
                    }
                } else {
                    $con_query->where("$table.status", 'active');
                }
    
                if (!empty($val['con_joins'])) {
                    foreach ($val['con_joins'] as $conJoinTable => $conJoinOn) {
                        $con_query->leftJoin($conJoinTable, $conJoinOn);
                    }
                }
    
                if (!empty($val['con_where'])) {
                    foreach ($val['con_where'] as $joinCol => $tableCol) {
                        $con_query->where($joinCol, $res->$tableCol);
                    }
                }
    
                $con_name = $val['con_name'];
                $res->$con_name = $con_query->get();
            }
        }
    
        return $results;
    }
    
    // desh(2024-10-18)
    public function commonDelete($id, $whereArr, $title, $table, $deletedBy = true, $recordLog = true) { 
        $arr = ['status' => 'delete'];
        if ($deletedBy) {
            $arr['updated_by'] = Auth::user()->id;
        }
    
        $re = DB::table($table)->where($whereArr)->update($arr);
    
        if ($recordLog) {
            //save in activity log
        }
    
        $status = $re ? 'success' : 'error';
        $action = 'Deleted';
        $message = $re ? "$title $action Successfully!!!" : "$title $action Failed!!!";
    
        return response()->json(['status' => $status, 'message' => $message, 'data' => ['id' => $id]]);
    }

    // desh(2024-10-18)
    public function commonChangeStatus($id, $whereArr, $title, $table, $status, $updatedBy = true, $recordLog = true) { 
        $arr = ['status' => $status];
        if ($updatedBy) {
            $arr['handled_by'] = session('user_id');
        }
    
        $re = DB::table($table)->where($whereArr)->update($arr);
    
        if ($recordLog) {            
            //save in activity log
        }
    
        $stt = $re ? 'ok' : 'error';
        $message = $re ? "$title $stt Successful!!!" : "$title $stt Failed!!!";
    
        return response()->json(['stt' => $stt, 'msg' => $message, 'data' => $id]);
    }
    
    // desh(2024-10-18)
    public function commonSave($table, $inputArr, $id = null, $idColumn = null, $createdBy = true, $updatedBy = true, $recordLog = true) {
        $type = $id ? 'updated' : 'added';

        try {
            if ($createdBy) {
                $type === 'added' && $inputArr['created_by'] = Auth::user()->id;
            }
    
            if ($updatedBy) {
                $inputArr['updated_by'] = Auth::user()->id;
            }
        
            if ($type === 'updated') {
                $re = DB::table($table)->where($idColumn, $id)->update($inputArr);
                $id = $re ? $id : -1;
            } else {
                $re = DB::table($table)->insert($inputArr);
                $id = $re ? DB::getPdo()->lastInsertId() : -1;
            }
        
            if ($recordLog) {
                //save in activity log
            }
        
            return $id;
        } catch (\Throwable $th) {
            return false;
        }
    }
   
    // desh(2024-10-18)
    public function uploadImage($imageId, $imageFile, $uploadPath, $thumbPath, $thumbWidth = 300, $thumbHeight = 300, $saveName = null)
    {
        // Check if the file was uploaded successfully
        if (!$imageFile->isValid()) {
            return response()->json(['stt' => 'error', 'msg' => 'File not uploaded!!!', 'data' => '']);
        }

        // Generate new file name if not provided
        if ($saveName === null) {
            $randomNumber = rand(100, 999);
            $saveName = $imageId . $randomNumber . now()->format('YmdHis');
        }

        // Get file extension
        $fileExt = strtolower($imageFile->getClientOriginalExtension());
        $fileName = $saveName . '.' . $fileExt;

        // Define the upload path
        $uploadFullPath = $uploadPath . '/' . $fileName;

        // Store the uploaded file
        $imageFile->storeAs($uploadPath, $fileName, 'public');

        // Create thumbnail
        $thumbnailPath = $thumbPath . '/' . $saveName . '_t.' . $fileExt;
        $thumbnailImage = Image::make($uploadFullPath)
            ->resize($thumbWidth, $thumbHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        // Save the thumbnail
        $thumbnailImage->save(public_path($thumbnailPath));

        // Prepare image data for return
        $imageData = [
            'imageId' => $imageId,
            'imageName' => $saveName,
            'imageExtension' => '.' . $fileExt,
            'imageOrgPath' => $uploadPath,
            'imageThumbPath' => $thumbPath
        ];

        return response()->json($imageData);
    }


}