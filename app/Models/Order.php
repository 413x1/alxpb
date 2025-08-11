<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'snap_token',
        'print_count'
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

    public function result(): HasOne
    {
        return $this->hasOne(ImageResult::class)->latest();
    }

    public function results(): HasMany
    {
        return $this->hasMany(ImageResult::class);
    }
}
