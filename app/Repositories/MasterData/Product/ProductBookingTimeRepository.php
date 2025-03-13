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

    public static function getBookingTimes($product_id, $date)
    {
        return ProductBookingTime::select(
            'product_booking_times.id',
            'product_booking_times.time',
            'booking_details.id as booking_detail_id',
        )
        ->where('product_booking_times.product_id', $product_id)
        ->leftJoin('booking_details', function ($join) use($date) {
            $join->on('product_booking_times.id', '=', 'booking_details.product_booking_time_id')
                ->whereDate('booking_details.booking_date', $date)
                ->whereNull('booking_details.deleted_at');
        })
        ->orderBy('product_booking_times.time', 'ASC')
        ->get();
    }

    public static function datatable()
    {
        return ProductBookingTime::query();
    }
}
