<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_id',
        'product_id',
        'device_id',
        'status',
        'qty',
        'total_price',
        'gateway_response',
        'is_active',
    ];
}
