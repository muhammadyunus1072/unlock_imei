<?php

namespace App\Livewire\Transaction\Transaction;

use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Helpers\FilePathHelper;
use App\Models\MasterData\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;
use App\Models\MasterData\PaymentMethod;
use App\Models\Transaction\TransactionStatus;
use App\Models\Transaction\TransactionPayment;
use App\Repositories\Transaction\Transaction\TransactionRepository;
use App\Repositories\Transaction\Transaction\TransactionDetailRepository;
use App\Repositories\Transaction\Transaction\TransactionStatusRepository;
use App\Repositories\Transaction\Transaction\TransactionPaymentRepository;

class Detail extends Component
{
    
    public $objId;
    public $cancellation_reason;
    public $isVerified;
    public $targetActiveId;
    public $targetPaymentId;
    public $canUpdateStatus = false;

    // Transaction Information
    public $transaction;
    public $next_statuses = [];
    public $transaction_details = [];
    public $transaction_payments = [];
    public $customer_ig;
    public $customer_fb;
    public $customer_ktp_url;
    public $customer_lat;
    public $customer_lng;
    public $subtotal = 0;
    public $admin_fee = 0;
    public $discount = 0;
    public $amount_due = 0;
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

    public function deleteStatus($id)
    {
        try {
            TransactionStatusRepository::delete($id);
            Alert::success($this, 'Berhasil', 'Status berhasil dihapus');
            $this->getData();
        } catch (\Exception $e) {
            Alert::fail($this, "Gagal", $e->getMessage());
        }
//         Pembayaran
// Bank BCA
// Nama Rekenning: AHMAT FAUZI
// No. Rekenning: 0891497953

    }

    private function getData()
    {
        $transaction = TransactionRepository::find(simple_decrypt($this->objId));
        $this->transaction = $transaction;
        $this->canUpdateStatus = $transaction->whereHas('transactionStatuses', function($query) {
            $query->where('name', TransactionStatus::STATUS_VERIFIED);
        })->orWhereHas('transactionStatuses', function($query) {
            $query->where('name', TransactionStatus::STATUS_CANCELLED);
        })->exists();
        $social_media = json_decode($transaction->customer_social_media);
        $this->customer_ig = $social_media->instagram;
        $this->customer_fb = $social_media->facebook;
        $this->customer_lat = $transaction->customer_lat;
        $this->customer_lng = $transaction->customer_long;
        $this->customer_ktp_url = $transaction->customer_ktp_url();
        $this->subtotal = $transaction->subtotal;
        $this->admin_fee = PaymentMethod::TYPE_PERCENTAGE == $transaction->payment_method_fee_type ? calculateAdminFee($this->subtotal, $transaction->payment_method_fee_amount) : $transaction->payment_method_fee_amount;
        $this->discount = Voucher::TYPE_PERCENTAGE == $transaction->voucher_type ? calculateAdminFee($this->subtotal, $transaction->voucher_amount) : $transaction->voucher_amount;
        $this->grand_total = $transaction->total_amount;
        $this->amount_due = $transaction->amount_due;
        $this->isVerified = $transaction->whereHas('transactionStatuses', function($query) {
            $query->where('name', TransactionStatus::STATUS_VERIFIED);
        })->exists();;
        $this->transaction_details = [];
        foreach($transaction->transactionDetails as $index => $item){
            $this->transaction_details[] = [
                'id' => Crypt::encrypt($item['id']),
                'imei_url' => generateUrl($item['imei'], FilePathHelper::FILE_CUSTOMER_IMEI),
                'product_name' => $item['product_name'],
                'product_price' => $item['product_price'],
                'active_at' => $item['active_at'],
            ];
        }
        
        $this->next_statuses = TransactionStatus::nextStatuses($transaction->last_status_id);
        $this->transaction_payments = [];
        foreach($transaction->transactionPayments as $index => $item){
            $this->transaction_payments[] = [
                'id' => Crypt::encrypt($item['id']),
                'image_url' => generateUrl($item['image'], FilePathHelper::FILE_CUSTOMER_TRANSACTION_PAYMENT),
                'payment_method_name' => $item['payment_method_name'],
                'amount' => valueToImask($item['amount']),
                'status' => $item['status'],
                'style' => $item->getStatusStyle(),
            ];
        }
    }

