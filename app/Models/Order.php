<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends BaseModel
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
        'is_voucher',
        'voucher_id',
        'is_active',
        'snap_token'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }
}
