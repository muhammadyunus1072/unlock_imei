<?php

namespace App\Models\Transaction;

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
        'product_detail_id',
        'imei',
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
            $model = $model->productDetail->saveInfo($model);
        });
    }

    public static function isNotAvailable($imei)
    {
        return self::join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transaction_details.imei', $imei)
            ->whereIn('transactions.status', [Transaction::STATUS_PENDING])
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
    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id', 'id');
    }
}
