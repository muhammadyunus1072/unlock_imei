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

    public static function datatable()
    {
        return Voucher::query();
    }
}
