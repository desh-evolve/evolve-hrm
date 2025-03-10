<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmployeePreferencesController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee preference', ['only' => ['']]);
        $this->middleware('permission:create employee preference', ['only' => ['']]);
        $this->middleware('permission:update employee preference', ['only' => ['']]);
        $this->middleware('permission:delete employee preference', ['only' => ['']]);

        $this->common = new CommonModel();
    }

    public function getEmployeePreferencesByEmployeeId($user_id){
        $ep = $this->common->commonGetById($user_id, 'user_id', 'user_preference', '*');
        return $ep;
    }

}
