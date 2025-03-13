<?php

namespace App\Repositories\Booking;


use App\Models\Booking\Booking;
use App\Repositories\MasterDataRepository;

class BookingRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return Booking::class;
    }

    public static function datatable()
    {
        return Booking::query();
    }
}
