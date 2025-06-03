<?php

namespace App\Livewire\MasterData\PaymentMethod;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\MasterData\PaymentMethod;
use App\Repositories\MasterData\PaymentMethod\PaymentMethodRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Validate;

class Detail extends Component
{
    
    public $objId;
    
    #[Validate('required', message: 'Nama Metode Pembayaran Diisi', onUpdate: false)]
    public $name;

    #[Validate('required', message: 'Jenis Biaya Admin Harus Diisi', onUpdate: false)]
    public $fee_type;

    #[Validate('required', message: 'Nilai Biaya Admin Harus Diisi', onUpdate: false)]
    public $fee_amount;

    #[Validate('required', message: 'Kode Harus Diisi', onUpdate: false)]
    public $code;

    public $is_active;

    public $type_choices = [];

    public function mount()
    {
        if($this->objId)
        {
            $product = PaymentMethodRepository::find(Crypt::decrypt($this->objId));
            $this->name = $product->name;
            $this->fee_type = $product->fee_type;
            $this->fee_amount = $product->fee_amount;
            $this->code = $product->code;
            $this->is_active = $product->is_active ? true : false;
        }else{
            $this->fee_type = PaymentMethod::TYPE_PERCENTAGE;
        }

        $this->type_choices = PaymentMethod::TYPE_CHOICE;
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('payment_method.edit', $this->objId);
        }else{
            $this->redirectRoute('payment_method.create');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('payment_method.index');
    }

    public function store()
    {
        try {       
            $this->validate();
            DB::beginTransaction();

            $validatedData = [
                'name' => $this->name,
                'fee_type' => $this->fee_type,
                'code' => $this->code,
                'fee_amount' => imaskToValue($this->fee_amount),
                'is_active' => $this->is_active,
            ];
            if ($this->objId) {
                $objId = Crypt::decrypt($this->objId);
                PaymentMethodRepository::update($objId, $validatedData);
            } else {
                $obj = PaymentMethodRepository::create($validatedData);
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
        return view('livewire.master-data.payment-method.detail');
    }
}
