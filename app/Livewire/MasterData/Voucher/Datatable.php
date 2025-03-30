<?php

namespace App\Livewire\MasterData\Voucher;

use Carbon\Carbon;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\MasterData\Voucher;
use App\Permissions\AccessMasterData;
use App\Permissions\PermissionHelper;
use Illuminate\Support\Facades\Crypt;
use App\Traits\Livewire\WithDatatable;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Account\UserRepository;
use App\Repositories\MasterData\Voucher\VoucherRepository;

class Datatable extends Component
{
    use WithDatatable;

    public $isCanUpdate;
    public $isCanDelete;
    public $isCanUpdateBookingTime;
    public $isCanUpdateDetail;

    // Delete Dialog
    public $targetDeleteId;

    public function onMount()
    {
        $authUser = UserRepository::authenticatedUser();
        $this->isCanUpdate = $authUser->hasPermissionTo(PermissionHelper::transform(AccessMasterData::VOUCHER, PermissionHelper::TYPE_UPDATE));
    }

    #[On('on-delete-dialog-confirm')]
    public function onDialogDeleteConfirm()
    {
        if (!$this->isCanDelete || $this->targetDeleteId == null) {
            return;
        }
        
        VoucherRepository::delete(Crypt::decrypt($this->targetDeleteId));
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
                    $id = Crypt::encrypt($item->id);
                    if ($this->isCanUpdate) {
                        $editUrl = route('voucher.edit', $id);
                        $editHtml = "<div class='col-auto mb-2'>
                            <a class='btn btn-primary btn-sm' href='$editUrl'>
                                <i class='ki-duotone ki-notepad-edit fs-1'>
                                    <span class='path1'></span>
                                    <span class='path2'></span>
                                </i>
                                Ubah
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
                'key' => 'code',
                'name' => 'Kode Voucher',
            ],

            [
                'key' => 'type',
                'name' => 'Jenis Voucher',
                'render' => function($item)
                {
                    return Voucher::TYPE_CHOICE[$item->type];
                }
            ],
            [
                'key' => 'amount',
                'name' => 'Nilai Voucher',
                'render' => function($item)
                {
                    return Voucher::TYPE_FIXED === $item->type ? "Rp ".numberFormat($item->amount) : numberFormat($item->amount)." %";
                }
            ],
            [
                'key' => 'start_date',
                'name' => 'Tanggal Mulai',
                'render' => function($item)
                {
                    return $item->start_date ? Carbon::parse($item->start_date)->translatedFormat('d F Y') : "-";
                }
            ],
            [
                'key' => 'end_date',
                'name' => 'Tanggal Akhir',
                'render' => function($item)
                {
                    return $item->end_date ? Carbon::parse($item->end_date)->translatedFormat('d F Y') : "-";
                }
            ],
            [
                'key' => 'is_active',
                'name' => 'Aktif',
                'render' => function($item)
                {
                    return $item->is_active ? "<span class='badge badge-success'>Aktif</span>" : "<span class='badge badge-danger'>Tidak Aktif</span>";
                }
            ],
        ];
    }

    public function getQuery(): Builder
    {
        return VoucherRepository::datatable();
    }

    public function getView(): string
    {
        return 'livewire.master-data.voucher.datatable';
    }
}
