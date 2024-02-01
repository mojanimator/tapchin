<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'agency_id',
        'province_id',
        'county_id',
        'district_id',
        'address',
        'location',
        'cities',
        'is_shop',
        'allow_visit',
        'phone',
        'status',
    ];

    protected $casts = [
        'cities' => 'array',
        'allow_visit' => 'boolean',
    ];

    public function shippingMethods()
    {
        return $this->hasMany(ShippingMethod::class, 'repo_id');
    }
}
