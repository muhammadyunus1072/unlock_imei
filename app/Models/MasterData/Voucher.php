<?php

namespace App\Models\MasterData;

use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'type',
        'amount',
        'code',
        'start_date',
        'end_date',
        'is_active',
    ];
    
    protected $guarded = ['id'];

    CONST TYPE_PERCENTAGE = 'percentage';
    CONST TYPE_FIXED = 'fixed';

    CONST TYPE_CHOICE = [
        self::TYPE_PERCENTAGE => 'Persentase',
        self::TYPE_FIXED => 'Nominal Tetap',
    ];
    
    public function isDeletable()
    {
        return true;
    }

    public function isEditable()
    {
        return true;
    }

    public function saveInfo($object, $prefix = "voucher_", $data = null)
    {
        $default = [

            "type",
            "amount",
            "code",
        ];

        return saveInfoHelper($object, $this, $data ?? $default, $prefix);
    }
}
