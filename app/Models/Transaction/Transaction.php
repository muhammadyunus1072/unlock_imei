<?php

namespace App\Models\Transaction;

use App\Models\User;
use App\Helpers\FilePathHelper;
use App\Helpers\RomanConverter;
use App\Services\XenditService;
use App\Helpers\NumberGenerator;
use App\Helpers\ServiceHelper;
use App\Models\MasterData\Voucher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterData\PaymentMethod;
use App\Models\Transaction\TransactionDetail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'subtotal',
        'admin_fee',
        'discount',
        'grand_total',

        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_lat',
        'customer_long',
        'customer_ip_lat',
        'customer_ip_long',
        'customer_ktp',
        'customer_social_media',
        
        'verified_at',
        'transaction_status',
        'payment_status',
        'cancellation_reason',

        // Payment Method Information
        'payment_method_id',
        
        // Voucher Information
        'voucher_id',

        'external_id',
        'checkout_link',
        'payment_callback',

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

    // Transaction lifecycle statuses
    const TRANSACTION_STATUS_PENDING = 'PENDING'; // Transaction created but not yet processed
    const TRANSACTION_STATUS_PROCESSING = 'PROCESSING'; // Actively being processed
    const TRANSACTION_STATUS_COMPLETED = 'COMPLETED'; // Order fulfilled or service delivered
    const TRANSACTION_STATUS_FAILED = 'FAILED'; // Failed due to system or manual issue
    const TRANSACTION_STATUS_CANCELED = 'CANCELED'; // Canceled by user or system

    const TRANSACTION_STATUS_CHOICE = [
        self::TRANSACTION_STATUS_PENDING => self::TRANSACTION_STATUS_PENDING,
        self::TRANSACTION_STATUS_PROCESSING => self::TRANSACTION_STATUS_PROCESSING,
        self::TRANSACTION_STATUS_COMPLETED => self::TRANSACTION_STATUS_COMPLETED,
        self::TRANSACTION_STATUS_FAILED => self::TRANSACTION_STATUS_FAILED,
        self::TRANSACTION_STATUS_CANCELED => self::TRANSACTION_STATUS_CANCELED,
    ];

    // Initial status
    const PAYMENT_STATUS_PENDING = 'PENDING'; // Waiting for payment

    // Xendit-specific statuses
    const PAYMENT_STATUS_PAID = 'PAID'; // Payment successful
    const PAYMENT_STATUS_EXPIRED = 'EXPIRED'; // Payment expired (e.g., virtual account expired)
    const PAYMENT_STATUS_FAILED = 'FAILED'; // Payment failed (e.g., insufficient funds, declined)
    const PAYMENT_STATUS_CANCELED = 'CANCELED'; // User or system canceled transaction

    // Refund-related statuses
    const PAYMENT_STATUS_REFUND_REQUESTED = 'REFUND_REQUESTED'; // User requested a refund
    const PAYMENT_STATUS_REFUNDED = 'REFUNDED'; // Payment refunded successfully
    const PAYMENT_STATUS_PARTIALLY_REFUNDED = 'PARTIALLY_REFUNDED'; // Partial refund issued

    // Settlement & Completion
    const PAYMENT_STATUS_SETTLED = 'SETTLED'; // Payment settled (confirmed by the bank)
    const PAYMENT_STATUS_COMPLETED = 'COMPLETED'; // Order/service fulfilled after payment

    const PAYMENT_STATUS_CHOICE = [
        self::PAYMENT_STATUS_PENDING => self::PAYMENT_STATUS_PENDING,
        self::PAYMENT_STATUS_PAID => self::PAYMENT_STATUS_PAID,
        self::PAYMENT_STATUS_EXPIRED => self::PAYMENT_STATUS_EXPIRED,
    ];

    protected static function onBoot()
    {
        self::creating(function ($model) {
            $model->number = NumberGenerator::generate(self::class, 'INV');
            $model->external_id = $model->number;
            $model = $model->paymentMethod->saveInfo($model);
            if($model->voucher_id)
            {
                $model = $model->voucher->saveInfo($model);
            }
        });
        self::created(function ($model) {
            logger('WA SENDING');
            $result = ServiceHelper::kirimWhatsapp();
            logger($result);
            logger('WA SENDED');
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

    public function getTransactionStatusBadge(): string
    {
        $badges = [
            'warning' => [self::TRANSACTION_STATUS_PENDING],
            'info' => [self::TRANSACTION_STATUS_PROCESSING],
            'success' => [self::TRANSACTION_STATUS_COMPLETED],
            'danger' => [self::TRANSACTION_STATUS_FAILED],
            'secondary' => [self::TRANSACTION_STATUS_CANCELED],
        ];

        $status = $this->transaction_status;
        
        // Find the corresponding badge class
        $badgeColor = array_keys(array_filter($badges, fn($statuses) => in_array($status, $statuses, true)))[0] ?? 'secondary';

        return $badgeColor;
        return "<span class='badge badge-{$badgeColor}'>" . strtoupper($status) . "</span>";
    }

    public function getTransactionAction(): string
    {
        $html = '';
        switch ($this->transaction_status) {
            case self::TRANSACTION_STATUS_PENDING:
                $html = "<div class='col-auto mb-2'>
                            <a class='btn btn-success btn-sm w-100' href='{$this->checkout_link}'>
                                Bayar Sekarang
                            </a>
                        </div>";
                break;

            case self::TRANSACTION_STATUS_COMPLETED:
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
    public function getPaymentStatusBadge(): string
    {
        $badges = [
            'warning' => [self::PAYMENT_STATUS_PENDING],
            'success' => [self::PAYMENT_STATUS_PAID],
            'danger' => [self::PAYMENT_STATUS_EXPIRED],
            'dark' => [self::PAYMENT_STATUS_EXPIRED],
            'secondary' => [self::PAYMENT_STATUS_CANCELED],
            'info' => [self::PAYMENT_STATUS_CANCELED],
        ];

        $status = $this->payment_status;
        
        // Find the corresponding badge class
        $badgeColor = array_keys(array_filter($badges, fn($statuses) => in_array($status, $statuses, true)))[0] ?? 'secondary';

        return $badgeColor;
        return "<span class='badge badge-{$badgeColor}'>" . strtoupper($status) . "</span>";
    }

    public function getPaymentAction(): string
    {
        $html = '';
        switch ($this->payment_status) {
            case self::PAYMENT_STATUS_PENDING:
                $html = "<div class='col-auto mb-2'>
                            <a class='btn btn-success btn-sm w-100' href='{$this->checkout_link}'>
                                Bayar Sekarang
                            </a>
                        </div>";
                break;

            case self::PAYMENT_STATUS_PAID:
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
        return $this->belongsTo(TransactionDetail::class, 'id', 'transaction_id')->orderBy('product_id', 'ASC');
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

    public function customer_ktp_url()
    {
        return $this->customer_ktp ? Storage::url(FilePathHelper::FILE_CUSTOMER_KTP . $this->customer_ktp) : null;
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
    
}
