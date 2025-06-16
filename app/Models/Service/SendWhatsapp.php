<?php

namespace App\Models\Service;

use App\Models\Transaction\Transaction;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SendWhatsapp extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        "transaction_id",
        "remarks_id",
        "remarks_type",
        "message_id",
        "status",
        "status_text_message",
        "status_text",
        "price",
        "data",
        "phone",
        "message",
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

    // Sent, Delivered, Received, Read, Cancel, Reject, Pending, System Error
    CONST STATUS_CREATED = 'Created';
    CONST STATUS_SEND = 'Sent';
    CONST STATUS_DELIVERED = 'Delivered';
    CONST STATUS_RECEIVED = 'Received';
    CONST STATUS_READ = 'Read';
    CONST STATUS_CANCEL = 'Cancel';
    CONST STATUS_REJECT = 'Reject';
    CONST STATUS_PENDING = 'Pending';
    CONST STATUS_SYSTEM_ERROR = 'System Error';

    CONST STATUS_CHOICE = [
        self::STATUS_CREATED => 'Created',
        self::STATUS_SEND => 'Send',
        self::STATUS_DELIVERED => 'Delivered',
        self::STATUS_RECEIVED => 'Received',
        self::STATUS_READ => 'Read',
        self::STATUS_CANCEL => 'Cancel',
        self::STATUS_REJECT => 'Reject',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_SYSTEM_ERROR => 'System Error',
    ];

    public function remarks()
    {
        return $this->belongsTo($this->remarks_type, 'remarks_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
