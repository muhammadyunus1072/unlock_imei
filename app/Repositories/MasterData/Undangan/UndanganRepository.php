<?php

namespace App\Repositories\MasterData\Undangan;

use App\Models\MasterData\Undangan;
use Illuminate\Support\Facades\DB;
use App\Models\MasterData\UndanganDetail;
use App\Repositories\MasterDataRepository;

class UndanganRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return Undangan::class;
    }

    public static function datatable($name, $description)
    {
        return Undangan::when($name, function ($query) use ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%')
                ->orWhere('description', 'LIKE', '%' . $name . '%');
        })
            ->when($description, function ($query) use ($description) {
                $query->where('name', 'LIKE', '%' . $description . '%')
                    ->orWhere('description', 'LIKE', '%' . $description . '%');
            });
    }
}
