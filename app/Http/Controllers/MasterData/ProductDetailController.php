<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function index()
    {
        return view('app.master-data.product-detail.index');
    }

    public function create()
    {
        return view('app.master-data.product-detail.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.master-data.product-detail.detail', ["objId" => $request->id]);
    }
}
