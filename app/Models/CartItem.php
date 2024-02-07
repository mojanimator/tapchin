<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'repo_id',
        'cart_id',
        'product_id',
        'shipping_id',
        'qty',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}