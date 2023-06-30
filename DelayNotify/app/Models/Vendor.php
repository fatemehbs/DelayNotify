<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';
    protected $primaryKey = 'id';
    public $timestamps = true;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = -1;

    const TYPE_RESTAURANT = 1;
    const TYPE_CAFE = 2;
    const TYPE_STORE = 3;

    protected $fillable = [
        'code',
        'phone_number',
        'type',
        'status',
        'title',
        'description',
        'address',
        'city',
        'banner_image',
        'delivery_fee',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'vendor_id', 'id');
    }


    public static function statusReadable($status)
    {
        switch ($status) {
            case self::STATUS_ACTIVE:
                return 'فعال';
            case self::STATUS_INACTIVE:
                return 'غیر فعال';
        }
    }

    public static function typeReadable($type)
    {
        switch ($type) {
            case self::TYPE_RESTAURANT:
                return 'رستوران';
            case self::TYPE_CAFE:
                return 'کافی شاپ';
            case self::TYPE_STORE:
                return 'فروشگاه';
        }
    }
}
