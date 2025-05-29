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
        'product_warranty_id',
        'name',
        'description',
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

    public function saveInfo($object, $prefix = "product_", $data = null)
    {
        $default = [
            "name",
            "description",
            "image",
        ];

        return saveInfoHelper($object, $this, $data ?? $default, $prefix);
    }

    public function image_url()
    {
        return $this->image ? Storage::url(FilePathHelper::FILE_PRODUCT_IMAGE . $this->image) : null;
    }

    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class, 'product_id', 'id');
    }

    public function productWarranty()
    {
        return $this->belongsTo(ProductWarranty::class, 'product_warranty_id', 'id');
    }
}
