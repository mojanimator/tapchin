<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partnership extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'meterage',
        'province_id',
        'county_id',
        'address',
        'description',
        'products',
        'phone',
    ];

    protected $casts = [
        'products' => 'array',
    ];
}
