<?php

namespace App\Models;

use Carbon\Carbon;
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

    public static function splitProfits($order, $shipping)
    {
        $percents = Setting::where('key', 'like', "order_percent_level_%")->get();

        //split shipping
        if ($order->shipping_price && $shipping) {
            $t = Transaction::create([
                'title' => sprintf(__('shipping_order_agency_*_*'), $order->id, optional($shipping)->agency_id),
                'type' => "shipping",
                'for_type' => 'order',
                'for_id' => $order->id,
                'from_type' => 'agency',
                'from_id' => 1,
                'to_type' => 'agency',
                'to_id' => optional($shipping)->agency_id,
                'is_success' => true,
                'info' => null,
                'coupon' => null,
                'payed_at' => Carbon::now(),
                'amount' => $order->shipping_price,
                'pay_id' => null,
            ]);
            $agencyF = AgencyFinancial::firstOrNew(['agency_id' => optional($shipping)->agency_id]);
            $agencyF->payment_balance += $order->shipping_price;
            $agencyF->save();
        }

        $agency = Agency::find($order->agency_id);
        if (!$agency) return;
        $percent = $percents->where('key', "order_percent_level_$agency->level")->first()->value ?? 0;
        if ($percent > 0) {
            $t = Transaction::create([
                'title' => sprintf(__('profit_order_agency_*_*_*'), $order->id, optional($shipping)->agency_id),
                'type' => "profit",
                'for_type' => 'order',
                'for_id' => $order->id,
                'from_type' => 'agency',
                'from_id' => 1,
                'to_type' => 'agency',
                'to_id' => $agency->id,
                'is_success' => true,
                'info' => null,
                'coupon' => null,
                'payed_at' => Carbon::now(),
                'amount' => floor($percent * $order->total_items_price),
                'pay_id' => null,
            ]);
            $agencyF = AgencyFinancial::firstOrNew(['agency_id' => $agency->id]);
            $agencyF->payment_balance += $t->amount;
            $agencyF->save();
        }
        //  split percents
        foreach (Agency::find($agency->parent_id) as $agency) {
            //not pay to central
            if (!$agency || $agency->level == '0') break;
            $percent = $percents->where('key', "order_percent_level_$agency->level")->first()->value ?? 0;
            if ($percent <= 0) continue;
            $t = Transaction::create([
                'title' => sprintf(__('profit_order_agency_*_*_*'), $order->id, optional($shipping)->agency_id),
                'type' => "profit",
                'for_type' => 'order',
                'for_id' => $order->id,
                'from_type' => 'agency',
                'from_id' => 1,
                'to_type' => 'agency',
                'to_id' => $agency->id,
                'is_success' => true,
                'info' => null,
                'coupon' => null,
                'payed_at' => Carbon::now(),
                'amount' => floor($percent * $order->total_items_price),
                'pay_id' => null,
            ]);
            $agencyF = AgencyFinancial::firstOrNew(['agency_id' => $agency->id]);
            $agencyF->payment_balance += $t->amount;
            $agencyF->save();

        }
    }
}