    public function activeHandler()
    {
        if(!$this->isVerified)
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
                'transaction_id' => simple_decrypt($this->objId),
                'name' => TransactionStatus::STATUS_VERIFIED,
                // 'transaction_status' => Transaction::TRANSACTION_STATUS_VERIFIED,
            ];;
            $transaction = TransactionStatusRepository::create($validatedData);
            DB::commit();
            $this->getData();
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
                'transaction_id' => simple_decrypt($this->objId),
                'name' => TransactionStatus::STATUS_CANCELLED,
                // 'transaction_status' => Transaction::TRANSACTION_STATUS_CANCELED,
            ];
            $transaction = TransactionStatusRepository::create($validatedData);
            $this->getData();
            
            DB::commit();
            Alert::success($this, 'Berhasil', 'Data berhasil di verifikasi');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }


    private function verifyPaymentHandler()
    {
        try {       
            DB::beginTransaction();

            $validatedData = [
                'status' => TransactionPayment::STATUS_PAID,
                'amount' => imaskToValue($this->transaction_payments[$this->targetPaymentId]['amount']),
                // 'transaction_status' => Transaction::TRANSACTION_STATUS_CANCELED,
            ];
            $objId = Crypt::decrypt($this->transaction_payments[$this->targetPaymentId]['id']);
            dd([
                $objId,
                $validatedData
            ]);
            $transactionPayment = TransactionPaymentRepository::update($objId, $validatedData);
            
            DB::commit();
            // $this->getData();
            Alert::success($this, 'Berhasil', 'Data berhasil di verifikasi');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    #[On('on-verify-payment-dialog-confirm')]
    public function onVerifyPaymentDialogConfirm()
    {
        $this->verifyPaymentHandler();
    }

    #[On('on-verify-payment-dialog-cancel')]
    public function onVerifyPaymentDialogCancel()
    {
        $this->targetPaymentId = null;
    }

    public function showVerifyPaymentDialog($id)
    {
        $this->targetPaymentId = $id;
        Alert::confirmation(
            $this,
            Alert::ICON_QUESTION,
            "Batalkan Data",
            "Apakah Anda Yakin Ingin Verifikasi Pembayaran Ini ?",
            "on-verify-payment-dialog-confirm",
            "on-verify-payment-dialog-cancel",
            "Ya",
            "Tidak",
        );
    }
    private function deletePaymentHandler()
    {
        try {       
            DB::beginTransaction();

            $validatedData = [
                'status' => TransactionPayment::STATUS_CANCELLED,
                'amount' => $this->transaction_payments[$this->targetPaymentId]['amount'],
                // 'transaction_status' => Transaction::TRANSACTION_STATUS_CANCELED,
            ];

            $objId = Crypt::decrypt($this->transaction_payments[$this->targetPaymentId]['id']);
            $transactionPayment = TransactionPaymentRepository::update($objId, $validatedData);
            DB::commit();
            $this->getData();
            Alert::success($this, 'Berhasil', 'Data berhasil di dibatalkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    #[On('on-delete-payment-dialog-confirm')]
    public function onDeletePaymentDialogConfirm()
    {
        $this->deletePaymentHandler();
    }

    #[On('on-delete-payment-dialog-cancel')]
    public function onDeletePaymentDialogCancel()
    {
        $this->targetPaymentId = null;
    }

    public function showDeletePaymentDialog($id)
    {
        $this->targetPaymentId = $id;
        Alert::confirmation(
            $this,
            Alert::ICON_QUESTION,
            "Batalkan Data",
            "Apakah Anda Yakin Ingin Membatalkan Pembayaran Ini ?",
            "on-delete-payment-dialog-confirm",
            "on-delete-payment-dialog-cancel",
            "Ya",
            "Tidak",
        );
    }

    public function render()
    {
        return view('livewire.transaction.transaction.detail');
    }
}
