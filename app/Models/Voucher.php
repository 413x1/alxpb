<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'is_used',
        'is_willcard',
        'used_at',
        'created_by',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'is_willcard' => 'boolean',
        'used_at' => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
