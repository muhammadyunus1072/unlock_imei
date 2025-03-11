<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return view('app.master-data.payment-method.index');
    }

    public function create()
    {
        return view('app.master-data.payment-method.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.master-data.payment-method.detail', ["objId" => $request->id]);
    }
}
