<?php

namespace App\Repositories\MasterData\Product;

use App\Models\MasterData\ProductDetail;
use App\Repositories\MasterDataRepository;

class ProductDetailRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return ProductDetail::class;
    }

    public static function datatable()
    {
        return ProductDetail::query();
    }
}
