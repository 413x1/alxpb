<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'url',
        'type',
        'is_active',
    ];
}
