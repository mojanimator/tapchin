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
        'pay_gate',
    ];

    protected $casts = [
    ];

    public static function splitProfits($order, $shipping)
    {
        $percents = Setting::where('key', 'like', "order_percent_level_%")->get();

        //split shipping
        if ($order->total_shipping_price && $shipping) {
            $t = Transaction::create([
                'title' => sprintf(__('shipping_order_agency_*_*'), $order->id, $shipping->agency_id),
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
                'amount' => $order->total_shipping_price,
                'pay_id' => null,
            ]);

            $agencyF = AgencyFinancial::firstOrNew(['agency_id' => $shipping->agency_id]);
            $agencyF->payment_balance += $order->total_shipping_price;
            $agencyF->save();
        }

        $agency = Agency::find($order->agency_id);
        if (!$agency) return;
        $percent = ($percents->where('key', "order_percent_level_$agency->level")->first()->value ?? 0);
        if ($percent > 0) {
            $t = Transaction::create([
                'title' => sprintf(__('profit_order_agency_*_*_*'), floor($percent), $order->id, $agency->id),
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
                'amount' => floor($percent / 100 * $order->total_items_price),
                'pay_id' => null,
            ]);
            $agencyF = AgencyFinancial::firstOrNew(['agency_id' => $agency->id]);
            $agencyF->payment_balance += $t->amount;
            $agencyF->save();
        }
        //  split percents
        $agencyItem = Agency::find($agency->parent_id);
        while ($agencyItem != null) {
            //not pay to central

            if (!$agencyItem || $agencyItem->level == '0') break;
            $percent = $percents->where('key', "order_percent_level_$agencyItem->level")->first()->value ?? 0;
            if ($percent <= 0) continue;
            $t = Transaction::create([
                'title' => sprintf(__('profit_order_agency_*_*_*'), $percent, $order->id, $agencyItem->id),
                'type' => "profit",
                'for_type' => 'order',
                'for_id' => $order->id,
                'from_type' => 'agency',
                'from_id' => 1,
                'to_type' => 'agency',
                'to_id' => $agencyItem->id,
                'is_success' => true,
                'info' => null,
                'coupon' => null,
                'payed_at' => Carbon::now(),
                'amount' => floor($percent / 100 * $order->total_items_price),
                'pay_id' => null,
            ]);
            $agencyF = AgencyFinancial::firstOrNew(['agency_id' => $agencyItem->id]);
            $agencyF->payment_balance += $t->amount;
            $agencyF->save();

            $agencyItem = Agency::find($agencyItem->parent_id);
        }
    }
}
