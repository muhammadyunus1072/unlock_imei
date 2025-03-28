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

    public static function datatableTransaction($search, $status)
    {
        return Transaction::select(
            'transactions.*',
        )
            ->when($status != 'Seluruh', function($query) use ($status){
                $query->where('status', '=', $status);
            })
            ->when($search, function($query) use($search) {
                $query->where(function($whereQuery) use($search) {
                    $whereQuery->orWhere('customer_email', env('QUERY_LIKE'), '%' . $search . '%')
                        ->orWhere('customer_phone', env('QUERY_LIKE'), '%' . $search . '%')
                        ->orWhereHas('transactionDetails', function ($q) use ($search) {
                            $q->where('transaction_details.product_name', 'LIKE', "%{$search}%")
                            ->orWhere('transaction_details.product_detail_name', 'LIKE', "%{$search}%")
                            ->orWhereHas('studio', function ($sq) use ($search) {
                                $sq->where('name', 'LIKE', "%{$search}%");
                            });
                        });
                });
            })
            ->when(auth()->user()->hasRole(config('template.registration_default_role')), function($query) {
                $query->where('user_id', auth()->user()->id);
            });
    }

    public static function datatable($status)
    {
        return Transaction::when($status != 'Seluruh', function($query) use ($status){
                $query->where('status', '=', $status);
            })
            ->when(auth()->user()->hasRole(config('template.registration_default_role')), function($query) {
                $query->where('user_id', auth()->user()->id);
            });
    }
}
