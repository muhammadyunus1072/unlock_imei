<?php

namespace App\Repositories\MasterData\Product;

use App\Models\MasterData\ProductBookingTime;
use App\Repositories\MasterDataRepository;

class ProductBookingTimeRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return ProductBookingTime::class;
    }

    public static function datatable()
    {
        return ProductBookingTime::query();
    }
}
