<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Variable;
use App\Http\Requests\OrderRequest;
use App\Models\Admin;
use App\Models\Agency;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;
use App\Models\UserFinancial;
use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function update(OrderRequest $request)
    {
        $response = ['message' => __('response_error')];
        $errorStatus = Variable::ERROR_STATUS;
        $successStatus = Variable::SUCCESS_STATUS;
        $id = $request->id;
        $cmnd = $request->cmnd;
        $status = $request->status;
        $data = Order::find($id);
        if (!starts_with($cmnd, 'bulk'))
            $this->authorize('edit', [Admin::class, $data]);

        if ($cmnd) {
            switch ($cmnd) {
                case 'status':
                    $availableStatuses = $data->getAvailableStatuses();

                    if (!$availableStatuses->where('name', $status)->first())
                        return response()->json(['message' => __('action_not_allowed'), 'status' => $data->status,], $errorStatus);
                    $data->status = $status;
                    if ($status == 'refunded' || $status == 'rejected') {

                        $data->status = 'canceled';
                        //return order to repo
                        $userF = UserFinancial::firstOrNew(['user_id' => $data->user_id]);
                        if (!$data->user_id)
                            return response()->json(['message' => __('user_not_found'), 'status' => $data->status,], $errorStatus);

                        foreach ($data->items()->get() as $item) {
                            $variation = Variation::find($item->variation_id);
                            if ($variation) {
                                $variation->in_repo += $item->qty;
                                $variation->save();
                            }
                        }
                        $userF->wallet += $data->total_price;
                        //TODO: Save transaction
                        $userF->save();
                        //return price to user wallet
                    }
                    $data->save();
                    return response()->json(['message' => __('updated_successfully'), 'status' => $data->status, 'statuses' => $data->getAvailableStatuses()], $successStatus);


            }
        } elseif ($data) {


            $request->merge([
//                'cities' => json_encode($request->cities ?? [])
            ]);


            if ($data->update($request->all())) {

                $res = ['flash_status' => 'success', 'flash_message' => __('updated_successfully')];
//                dd($request->all());
                Telegram::log(null, 'repository_edited', $data);
            } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
            return back()->with($res);
        }

        return response()->json($response, $errorStatus);
    }

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
                'location' => $cart->address['lat'] && $cart->address['lon'] ? ($cart->address['lat'] . "," . $cart->address['lon']) : null,
                'status' => 'pending',
                'repo_id' => $cart->repo_id,
                'agency_id' => $cart->agency_id,
                'total_shipping_price' => $cart->total_shipping_price,
                'total_items_price' => $cart->total_items_price,
                'total_items' => $cart->total_items,
                'total_discount' => $cart->total_discount,
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
                            'delivery_date' => $cartItem->delivery_date,
                            'delivery_timestamp' => $cartItem->delivery_timestamp,

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

    protected
    function searchPanel(Request $request)
    {
        $admin = $request->user();

        $search = $request->search;
        $page = $request->page ?: 1;
        $orderBy = $request->order_by ?: 'id';
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;
        $status = $request->status;
        $isFromAgency = $request->is_from_agency;
        $isToAgency = $request->is_to_agency;
        $query = Order::query()->select('*');

        $myAgency = Agency::find($admin->agency_id);

        $agencyIds = $admin->allowedAgencies($myAgency)->pluck('id');

        if ($search)
            $query = $query->whereIn('status', collect(Variable::ORDER_STATUSES)->filter(fn($e) => str_contains(__($e['name']), $search))->pluck('name'));
        if ($status)
            $query = $query->where('status', $status);
        $query->whereIntegerInRaw('agency_id', $agencyIds);


        return tap($query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page), function ($paginated) {
            return $paginated->getCollection()->transform(
                function ($item) {
                    $item->statuses = $item->getAvailableStatuses();
                    return $item;
                }

            );
        });
    }

}
