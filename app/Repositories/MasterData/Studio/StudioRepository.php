<?php

namespace App\Repositories\MasterData\Studio;

use App\Models\MasterData\Studio;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\MasterDataRepository;

class StudioRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return Studio::class;
    }

    public static function search($request)
    {
        $data = Studio::select('id', 'name as text')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', env('QUERY_LIKE'), "%$request->search%");
            })
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get()
            ->toArray();

        foreach ($data as $index => $item) {
            $data[$index]['id'] = Crypt::encrypt($item['id']);
        }

        return json_encode($data);
    }

    public static function datatable()
    {
        return Studio::query();
    }
}
