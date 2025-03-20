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

    public function isDeletable()
    {
        return true;
    }

    public function isEditable()
    {
        return true;
    }

    public function saveInfo($object, $data = null, $prefix = "voucher_")
    {
        if($data)
        {
            foreach($data as $item)
            {
                $object[$prefix . "".$item] = $this->$item;
            }
        }else{
            $object[$prefix . "type"] = $this->type;
            $object[$prefix . "amount"] = $this->amount;
            $object[$prefix . "code"] = $this->code;
            $object[$prefix . "start_date"] = $this->start_date;
            $object[$prefix . "end_date"] = $this->end_date;
            $object[$prefix . "is_active"] = $this->is_active;
        }

        return $object;
    }
}
