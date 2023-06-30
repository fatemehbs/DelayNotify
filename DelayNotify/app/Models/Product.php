<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';
    public $timestamps = true;

    const STATUS_AVAILABLE = 1;
    const STATUS_UNAVAILABLE = 0;

    protected $fillable = ['vendor_id', 'price', 'status', 'title', 'description'];

}
