<?php

namespace App\Livewire\Transaction\Transaction;

use Carbon\Carbon;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Permissions\PermissionHelper;
use Illuminate\Support\Facades\Crypt;
use App\Permissions\AccessTransaction;
use App\Traits\Livewire\WithDatatable;
use App\Models\MasterData\PaymentMethod;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Account\UserRepository;
use App\Repositories\Transaction\Transaction\TransactionRepository;

class Datatable extends Component
{
    use WithDatatable;

    public $isCanUpdate;
    public $isCanDelete;
    public $isCanUpdateBookingTime;
    public $isCanUpdateDetail;

    public $dateStart;
    public $dateEnd;

    // Delete Dialog
    public $targetDeleteId;

    public $status = 'Seluruh';

    public function onMount()
    {
        $authUser = UserRepository::authenticatedUser();
        $this->isCanUpdate = $authUser->hasPermissionTo(PermissionHelper::transform(AccessTransaction::TRANSACTION, PermissionHelper::TYPE_UPDATE));

        $this->dateStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    #[On('on-delete-dialog-confirm')]
    public function onDialogDeleteConfirm()
    {
        if (!$this->isCanDelete || $this->targetDeleteId == null) {
            return;
        }
        
        TransactionRepository::delete(simple_decrypt($this->targetDeleteId));
        Alert::success($this, 'Berhasil', 'Data berhasil dihapus');
    }

    #[On('on-delete-dialog-cancel')]
    public function onDialogDeleteCancel()
    {
        $this->targetDeleteId = null;
    }

    public function showDeleteDialog($id)
    {
        $this->targetDeleteId = $id;

        Alert::confirmation(
            $this,
            Alert::ICON_QUESTION,
            "Hapus Data",
            "Apakah Anda Yakin Ingin Menghapus Data Ini ?",
            "on-delete-dialog-confirm",
            "on-delete-dialog-cancel",
            "Hapus",
            "Batal",
        );
    }

    public function getColumns(): array
    {
        return [
            [
                'name' => 'Aksi',
                'sortable' => false,
                'searchable' => false,
                'render' => function ($item) {

                    $editHtml = "";
                    $id = simple_encrypt($item->id);
                    if ($this->isCanUpdate) {
                        $editUrl = route('transaction.edit', $id);
                        $editHtml = "<div class='col-auto mb-2'>
                            <a class='btn btn-primary btn-sm' href='$editUrl'>
                                <i class='ki-duotone ki-eye fs-1'>
                                    <span class='path1'></span>
                                    <span class='path2'></span>
                                    <span class='path3'></span>
                                    <span class='path4'></span>
                                    <span class='path5'></span>
                                    <span class='path6'></span>
                                </i>
                                Detail
                            </a>
                        </div>";
                    }

                    $destroyHtml = "";
                    if ($this->isCanDelete) {
                        $destroyHtml = "<div class='col-auto mb-2'>
                            <button class='btn btn-danger btn-sm m-0' 
                                wire:click=\"showDeleteDialog('$id')\">
                                <i class='ki-duotone ki-trash fs-1'>
                                    <span class='path1'></span>
                                    <span class='path2'></span>
                                    <span class='path3'></span>
                                    <span class='path4'></span>
                                    <span class='path5'></span>
                                </i>
                                Hapus
                            </button>
                        </div>";
                    }
                    

                    $html = "<div class='row'>
                        $editHtml $destroyHtml 
                    </div>";

                    return $html;
                },
            ],
            [
                'key' => 'number',
                'name' => 'No.Transaksi',
            ],
            [
                'key' => 'customer_name',
                'name' => 'Informasi Pengguna',
                'render' => function($item)
                {
                    $social_media = json_decode($item->customer_social_media);
                    return "
                    <p class='mb-0 text-dark fw-normal fs-5 m-0 p-0 lh-1'>Nama &nbsp: {$item->customer_name}</p>
                    <p class='mb-0 text-dark fw-normal fs-5 m-0 p-0 lh-1'>Email &nbsp&nbsp: {$item->customer_email}</p>
                    <p class='mb-0 text-dark fw-normal fs-5 m-0 p-0 lh-1'>Phone : {$item->customer_phone}</p>
                    <p class='mb-0 text-dark fw-normal fs-5 m-0 p-0 lh-1'>Instagram : {$social_media->instagram}</p>
                    <p class='mb-0 text-dark fw-normal fs-5 m-0 p-0 lh-1'>Facebook : {$social_media->facebook}</p>
                    ";
                }
            ],
            [
                'sortable' => false,
                'searchable' => false,
                'name' => 'Informasi Produk',
                'render' => function($item)
                {
                    return $item->transactionDetails->count()." IMEI";
                }
            ],
            [
                'key' => 'grand_total',
                'name' => 'Grand Total',
                'render' => function($item)
                {
                    return "Rp ".numberFormat($item->grand_total);
                }
            ],
            [
                'key' => 'payment_method_name',
                'name' => 'Metode Pembayaran',
                'render' => function($item)
                {
                    $html = "<p class='mb-0'>{$item->payment_method_name}</p>";
                    $html .= "<span class='badge badge-{$item->lastStatus->getStatusStyle()}'>" . $item->lastStatus->name . "</span>";

                    return $html;
                }
            ],
            [
                'key' => 'verified_at',
                'name' => 'Verifikasi',
                'render' => function ($item) {
                    $html = $item->verified_at ? Carbon::parse($item->verified_at)->translatedFormat('d/m/Y H:i') : "<span class='badge badge-warning ms-1'>Belum Check In</span>";
                    return $html;
                },
            ],
        ];
    }

    public function getQuery(): Builder
    {
        return TransactionRepository::datatable($this->search, $this->status, $this->dateStart, $this->dateEnd, true);
    }

    public function getView(): string
    {
        return 'livewire.transaction.transaction.datatable';
    }
}
