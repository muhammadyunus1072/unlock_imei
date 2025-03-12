<?php

namespace App\Livewire\MasterData\Product;

use App\Helpers\Alert;
use App\Permissions\AccessMasterData;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\Livewire\WithDatatable;
use App\Permissions\PermissionHelper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Account\UserRepository;
use App\Repositories\MasterData\Product\ProductRepository;

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
        $this->isCanUpdate = $authUser->hasPermissionTo(PermissionHelper::transform(AccessMasterData::PRODUCT, PermissionHelper::TYPE_UPDATE));
        $this->isCanUpdateBookingTime = $authUser->hasPermissionTo(PermissionHelper::transform(AccessMasterData::PRODUCT_BOOKING_TIME, PermissionHelper::TYPE_UPDATE));
        $this->isCanUpdateDetail = $authUser->hasPermissionTo(PermissionHelper::transform(AccessMasterData::PRODUCT_DETAIL, PermissionHelper::TYPE_UPDATE));
    }

    #[On('on-delete-dialog-confirm')]
    public function onDialogDeleteConfirm()
    {
        if (!$this->isCanDelete || $this->targetDeleteId == null) {
            return;
        }
        
        ProductRepository::delete(Crypt::decrypt($this->targetDeleteId));
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
                        $editUrl = route('product.edit', $id);
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
                    $bookingTimeHtml = "";
                    if ($this->isCanUpdateBookingTime) {
                        $bookingTimeUrl = route('product_booking_time.edit', $id);
                        $bookingTimeHtml = "<div class='col-auto mb-2'>
                            <a class='btn btn-warning btn-sm w-100' href='$bookingTimeUrl'>
                            <i class='ki-duotone ki-time fs-1'>
                                <span class='path1'></span>
                                <span class='path2'></span>
                                <span class='path3'></span>
                            </i>
                            Waktu Booking
                        </a>
                        </div>";
                    }
                    $detailHtml = "";
                    if ($this->isCanUpdateDetail) {
                        $detailUrl = route('product_detail.edit', $id);
                        $detailHtml = "<div class='col-auto mb-2'>
                            <a class='btn btn-success btn-sm w-100' href='$detailUrl'>
                            <i class='ki-duotone ki-element-11 fs-1'>
                                <span class='path1'></span>
                                <span class='path2'></span>
                                <span class='path3'></span>
                                <span class='path4'></span>
                                <span class='path5'></span>
                                <span class='path6'></span>
                                <span class='path7'></span>
                            </i>
                            Detail Produk
                        </a>
                        </div>";
                    }
                    

                    $html = "<div class='row'>
                        $editHtml $bookingTimeHtml $detailHtml $destroyHtml 
                    </div>";

                    return $html;
                },
            ],
            [
                'key' => 'name',
                'name' => 'Nama Produk',
            ],
            [
                'key' => 'price',
                'name' => 'Harga',
                'render' => function ($item) {
                    return number_format($item->price, 0, ',', '.');
                },
            ],
        ];
    }

    public function getQuery(): Builder
    {
        return ProductRepository::datatable();
    }

    public function getView(): string
    {
        return 'livewire.master-data.product.datatable';
    }
}
