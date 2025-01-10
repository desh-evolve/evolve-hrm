<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmployeeDetailReportController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee detail report', ['only' => [
            'index',
            'form',
            'getDropdownData',
            'getAllEmployeeDetail',
        ]]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('reports.employee_detail_report.filter');
    }

    public function form()
    {
        return view('reports.employee_detail_report.report');
    }

    public function getDropdownData()
    {

        $object_type = $this->common->commonGetAll('object_type', '*');
        $users = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);

        //type => create table
        $type = [
            ['id' => 1, 'name' => 'Tax', 'value' => 'tax'],
            ['id' => 2, 'name' => 'Deduction', 'value' => 'deduction'],
            ['id' => 3, 'name' => 'Other', 'value' => 'other'],
        ];

        return response()->json([
            'data' => [
                'users' => $users,
                'type' => $type,
                'object_type' => $object_type,
            ]
        ], 200);
    }

    public function getAllEmployeeDetail()
    {
        $data = DB::table('hierarchy_control as hc')
            ->select(
                'hc.id as hierarchy_control_id',
                'hc.name as hierarchy_name',
                'hc.description',
                'hc.status',
                DB::raw("(
            SELECT GROUP_CONCAT(ot.name SEPARATOR '\n')
            FROM hierarchy_object_type hot
            INNER JOIN object_type ot ON hot.object_type_id = ot.id
            WHERE hot.hierarchy_control_id = hc.id
        ) as object_types")
            )
            ->where('hc.status', 'active')
            ->get();
        return response()->json(['data' => $data], 200);
    }
}
