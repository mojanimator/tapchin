<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'type',
        'for_type',
        'for_id',
        'from_type',
        'from_id',
        'to_type',
        'to_id',
        'info',
        'amount',
        'pay_id',
        'coupon',
        'payed_at',
    ];

    protected $casts = [
    ];
}
