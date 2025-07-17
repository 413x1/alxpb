<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active',
    ];

    public function banners(): HasMany
    {
        return $this->hasMany(ProductBanner::class, 'product_id');
    }
}
