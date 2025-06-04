<?php

namespace App\Models\Transaction;

use Carbon\Carbon;
use App\Helpers\FilePathHelper;
use App\Models\MasterData\Studio;
use App\Models\MasterData\Product;
use Illuminate\Support\Facades\DB;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterData\PaymentMethod;
use App\Models\MasterData\ProductDetail;
use App\Models\MasterData\ProductBookingTime;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionPayment extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    // Initial status
    const STATUS_PENDING = 'PENDING'; // Waiting for payment

    // Xendit-specific statuses
    const STATUS_PAID = 'PAID'; // Payment successful
    const STATUS_EXPIRED = 'EXPIRED'; // Payment expired (e.g., virtual account expired)
    const STATUS_FAILED = 'FAILED'; // Payment failed (e.g., insufficient funds, declined)
    const STATUS_CANCELLED = 'CANCELLED'; // User or system canceLled transaction

    // Refund-related statuses
    const STATUS_REFUND_REQUESTED = 'REFUND_REQUESTED'; // User requested a refund
    const STATUS_REFUNDED = 'REFUNDED'; // Payment refunded successfully
    const STATUS_PARTIALLY_REFUNDED = 'PARTIALLY_REFUNDED'; // Partial refund issued

    // Settlement & Completion
    const STATUS_SETTLED = 'SETTLED'; // Payment settled (confirmed by the bank)
    const STATUS_COMPLETED = 'COMPLETED'; // Order/service fulfilled after payment

    const STATUS_CHOICE = [
        self::STATUS_PENDING => self::STATUS_PENDING,
        self::STATUS_PAID => self::STATUS_PAID,
        self::STATUS_EXPIRED => self::STATUS_EXPIRED,
    ];

    protected $fillable = [
        'transaction_id',
        'image',
        'note',
        'amount',
        'payment_method_id',
        'external_id',
        'checkout_link',
        'payment_callback',
        'status',
    ];
    
    protected $guarded = ['id'];

    protected static function onBoot()
    {
        self::creating(function ($model) {
            $model->status = self::STATUS_PENDING;
            $model = $model->paymentMethod->saveInfo($model);
        });
        self::updated(function ($model) {
            if($model->status == self::STATUS_PAID)
            {
                $transaction = $model->transaction;
                $amount_due = $transaction->amount_due - $model->amount;
                $transaction->amount_due = $amount_due <= 0 ? 0 : $amount_due;
                $transaction->save();
            }

                if($transaction->amount_due <= 0)
                {
                    $status = new TransactionStatus();
                    $status->transaction_id = $transaction->id;
                    $status->name = TransactionStatus::STATUS_PAID;
                    $status->description = null;
                    $status->remarks_id = $model->id;
                    $status->remarks_type = self::class;
                    $status->save();
                }
        });
    }
    
    public function isDeletable()
    {
        return true;
    }

    public function isEditable()
    {
        return true;
    }

    public function getStatusStyle(): string
    {
        $badges = [
            'warning' => [self::STATUS_PENDING],
            'success' => [self::STATUS_PAID],
            'danger' => [self::STATUS_CANCELLED],
        ];

        $status = $this->status;
        
        // Find the corresponding badge class
        $badgeColor = array_keys(array_filter($badges, fn($statuses) => in_array($status, $statuses, true)))[0] ?? 'secondary';

        return $badgeColor;
        // return "<span class='badge badge-{$badgeColor}'>" . strtoupper($status) . "</span>";
    }

    public function image_url()
    {
        return $this->image ? Storage::url(FilePathHelper::FILE_CUSTOMER_TRANSACTION_PAYMENT . $this->image) : null;
    }
    
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
}
