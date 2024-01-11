<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Variable;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{

    protected function update(Request $request)
    {
        $user = auth('sanctum')->user();
        $ip = $request->ip();
        $productId = $request->product_id;
        $qty = $request->qty;
        $product = Product::find($productId);

        if (isset($qty) && !$product)
            return response()->json(['message' => __('product_not_found')], Variable::ERROR_STATUS);
        $cart = $user ? Cart::where('user_id', $user->id)->with('items')->first() : Cart::where('ip', $ip)->with('items')->first();

        $cartItems = $cart ? $cart->getRelation('items') : collect([]);
        $cart = $cart ?? Cart::create([
            'user_id' => optional($user)->id,
            'ip' => $ip,
            'last_activity' => Carbon::now(),
            'order_id' => null,
        ]);


        $beforeQty = 0;

        if ($cartItems) {
            $cartItem = $cartItems->where('product_id', $productId)->first();
            $beforeQty = optional($cartItem)->qty ?? 0;
            $inShopQty = $product->in_shop ?? 0;
            if ($qty > $inShopQty)
                return response()->json(['message' => sprintf(__('validator.max_items'), $inShopQty, $qty)], Variable::ERROR_STATUS);

            if ($cartItem) {
                $cartItems->where('product_id', $productId)->first()->update(['qty' => $qty]);
                $cart->setRelation('items', $cartItems);
            } else {
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'qty' => $qty,
                ]);
                $cartItems->push($cartItem);
                $cart->setRelation('items', $cartItems);
            }
        }
        return response()->json(['message' => __('cart_updated'), 'cart' => $cart], Variable::SUCCESS_STATUS);;
    }
}
