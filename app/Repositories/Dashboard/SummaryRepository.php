<?php

namespace App\Repositories\Dashboard;

use App\Models\Service\SendWhatsapp;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use App\Models\Transaction\TransactionStatus;

class SummaryRepository
{
    // SUMMARY
    
    public static function amountNotVerified()
    {
        $queryDetail = TransactionDetail::select(
            'transaction_id',
            DB::raw('COUNT(id) as qty'),
        )
        ->groupBy('transaction_id');
        return Transaction::whereHas('transactionStatuses', function ($query) {
                $query->where('name', TransactionStatus::STATUS_NOT_VERIFIED);
            })
            ->whereDoesntHave('transactionStatuses', function ($q) {
                $q->where('name', TransactionStatus::STATUS_VERIFIED);
            })
            ->joinSub($queryDetail, 'detail', function ($join) {
                $join->on('transactions.id', '=', 'detail.transaction_id');
            })
            ->sum('detail.qty');
    }
    public static function amountWaitingPayment()
    {
        $queryDetail = TransactionDetail::select(
            'transaction_id',
            DB::raw('COUNT(id) as qty'),
        )
        ->groupBy('transaction_id');
        return Transaction::whereHas('transactionStatuses', function ($query) {
                $query->where('name', TransactionStatus::STATUS_ACTIVED)
                    ->orWhere('name', TransactionStatus::STATUS_AWAITING_PAYMENT);
            })
            ->whereDoesntHave('transactionStatuses', function ($q) {
                $q->where('name', TransactionStatus::STATUS_PAID);
            })
            ->joinSub($queryDetail, 'detail', function ($join) {
                $join->on('transactions.id', '=', 'detail.transaction_id');
            })
            ->sum('detail.qty');
    }

    // DAILY SUMMARY
    public static function transactionToday()
    {
        $queryDetail = TransactionDetail::select(
            'transaction_id',
            DB::raw('COUNT(id) as qty'),
        )
        ->groupBy('transaction_id');
        return Transaction::select('total_amount', 'detail.qty')
            ->whereHas('transactionStatuses', function ($q) {
                $q->where('name', TransactionStatus::STATUS_COMPLETED);
            })
            ->whereDate('created_at', now())
            ->joinSub($queryDetail, 'detail', function ($join) {
                $join->on('transactions.id', '=', 'detail.transaction_id');
            })
            ->get();
    }
    public static function transactionDaily()
    {
        $queryDetail = TransactionDetail::select(
            'transaction_id',
            DB::raw('COUNT(id) as qty'),
        )
        ->groupBy('transaction_id');
        return Transaction::selectRaw('DATE(created_at) as transaction_date, SUM(detail.qty) as transaction_amount, SUM(total_amount) as transaction_value')
            ->whereHas('transactionStatuses', function ($q) {
                $q->where('name', TransactionStatus::STATUS_COMPLETED);
            })
            ->where('created_at', '<', now()->format('Y-m-d')." 00:00:00")
            ->joinSub($queryDetail, 'detail', function ($join) {
                $join->on('transactions.id', '=', 'detail.transaction_id');
            })
            ->groupBy('transaction_date')
            ->orderBy('transaction_date', 'DESC');            
    }

