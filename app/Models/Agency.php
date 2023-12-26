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
        'owner_id',
        'province_id',
        'county_id',
        'address',
    ];
    protected $casts = [
        'access' => 'array'
    ];
}
