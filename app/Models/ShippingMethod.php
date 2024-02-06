<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $table = 'shipping_methods';

    protected $fillable = [
        'repo_id',
        'status',
        'products',
        'cities',
        'per_weight_price',
        'base_price',
        'name',
        'description',
        'free_from_price',
    ];

    protected $casts = [
        'products' => 'array',
        'cities' => 'array',
    ];
}
