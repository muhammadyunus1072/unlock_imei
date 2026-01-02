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

class Undangan extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'name',
        'description',
        'quantity',
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

    public function saveInfo($object, $prefix = "undangan_", $data = null)
    {
        $default = [
            "name",
            "description",
            "quantity",
        ];

        return saveInfoHelper($object, $this, $data ?? $default, $prefix);
    }
}
