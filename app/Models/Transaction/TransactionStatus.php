<?php

namespace App\Models\Transaction;

use Carbon\Carbon;
use App\Helpers\ServiceHelper;
use App\Models\MasterData\Studio;
use App\Models\MasterData\Product;
use Illuminate\Support\Facades\DB;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterData\ProductDetail;
use App\Models\MasterData\ProductBookingTime;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionStatus extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    const STATUS_NOT_VERIFIED = "Belum Diverifikasi";
    const STATUS_VERIFIED = "Diverifikasi";
    const STATUS_ACTIVED = "Diaktifkan";
    const STATUS_PAID = "Dibayar Lunas";
    const STATUS_COMPLETED = "Selesai";
    const STATUS_CANCELLED = "Dibatalkan";

    const STATUS_CHOICE = [
        self::STATUS_NOT_VERIFIED => self::STATUS_NOT_VERIFIED,
        self::STATUS_VERIFIED => self::STATUS_VERIFIED,
        self::STATUS_COMPLETED => self::STATUS_COMPLETED,
        self::STATUS_CANCELLED => self::STATUS_CANCELLED,
    ];

    const STATUS_STEPS = [
        self::STATUS_NOT_VERIFIED => [self::STATUS_VERIFIED, self::STATUS_CANCELLED],
        self::STATUS_VERIFIED => [self::STATUS_COMPLETED],
    ];
    protected $fillable = [
        'transaction_id',
        'name',
        'description',
        'remarks_id',
        'remarks_type',
    ];
    
    protected $guarded = ['id'];

    protected static function onBoot()
    {
        self::created(function ($model) {
            // Update Last Status
            $transaction = $model->transaction;
            $transaction->last_status_id = $model->id;
            $transaction->save();

            if ($transaction->transactionStatuses()->where('name', self::STATUS_ACTIVED)->where('name', self::STATUS_PAID)->exists()) {
                $status = new TransactionStatus();
                $status->transaction_id = $transaction->id;
                $status->name = TransactionStatus::STATUS_COMPLETED;
                $status->description = null;
                $status->remarks_id = $model->id;
                $status->remarks_type = self::class;
                $status->save();
            }
            if ($model->name === self::STATUS_ACTIVED && $transaction->transactionStatuses()->where('name', self::STATUS_PAID)->doesntExist()) {
                
                logger('WA SENDING');
                ServiceHelper::kirimWhatsapp($model->transaction->customer_phone, ServiceHelper::generateWhatsappPaymentMessage($model->transaction));
                logger('WA SENDED');
            }

        });

        self::deleted(function ($model) {
            // Update Last Status
            $transaction = $model->transaction;
            $transaction->last_status_id = $transaction->transactionStatuses()->orderBy('id', 'DESC')->first()->id;
            $transaction->save();
        });
    }

    public function isDeletable()
    { 
        return $this->name !== self::STATUS_NOT_VERIFIED;
    }

    public function isEditable()
    {
        return true;
    }

    public function getStatusStyle(): string
    {
        $badges = [
            'secondary' => [self::STATUS_NOT_VERIFIED],
            'primary' => [self::STATUS_VERIFIED],
            'warning' => [self::STATUS_ACTIVED],
            'success' => [self::STATUS_COMPLETED],
            'danger' => [self::STATUS_CANCELLED],
        ];

        $status = $this->status;
        
        // Find the corresponding badge class
        $badgeColor = array_keys(array_filter($badges, fn($statuses) => in_array($status, $statuses, true)))[0] ?? 'secondary';

        return $badgeColor;
        // return "<span class='badge badge-{$badgeColor}'>" . strtoupper($status) . "</span>";
    }

    public static function getStyle($status): string
    {
        $badges = [
            'secondary' => [self::STATUS_NOT_VERIFIED],
            'primary' => [self::STATUS_VERIFIED],
            'warning' => [self::STATUS_ACTIVED],
            'success' => [self::STATUS_COMPLETED],
            'danger' => [self::STATUS_CANCELLED],
        ];
        
        // Find the corresponding badge class
        $badgeColor = array_keys(array_filter($badges, fn($statuses) => in_array($status, $statuses, true)))[0] ?? 'secondary';

        return $badgeColor;
        // return "<span class='badge badge-{$badgeColor}'>" . strtoupper($status) . "</span>";
    }

    public static function nextStatuses($id)
    {
        $current_status = self::find($id);
        
        // Handle Next Statuses For Verified Status
        $next_statuses = isset(self::STATUS_STEPS[$current_status->name]) ? self::STATUS_STEPS[$current_status->name] : [];

        $permitted_next_statuses = [];
        foreach ($next_statuses as $nextStatus) {
            array_push($permitted_next_statuses, [
                'status' => $nextStatus,
                'style' => self::getStyle($nextStatus),
            ]);
        }

        return $permitted_next_statuses;
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
