<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;

class BsaleDocument extends Model
{
    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
