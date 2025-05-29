<?php

namespace App\Repositories\MasterData\Product;

use App\Models\MasterData\Product;
use Illuminate\Support\Facades\DB;
use App\Models\MasterData\ProductDetail;
use App\Repositories\MasterDataRepository;

class ProductRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return Product::class;
    }

    public static function datatable()
    {
        $query = ProductDetail::select(
            'product_details.product_id',
            DB::raw('SUM(product_details.price) as total_price'),
        )
        ->groupBy('product_details.product_id');
        return Product::select(
            'products.*',
            'product_warranties.name as product_warranty_name',  
            'product_details.total_price',          
        )
        ->join('product_warranties', 'product_warranties.id', '=', 'products.product_warranty_id')
        ->leftJoinSub($query, 'product_details', function ($join) {
            $join->on('products.id', '=', 'product_details.product_id');
        });
    }
}
