<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('app.layouts.panel');
    }

}
