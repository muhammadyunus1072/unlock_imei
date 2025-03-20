<?php

namespace App\Models\MasterData;

use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studio extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude',
        'map_zoom',
        'city',
        'address',
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

    public function saveInfo($object, $data = null, $prefix = "studio_")
    {
        if($data)
        {
            foreach($data as $item)
            {
                $object[$prefix . "".$item] = $this->$item;
            }
        }else{
            $object[$prefix . "name"] = $this->name;
            $object[$prefix . "description"] = $this->desrirption;
            $object[$prefix . "latitude"] = $this->latitude;
            $object[$prefix . "longitude"] = $this->longitude;
            $object[$prefix . "map_zoom"] = $this->map_zoom;
            $object[$prefix . "city"] = $this->city;
            $object[$prefix . "address"] = $this->address;
        }

        return $object;
    }
}
