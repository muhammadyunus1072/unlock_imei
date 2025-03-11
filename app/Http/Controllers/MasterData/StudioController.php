<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Repositories\MasterData\Studio\StudioRepository;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function index()
    {
        return view('app.master-data.studio.index');
    }

    public function create()
    {
        return view('app.master-data.studio.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.master-data.studio.detail', ["objId" => $request->id]);
    }

    public function search(Request $request)
    {
        return StudioRepository::search($request);
    }
}
