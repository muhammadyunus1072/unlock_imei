<?php

namespace App\Models\Transaction;

use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_quantity',
        'customer_label',
        'grand_total',
        'status',
        'cancellation_reason',
        'snap_token',
        'booking_code',
        'checkin_at',
        'payment_method_id',
        'voucher_id',
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

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }
}
