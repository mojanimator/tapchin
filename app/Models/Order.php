<?php

namespace App\Models;

use App\Http\Requests\OrderRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'repo_id',
        'agency_id',
        'province_id',
        'county_id',
        'district_id',
        'receiver_fullname',
        'receiver_phone',
        'location',
        'postal_code',
        'address',
        'status',
        'total_shipping_price',
        'total_items_price',
        'total_items',
        'total_price',
    ];

    public function store(OrderRequest $request)
    {

    }
}
