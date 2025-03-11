<?php

namespace App\Repositories\MasterData\PaymentMethod;

use App\Models\MasterData\PaymentMethod;
use App\Repositories\MasterDataRepository;

class PaymentMethodRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return PaymentMethod::class;
    }

    public static function datatable()
    {
        return PaymentMethod::query();
    }
}
