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
        'discount',
        'total_amount',
        'amount_due',

        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_lat',
        'customer_long',
        'customer_ip_lat',
        'customer_ip_long',
        'customer_ktp',
        'customer_social_media',
        
        // Voucher Information
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

    protected static function onBoot()
    {
        self::creating(function ($model) {
            $model->number = NumberGenerator::generate(self::class, 'INV');
            if($model->voucher_id)
            {
                $model = $model->voucher->saveInfo($model);
            }
        });
        self::created(function ($model) {
            $status = new TransactionStatus();
            $status->transaction_id = $model->id;
            $status->name = TransactionStatus::STATUS_NOT_VERIFIED;
            $status->description = null;
            $status->remarks_id = $model->id;
            $status->remarks_type = self::class;
            $status->save();
        });        
    }

    public function onCreated()
    {
        logger('WA SENDING');
        ServiceHelper::kirimWhatsapp(env('ADMIN_PHONE'), ServiceHelper::generateOrderConfirmationMessage($this));
        logger('WA SENDED');
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

    public function lastStatus()
    {
        return $this->hasOne(TransactionStatus::class, 'id', 'last_status_id');
    }

    public function transactionStatuses()
    {
        return $this->hasMany(TransactionStatus::class, 'transaction_id', 'id')->orderBy('id', "DESC");
    }

    public function TransactionPayments()
    {
        return $this->hasMany(TransactionPayment::class, 'transaction_id', 'id')->orderBy('id', "DESC");
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
