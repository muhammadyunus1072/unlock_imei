<?php

namespace App\Repositories\Transaction\Transaction;

use Carbon\Carbon;
use App\Models\Transaction\TransactionStatus;
use App\Repositories\MasterDataRepository;

class TransactionStatusRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return TransactionStatus::class;
    }
}
