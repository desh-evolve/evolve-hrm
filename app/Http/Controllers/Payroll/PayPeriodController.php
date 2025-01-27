<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PayPeriodController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function getById($id){
        $table = 'pay_period';
        $idColumn = 'id';
        $fields = '*';
        $joinArr = [];
        $whereArr = [];

        $res = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr, $whereArr);

        return $res;
    }

}

?>