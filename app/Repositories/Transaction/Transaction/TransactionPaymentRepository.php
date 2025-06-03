<?php

namespace App\Repositories\Transaction\Transaction;

use Carbon\Carbon;
use App\Models\Transaction\TransactionPayment;
use App\Repositories\MasterDataRepository;

class TransactionPaymentRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return TransactionPayment::class;
    }
}
