<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Variable;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Repository;
use App\Models\Setting;
use App\Models\ShippingMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use PHPUnit\Framework\Constraint\Count;

class CartController extends Controller
{

    protected function update(Request $request)
    {

//        DB::listen(function ($query) {
//            Log::info($query->sql);
//        });
        $user = auth('sanctum')->user();
        $ip = $request->ip();
        $productId = $request->product_id;
        $qty = $request->qty;
        $cmnd = $request->cmnd;
        $cityId = session()->get('city_id');

//        if ($cmnd == 'count') {
//            $product = Product::with('repository')->find($productId);
//            if (isset($qty) && !$product)
//                return response()->json(['message' => __('product_not_found')], Variable::ERROR_STATUS);
//
//            $repository = $product->getRelation('repository');
//            if (!$repository)
//                return response()->json(['message' => __('repository_not_found')], Variable::ERROR_STATUS);
//            if (!$cityId || !is_int($cityId))
//                return response()->json(['message' => __('select_city_from')], Variable::ERROR_STATUS);
//            if (!in_array($cityId, $repository->cities))
//                return response()->json(['message' => __('repository_not_support_city')], Variable::ERROR_STATUS);
//        }
        $cart = $user ? Cart::where('user_id', $user->id)->with('items.product:id,name,repo_id,price,auction_price,in_shop,weight')->first() : Cart::where('ip', $ip)->with('items.product:id,name,repo_id,price,auction_price,in_shop,weight')->first();

        $cartItems = $cart ? $cart->getRelation('items') : collect([]);
        $cart = $cart ?? Cart::create([
            'user_id' => optional($user)->id,
            'ip' => $ip,
            'last_activity' => Carbon::now(),
            'order_id' => null,
        ]);

//        $productRepositories = Repository::whereIn('id', $cartItems->pluck('product.repo_id'))->get();

        $beforeQty = 0;
        $isAuction = Setting::getValue('is_auction');

        //add/remove/update an item
        if ($productId && is_int($qty)) {
            $cartItem = $cartItems->where('product_id', $productId)->first();
            $product = optional($cartItem)->getRelation('product') ?? Product::find($productId);
            $inShopQty = optional($product)->in_shop ?? 0;
            if ($qty < 0)
                return response()->json(['message' => sprintf(__('validator.invalid'), __('requested_qty'))], Variable::ERROR_STATUS);
            elseif ($qty > $inShopQty)
                return response()->json(['message' => sprintf(__('validator.max_items'), __('product'), $inShopQty, $qty)], Variable::ERROR_STATUS);

            if ($cartItem) {
                if ($qty == 0) {
                    CartItem::findOrNew($cartItem->id)->delete();
                    $cartItem->delete();
                    $cartItems->reject(function ($element) use ($cartItem) {
//                        dd($element->id . ' ' . $cartItem->id);
                        return $element->id == $cartItem->id;
                    });
                }
                $cartItem->qty = $qty;
                $cartItem->save();
//                dd($cartItems);

            } elseif ($qty > 0) {
                $cartItem = CartItem::create([
                    'repo_id' => $product->repo_id,
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'qty' => $qty,
                    'total_price' => 0,
                ]);
                $cartItem->setRelation('product', $product);
                $cartItems->push($cartItem);
            }
            $cart->setRelation('items', $cartItems);

        }

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->getRelation('product');
            $itemTotalPrice = $cartItem->qty * ($isAuction ? $product->auction_price : $product->price);
//            $cartItem->save();
            $cartItem->total_price = $itemTotalPrice;
        }
        $cart->setRelation('items', $cartItems);

        //select shipping
        $shippingMethods = ShippingMethod::whereIn('repo_id', $cartItems->pluck('repo_id'))->get();
        $shipments = [];
        foreach ($cartItems->all() as $idx => $cartItem) {
            $cityProductRestrict = $shippingMethods
                ->filter(function ($e) use ($cartItem, $cityId) {
                    $products = $e->products ?? [];
                    $cities = $e->cities ?? [];
                    return count($products) > 0 && count($cities) > 0 && in_array($cartItem->product_id, $products) && in_array($cityId, $cities);
                })->first();
            if ($cityProductRestrict) {
                $shipments[$idx] = ['method_id' => $cityProductRestrict->id, 'cart_item' => $cartItem, 'shipping' => $cityProductRestrict];
                continue;
            }
            $productRestrict = $shippingMethods
                ->filter(function ($e) use ($cartItem) {
                    $products = $e->products ?? [];
                    return count($products) > 0 && in_array($cartItem->product_id, $products);
                })->first();
            if ($productRestrict) {
                $shipments[$idx] = ['method_id' => $productRestrict->id, 'cart_item' => $cartItem, 'shipping' => $productRestrict];
                continue;
            }
            $cityRestrict = $shippingMethods
                ->filter(function ($e) use ($cartItem, $cityId) {
                    $cities = $e->cities ?? [];
                    return count($cities) > 0 && in_array($cityId, $cities);
                })->first();
            if ($cityRestrict) {
                $shipments[$idx] = ['method_id' => $cityRestrict->id, 'cart_item' => $cartItem, 'shipping' => $cityRestrict];
                continue;
            }
            $noRestrict = $shippingMethods->first();
            if ($noRestrict) {
                $shipments[$idx] = ['method_id' => $noRestrict->id, 'cart_item' => $cartItem, 'shipping' => $noRestrict];
                continue;
            }
            $repo = Repository::find($cartItem->getRelation('product')->repo_id);

            $default = Variable::getDefaultShippingMethods()[0];
            $default['description'] = $repo->address;
            $default['location'] = $repo->location;
            $default['province_id'] = $repo->province_id;
            $default['county_id'] = $repo->county_id;
            $shipments[$idx] = ['method_id' => $default['id'], 'cart_item' => $cartItem, 'shipping' => collect($default)];

        }
        $cart->shipments = collect($shipments)->groupBy('method_id');

//        dd($cart);
//        dd($cart);
//        dd($cartItems->pluck('repo_id'));
//        dd(ShippingMethod::whereIn('repo_id', $cartItems->pluck('repo_id'))->get());

        return response()->json(['message' => __('cart_updated'), 'cart' => $cart], Variable::SUCCESS_STATUS);;
    }
}
