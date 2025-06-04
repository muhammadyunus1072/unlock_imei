<?php

namespace App\Livewire\Public\ProductOrder;

use App\Helpers\Alert;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Helpers\FilePathHelper;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Models\MasterData\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;
use App\Models\MasterData\PaymentMethod;
use App\Repositories\Transaction\Transaction\TransactionRepository;
use App\Repositories\MasterData\PaymentMethod\PaymentMethodRepository;
use App\Repositories\Transaction\Transaction\TransactionPaymentRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Throwable;

class Payment extends Component
{
    use WithFileUploads;
    public $objId;
    public $amount;

    public $image;
    public $transaction;
    public $subtotal;
    public $discount;
    public $grand_total;
    public $amount_due;
    public $admin_fee;

    public $payment_method_choices = [];
    public $payment_method;
    public $selected_payment_method = [];

    public function mount()
    {
        $this->getData();

        $this->payment_method_choices = PaymentMethodRepository::getBy([
            ['is_active', true]
        ])
        ->map(function ($payment) {
            return [
                'id' => Crypt::encrypt($payment->id),
                'name' => $payment->name,
                'is_xendit' => $payment->is_xendit,
                'code' => $payment->code,
                'fee_type' => $payment->fee_type,
                'fee_amount' => $payment->fee_amount,
            ];
        })->toArray();
    }

    public function updatedPaymentMethod($value)
    {
        $this->selected_payment_method = collect($this->payment_method_choices)
            ->firstWhere('id', $value);
        consoleLog($this, $this->selected_payment_method);
        if($this->selected_payment_method['fee_type'] === PaymentMethod::TYPE_PERCENTAGE)
        {
            $this->admin_fee = calculateAdminFee($this->subtotal, $this->selected_payment_method['fee_amount']);
            
        }else{
            $this->admin_fee = $this->selected_payment_method['fee_amount'];
            
        }
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('public.order-payment', ['id' => $this->objId]);
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('public.index');
    }

    private function getData()
    {
        if (!$this->objId) {
            return;
        }
        $this->transaction = TransactionRepository::findBy([
            ['id', simple_decrypt($this->objId)]
        ]);
        $this->calculatedTotal();
    }

    private function calculatedTotal()
    {
        $this->subtotal = count($this->transaction->transactionDetails) * $this->transaction->transactionDetails[0]->product_price;
        
        $this->amount_due = $this->transaction->amount_due;
        // DISCOUNT
        $this->discount = $this->transaction->discount;

        $this->grand_total = $this->subtotal - $this->discount;
    }

    public function store()
    {

        try {       
            $this->validate([
                'image' => 'required|image|max:2048',
                'amount' => 'required',
                'payment_method' => 'required',
            ],[
                'image.required' => 'Upload Bukti Pembayaran wajib diisi.',
                'amount.required' => 'Jumlah Dibayar wajib diisi.',
                'payment_method.required' => 'Metode Pembayaran wajib diisi.',
            ]);
            DB::beginTransaction();

            $image = $this->image->store(FilePathHelper::FILE_CUSTOMER_TRANSACTION_PAYMENT, 'public');
            $validatedData = [
                'transaction_id' => simple_decrypt($this->objId),
                'amount' => imaskToValue($this->amount),
                'image' => basename($image),
                'payment_method_id' => Crypt::decrypt($this->payment_method),
            ];
            // dd($validatedData);
            $obj = TransactionPaymentRepository::create($validatedData);

            DB::commit();
            $this->getData();
            Alert::confirmation(
                $this,
                Alert::ICON_SUCCESS,
                "Berhasil",
                "Data Berhasil Disimpan",
                "on-dialog-confirm",
                "on-dialog-cancel",
                "Oke",
                "Tutup",
            );
        } catch (ValidationException $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->validator->errors()->first());
        } catch (Throwable $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.public.product-order.payment');
    }
}
