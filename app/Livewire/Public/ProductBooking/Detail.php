<?php

namespace App\Livewire\Public\ProductBooking;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use App\Models\MasterData\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\Booking\BookingDetailRepository;
use App\Repositories\MasterData\Product\ProductBookingTimeRepository;

class Detail extends Component
{
    public $objId;
    
    public $product_description;
    public $product_name;
    public $product_note;
    public $product_image_url;
    public $product_studio_name;
    public $product_studio_location;
    
    public $booking_date;

    public $product_booking_times = [];
    public $product_detail_choice = [];
    public $product_booking_details = [];

    public function mount()
    {
        $this->booking_date = now()->format('Y-m-d');

        $product = Product::where('id', '=', Crypt::decrypt($this->objId))->first();
        $this->product_name = $product->name;
        $this->product_description = $product->description;
        $this->product_note = $product->note;
        $this->product_image_url = $product->image_url();
        $this->product_studio_name = $product->studio->name . " - " . $product->studio->city;
        $this->product_studio_location = "https://www.google.com/maps?q=". $product->studio->latitude.','.$product->studio->longitude;
        
        $this->product_detail_choice = $product->productDetails
            ->map(function ($studio) {
                return [
                    'id' => Crypt::encrypt($studio->id),
                    'name' => $studio->name,
                    'description' => $studio->description,
                    'price' => $studio->price,
                    'image_url' => $studio->image_url(),
                    'is_checked' => false,
                ];
            })->toArray();

        $this->getBookedTimes(now()->format('Y-m-d'));

    }

    public function updatedBookingDate()
    {
        $this->getBookedTimes($this->booking_date);
        $this->product_booking_details = [];
    }

    public function handleProductDetail($bookingIndex, $index)
    {
        if($this->product_booking_details[$bookingIndex]['product_details'][$index]['is_checked']){
            $this->product_booking_details[$bookingIndex]['product_details'][$index]['is_checked'] = !$this->product_booking_details[$bookingIndex]['product_details'][$index]['is_checked'];
        }else{
            $this->product_booking_details[$bookingIndex]['product_details'] = collect($this->product_booking_details[$bookingIndex]['product_details'])->map(function ($product_detail) {
                return [

                    'id' => $product_detail['id'],
                    'name' => $product_detail['name'],
                    'description' => $product_detail['description'],
                    'price' => $product_detail['price'],
                    'image_url' => $product_detail['image_url'],
                    'is_checked' => false,
                ];
            })->toArray();
            $this->product_booking_details[$bookingIndex]['product_details'][$index]['is_checked'] = true;
        }
    }

    public function handleBookingTime($index)
    {
        $index = Crypt::decrypt($index);   
        $this->product_booking_times[$index]['is_checked'] = !$this->product_booking_times[$index]['is_checked'];
        if($this->product_booking_times[$index]['is_checked']){
            $this->product_booking_details[$index] = [
                'product_booking_time_id' => $this->product_booking_times[$index]['id'],
                'time' => $this->product_booking_times[$index]['time'],
                'product_booking_times' => $this->product_booking_times[$index],
                'product_details' => $this->product_detail_choice,
            ];
            $this->dispatch('refreshFsLightbox');
        }else{
            unset($this->product_booking_details[$index]);
        }
    }

    private function getBookedTimes($date)
    {
        $this->product_booking_times = ProductBookingTimeRepository::getBookingTimes(Crypt::decrypt($this->objId), $date)
            ->map(function ($productBookingTime) {
                return [
                    'id' => Crypt::encrypt($productBookingTime->id),
                    'time' => $productBookingTime->time,
                    'booking_detail_id' => $productBookingTime->booking_detail_id ? Crypt::encrypt($productBookingTime->booking_detail_id) : null,
                    'is_checked' => false,
                ];
            })->toArray();
    }

    public function store()
    {
        try {
            
            if(!$this->product_booking_details || !$this->booking_date)
            {
                throw new \Exception("Pilih Tanggal dan Waktu Booking terlebih dahulu");
            }

            $hasError = collect($this->product_booking_details)->contains(fn($booking) => 
                collect($booking['product_details'])->doesntContain('is_checked', true)
            );
            
            if($hasError)
            {
                throw new \Exception("Pilih background terlebih dahulu");
            }

            $booking_details = [];
            foreach($this->product_booking_details as $index =>  $item)
            {
                $booking_details[] = [
                    'product_booking_time_id' => $item['product_booking_time_id'],
                    'time' => $item['time'],
                    'product_booking_times' => $item['product_booking_times'],
                    'product_details' => collect($item['product_details'])->firstWhere('is_checked', true),
                ];
            }

            session()->put('booking_data', [
                'data' => [
                    'product_id' => $this->objId,
                    'booking_date' => $this->booking_date,
                    'booking_details' => $booking_details,
                ],
            ]);
            
            if (!auth()->check()) {
                $this->dispatch('loginAlert');
                return;
            }

            $this->dispatch('booking_term');
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function createOrder()
    {
        return redirect()->route('public.booking-review', $this->objId);
    }

    public function render()
    {
        return view('livewire.public.product-booking.detail');
    }
}
