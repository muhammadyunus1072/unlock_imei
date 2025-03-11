<?php

namespace App\Models\MasterData;

use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductDetail extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'product_id',
        'name',
        'description',
        'price',
        'image',
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

    public function saveInfo($object, $prefix = "product_detail")
    {
        $object[$prefix . 'name'] = $this->name;
        $object[$prefix . 'description'] = $this->description;
        $object[$prefix . 'price'] = $this->price;
        $object[$prefix . 'image'] = $this->image;

        return $object;
    }
}
