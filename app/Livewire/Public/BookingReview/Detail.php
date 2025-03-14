<?php

namespace App\Livewire\Public\BookingReview;

use Exception;
use App\Helpers\Alert;
use App\Models\MasterData\PaymentMethod;
use Livewire\Component;
use App\Models\MasterData\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\Booking\BookingDetailRepository;
use App\Repositories\MasterData\PaymentMethod\PaymentMethodRepository;
use App\Repositories\MasterData\Product\ProductBookingTimeRepository;
use App\Repositories\MasterData\Product\ProductRepository;

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
            $subtotal += $booking_detail['product_details']['price'];
        }

        $this->subtotal = $subtotal;
    }

    public function store()
    {
        try {
            $phone = preg_replace('/[^\d]/', '', $this->phone);
            if (!preg_match("/^8[0-9]{9,11}$/", $phone) || (strlen($phone) < 9 || strlen($phone) > 11)) {
                Alert::fail($this, 'Gagal', 'Format No Telp tidak sesuai, contoh: +62 8XX-XXX-XXX');
                return;
            }
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.public.booking-review.detail');
    }
}
