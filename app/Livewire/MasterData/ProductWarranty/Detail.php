<?php

namespace App\Livewire\MasterData\ProductWarranty;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Repositories\MasterData\Product\ProductWarrantyRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Validate;

class Detail extends Component
{
    use WithFileUploads;
    
    public $objId;

    #[Validate('required', message: 'Nama Garansi Harus Diisi', onUpdate: false)]
    public $name;
    public $days;

    public function mount()
    {
        if($this->objId){
            $product_warranty = ProductWarrantyRepository::find(Crypt::decrypt($this->objId));
            $this->name = $product_warranty->name;
            $this->days = valueToImask($product_warranty->days);
        }
    }
 #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('product_warranty.edit', $this->objId);
        } else {
            $this->redirectRoute('product_warranty.create');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('product_warranty.index');
    }

    public function store()
    {
        try {       
            DB::beginTransaction();
            $validatedData = [
                'name' => $this->name,
                'days' => imaskToValue($this->days),
            ];
            if ($this->objId) {
                $objId = Crypt::decrypt($this->objId);
                ProductWarrantyRepository::update($objId, $validatedData);
            } else {
                $obj = ProductWarrantyRepository::create($validatedData);
                $objId = $obj->id;
            }

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

    public function render()
    {
        return view('livewire.master-data.product-warranty.detail');
    }
}
