<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip extends Model
{
    use HasFactory;

    protected $table = 'trips';
    protected $primaryKey = 'id';
    public $timestamps = true;

    const STATUS_ASSIGNED = 0;
    const STATUS_AT_VENDOR = 1;
    const STATUS_PICKED = 2;
    const STATUS_DELIVERED = 3;

    protected $fillable = ['order_id', 'status', 'rider_name'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
