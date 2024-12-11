<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmployeePromotionController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee promotion', ['only' => ['']]);
        $this->middleware('permission:create employee promotion', ['only' => ['']]);
        $this->middleware('permission:update employee promotion', ['only' => ['']]);
        $this->middleware('permission:delete employee promotion', ['only' => ['']]);

        $this->common = new CommonModel();
    }

    

}