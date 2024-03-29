<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminFinancial extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'wallet',
        'card',
        'sheba',
    ];
}
