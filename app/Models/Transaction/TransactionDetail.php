<?php

namespace App\Models\Transaction;

use App\Models\MasterData\Product;
use App\Models\MasterData\ProductBookingTime;
use App\Models\MasterData\ProductDetail;
use App\Models\MasterData\Studio;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionDetail extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'transaction_id',
        'booking_date',
        'product_id',
        'product_detail_id',
        'product_booking_time_id',
    ];
    
    protected $guarded = ['id'];

    public function isDeletable()
    {
        return true;
    }

    public function isEditable()
    {
        return true;
    }

    protected static function onBoot()
    {
        self::creating(function ($model) {
            $model = $model->product->saveInfo($model);
            $model = $model->productDetail->saveInfo($model);
            $model = $model->productBookingTime->saveInfo($model);
        });
    }

    public function saveInfo($object, $data = null, $prefix = "transaction_detail_")
    {
        if($data)
        {
            foreach($data as $item)
            {
                $object[$prefix . "".$item] = $this->$item;
            }
        }else{
            $object[$prefix. "booking_date"] = $this->booking_date;
            // Product Information
            $object[$prefix. "product_id"] = $this->product_id;
            $object[$prefix. "product_studio_id"] = $this->product_studio_id;
            $object[$prefix. "product_name"] = $this->product_name;
            $object[$prefix. "product_description"] = $this->product_description;
            $object[$prefix. "product_price"] = $this->product_price;
            $object[$prefix. "product_image"] = $this->product_image;
            $object[$prefix. "product_note"] = $this->product_note;

            // Product Detail Information
            $object[$prefix. "product_detail_id"] = $this->product_detail_id;
            $object[$prefix. "product_detail_name"] = $this->product_detail_name;
            $object[$prefix. "product_detail_description"] = $this->product_detail_description;
            $object[$prefix. "product_detail_price"] = $this->product_detail_price;
            $object[$prefix. "product_detail_image"] = $this->product_detail_image;

            // Product Booking Time Information
            $object[$prefix. "product_booking_time_id"] = $this->product_booking_time_id;
            $object[$prefix. "product_booking_time_time"] = $this->product_booking_time_time;
        }

        return $object;
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function studio()
    {
        return $this->belongsTo(Studio::class, 'product_studio_id', 'id');
    }
    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id', 'id');
    }
    public function productBookingTime()
    {
        return $this->belongsTo(ProductBookingTime::class, 'product_booking_time_id', 'id');
    }
}
