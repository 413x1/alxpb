<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Banner extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'type',
        'is_active',
    ];

    public function getImageUrlAttribute(): ?string
    {
        return $this->url ? Storage::url($this->url) : null;
    }
}
