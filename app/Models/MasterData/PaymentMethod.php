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
        'type',
        'amount',
        'code',
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

    public function saveInfo($object, $data = null, $prefix = "payment_method")
    {
        if($data)
        {
            foreach($data as $item)
            {
                $object[$prefix . "_".$item] = $this->$item;
            }
        }else{
            $object[$prefix . "_name"] = $this->name;
            $object[$prefix . "_type"] = $this->type;
            $object[$prefix . "_amount"] = $this->amount;
            $object[$prefix . "_code"] = $this->code;
        }

        return $object;
    }
}
