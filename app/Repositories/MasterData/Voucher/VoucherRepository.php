<?php

namespace App\Repositories\MasterData\Voucher;

use App\Models\MasterData\Voucher;
use App\Repositories\MasterDataRepository;

class VoucherRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return Voucher::class;
    }

    public static function findByCode($code)
    {
        return Voucher::where('code', $code)
        ->where('is_active', true)
        ->where(function ($q) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', now());
        })
        ->where(function ($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        })
        ->first();
    }

    public static function datatable()
    {
        return Voucher::query();
    }
}
