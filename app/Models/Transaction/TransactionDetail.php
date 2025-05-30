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
            $model->warranty_expired_at = $model->warranty_days >= 0 ? Carbon::now()->addDays($model->product->warranty_days) : null;
        });
    }

    public static function isNotAvailable($imei)
    {
        return self::join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transaction_details.imei', $imei)
            ->whereIn('transactions.payment_status', [Transaction::PAYMENT_STATUS_PENDING])
            ->where('transaction_details.deleted_at', null)
            ->lockForUpdate() // ✅ Lock slot before creating transaction
            ->exists();
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
