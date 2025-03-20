<?php

namespace App\Models\MasterData;

use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductBookingTime extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'product_id',
        'time',
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
    public function saveInfo($object, $data = null, $prefix = "product_booking_time")
    {
        if($data)
        {
            foreach($data as $item)
            {
                $object[$prefix . "_".$item] = $this->$item;
            }
        }else{
            $object[$prefix . "_time"] = $this->time;
        }

        return $object;
    }
}