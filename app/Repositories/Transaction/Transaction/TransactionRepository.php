<?php

namespace App\Repositories\Transaction\Transaction;

use Carbon\Carbon;
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
            ->whereIn('payemnt_status', ['paid', 'expired']) // Already processed
            ->first();
    }

    public static function datatable($search, $status, $dateStart, $dateEnd, $allUser = false)
    {
        // dd(["$dateStart 00:00:00", "$dateEnd 23:59:59"]);
        return Transaction::when($status != 'Seluruh', function($query) use ($status){
                $query->where('payment_status', '=', $status);
            })
            ->whereBetween('created_at', [Carbon::parse($dateStart)->startOfDay(), Carbon::parse($dateEnd)->endOfDay()])
            ->when($search, function($query) use($search) {
                $query->where(function($whereQuery) use($search) {
                    $whereQuery->orWhere('customer_email', env('QUERY_LIKE'), '%' . $search . '%')
                        ->orWhere('customer_phone', env('QUERY_LIKE'), '%' . $search . '%')
                        ->orWhereHas('transactionDetails', function ($q) use ($search) {
                            $q->where('transaction_details.product_name', env('QUERY_LIKE'), "%{$search}%");
                        });
                });
            })
            ->when(auth()->user()->hasRole(config('template.registration_default_role')), function($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->when(auth()->user()->hasRole(config('template.admin_role')) && !$allUser, function($query) {
                $query->where('user_id', auth()->user()->id);
            });
    }
}
