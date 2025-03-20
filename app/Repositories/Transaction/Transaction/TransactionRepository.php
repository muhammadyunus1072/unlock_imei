<?php

namespace App\Repositories\Transaction\Transaction;

use App\Models\Transaction\Transaction;
use App\Repositories\MasterDataRepository;

class TransactionRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return Transaction::class;
    }

    public static function findAlreadyProcessed($invoiceNumber)
    {
        return Transaction::where('number', $invoiceNumber)
            ->whereIn('status', ['paid', 'expired']) // Already processed
            ->first();
    }

    public static function datatable()
    {
        return Transaction::query();
    }
}
