<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'phone',
        'postal_code',
        'access',
        'level',
//        'has_shop',
//        'owner_id',
        'parent_id',
        'province_id',
        'county_id',
        'district_id',
        'address',
        'location',
        'status',
    ];
    protected $casts = [
        'access' => 'array'
    ];

    public function owner()
    {
        return $this->hasOne(Admin::class, 'agency_id');
    }
}
