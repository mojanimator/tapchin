<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Variable;
use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{


    public function create(OrderRequest $request)
    {

//        $cart = (new CartController())->update($request);
//        $cart = $cart ? $cart->getData() : null;
//        $cart = optional($cart)->cart ?? null;
        $user = auth('sanctum')->user();
        $cart = $request->cart;
        if (!$cart) {
            return response()->json(['message' => __('problem_in_create_order'), 'cart' => $cart], Variable::ERROR_STATUS);
        }

        if ($cart->errors) {
            return response()->json(['message' => __('please_correct_errors'), 'cart' => $cart], Variable::ERROR_STATUS);

        }
        if (count($cart->orders) == 0) {
            return response()->json(['message' => __('cart_is_empty'), 'cart' => $cart], Variable::ERROR_STATUS);
        }

//split cart to each repo

        //create order for each repo
        foreach ($cart->orders as $cart) {
            $order = Order::create([
                'user_id' => $user->id,
                'province_id' => $cart->address['province_id'] ?? null,
                'county_id' => $cart->address['county_id'] ?? null,
                'district_id' => $cart->address['district_id'] ?? null,
                'receiver_fullname' => $cart->address['receiver_fullname'] ?? null,
                'receiver_phone' => $cart->address['receiver_phone'] ?? null,
                'postal_code' => $cart->address['postal_code'] ?? null,
                'address' => $cart->address['address'] ?? null,
                'status' => 'pending',
                'repo_id' => $cart->repo_id,
                'agency_id' => $cart->agency_id,
                'total_shipping_price' => $cart->total_shipping_price,
                'total_items_price' => $cart->total_items_price,
                'total_items' => $cart->total_items,
                'total_price' => $cart->total_price,
            ]);
            if ($order) {
                $items = [];
                $shippings = [];
                foreach ($cart->shipments as $shipment) {
                    foreach ($shipment['items'] as $item) {
                        $cartItem = $item ['cart_item'];
                        $methodId = $item ['method_id'];
                        $product = Variation::find($cartItem->variation_id);
                        if (!$product)
                            return response()->json(['message' => __('problem_in_create_order')], Variable::ERROR_STATUS);
                        $product->in_shop -= $cartItem->qty;
                        $product->save();
                        if (str_starts_with($methodId, 'repo-')) //visit-repo [change id to 1]
                            $methodId = 1;
                        $items[] = [
                            'name' => $cartItem->name,
                            'order_id' => $order->id,
                            'variation_id' => $cartItem->variation_id,
                            'qty' => $cartItem->qty,
                            'shipping_method_id' => $methodId,
                            'shipping_id' => null,
                            'repo_id' => $cartItem->repo_id,
                            'total_price' => $cartItem->total_price ?? 0,
                            'discount_price' => $cartItem->discount_price ?? 0,
                            'created_at' => Carbon::now(),

                        ];
                    }

                }
                if (!DB::table('order_items')->insert($items))
                    return response()->json(['message' => __('problem_in_create_order')], Variable::ERROR_STATUS);

                if (CartItem::where('cart_id', $cart->id)->delete())
                    Cart::find($cart->id)->delete();
            }
        }
        return response()->json(['message' => __('redirect_to_payment_page'), 'url' => 'https://tapchin.ir'], Variable::SUCCESS_STATUS);
    }
}
