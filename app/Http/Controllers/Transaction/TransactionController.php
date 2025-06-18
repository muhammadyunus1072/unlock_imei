<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $status = 'Seluruh';
        if ($request->filled('status')) {
            $status = $request->status;
        }
        return view('app.transaction.transaction.index', ["status" => $status]);
    }

    public function create()
    {
        return view('app.transaction.transaction.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.transaction.transaction.detail', ["objId" => $request->id]);
    }
}
