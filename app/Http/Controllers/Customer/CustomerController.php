<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('app.customer.customer.index');
    }

    public function create()
    {
        return view('app.customer.customer.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.customer.customer.detail', ["objId" => $request->id]);
    }
}
