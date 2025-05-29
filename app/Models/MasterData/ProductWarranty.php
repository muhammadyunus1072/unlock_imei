<?php

namespace App\Models\MasterData;

use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductWarranty extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'name',
        'days',
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
    public function saveInfo($object, $data = null, $prefix = "product_warranty_")
    {
        $default = [
            "name",
            "days",
        ];

        return saveInfoHelper($object, $this, $data ?? $default, $prefix);
    }
}
