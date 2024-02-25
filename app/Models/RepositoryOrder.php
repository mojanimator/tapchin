<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepositoryOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_admin_id',
        'to_admin_id',
        'from_repo_id',
        'from_agency_id',
        'to_repo_id',
        'to_agency_id',
        'from_province_id',
        'to_province_id',
        'from_county_id',
        'to_county_id',
        'from_district_id',
        'to_district_id',
        'to_fullname',
        'to_phone',
        'from_fullname',
        'from_phone',
        'from_location',
        'to_location',
        'from_postal_code',
        'to_postal_code',
        'from_address',
        'to_address',
        'status',
        'total_shipping_price',
        'total_items_price',
        'total_items',
        'total_price',
        'total_discount',
    ];

    public function items()
    {
        return $this->hasMany(RepositoryOrderItem::class, 'order_id');
    }
}
