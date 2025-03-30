<?php

namespace App\Livewire\Public\BookingReview;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;
use App\Models\MasterData\PaymentMethod;
use App\Models\MasterData\Voucher;
use App\Models\Transaction\TransactionDetail;
use App\Repositories\MasterData\Product\ProductRepository;
use App\Repositories\MasterData\Voucher\VoucherRepository;
use App\Repositories\Transaction\Transaction\TransactionRepository;
use App\Repositories\MasterData\PaymentMethod\PaymentMethodRepository;
use App\Repositories\Transaction\Transaction\TransactionDetailRepository;

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
    public $admin_fee;

    public $code;
    public $discount = 0;

    // Input
    public $phone;
    public $payment_method;
    public $voucher_id;
    public $voucher_type;
    public $voucher_amount;

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

        $this->payment_method_choices = PaymentMethodRepository::getBy([
            ['is_active', true]
        ])
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
            $this->admin_fee = calculatedAdminFee($this->subtotal, $selectedPayment['amount']);
            $this->grand_total = $this->subtotal + $this->admin_fee - $this->discount;
        }else{
            $this->admin_fee = $selectedPayment['amount'];
            $this->grand_total = $this->subtotal + $this->admin_fee - $this->discount;
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

    public function couponHandler()
    {
        $voucher = VoucherRepository::findByCode($this->code);

        if($voucher)
        {
            $this->voucher_id = Crypt::encrypt($voucher['id']);
            $this->voucher_type = $voucher['type'];
            $this->voucher_amount = $voucher['amount'];
            $this->discount = $voucher['type'] == Voucher::TYPE_PERCENTAGE ? calculatedDiscount($this->subtotal, $voucher['amount']) : $voucher['amount'];
            $this->grand_total = $this->subtotal + $this->admin_fee - $this->discount;
        }else{
            $this->voucher_id = null;
            $this->voucher_type = null;
            $this->voucher_amount = null;
            $this->discount = 0;
            $this->grand_total = $this->subtotal + $this->admin_fee - $this->discount;
            Alert::fail($this, "Gagal", "Maaf, Coupon Tidak Tersedia");
        }
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
                $isNotAvailable = TransactionDetail::isNotAvailable(
                    $this->booking_date, 
                    Crypt::decrypt($this->objId), 
                    Crypt::decrypt($booking['product_booking_time']['id'])
                );

                if ($isNotAvailable) {
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
                'subtotal' => $this->subtotal,
                'payment_method_id' => Crypt::decrypt($this->payment_method), // Example
                'voucher_id' => $this->voucher_id ? Crypt::decrypt($this->voucher_id) : null,
                'grand_total' => $this->grand_total,
                'subtotal' => $this->subtotal,
                'admin_fee' => $this->admin_fee,
                'discount' => $this->discount,
                'status' => Transaction::STATUS_PENDING,
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
