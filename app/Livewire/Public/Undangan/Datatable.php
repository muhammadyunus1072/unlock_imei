<?php

namespace App\Livewire\Public\Undangan;

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
use App\Repositories\MasterData\Undangan\UndanganRepository;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Exception;

class Datatable extends Component
{
    use WithDatatable;

    public $isCanUpdate;
    public $isCanDelete;
    public $isCanUpdateBookingTime;
    public $isCanUpdateDetail;

    // Delete Dialog
    public $targetDeleteId;

    #[Validate('required', message: 'Nama Undangan Harus Diisi', onUpdate: false)]
    public $name = "";

    public $description = "";

    public function onMount()
    {
        $authUser = UserRepository::authenticatedUser();
        $this->isCanUpdate = true;
        $this->isCanDelete = true;
    }

    #[On('on-delete-dialog-confirm')]
    public function onDialogDeleteConfirm()
    {
        if (!$this->isCanDelete || $this->targetDeleteId == null) {
            return;
        }

        UndanganRepository::delete(Crypt::decrypt($this->targetDeleteId));
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

    public function addUndangan()
    {
        $this->validate();
        try {
            $validatedData = [
                'name' => $this->name,
                'description' => $this->description,
            ];

            DB::beginTransaction();
            $obj = UndanganRepository::create($validatedData);
            $objId = $obj->id;

            DB::commit();

            Alert::confirmation(
                $this,
                Alert::ICON_SUCCESS,
                "Berhasil",
                "Data Berhasil Diperbarui",
                "on-dialog-confirm",
                "on-dialog-cancel",
                "Oke",
                "Tutup",
            );
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
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
                'key' => 'name',
                'name' => 'Nama Undangan',
            ],
            [
                'key' => 'description',
                'name' => 'Deskripsi Undangan',
            ],
            // [
            //     'key' => 'quantity',
            //     'name' => 'Quantity',
            //     'render' => function ($item) {
            //         return number_format($item->quantity, 0, ',', '.');
            //     },
            // ],
        ];
    }

    public function getQuery(): Builder
    {
        return UndanganRepository::datatable($this->name, $this->description);
    }

    public function getView(): string
    {
        return 'livewire.public.undangan.datatable';
    }
}
