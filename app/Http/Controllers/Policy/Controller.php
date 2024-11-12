<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;
}