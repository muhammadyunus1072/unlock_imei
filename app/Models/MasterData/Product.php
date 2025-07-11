<?php

namespace App\Models\MasterData;

use App\Helpers\FilePathHelper;
use App\Models\MasterData\Studio;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterData\ProductBookingTime;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'name',
        'warranty_days',
        'description',
        'image',
        'price',
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

    public function saveInfo($object, $prefix = "product_", $data = null)
    {
        $default = [
            "name",
            "warranty_days",
            "description",
            "image",
            "price",
        ];

        return saveInfoHelper($object, $this, $data ?? $default, $prefix);
    }

    public function image_url()
    {
        return $this->image ? Storage::url(FilePathHelper::FILE_PRODUCT_IMAGE . $this->image) : null;
    }
}
