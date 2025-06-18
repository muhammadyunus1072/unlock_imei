<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Traits\Livewire\WithDatatableHeader;
use App\Repositories\Dashboard\SummaryRepository;
use App\Repositories\Core\Setting\SettingRepository;
use App\Settings\SettingFinance;

class CurrentMonthSummary extends Component
{
    use WithDatatableHeader;

    public function getHeaderData()
    {
        $transactionCurrentMonth = SummaryRepository::transactionCurrentMonth();
        $currentMonthAmount = $transactionCurrentMonth->sum('qty');
        $currentMonthValue = collect($transactionCurrentMonth)->sum('total_amount');
        
        $setting = SettingRepository::findBy(whereClause: [['name', SettingFinance::NAME]]);
        
        $settings = json_decode($setting->setting);
        $web = $currentMonthAmount * $settings->web;

        $notificationCurrentMonth = SummaryRepository::notificationCurrentMonth();
        $notification = $notificationCurrentMonth + $settings->adsmedia;
        return [
            [
                "col" => 6,
                "name" => "Jumlah Transaksi IMEI",
                "value" => $currentMonthAmount
            ],
            [
                "col" => 6,
                "name" => "Nilai Transaksi",
                "value" => $currentMonthValue
            ],
            [
                "col" => 4,
                "name" => "Biaya Web",
                "value" => $web
            ],
            [
                "col" => 4,
                "name" => "Biaya Notifikasi",
                "value" => $notification
            ],
            [
                "col" => 4,
                "name" => "Nilai Akhir",
                "value" => $currentMonthValue - $web - $notification
            ],
        ];
    }
}
