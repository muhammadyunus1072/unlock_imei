<?php

namespace App\Repositories\Report\TransactionReport;

use App\Models\Transaction\Transaction;

class TransactionReportRepository 
{
    CONST DATE_TYPE_DAILY = 'DAILY';
    CONST DATE_TYPE_WEEKLY = 'WEEKLY';
    CONST DATE_TYPE_MONTHLY = 'MONTHLY';
    CONST DATE_TYPE_YEARLY = 'YEARLY';

    CONST DATE_TYPE_CHOICE = [
        self::DATE_TYPE_DAILY => 'HARIAN',
        self::DATE_TYPE_WEEKLY => 'MINGGUAN',
        self::DATE_TYPE_MONTHLY => 'BULANAN',
        self::DATE_TYPE_YEARLY => 'TAHUNAN',
    ];

    protected static function className(): string
    {
        return Transaction::class;
    }

    public static function datatable($search, $dateType)
    {
        
        return Transaction::select(
            'transactions.*'
        )
            ->when($dateType == self::DATE_TYPE_DAILY, function ($query) {
                $query->whereDate('created_at', now());
            })
            ->when($dateType == self::DATE_TYPE_WEEKLY, function ($query) {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            })
            ->when($dateType == self::DATE_TYPE_MONTHLY, function ($query) {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            })
            ->when($dateType == self::DATE_TYPE_YEARLY, function ($query) {
                $query->whereYear('created_at', now()->year);
            })
            ->where('status', Transaction::STATUS_PAID)
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
            });
    }
}
