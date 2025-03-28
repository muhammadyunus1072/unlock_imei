<?php

namespace App\Livewire\Report\TransactionReport;

use Livewire\Component;
use App\Traits\Livewire\WithDatatableHeader;
use App\Repositories\Report\TransactionReport\TransactionReportRepository;

class DatatableHeader extends Component
{
    use WithDatatableHeader;

    public $search;
    public $dateType = TransactionReportRepository::DATE_TYPE_DAILY;

    public function getHeaderData()
    {
        $data = TransactionReportRepository::datatable(
            $this->search,
            $this->dateType,
        )->get();

        $transaction_count = $data->count();
        $transaction_amount = $data->sum('grand_total');

        return [
            [
                "col" => 4,
                "name" => "Jumlah Transaksi",
                "value" => $transaction_count
            ],
            [
                "col" => 4,
                "name" => "Nilai Transaksi",
                "value" => $transaction_amount
            ],
        ];
    }
}
