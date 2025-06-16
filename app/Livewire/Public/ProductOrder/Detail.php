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

    public $isLocationEnabled = false;

    // Customer Information
    #[Validate('required', message: 'Nama Harus Diisi', onUpdate: false)]
    public $customer_name;
    #[Validate('required', message: 'Email Harus Diisi', onUpdate: false)]
    public $customer_email;
    #[Validate('required', message: 'Telp / WA Harus Diisi', onUpdate: false)]
    public $customer_phone;
    #[Validate('required', message: 'KTP Harus Diisi', onUpdate: false)]
    public $customer_ktp;
    public $customer_lat;
    public $customer_lang;
    public $customer_fb;
    public $customer_ig;

    
    public $product;
    public $product_warranty_text;
    public $product_details = [];

    // public $payment_method_choices = [];

    public $subtotal;
    public $grand_total;
    public $admin_fee;

    public $code;
    public $discount = 0;

    // Input
    public $phone;
    // public $payment_method;
    public $voucher_id;
    public $voucher_type;
    public $voucher_amount;

    public $modal_image_url = '';
    public $editedIndex;

    public $lat;
    public $lng;

    public function mount()
    {   
        // $position = Location::get(request()->ip());
        // $this->lat = $position->latitude;
        // $this->lng = $position->longitude;
        $product = ProductRepository::find(Crypt::decrypt($this->objId));
        $this->product = [
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
        ];

        $this->product_details[] = [
            'imei' => '',
            // 'imei_url' => $product->imei ? Storage::url(FilePathHelper::FILE_PRODUCT_DETAIL_IMEI . $product->imei) : asset("media/404.png")
        ];

        // $this->payment_method_choices = PaymentMethodRepository::getBy([
        //     ['is_active', true]
        // ])
        // ->map(function ($payment) {
        //     return [
        //         'id' => Crypt::encrypt($payment->id),
        //         'name' => $payment->name,
        //         'fee_type' => $payment->fee_type,
        //         'fee_amount' => $payment->fee_amount,
        //     ];
        // })->toArray();

        $this->calculatedTotal();
        $this->setGrandTotal();
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('public.index');
        } else {
            $this->redirectRoute('public.index');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('public.index');
    }

    public function setLocation($lat, $lng)
    {
        $this->customer_lat = $lat;
        $this->customer_lang = $lng;
    }

    private function setGrandTotal()
    {
        $this->grand_total = $this->subtotal + $this->admin_fee - $this->discount;
    }

    public function addProduct()
    {
        $this->product_details[] = [
            'imei' => '',
            // 'imei_url' => asset("media/404.png")
        ];
        $this->calculatedTotal();
        $this->setGrandTotal();
    }

    public function removeProduct($index)
    {
        unset($this->product_details[$index]);
        $this->product_details = array_values($this->product_details); // Re-index the array
        $this->calculatedTotal();
        $this->setGrandTotal();
    }

    private function calculatedTotal()
    {
        $this->subtotal = count($this->product_details) * $this->product['price'];
    }

    public function couponHandler()
    {
        $voucher = VoucherRepository::findByCode($this->code);

        if($voucher)
        {
            $this->voucher_id = Crypt::encrypt($voucher['id']);
            $this->voucher_type = $voucher['type'];
            $this->voucher_amount = $voucher['amount'];
            $this->discount = $voucher['type'] == Voucher::TYPE_PERCENTAGE ? calculatePercentage($this->subtotal, $voucher['amount']) : $voucher['amount'];
            $this->setGrandTotal();
        }else{
            $this->voucher_id = null;
            $this->voucher_type = null;
            $this->voucher_amount = null;
            $this->discount = 0;
            $this->setGrandTotal();
            Alert::fail($this, "Gagal", "Maaf, Coupon Tidak Tersedia");
        }
    }

    public function store()
    {
        try {

        $this->validate([
            'customer_ktp' => 'required|image|max:2048',
            'product_details.*.imei.*' => 'required|image|max:2048',
            'customer_email' => 'required|email',
            'customer_ig' => 'required',
            'customer_fb' => 'required',
        ],[
            'product_details.*.imei.*.image' => 'File yang diupload harus berupa gambar',
            'product_details.*.imei.*.max' => 'Ukuran file maksimal 2MB',
            'customer_ig.required' => 'Akun Instagram harus diisi',
            'customer_fb.required' => 'Akun Facebook harus diisi',
        ]);
            $phone = preg_replace('/[^\d]/', '', $this->customer_phone);
            if (!preg_match("/^8[0-9]{9,11}$/", $phone) || (strlen($phone) < 9 || strlen($phone) > 11)) {
                throw new \Exception("Format No Telp tidak sesuai,<br>Contoh: +62 8XX-XXXX-XXXX");
            }

            if(collect($this->product_details)->where('imei', '')->count() > 0)
            {
                throw new \Exception("IMEI harus diisi");
            }

            if(!$this->customer_ig || !$this->customer_fb)
            {
                throw new \Exception("Masukkan data Media Sosial terlebih dahulu");
            }

            if(!$this->customer_lat || !$this->customer_lang)
            {
                return redirect()->route('public.index');
            }

            // if(!$this->payment_method)
            // {
            //     throw new \Exception("Pilih Metode Pembayaran terlebih dahulu");
            // }

            DB::beginTransaction();

            $ktp = $this->customer_ktp->store(FilePathHelper::FILE_CUSTOMER_KTP, 'public');
            // 2️⃣ Create the transaction after ensuring no conflicts
            $validatedData = [
                'customer_name' => $this->customer_name,
                'customer_email' => $this->customer_email,
                'customer_phone' => $phone,
                'customer_lat' => $this->customer_lat,
                'customer_long' => $this->customer_lang,
                'customer_ip_lat' => $this->customer_lat,
                'customer_ip_long' => $this->customer_lang,
                'customer_ktp' => basename($ktp),
                'customer_social_media' => json_encode([
                    'facebook' => preg_replace('/\s+/', '.', $this->customer_fb),
                    'instagram' => preg_replace('/\s+/', '', $this->customer_ig),
                ]),
                'voucher_id' => $this->voucher_id ? Crypt::decrypt($this->voucher_id) : null,
                
                'subtotal' => $this->subtotal,
                'discount' => $this->discount,
                'total_amount' => $this->grand_total,
                'amount_due' => $this->grand_total,
            ];
            $transaction = TransactionRepository::create($validatedData);

            // 3️⃣ Insert booking details
            foreach ($this->product_details as $detail) {

                $imei = $detail['imei']->store(FilePathHelper::FILE_CUSTOMER_IMEI, 'public');
                $validatedData = [
                    'transaction_id' => $transaction->id,
                    'product_id' => Crypt::decrypt($this->objId),
                    'imei' => basename($imei),
                ];
                TransactionDetailRepository::create($validatedData);
            }
            DB::commit();
            $transaction->onCreated();
            
            return redirect()->route('public.order_invoice', [
                'id' => simple_encrypt($transaction->id),
            ]);
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
