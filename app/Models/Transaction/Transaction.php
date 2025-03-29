<?php

namespace App\Models\Transaction;

use App\Helpers\RomanConverter;
use App\Services\XenditService;
use App\Helpers\NumberGenerator;
use App\Models\MasterData\Voucher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterData\PaymentMethod;
use App\Models\User;
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
        'scanned_at',
        'subtotal',
        'admin_fee',
        'discount',

        // Seeder
        'created_at',
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
    const STATUS_PENDING = 'PENDING'; // Waiting for payment

    // Xendit-specific statuses
    const STATUS_PAID = 'PAID'; // Payment successful
    const STATUS_EXPIRED = 'EXPIRED'; // Payment expired (e.g., virtual account expired)
    const STATUS_FAILED = 'FAILED'; // Payment failed (e.g., insufficient funds, declined)
    const STATUS_CANCELED = 'CANCELED'; // User or system canceled transaction

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

    protected static function onBoot()
    {
        self::creating(function ($model) {
            $model->number = NumberGenerator::generate(self::class, 'STUDIO');
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

    public function getStatusBadge(): string
    {
        $badges = [
            'warning' => [self::STATUS_PENDING],
            'success' => [self::STATUS_PAID],
            'danger' => [self::STATUS_EXPIRED],
            'dark' => [self::STATUS_EXPIRED],
            'secondary' => [self::STATUS_CANCELED],
            'info' => [self::STATUS_CANCELED],
        ];

        $status = $this->status;
        
        // Find the corresponding badge class
        $badgeColor = array_keys(array_filter($badges, fn($statuses) => in_array($status, $statuses, true)))[0] ?? 'secondary';

        return $badgeColor;
        return "<span class='badge badge-{$badgeColor}'>" . strtoupper($status) . "</span>";
    }

    public function getAction(): string
    {
        $html = '';
        switch ($this->status) {
            case self::STATUS_PENDING:
                $html = "<div class='col-auto mb-2'>
                            <a class='btn btn-success btn-sm w-100' href='{$this->checkout_link}'>
                                Bayar Sekarang
                            </a>
                        </div>";
                break;

            case self::STATUS_PAID:
                $route = route('service.generate', ['id' => Crypt::encrypt($this->id)]);
                $html = "<div class='col-auto mb-2'>
                            <a class='btn btn-primary btn-sm w-100' href='{$route}' target='_BLANK'>
                                Generate QR-Code
                            </a>
                        </div>";
                break;
            
            default:
                $html = '<p class="text-center fw-bold fs-3"> - </p>';
                break;
        }
        return $html;
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }

    public function transactionDetailSample()
    {
        return $this->belongsTo(TransactionDetail::class, 'id', 'transaction_id')->orderBy('product_booking_time_time', 'ASC');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
    
}
