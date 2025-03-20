<?php

namespace App\Models\Transaction;

use App\Helpers\RomanConverter;
use App\Services\XenditService;
use App\Helpers\NumberGenerator;
use App\Models\MasterData\Voucher;
use Illuminate\Support\Facades\Log;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterData\PaymentMethod;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'user_id',
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
        'external_id',
        'checkout_link',
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

    // Initial status
    const STATUS_PENDING = 'pending'; // Waiting for payment

    // Xendit-specific statuses
    const STATUS_PAID = 'paid'; // Payment successful
    const STATUS_EXPIRED = 'expired'; // Payment expired (e.g., virtual account expired)
    const STATUS_FAILED = 'failed'; // Payment failed (e.g., insufficient funds, declined)
    const STATUS_CANCELED = 'canceled'; // User or system canceled transaction

    // Refund-related statuses
    const STATUS_REFUND_REQUESTED = 'refund_requested'; // User requested a refund
    const STATUS_REFUNDED = 'refunded'; // Payment refunded successfully
    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded'; // Partial refund issued

    // Settlement & Completion
    const STATUS_SETTLED = 'settled'; // Payment settled (confirmed by the bank)
    const STATUS_COMPLETED = 'completed'; // Order/service fulfilled after payment

    protected static function onBoot()
    {
        self::creating(function ($model) {
            $model->number = NumberGenerator::generate(self::class, 'STUDIO');
            $model->status = self::STATUS_PENDING;
            $model->external_id = $model->number;

            if($model->invoice_id)
            {
                $model->invoice->saveInfo($model);
            }

            $model->paymentMethod->saveInfo($model);
        });
    }

    public function createInvoice()
    {
        try {
            $xendit = XenditService::createInvoice($this);
    
            if (!isset($xendit['invoice_url'])) {
                throw new \Exception('Invoice URL missing, Transaction ID '.$this->id);
            }
    
            return $this->update(['checkout_link' => $xendit['invoice_url']]);
        } catch (\Exception $e) {
            Log::error('Create Invoice Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }

    public function transactionDetailSample()
    {
        return $this->belongsTo(TransactionDetail::class, 'id', 'transaction_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
    
}
