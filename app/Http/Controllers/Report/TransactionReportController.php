<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;

class TransactionReportController extends Controller
{
    public function index()
    {
        return view('app.report.transaction-report.index');
    }
}
