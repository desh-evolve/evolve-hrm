<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class JobHistoryController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee job history', ['only' => [
            'index', 
            'getJobHistoryByEmployeeId', 
        ]]);
        $this->middleware('permission:create employee job history', ['only' => ['createJobHistory']]);
        $this->middleware('permission:update employee job history', ['only' => ['updateJobHistory']]);
        $this->middleware('permission:delete employee job history', ['only' => ['deleteJobHistory']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('employee.job_history');
    }

    public function getJobHistoryByEmployeeId(){}
    public function createJobHistory(){}
    public function updateJobHistory(){}
    public function deleteJobHistory(){}

}
