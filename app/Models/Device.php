<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'identifier',
        'code',
        'is_active',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
