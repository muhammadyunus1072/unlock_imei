<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductBookingTimeController extends Controller
{
    public function index()
    {
        return view('app.master-data.product-booking-time.index');
    }

    public function create()
    {
        return view('app.master-data.product-booking-time.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.master-data.product-booking-time.detail', ["objId" => $request->id]);
    }
}
