<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductWarrantyController extends Controller
{
    public function index()
    {
        return view('app.components.coba');
    }

    public function create()
    {
        return view('app.master-data.product-warranty.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.master-data.product-warranty.detail', ["objId" => $request->id]);
    }
}
