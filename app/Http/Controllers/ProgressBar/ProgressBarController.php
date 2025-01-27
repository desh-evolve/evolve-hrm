<?php

namespace App\Http\Controllers\ProgressBar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Carbon\Carbon;

class ProgressBarController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function initProgressBar($data)
    {
        print_r($data);exit;
        // Retrieve session data
        $action = session('action');
        $next_page = session('next_page');
        $pay_period_ids = session('pay_period_ids');
        $filter_user_id = session('filter_user_id');
        $pay_stub_ids = session('pay_stub_ids');
        $data = session('data');

        //$action = strtolower($request->query('action', ''));
        $comment = __('Test Progress Bar...');

        switch ($action) {
            case 'recalculate_company':
            case 'recalculate_employee':
            case 'generate_paystubs':
                echo 'Generate Pay Stubs!';
                //exit;
                break;
            case 'generate_paymiddle':
            case 'recalculate_paystub_ytd':
            case 'add_mass_punch':
            case 'add_mass_schedule':
            case 'add_mass_schedule_npvc':
            case 'recalculate_accrual_policy':
            case 'process_late_leave':
            default:
                echo 'Default';
                break;
        }
        
        return $comment;

        $progressData = [
            'comment' => $comment,
            'next_page' => $request->query('next_page', ''),
        ];

    }

}

?>