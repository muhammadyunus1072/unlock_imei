<?php

namespace App\Repositories\MasterData\Product;

use App\Models\MasterData\ProductWarranty;
use App\Repositories\MasterDataRepository;

class ProductWarrantyRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return ProductWarranty::class;
    }

    public static function datatable()
    {
        return ProductWarranty::query();
    }
}
