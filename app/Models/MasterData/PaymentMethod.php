<?php

namespace App\Models\MasterData;

use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'name',
        'fee_type',
        'fee_amount',
        'code',
        'is_active',
        'is_xendit',
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

    CONST TYPE_PERCENTAGE = 'percentage';
    CONST TYPE_FIXED = 'fixed';

    CONST TYPE_CHOICE = [
        self::TYPE_PERCENTAGE => 'Persentase',
        self::TYPE_FIXED => 'Nominal Tetap',
    ];

    public function saveInfo($object, $prefix = "payment_method_", $data = null)
    {
        $default = [
            'name',
            'fee_type',
            'fee_amount',
            'code',
            'is_xendit',
        ];

        return saveInfoHelper($object, $this, $data ?? $default, $prefix);
    }
}
