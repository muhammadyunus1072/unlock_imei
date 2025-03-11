<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('app.master-data.product.index');
    }

    public function create()
    {
        return view('app.master-data.product.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.master-data.product.detail', ["objId" => $request->id]);
    }
}
