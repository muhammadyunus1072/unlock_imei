<?php

namespace App\Livewire\MasterData\ProductBookingTime;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Helpers\FilePathHelper;
use App\Repositories\MasterData\Product\ProductBookingTimeRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MasterData\Product\ProductRepository;
use App\Repositories\MasterData\Product\ProductDetailRepository;

class Detail extends Component
{
    use WithFileUploads;
    
    public $objId;
    public $time;
    public $product_name;
    
    public $product_booking_times = [];
    public $product_booking_time_removes = [];

    public function mount()
    {
        $product = ProductRepository::find(Crypt::decrypt($this->objId));
        $this->product_name = $product->name;
        if($product->productBookingTimes->count() > 0){
            foreach ($product->productBookingTimes as $key => $value) {
                $this->product_booking_times[] = [
                    'id' => Crypt::encrypt($value->id),
                    'key' => Str::random(30),
                    'time' => $value->time,
                ];
            }
        }
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('product_booking_time.edit', $this->objId);
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('product.edit', $this->objId);
    }

    public function addProductBookingTime()
    {
        if(!$this->time){
            Alert::fail($this, "Gagal", "Waktu tidak boleh kosong");
            return;
        }

        if (collect($this->product_booking_times)->pluck('time')->contains($this->time)) {
            consoleLog($this, $this->time);
            return;
        }
    
        $this->product_booking_times[] = [
            'id' => null,
            'key' => Str::random(30),
            'time' => $this->time,
        ];

        $this->product_booking_times = collect($this->product_booking_times)
        ->sortBy('time')
        ->values()
        ->toArray();

        $this->time = null;
    
    }

    public function removeProductBookingTime($index)
    {
        if ($this->product_booking_times[$index]['id']) {
            $this->product_booking_time_removes[] = $this->product_booking_times[$index]['id'];
        }

        unset($this->product_booking_times[$index]);
    }

    public function store()
    {
        try {       
            DB::beginTransaction();
            foreach($this->product_booking_times as $product_booking_time){
                $validatedData = [
                    'product_id' => Crypt::decrypt($this->objId),
                    'time' => $product_booking_time['time'],
                ];
                if ($product_booking_time['id']) {
                    $objId = Crypt::decrypt($product_booking_time['id']);
                    ProductBookingTimeRepository::update($objId, $validatedData);
                } else {
                    $obj = ProductBookingTimeRepository::create($validatedData);
                    $objId = $obj->id;
                }
            }

            foreach($this->product_booking_time_removes as $product_booking_time_id)
            {
                ProductBookingTimeRepository::delete(Crypt::decrypt($product_booking_time_id));
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
        return view('livewire.master-data.product-booking-time.detail');
    }
}
