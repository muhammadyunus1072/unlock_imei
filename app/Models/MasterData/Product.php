<?php

namespace App\Models\MasterData;

use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'studio_id',
        'name',
        'description',
        'price',
        'image',
        'note',
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

    public function saveInfo($object, $prefix = "product")
    {
        $object[$prefix . 'studio_id'] = $this->studio_id;
        $object[$prefix . 'name'] = $this->name;
        $object[$prefix . 'description'] = $this->description;
        $object[$prefix . 'price'] = $this->price;
        $object[$prefix . 'image'] = $this->image;
        $object[$prefix . 'note'] = $this->note;

        return $object;
    }
}