    // WEEKLY SUMMARY
    public static function transactionWeekly()
    {
        $queryDetail = TransactionDetail::select(
            'transaction_id',
            DB::raw('COUNT(id) as qty'),
        )
        ->groupBy('transaction_id');
        // 1 Minggu Sebelum
        return Transaction::selectRaw('DAYNAME(created_at) as transaction_day, SUM(detail.qty) as transaction_amount')
        ->whereBetween('created_at', [now()->subDays(6), now()])
        ->joinSub($queryDetail, 'detail', function ($join) {
                $join->on('transactions.id', '=', 'detail.transaction_id');
            })
        ->groupBy('transaction_day')
        ->orderByRaw("FIELD(transaction_day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')")
        ->get()
        ->keyBy('transaction_day');

        // // 1 Minggu Sekarang
        // return Transaction::selectRaw("
        //     DAYNAME(created_at) as transaction_day, 
        //     COUNT(*) as transaction_amount
        // ")
        // ->whereHas('transactionStatuses', function ($q) {
        //     $q->where('name', TransactionStatus::STATUS_COMPLETED);
        // })
        // ->whereBetween('created_at', [Carbon::now()->startOfWeek(Carbon::SUNDAY), Carbon::now()->endOfWeek(Carbon::SATURDAY)])
        // ->groupBy('transaction_day')
        // ->get();
    }
    // MONTHLY SUMMARY
    public static function notificationCurrentMonth()
    {
        return SendWhatsapp::select('price')
            ->where('status', 1)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('price');
    }
    public static function transactionCurrentMonth()
    {
        $queryDetail = TransactionDetail::select(
            'transaction_id',
            DB::raw('COUNT(id) as qty'),
        )
        ->groupBy('transaction_id');
        return Transaction::select('total_amount', 'detail.qty')
            ->whereHas('transactionStatuses', function ($q) {
                $q->where('name', TransactionStatus::STATUS_COMPLETED);
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->joinSub($queryDetail, 'detail', function ($join) {
                $join->on('transactions.id', '=', 'detail.transaction_id');
            })
            ->get();
    }

    public static function transactionMonthly()
    {
        $queryDetail = TransactionDetail::select(
            'transaction_id',
            DB::raw('COUNT(id) as qty'),
        )
        ->groupBy('transaction_id');
        return Transaction::selectRaw('DATE(created_at) as transaction_date, SUM(detail.qty) as transaction_amount, SUM(total_amount) as transaction_value')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->whereHas('transactionStatuses', function ($q) {
            $q->where('name', TransactionStatus::STATUS_COMPLETED);
        })
        ->joinSub($queryDetail, 'detail', function ($join) {
                $join->on('transactions.id', '=', 'detail.transaction_id');
            })
        ->groupBy('transaction_date')
        ->orderBy('transaction_date')
        ->get();
    }

    // YEARLY SUMMARY
    public static function transactionYearly()
    {
        $queryDetail = TransactionDetail::select(
            'transaction_id',
            DB::raw('COUNT(id) as qty'),
        )
        ->groupBy('transaction_id');
        return Transaction::select('total_amount', 'detail.qty')
            ->whereHas('transactionStatuses', function ($q) {
                $q->where('name', TransactionStatus::STATUS_COMPLETED);
            })
            ->joinSub($queryDetail, 'detail', function ($join) {
                $join->on('transactions.id', '=', 'detail.transaction_id');
            })
            ->whereYear('created_at', now())
            ->get();
    }
    public static function transactionByMonth()
    {
        $queryDetail = TransactionDetail::select(
            'transaction_id',
            DB::raw('COUNT(id) as qty')
        )->groupBy('transaction_id');

        $queryNotification = SendWhatsapp::select(
            'transaction_id',
            DB::raw('SUM(price) as price')
        )
        ->where('status', 1)
        ->groupBy('transaction_id');

        return Transaction::selectRaw("
                MONTH(created_at) as month_number,
                DATE_FORMAT(created_at, '%M') as month_name,
                SUM(total_amount) as total_amount,
                SUM(detail.qty) as total_transaction,
                SUM(notification.price) as notification_price
            ")
            ->whereHas('transactionStatuses', function ($q) {
                $q->where('name', TransactionStatus::STATUS_COMPLETED);
            })
            ->whereYear('created_at', now()->year)
            ->joinSub($queryDetail, 'detail', function ($join) {
                $join->on('transactions.id', '=', 'detail.transaction_id');
            })
            ->leftJoinSub($queryNotification, 'notification', function ($join) {
                $join->on('transactions.id', '=', 'notification.transaction_id');
            })
            ->groupBy(DB::raw('MONTH(created_at), DATE_FORMAT(created_at, "%M")'))
            ->orderBy(DB::raw('MONTH(created_at)'));
    }

}
