<?php

namespace App\Livewire\Public\ProductOrder;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Helpers\FilePathHelper;
use Livewire\Attributes\Validate;
use App\Models\MasterData\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterData\PaymentMethod;
use App\Models\Transaction\TransactionDetail;
use App\Repositories\MasterData\Product\ProductRepository;
use App\Repositories\MasterData\Voucher\VoucherRepository;
use App\Repositories\Transaction\Transaction\TransactionRepository;
use App\Repositories\MasterData\PaymentMethod\PaymentMethodRepository;
use App\Repositories\Transaction\Transaction\TransactionDetailRepository;
use Stevebauman\Location\Facades\Location;

class Detail extends Component
{
    use WithFileUploads;

    public $objId;

    // Customer Information
    #[Validate('required', message: 'Nama Harus Diisi', onUpdate: false)]
    public $customer_name;
    #[Validate('required', message: 'Email Harus Diisi', onUpdate: false)]
    public $customer_email;
    #[Validate('required', message: 'Telp / WA Harus Diisi', onUpdate: false)]
    public $customer_phone;
    #[Validate('required', message: 'KTP Harus Diisi', onUpdate: false)]
    public array $customer_ktp = [];
    public $customer_lat;
    public $customer_lang;
    public $customer_fb;
    public $customer_ig;

    
    public $product;
    public $product_warranty_text;
    public $product_details = [];

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

    public $modal_image_url = '';
    public $editedIndex;

    public function mount()
    {   
        $position = Location::get(request()->ip());
        dd($position);
        $product = ProductRepository::find(Crypt::decrypt($this->objId));
        $this->product = [
            'name' => $product->name,
            'description' => $product->description,
            'warranty_text' => $product->product_warranty_id ? $product->productWarranty->name : 'Tidak Ada Garansi',
        ];

        foreach ($product->productDetails as $key => $value) {
            $this->product_details[] = [
                'id' => Crypt::encrypt($value->id),
                'description' => $value->description,
                'price' => $value->price,
                'imei' => '',
                'imei_url' => $value->imei ? Storage::url(FilePathHelper::FILE_PRODUCT_DETAIL_IMEI . $value->imei) : asset("media/404.png")
            ];
        }

        $this->payment_method_choices = PaymentMethodRepository::getBy([
            ['is_active', true]
        ])
        ->map(function ($payment) {
            return [
                'id' => Crypt::encrypt($payment->id),
                'name' => $payment->name,
                'fee_type' => $payment->fee_type,
                'fee_amount' => $payment->fee_amount,
            ];
        })->toArray();

        $this->calculatedTotal();
        $this->grand_total = $this->subtotal;
    }

    public function getFile()
    {
        consoleLog($this, $this->customer_ktp);
    }


    public function editImage($index)
    {
        $this->modal_image_url = $this->product_details[$index]['image_url'];
        $this->editedIndex = $index;
        $this->dispatch('openModal');
    }

    public function saveImage()
    {
        try {
            DB::beginTransaction(); 
            // Validate the uploaded image file
            $this->validate([
                'image' => 'required|image',
            ],[
                'image.required' => 'Pilih gambar terlebih dahulu',
                'image.image' => 'File yang diupload harus berupa gambar',
            ]);
            
            $file = $this->image;      
            $filePath = $file->store(FilePathHelper::FILE_PRODUCT_DETAIL_IMEI, 'public');
            $product_detail = $this->product_details[$this->editedIndex];
            if ($product_detail['id']) {
                $validatedData = [
                    'image' => basename($filePath),
                ];
                $objId = Crypt::decrypt($product_detail['id']);
                ProductRepository::update($objId, $validatedData);
            }else{
                $image_url = Storage::url(FilePathHelper::FILE_PRODUCT_DETAIL_IMEI . basename($filePath));
                $this->product_details[$this->editedIndex]['image_url'] = $image_url;
                $this->product_details[$this->editedIndex]['image'] = basename($filePath);
            }
            // Commit the database transaction
            $this->modal_image_url = asset("media/404.png");
            $this->dispatch('closeModal');
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function updatedPaymentMethod($value)
    {
        $selectedPayment = collect($this->payment_method_choices)
            ->firstWhere('id', $value);

        if($selectedPayment['fee_type'] === PaymentMethod::TYPE_PERCENTAGE)
        {
            $this->admin_fee = calculatedAdminFee($this->subtotal, $selectedPayment['fee_amount']);
            $this->grand_total = $this->subtotal + $this->admin_fee - $this->discount;
        }else{
            $this->admin_fee = $selectedPayment['fee_amount'];
            $this->grand_total = $this->subtotal + $this->admin_fee - $this->discount;
        }
    }

    private function calculatedTotal()
    {
        $subtotal = 0;

        foreach($this->product_details as $product_detail)
        {
            $subtotal += $product_detail['price'];
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
        $this->validate([
            'product_details.*.imei.*' => 'image|max:2048',
        ]);

        foreach ($this->product_details as $index => $product_detail) {
            foreach ($product_detail['imei'] ?? [] as $file) {
                // Save or inspect each file
                consoleLog($this, [
                    'index' => $index,
                    'file_name' => $file->getClientOriginalName(),
                    'real_path' => $file->getRealPath(),
                ]);

                // Example: store it
                // $path = $file->store('uploads/imei', 'public');
            }
        }

        return;
        try {
            $phone = preg_replace('/[^\d]/', '', $this->phone);
            if (!preg_match("/^8[0-9]{9,11}$/", $phone) || (strlen($phone) < 9 || strlen($phone) > 11)) {
                throw new \Exception("Format No Telp tidak sesuai,<br>Contoh: +62 8XX-XXXX-XXXX");
            }

            if(!$this->customer_ig && !$this->customer_fb)
            {
                throw new \Exception("Masukkan data Media Sosial terlebih dahulu");
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
                'status' => Transaction::PAYMENT_STATUS_PENDING,
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
        return view('livewire.public.product-order.detail');
    }
}
