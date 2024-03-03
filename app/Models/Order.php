<?php

namespace App\Models;

use App\Http\Helpers\Variable;
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
        'total_discount',
    ];

    public function store(OrderRequest $request)
    {

    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function getAvailableStatuses()
    {
        $statuses = collect(Variable::ORDER_STATUSES)->map(function ($e) {
            if ($e['name'] == 'canceled')
                $e['message'] = __('accept_cancel_order');
            elseif ($e['name'] == 'ready')
                $e['message'] = __('order_will_be_ready_send');
            elseif ($e['name'] == 'refunded')
                $e['message'] = __('order_will_cancel_and_refunded');
            elseif ($e['name'] == 'sending')
                $e['message'] = __('order_will_be_sending');
            elseif ($e['name'] == 'processing')
                $e['message'] = __('order_will_be_processing');
            elseif ($e['name'] == 'delivered')
                $e['message'] = __('order_will_be_delivered');
            elseif ($e['name'] == 'rejected')
                $e['message'] = __('order_will_cancel_and_refunded');

            return $e;
        });
        switch ($this->status) {
            case    'pending':
                return $statuses->whereIn('name', ['canceled']);
            case    'processing':
                return $statuses->whereIn('name', ['ready', 'refunded']);
            case    'ready':
                return $statuses->whereIn('name', ['processing', 'sending', 'delivered', 'refunded']);
            case    'sending':
                return $statuses->whereIn('name', ['ready', 'delivered', 'rejected']);
            case    'delivered':
            case    'canceled':
                return $statuses->whereIn('name', []);
            case    'rejected':
                return $statuses->whereIn('name', []);
            case    'refunded':
                return $statuses->whereIn('name', ['cancelled']);
            default:
                return $statuses->whereIn('name', []);
        }
    }
}
