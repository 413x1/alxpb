<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageResult extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'customer_id', 'location', 'url'];
}
