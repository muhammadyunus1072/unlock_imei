<?php

namespace App\Livewire\Transaction\Transaction;

use App\Helpers\Alert;
use Livewire\Component;
use App\Helpers\FilePathHelper;
use App\Models\MasterData\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;
use App\Models\MasterData\PaymentMethod;
use App\Repositories\Transaction\Transaction\TransactionDetailRepository;
use App\Repositories\Transaction\Transaction\TransactionRepository;
use Livewire\Attributes\On;

class Detail extends Component
{
    
    public $objId;
    public $cancellation_reason;
    public $verified_at;
    public $targetActiveId;

    // Transaction Information
    public $transaction = [];
    public $transaction_details = [];

    public $transaction_status_badge;
    public $transaction_status_name;
    public $payment_status_badge;
    public $payment_status_name;
    public $customer_ig;
    public $customer_fb;
    public $customer_ktp_url;
    public $customer_lat;
    public $customer_lng;
    public $subtotal = 0;
    public $admin_fee = 0;
    public $discount = 0;
    public $grand_total = 0;


    public function mount()
    {
        if($this->objId)
        {
            $this->getData();
        }
    }

    #[On('on-verify-dialog-confirm')]
    public function onVerifyDialogConfirm()
    {
        $this->verifyHandler();
    }

    #[On('on-verify-dialog-cancel')]
    public function onVerifyDialogCancel()
    {
    }

    public function showVerifyDialog()
    {
        Alert::confirmation(
            $this,
            Alert::ICON_QUESTION,
            "Verifikasi Data",
            "Apakah Anda Yakin Ingin Memverifikasi Data Ini ?",
            "on-verify-dialog-confirm",
            "on-verify-dialog-cancel",
            "Verifikasi",
            "Batal",
        );
    }

    #[On('on-cancel-dialog-confirm')]
    public function onCancelDialogConfirm()
    {
        $this->cancelHandler();
    }

    #[On('on-cancel-dialog-cancel')]
    public function onCancelDialogCancel()
    {
    }

    public function showCancelDialog()
    {
        Alert::confirmation(
            $this,
            Alert::ICON_QUESTION,
            "Batalkan Data",
            "Apakah Anda Yakin Ingin Membatalkan Data Ini ?",
            "on-cancel-dialog-confirm",
            "on-cancel-dialog-cancel",
            "Ya",
            "Tidak",
        );
    }

    #[On('on-active-dialog-confirm')]
    public function onActiveDialogConfirm()
    {
        $this->ActiveHandler();
    }

    #[On('on-active-dialog-cancel')]
    public function onActiveDialogCancel()
    {
        $this->targetActiveId = null;
    }

    public function showActiveDialog($id)
    {
        $this->targetActiveId = $id;
        Alert::confirmation(
            $this,
            Alert::ICON_QUESTION,
            "Batalkan Data",
            "Apakah Anda Yakin Ingin Mengaktifkan Imei Ini ?",
            "on-active-dialog-confirm",
            "on-active-dialog-cancel",
            "Ya",
            "Tidak",
        );
    }

    private function getData()
    {
        $transaction = TransactionRepository::find(Crypt::decrypt($this->objId));
        $social_media = json_decode($transaction->customer_social_media);
        $this->customer_ig = $social_media->instagram;
        $this->customer_fb = $social_media->facebook;
        $this->transaction = $transaction->toArray();
        $this->customer_lat = $transaction->customer_lat;
        $this->customer_lng = $transaction->customer_long;
        $this->transaction_status_badge = $transaction->getTransactionStatusBadge();
        $this->payment_status_badge = $transaction->getPaymentStatusBadge();
        $this->customer_ktp_url = $transaction->customer_ktp_url();
        $this->subtotal = $transaction->subtotal;
        $this->admin_fee = PaymentMethod::TYPE_PERCENTAGE == $transaction->payment_method_fee_type ? calculatedAdminFee($this->subtotal, $transaction->payment_method_fee_amount) : $transaction->payment_method_fee_amount;
        $this->discount = Voucher::TYPE_PERCENTAGE == $transaction->voucher_type ? calculatedAdminFee($this->subtotal, $transaction->voucher_amount) : $transaction->voucher_amount;
        $this->grand_total = $transaction->grand_total;
        $this->verified_at = $transaction->verified_at;
        foreach($transaction->transactionDetails as $index => $item){
            $this->transaction_details[] = [
                'id' => Crypt::encrypt($item['id']),
                'imei_url' => generateUrl($item['imei'], FilePathHelper::FILE_CUSTOMER_IMEI),
                'product_name' => $item['product_name'],
                'product_price' => $item['product_price'],
                'active_at' => $item['active_at'],
            ];
        }
    }

    public function activeHandler()
    {
        if(!$this->verified_at)
        {
            return;
        }

        try {       
            DB::beginTransaction();

            $validatedData = [
                'active_at' => now(),
            ];
            $objId = Crypt::decrypt($this->targetActiveId);
            $transactionDetail = TransactionDetailRepository::update($objId, $validatedData);
            DB::commit();

            $detail = collect($this->transaction_details)->where('id', '=', $this->targetActiveId)->keys();
            $this->transaction_details[$detail[0]]['active_at'] = $validatedData['active_at'];
            $this->getData();
            Alert::success($this, 'Berhasil', 'Imei berhasil diaktifkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    private function verifyHandler()
    {
        try {       
            DB::beginTransaction();

            $validatedData = [
                'verified_at' => now(),
                'transaction_status' => Transaction::TRANSACTION_STATUS_VERIFIED,
            ];
            $objId = Crypt::decrypt($this->objId);
            $transaction = TransactionRepository::update($objId, $validatedData);
            
            DB::commit();
            $this->verified_at = $validatedData['verified_at'];
            Alert::success($this, 'Berhasil', 'Data berhasil di verifikasi');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    private function cancelHandler()
    {
        try {       
            DB::beginTransaction();

            $validatedData = [
                'verified_at' => null,
                'cancellation_reason' => $this->cancellation_reason,
                'transaction_status' => Transaction::TRANSACTION_STATUS_CANCELED,
            ];

            $objId = Crypt::decrypt($this->objId);
            $transaction = TransactionRepository::update($objId, $validatedData);
            $this->verified_at = null;
            
            DB::commit();
            Alert::success($this, 'Berhasil', 'Data berhasil di verifikasi');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.transaction.transaction.detail');
    }
}
