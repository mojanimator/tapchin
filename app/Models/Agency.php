<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'access',
        'type',
        'has_shop',
        'owner_id',
        'province_id',
        'county_id',
        'address',
        'status',
    ];
    protected $casts = [
        'access' => 'array'
    ];
}
