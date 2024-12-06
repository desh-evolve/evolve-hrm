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

class TimeSheetController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view timesheet', ['only' => ['index']]);
        $this->middleware('permission:create timesheet', ['only' => ['']]);
        $this->middleware('permission:update timesheet', ['only' => ['']]);
        $this->middleware('permission:delete timesheet', ['only' => ['']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('attendance.timesheet.index');
    }

}