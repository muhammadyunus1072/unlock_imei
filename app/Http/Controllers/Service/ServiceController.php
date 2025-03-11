<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view('app.service.service.index');
    }

    public function create()
    {
        return view('app.service.service.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.service.service.detail', ["objId" => $request->id]);
    }
}
