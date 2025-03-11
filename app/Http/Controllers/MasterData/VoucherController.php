<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        return view('app.master-data.voucher.index');
    }

    public function create()
    {
        return view('app.master-data.voucher.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.master-data.voucher.detail', ["objId" => $request->id]);
    }
}
