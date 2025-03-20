<?php

namespace App\Repositories\Transaction\Transaction;

use App\Models\Transaction\TransactionDetail;
use App\Repositories\MasterDataRepository;

class TransactionDetailRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return TransactionDetail::class;
    }

    public static function datatable()
    {
        return TransactionDetail::query();
    }
}
