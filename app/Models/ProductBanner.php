<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductBanner extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'description',
        'url',
        'is_active',
    ];

    public function getImageUrlAttribute(): ?string
    {
        return $this->url ? Storage::url($this->url) : null;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
