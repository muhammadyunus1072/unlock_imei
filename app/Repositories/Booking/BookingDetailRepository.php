<?php

namespace App\Repositories\Booking;


use App\Models\Booking\BookingDetail;
use App\Repositories\MasterDataRepository;

class BookingDetailRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return BookingDetail::class;
    }

    public static function getBooked($product_id, $date)
    {
        return BookingDetail::select(
            'product_booking_time_id',
        )
            ->where('product_id', $product_id)
            ->whereDate('booking_date', $date)
            ->get();
    }

    public static function datatable()
    {
        return BookingDetail::query();
    }
}
