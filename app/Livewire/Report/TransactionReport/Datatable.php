<?php

namespace App\Livewire\Report\TransactionReport;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Crypt;
use App\Traits\Livewire\WithDatatable;
use App\Exports\LivewireDatatableExport;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Livewire\WithDatatableExport;
use App\Repositories\Report\TransactionReport\TransactionReportRepository;

class Datatable extends Component
{
    use WithDatatable, WithDatatableExport;

    public $dateType = TransactionReportRepository::DATE_TYPE_DAILY;
    public $dateChoice = TransactionReportRepository::DATE_TYPE_CHOICE;

    public function updatedSearch()
    {
        $this->dispatch('on-search-updated', [
            'search' => $this->search,
        ]);
    }

    /*
    | WITH DATATABLE
    */
    public function getColumns(): array
    {
        
        return [
            [
                'key' => 'number',
                'name' => 'No.Transaksi',
            ],
            [
                'key' => 'customer_name',
                'name' => 'Informasi Pengguna',
                'render' => function($item)
                {
                    return "
                    <p class='mb-0 text-dark fw-normal fs-5 m-0 p-0 lh-1'>Nama  : {$item->customer_name}</p>
                    <p class='mb-0 text-dark fw-normal fs-5 m-0 p-0 lh-1'>Email : {$item->customer_email}</p>
                    <p class='mb-0 text-dark fw-normal fs-5 m-0 p-0 lh-1'>Phone : {$item->customer_phone}</p>
                    ";
                }
            ],
            [
                'sortable' => false,
                'searchable' => false,
                'name' => 'Informasi Booking',
                'render' => function($item)
                {
                    return "
                    <p class='mb-0 text-dark fw-bold fs-4 m-0 p-0 lh-1'>{$item->transactionDetailSample['product_name']}</p>
                    <small>".Carbon::parse($item->transactionDetailSample['booking_date'])->translatedFormat('l, d F Y')." ".Carbon::parse($item->transactionDetailSample['product_booking_time_time'])->translatedFormat('H:i')."</small><br>
                    <span class='badge badge-info'>{$item->transactionDetailSample->studio->name} - {$item->transactionDetailSample->studio->city}</span>
                    ";
                }
            ],
            [
                'sortable' => false,
                'searchable' => false,
                'name' => 'Data Product',
                'render' => function($item)
                {
                    $html = '';
                    foreach ($item->transactionDetails as $index => $detail) {
                        $html .= "<span class='badge badge-secondary ms-1'>{$detail->product_detail_name}</span>" . (count($item->transactionDetails) > $index +1 ? ',' : '');
                    }
                    return $html;
                }
            ],
            [
                'key' => 'payment_method_name',
                'name' => 'Metode Pembayaran',
                'render' => function($item)
                {
                    $html = "<p class='mb-0'>{$item->payment_method_name} </p>";
                    $html .= "<span class='badge badge-{$item->getStatusBadge()}'> STATUS " . $item->status . "</span>";

                    return $html;
                }
            ],
            [
                'key' => 'scanned_at',
                'name' => 'Check In',
                'render' => function ($item) {
                    $html = $item->scanned_at ? Carbon::parse($item->scanned_at)->translatedFormat('d/m/Y H:i') : "<span class='badge badge-warning ms-1'>Belum Check In</span>";
                    

                    return $html;
                },
            ],
            [
                'key' => 'grand_total',
                'name' => 'Grand Total',
                'render' => function($item)
                {
                    return "Rp ".numberFormat($item->grand_total);
                },
                'export_footer_type' => LivewireDatatableExport::FOOTER_TYPE_SUM,
                'export_footer_data' => function ($item) {
                    return $item->grand_total;
                },
                'export_footer_format' => function ($footerValue, $exportType) {
                    return $exportType == LivewireDatatableExport::EXPORT_PDF ? "Rp ".numberFormat($footerValue) : $footerValue;
                },
            ],
        ];
    }

    public function getQuery(): Builder
    {
        return TransactionReportRepository::datatable(
            $this->search,
            $this->dateType,
        );
    }

    public function getView(): string
    {
        return 'livewire.report.transaction-report.datatable';
    }

    /*
    | WITH DATATABLE EXPORT
    */
    function datatableExportFileName(): string
    {
        $date_info = "";
        switch ($this->dateType) {
            case TransactionReportRepository::DATE_TYPE_DAILY:
                $date_info = Carbon::now()->translatedFormat('Y-m-d');
                break;
            case TransactionReportRepository::DATE_TYPE_WEEKLY:
                $date_info = Carbon::parse(now()->startOfWeek())->translatedFormat('Y-m-d') .' sd ' . Carbon::parse(now()->endOfWeek())->translatedFormat('Y-m-d');
                break;
            case TransactionReportRepository::DATE_TYPE_MONTHLY:
                $date_info = Carbon::parse(now()->startOfMonth())->translatedFormat('Y-m-d') .' sd ' . Carbon::parse(now()->endOfMonth())->translatedFormat('Y-m-d');
                break;
            case TransactionReportRepository::DATE_TYPE_YEARLY:
                $date_info = Carbon::parse(now()->startOfYear())->translatedFormat('Y-m-d') .' sd ' . Carbon::parse(now()->endOfYear())->translatedFormat('Y-m-d');
                break;
            
        }
        return 'Laporan Transaksi ' . TransactionReportRepository::DATE_TYPE_CHOICE[$this->dateType] . " " . $date_info;
    }

    function datatableExportTitle(): string
    {
        return 'Laporan Transaksi';
    }

    function datatableExportSubtitle(): array
    {
        return [
            'Jenis Laporan' => TransactionReportRepository::DATE_TYPE_CHOICE[$this->dateType],
        ];
    }
}
