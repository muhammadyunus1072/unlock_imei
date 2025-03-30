<?php

namespace App\Repositories\MasterData\Product;

use App\Models\MasterData\ProductBookingTime;
use App\Models\Transaction\Transaction;
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
            'transaction_details.id as booking_detail_id',
        )
        ->where('product_booking_times.product_id', $product_id)
        ->leftJoin('transaction_details', function ($join) use($date) {
            $join->on('product_booking_times.id', '=', 'transaction_details.product_booking_time_id')
                ->whereDate('transaction_details.booking_date', $date)
                ->whereNull('transaction_details.deleted_at');
        })
        ->leftJoin('transactions', function ($join) {
            $join->on('transaction_details.transaction_id', '=', 'transactions.id')
                ->where('transactions.status', Transaction::STATUS_PAID);
        })
        ->orderBy('product_booking_times.time', 'ASC')
        ->get();
    }

    public static function datatable()
    {
        return ProductBookingTime::query();
    }
}
