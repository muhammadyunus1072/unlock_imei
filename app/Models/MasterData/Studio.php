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

    public function saveInfo($object, $data = null, $prefix = "studio")
    {
        if($data)
        {
            foreach($data as $item)
            {
                $object[$prefix . "_".$item] = $this->$item;
            }
        }else{
            $object[$prefix . "_name"] = $this->name;
            $object[$prefix . "_description"] = $this->desrirption;
            $object[$prefix . "_latitude"] = $this->latitude;
            $object[$prefix . "_longitude"] = $this->longitude;
            $object[$prefix . "_map_zoom"] = $this->map_zoom;
            $object[$prefix . "_city"] = $this->city;
            $object[$prefix . "_address"] = $this->address;
        }

        return $object;
    }
}
