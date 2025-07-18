<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'identifier',
        'code',
        'is_active',
    ];
}
