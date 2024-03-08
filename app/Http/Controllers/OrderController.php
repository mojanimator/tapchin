<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Variable;
use App\Http\Requests\OrderRequest;
use App\Models\Admin;
use App\Models\Agency;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\RepositoryOrder;
use App\Models\Shipping;
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

                    if ($data->shipping_id)
                        if ($data->status == 'sending' && !in_array($status, ['refunded', 'rejected', 'delivered']))
                            return response()->json(['message' => __('shipping_orders_cant_be_edited'), 'status' => $data->status,], $errorStatus);
                        elseif ($status == 'delivered') {
                            $shipping = Shipping::find($data->shipping_id);
                            if (!$shipping)
                                return response()->json(['message' => __('shipping_not_found'), 'status' => $data->status,], $errorStatus);
                            $data->done_at = Carbon::now();
                            $shipping->order_delivered_qty++;
                            //change status to done if no shipping order
                            if (Order::where('shipping_id', $shipping->id)->where('status', 'shipping')->count() == 0
                                && RepositoryOrder::where('shipping_id', $shipping->id)->where('status', 'shipping')->count() == 0) {
                                $shipping->status = 'done';
                            }
                            $shipping->save();
                        }

                    $data->status = $status;

                    if ($status == 'refunded' || $status == 'rejected') {

//                        $data->status = 'canceled';
                        $data->status = $status;
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
                'receiver_phone' => $cart->address['receiver_phone'] ?? $user->phone ?? null,
                'postal_code' => $cart->address['postal_code'] ?? null,
                'address' => $cart->address['address'] ?? null,
                'location' => ($cart->address['lat'] ?? false) && ($cart->address['lon'] ?? false) ? ($cart->address['lat'] . "," . $cart->address['lon']) : null,
                'status' => 'pending',
                'repo_id' => $cart->repo_id,
                'agency_id' => $cart->agency_id,
                'total_shipping_price' => $cart->total_shipping_price,
                'total_items_price' => $cart->total_items_price,
                'total_items' => $cart->total_items,
                'total_discount' => $cart->total_discount,
                'total_price' => $cart->total_price,
                'shipping_method_id' => $cart->shipping_method_id,
                'shipping_id' => null,
                'delivery_date' => $cart->delivery_date,
                'delivery_timestamp' => $cart->delivery_timestamp,

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

        $agencies = $admin->allowedAgencies($myAgency)->get();
        $agencyIds = $agencies->pluck('id');

        if ($search)
            $query = $query->whereIn('status', collect(Variable::ORDER_STATUSES)->filter(fn($e) => str_contains(__($e['name']), $search))->pluck('name'));
        if ($status)
            $query = $query->where('status', $status);
        $query->whereIntegerInRaw('agency_id', $agencyIds);


        return tap($query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page), function ($paginated) use ($agencies) {
            return $paginated->getCollection()->transform(
                function ($item) use ($agencies) {
                    $item->statuses = $item->getAvailableStatuses();
                    $item->setRelation('agency', $agencies->where('id', $item->agency_id)->first());

                    return $item;
                }

            );
        });
    }

    public
    function searchMerged(Request $request)
    {
        $admin = $request->user();

        $search = $request->search;
        $page = $request->page ?: 1;
        $orderBy = $request->order_by && $request->order_by != 'items' ? $request->order_by : 'id';
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;
        $status = $request->status;
        $repoId = $request->repo_id;
        $notIn1 = $request->before_selected_user_orders;
        $notIn2 = $request->before_selected_agency_orders;
        $agencyId = $request->agency_id;
        $shippingId = $request->shipping_id;

        $query1 = Order::query()->select(
            'id',
            'agency_id AS from_agency_id',
            'repo_id AS from_repo_id',
            'user_id AS to_user_id',
            'province_id AS to_province_id',
            'county_id AS to_county_id',
            'district_id AS to_district_id',
            'receiver_fullname AS to_fullname',
            'receiver_phone AS to_phone',
            'postal_code AS to_postal_code',
            'address AS to_address',
            'location AS to_location',
            'status',
            'total_discount',
            'total_items',
            'total_price',
            'total_items_price',
            'total_shipping_price',
            'created_at',
            'updated_at',
            'done_at',
            'delivery_date',
            'delivery_timestamp',
            'shipping_id',
            'shipping_method_id',
            DB::raw('NULL AS to_admin_id'),
            DB::raw('NULL AS to_repo_id'),
            DB::raw('NULL AS to_agency_id'),
            DB::raw('NULL AS from_province_id'),
            DB::raw('NULL AS from_county_id'),
            DB::raw('NULL AS from_district_id'),
            DB::raw('NULL AS from_fullname'),
            DB::raw('NULL AS from_phone'),
            DB::raw('NULL AS from_location'),
            DB::raw('NULL AS from_postal_code'),
            DB::raw('NULL AS from_address'),
            DB::raw('"user" AS type'),

        );
        $query2 = RepositoryOrder::query()->select(
            'id',
            'from_agency_id',
            'from_repo_id',
            'to_admin_id AS to_user_id',
            'to_province_id',
            'to_county_id',
            'to_district_id',
            'to_fullname',
            'to_phone',
            'to_postal_code',
            'to_address',
            'to_location',
            'status',
            'total_discount',
            'total_items',
            'total_price',
            'total_items_price',
            'total_shipping_price',
            'created_at',
            'updated_at',
            'done_at',
            'delivery_date',
            'delivery_timestamp',
            'to_admin_id',
            'to_repo_id',
            'to_agency_id',
            'from_province_id',
            'from_county_id',
            'from_district_id',
            'from_fullname',
            'from_phone',
            'from_location',
            'from_postal_code',
            'from_address',
            'shipping_id',
            'shipping_method_id',
            DB::raw('"agency" AS type'),

        );

        $myAgency = Agency::find($admin->agency_id);

        $agencyIds = $admin->allowedAgencies($myAgency)->pluck('id');

        if ($search) {
            $query1->whereIn('status', collect(Variable::ORDER_STATUSES)->filter(fn($e) => str_contains(__($e['name']), $search))->pluck('name'));
            $query2->whereIn('status', collect(Variable::ORDER_STATUSES)->filter(fn($e) => str_contains(__($e['name']), $search))->pluck('name'));
        }
        if ($status) {
            $query1->where('status', $status);
            $query2->where('status', $status);
        }
        if ($repoId) {
            $query1->where('repo_id', $repoId);
            $query2->where('from_repo_id', $repoId);
        }
        if ($notIn1)
            $query1->whereNotIn('id', $notIn1);
        if ($notIn2)
            $query2->whereNotIn('id', $notIn2);

        $query1->whereIntegerInRaw('agency_id', $agencyIds);
        $query2->whereIntegerInRaw('from_agency_id', $agencyIds);

        if ($agencyId) {
            $query1->where('agency_id', $agencyId);
            $query2->where('from_agency_id', $agencyId);
        }
        if ($shippingId) {
            $query1->where('shipping_id', $shippingId);
            $query2->where('shipping_id', $shippingId);
        }
        $query1->with('items.variation:id,name,weight,pack_id');
        $query2->with('items.variation:id,name,weight,pack_id');

        $res = $query1->union($query2)->orderBy($orderBy, $dir);
        if ($request->for_edit)
            return $res->get();
        return $res->paginate($paginate, ['*'], 'page', $page);
    }

}
