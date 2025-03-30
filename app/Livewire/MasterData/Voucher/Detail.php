<?php

namespace App\Livewire\MasterData\Voucher;

use Exception;
use Carbon\Carbon;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Models\MasterData\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\MasterData\Voucher\VoucherRepository;

class Detail extends Component
{
    
    public $objId;
    
    #[Validate('required', message: 'Kode Voucher Harus Diisi', onUpdate: false)]
    public $code;

    #[Validate('required', message: 'Jenis Voucher Harus Diisi', onUpdate: false)]
    public $type;

    #[Validate('required', message: 'Nilai Voucher Harus Diisi', onUpdate: false)]
    public $amount;

    public $start_date;
    public $end_date;

    public $is_active;

    public $type_choices = [];

    public function mount()
    {
        if($this->objId)
        {
            $voucher = VoucherRepository::find(Crypt::decrypt($this->objId));
            $this->type = $voucher->type;
            $this->amount = $voucher->amount;
            $this->code = $voucher->code;
            $this->start_date = $voucher->start_date ? Carbon::parse($voucher->start_date)->format('Y-m-d') : null;
            $this->end_date = $voucher->end_date ? Carbon::parse($voucher->end_date)->format('Y-m-d') : null;
            $this->is_active = $voucher->is_active ? true : false;
        }else{
            $this->type = Voucher::TYPE_FIXED;
        }

        $this->type_choices = Voucher::TYPE_CHOICE;
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('voucher.edit', $this->objId);
        }else{
            $this->redirectRoute('voucher.create');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('voucher.index');
    }

    public function store()
    {
        try {       
            $this->validate();
            DB::beginTransaction();

            $validatedData = [
                'start_date' => $this->start_date ? $this->start_date : null,
                'end_date' => $this->end_date ? $this->end_date : null,
                'type' => $this->type,
                'code' => $this->code,
                'amount' => imaskToValue($this->amount),
                'is_active' => $this->is_active,
            ];
            
            if ($this->objId) {
                $objId = Crypt::decrypt($this->objId);
                VoucherRepository::update($objId, $validatedData);
            } else {
                $obj = VoucherRepository::create($validatedData);
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
        return view('livewire.master-data.voucher.detail');
    }
}
