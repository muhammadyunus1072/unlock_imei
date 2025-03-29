<?php

namespace App\Livewire\Public\Transaction;

use Carbon\Carbon;
use Livewire\Component;
use App\Traits\Livewire\WithDatatable;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Transaction\Transaction\TransactionRepository;

class Datatable extends Component
{
    use WithDatatable;

    public $status = 'Seluruh';
    public $dateStart;
    public $dateEnd;

    public function onMount()
    {
        $this->dateStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function getColumns(): array
    {
        return [
            [
                'key' => 'number',
                'name' => 'No.Transaksi',
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
                        $html .= "<span class='badge badge-secondary ms-1'>{$detail->product_detail_name}</span>";
                    }
                    return $html;
                }
            ],
            [
                'key' => 'payment_method_name',
                'name' => 'Metode Pembayaran',
                'render' => function($item)
                {
                    $html = "<p class='mb-0'>{$item->payment_method_name}</p>";
                    $html .= "<span class='badge badge-{$item->getStatusBadge()}'>" . $item->status . "</span>";

                    return $html;
                }
            ],
            [
                'key' => 'scanned_at',
                'name' => 'Check In',
                'render' => function ($item) {
                    $html = $item->scanned_at ? Carbon::parse($item->scanned_at)->translatedFormat('d/m/Y H:i') : "<span class='badge badge-primary ms-1'>Belum Check In</span>";
                    

                    return $html;
                },
            ],
            [
                'name' => 'Aksi',
                'sortable' => false,
                'searchable' => false,
                'render' => function ($item) {
                    $actionHtml = $item->getAction();
                    
                    $html = "<div class='row'>
                        $actionHtml
                    </div>";

                    return $html;
                },
            ],
        ];
    }

    public function getQuery(): Builder
    {
        return TransactionRepository::datatable($this->search, $this->status, $this->dateStart, $this->dateEnd, false);
    }

    public function getView(): string
    {
        return 'livewire.public.transaction.datatable';
    }
}
