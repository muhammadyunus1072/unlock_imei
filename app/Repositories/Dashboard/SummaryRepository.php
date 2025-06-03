<?php

namespace App\Repositories\Dashboard;

use Carbon\Carbon;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionStatus;

class SummaryRepository
{
    // SUMMARY
    public static function transactionCount()
    {
        return Transaction::count();
    }
    public static function bookingActive()
    {
        return Transaction::whereHas('transactionStatuses', function ($query) {
                $query->where('name', TransactionStatus::STATUS_COMPLETED);
            })
            ->count();
    }
    public static function bookingPending()
    {
        return 0;
    }
    public static function bookingPaid()
    {
        return 0;
    }
    public static function bookingExpired()
    {
        return 0;
    }

    // DAILY SUMMARY
    public static function transactionToday()
    {
        return Transaction::selectRaw('total_amount')
            // ->where('payment_status', Transaction::PAYMENT_STATUS_PAID)
            ->whereDate('created_at', now())
            ->get();
    }
    public static function transactionDaily()
    {
        return Transaction::selectRaw('DATE(created_at) as transaction_date, COUNT(*) as transaction_amount, SUM(total_amount) as transaction_value')
            // ->where('payment_status', Transaction::PAYMENT_STATUS_PAID)
            ->whereDate('created_at', '!=', now())
            ->groupBy('transaction_date')
            ->orderBy('transaction_date', 'DESC');            
    }

    // WEEKLY SUMMARY
    public static function transactionWeekly()
    {
        // 1 Minggu Sebelum
        return Transaction::selectRaw('DAYNAME(created_at) as transaction_day, COUNT(*) as transaction_amount')
        ->whereBetween('created_at', [now()->subDays(6), now()])
        ->groupBy('transaction_day')
        ->orderByRaw("FIELD(transaction_day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')")
        ->get()
        ->keyBy('transaction_day');

        // 1 Minggu Sekarang
        return Transaction::selectRaw("
            DAYNAME(created_at) as transaction_day, 
            COUNT(*) as transaction_amount
        ")
        // ->where('payment_status', Transaction::PAYMENT_STATUS_PAID)
        ->whereBetween('created_at', [Carbon::now()->startOfWeek(Carbon::SUNDAY), Carbon::now()->endOfWeek(Carbon::SATURDAY)])
        ->groupBy('transaction_day')
        ->get();
    }
    // MONTHLY SUMMARY
    public static function transactionMonthly()
    {
        return Transaction::selectRaw('DATE(created_at) as transaction_date, COUNT(*) as transaction_amount, SUM(total_amount) as transaction_value')
        ->whereMonth('created_at', now()->month)
        ->groupBy('transaction_date')
        // ->where('payment_status', Transaction::PAYMENT_STATUS_PAID)
        ->orderBy('transaction_date')
        ->get();
    }

    // YEARLY SUMMARY
    public static function transactionYearly()
    {
        return Transaction::selectRaw('total_amount')
            // ->where('payment_status', Transaction::PAYMENT_STATUS_PAID)
            ->whereYear('created_at', now())
            ->get();
    }
}
