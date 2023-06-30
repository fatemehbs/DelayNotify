<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    public $timestamps = true;

    const STATUS_DELIVERED = 1;
    const STATUS_WAITING = 0;
    const STATUS_DELAYED = 2;
    const STATUS_FAILED = -1;

    protected $fillable = ['user_id', 'status', 'total_price', 'in_delay_queue', 'shipment_amount', 'delivery_time'];

    public function delayReports(): HasMany
    {
        return $this->hasMany(DelayReport::class, 'order_id','id');
    }

    public function trip(): HasOne
    {
        return $this->hasOne(Trip::class, 'order_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function scopeOrdersInDelay($query){
        return $query->where('in_delay_queue', true);
    }
}
