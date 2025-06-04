<?php

namespace App\Models\Transaction;

use Carbon\Carbon;
use App\Models\MasterData\Studio;
use App\Models\MasterData\Product;
use Illuminate\Support\Facades\DB;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterData\ProductDetail;
use App\Models\MasterData\ProductBookingTime;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionDetail extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'imei',
        'active_at',
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
            $model = $model->product->saveInfo($model);
        });
        self::updating(function ($model) {
            if($model->active_at)
            {
                $model->warranty_expired_at = $model->warranty_days >= 0 ? Carbon::now()->addDays($model->product->warranty_days) : null;
            }
        });
        self::updated(function ($model) {
            if($model->active_at)
            {
                $transaction = $model->transaction; // assuming `belongsTo(Transaction::class)`

                // Check if all transaction details are activated (active_at is not null)
                $allActive = $transaction->transactionDetails()->whereNull('active_at')->doesntExist();

                if ($allActive) {
                    $status = new TransactionStatus();
                    $status->transaction_id = $transaction->id;
                    $status->name = TransactionStatus::STATUS_ACTIVED;
                    $status->description = null;
                    $status->remarks_id = $model->id;
                    $status->remarks_type = self::class;
                    $status->save();
                }
            }
        });
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
