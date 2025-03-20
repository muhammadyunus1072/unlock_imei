<?php

namespace App\Livewire\Public\BookingReview;

use Exception;
use App\Helpers\Alert;
use App\Models\MasterData\PaymentMethod;
use Livewire\Component;
use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\On;
use App\Repositories\MasterData\PaymentMethod\PaymentMethodRepository;
use App\Repositories\MasterData\Product\ProductRepository;
use App\Repositories\Transaction\Transaction\TransactionDetailRepository;
use App\Repositories\Transaction\Transaction\TransactionRepository;

class Detail extends Component
{
    public $objId;
    
    public $product_name;
    public $product_note;
    public $product_price;
    public $product_studio_name;

    public $booking_details = [];
    public $booking_date;

    public $payment_method_choices = [];

    public $subtotal;
    public $grand_total;

    // Input
    public $phone;
    public $payment_method;
    public $voucher;

    public function mount()
    {
        $booking_details = session('booking_data');
        
        $this->objId = $booking_details['data']['product_id'];
        $this->booking_date = $booking_details['data']['booking_date'];
        $this->booking_details = $booking_details['data']['booking_details'];
        
        $product = ProductRepository::find(Crypt::decrypt($this->objId));
        $this->product_name = $product->name;
        $this->product_note = $product->note;
        $this->product_price = $product->price;
        $this->product_studio_name = $product->studio->name ." - ". $product->studio->city;

        $this->payment_method_choices = PaymentMethodRepository::all()
        ->map(function ($payment) {
            return [
                'id' => Crypt::encrypt($payment->id),
                'name' => $payment->name,
                'type' => $payment->type,
                'amount' => $payment->amount,
            ];
        })->toArray();

        $this->calculatedTotal();
        $this->grand_total = $this->subtotal;
    }

    public function updatedPaymentMethod($value)
    {
        $selectedPayment = collect($this->payment_method_choices)
            ->firstWhere('id', $value);

        if($selectedPayment['type'] === PaymentMethod::TYPE_PERCENTAGE)
        {
            $this->grand_total = $this->subtotal + calculatedAdminFee($this->subtotal, $selectedPayment['amount']);
        }else{
            $this->grand_total = $this->subtotal + $selectedPayment['amount'];
        }
    }

    private function calculatedTotal()
    {
        $subtotal = 0;

        foreach($this->booking_details as $booking_detail)
        {
            $subtotal += $this->product_price;
            $subtotal += $booking_detail['product_detail']['price'];
        }

        $this->subtotal = $subtotal;
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        $this->redirectRoute('public.product-booking', $this->objId);
    }

    public function store()
    {
        try {
            $phone = preg_replace('/[^\d]/', '', $this->phone);
            if (!preg_match("/^8[0-9]{9,11}$/", $phone) || (strlen($phone) < 9 || strlen($phone) > 11)) {
                throw new \Exception("Format No Telp tidak sesuai,<br>Contoh: +62 8XX-XXXX-XXXX");
            }

            if(!$this->payment_method)
            {
                throw new \Exception("Pilih Metode Pembayaran terlebih dahulu");
            }

            DB::beginTransaction();

            // 1️⃣ Check if any booking slot is already taken and lock them
            foreach ($this->booking_details as $booking) {

                if ($booking['product_booking_time']['booking_detail_id']) {
                    throw new \Exception("Maaf, jam yang kamu pilih sudah tidak tersedia. Silahkan memilih jam booking yang lain.");
                }

                $exists = DB::table('transaction_details')
                    ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
                    ->where('transaction_details.booking_date', $this->booking_date)
                    ->where('transaction_details.product_id', Crypt::decrypt($this->objId))
                    ->where('transaction_details.product_detail_id', Crypt::decrypt($booking['product_detail']['id']))
                    ->where('transaction_details.product_booking_time_id', Crypt::decrypt($booking['product_booking_time']['id']))
                    ->whereIn('transactions.status', ['pending', 'paid'])
                    ->lockForUpdate() // ✅ Lock slot before creating transaction
                    ->exists();

                if ($exists) {
                    throw new \Exception("Maaf, jam yang kamu pilih sudah tidak tersedia. Silahkan memilih jam booking yang lain.");
                }
            }

            // 2️⃣ Create the transaction after ensuring no conflicts
            $transaction = TransactionRepository::create([
                'user_id' => auth()->id(),
                'customer_name' => auth()->user()->name,
                'customer_email' => auth()->user()->email,
                'customer_phone' => $phone,
                'customer_label' => 'Customer',
                'grand_total' => $this->grand_total,
                'payment_method_id' => Crypt::decrypt($this->payment_method), // Example
                'voucher_id' => null,
            ]);

            // 3️⃣ Insert booking details
            foreach ($this->booking_details as $booking) {
                TransactionDetailRepository::create([
                    'transaction_id' => $transaction->id,
                    'booking_date' => $this->booking_date,
                    'product_id' => Crypt::decrypt($this->objId),
                    'product_detail_id' => Crypt::decrypt($booking['product_detail']['id']),
                    'product_booking_time_id' => Crypt::decrypt($booking['product_booking_time']['id']),
                ]);
            }
            $transaction->createInvoice();

            DB::commit();
            return redirect()->away($transaction->checkout_link);
        } catch (Exception $e) {
            DB::rollBack();
            // Alert::fail($this, "Gagal", $e->getMessage(), "on-dialog-confirm");
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.public.booking-review.detail');
    }
}
