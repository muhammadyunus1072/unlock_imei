<?php

namespace App\Models\MasterData;

use App\Helpers\FilePathHelper;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductDetail extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'product_id',
        'description',
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
    public function saveInfo($object, $data = null, $prefix = "product_detail_")
    {
        $default = [
            "description",
            "price",
        ];

        return saveInfoHelper($object, $this, $data ?? $default, $prefix);
    }

    public function image_url()
    {
        return $this->image ? Storage::url(FilePathHelper::FILE_PRODUCT_DETAIL_IMAGE . $this->image) : null;
    }
}
