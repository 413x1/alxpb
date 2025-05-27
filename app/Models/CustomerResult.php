<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'url',
        'type',
        'is_active'
    ];
}
