<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Telegram;
use App\Http\Helpers\Variable;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\RepositoryOrderRequest;
use App\Models\Admin;
use App\Models\Agency;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Repository;
use App\Models\RepositoryCart;
use App\Models\RepositoryCartItem;
use App\Models\RepositoryOrder;
use App\Models\ShippingMethod;
use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RepositoryOrderController extends Controller
{
    //

    public function createFromCard(Request $request)
    {

//        $cart = (new CartController())->update($request);
//        $cart = $cart ? $cart->getData() : null;
//        $cart = optional($cart)->cart ?? null;
        $admin = $request->user();
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
            $fromRepoId = $cart->repo_id;
            $toRepoId = $cart->to_repo_id;
            $repos = Repository::whereIn('id', [$fromRepoId, $toRepoId])->get();
            $fromRepo = $repos->where('id', $fromRepoId)->first();
            $toRepo = $repos->where('id', $toRepoId)->first();
            $toAdmin = Admin::find($toRepo->admin_id) ?? Admin::where('agency_id', $toRepo->agency_id)->where('role', 'owner')->first();
            $fromAdmin = Admin::find($fromRepo->admin_id) ?? Admin::where('agency_id', $fromRepo->agency_id)->where('role', 'owner')->first();
            $order = RepositoryOrder::create([
                /*  'to_province_id' => $cart->to_address['province_id'] ?? null,
                  'to_county_id' => $cart->to_address['county_id'] ?? null,
                  'to_district_id' => $cart->to_address['district_id'] ?? null,
                  'to_fullname' => $cart->to_address['receiver_fullname'] ?? null,
                  'to_phone' => $cart->to_address['receiver_phone'] ?? null,
                  'to_postal_code' => $cart->to_address['postal_code'] ?? null,
                  'to_address' => $cart->to_address['address'] ?? null,
                  'to_location' => $cart->to_address['location'] ?? null,
               */
                'status' => $cart->status ?? 'request',

                'from_repo_id' => $cart->repo_id,
                'from_agency_id' => $cart->agency_id,
                'to_repo_id' => $cart->to_repo_id,
                'to_agency_id' => $cart->to_agency_id,

                'to_admin_id' => $toAdmin->id ?? null,
                'from_admin_id' => $fromAdmin->id ?? null,

                'to_province_id' => $toRepo->province_id,
                'to_county_id' => $toRepo->county_id,
                'to_district_id' => $toRepo->district_id,
                'to_fullname' => $toAdmin->fullname ?? null,
                'to_phone' => $toRepo->phone ?? $toAdmin->phone ?? null,
                'to_postal_code' => $toRepo->postal_code,
                'to_address' => $toRepo->address,
                'to_location' => $toRepo->location,


                'from_province_id' => $fromRepo ? $fromRepo->province_id : $request->from_province_id,
                'from_county_id' => $fromRepo ? $fromRepo->county_id : $request->from_county_id,
                'from_district_id' => $fromRepo ? $fromRepo->district_id : $request->from_district_id,
                'from_fullname' => $fromAdmin->fullname ?? null,
                'from_phone' => $fromRepo->phone ?? $fromAdmin->phone ?? null,
                'from_postal_code' => $fromRepo ? $fromRepo->postal_code : $request->from_postal_code,
                'from_address' => $fromRepo ? $fromRepo->address : $request->from_address,
                'from_location' => $fromRepo ? $fromRepo->location : $request->from_location,


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

                        ];
                    }

                }
                if (!DB::table('repository_order_items')->insert($items))
                    return response()->json(['message' => __('problem_in_create_order')], Variable::ERROR_STATUS);

                if (RepositoryCartItem::where('cart_id', $cart->id)->delete())
                    RepositoryCart::find($cart->id)->delete();
            }
        }
        return response()->json(['status' => 'success', 'message' => __('order_will_process_and_send_pay_link'), 'url' => route('admin.panel.repository.shop.index')], Variable::SUCCESS_STATUS);
    }

    public function create(RepositoryOrderRequest $request)
    {
        $admin = $request->user();

        $orderType = $request->order_type;
        $products = $request->products;
        $fromRepoId = $orderType == __('external') ? null : $request->from_repo_id;
        $toRepoId = $request->to_repo_id;
        $repos = Repository::whereIn('id', $fromRepoId ? [$fromRepoId, $toRepoId] : [$toRepoId])->get();
        $fromRepo = $repos->where('id', $fromRepoId)->first();
        $toRepo = $repos->where('id', $toRepoId)->first();

        $order = RepositoryOrder::create([
            'from_repo_id' => optional($fromRepo)->id,
            'from_agency_id' => optional($fromRepo)->agency_id,
            'to_repo_id' => $toRepo->id,
            'to_agency_id' => $toRepo->agency_id,
            'to_admin_id' => $toRepo->agency_id == $admin->agency_id ? $admin->id : null,
            'from_admin_id' => optional($fromRepo)->agency_id == $admin->agency_id ? $admin->id : null,

            'to_province_id' => $toRepo->province_id,
            'to_county_id' => $toRepo->county_id,
            'to_district_id' => $toRepo->district_id,
            'to_fullname' => optional(Admin::find($toRepo->admin_id))->fullname,
            'to_phone' => $toRepo->phone,
            'to_postal_code' => $toRepo->postal_code,
            'to_address' => $toRepo->address,
            'to_location' => $toRepo->location,


            'from_province_id' => $fromRepo ? $fromRepo->province_id : $request->from_province_id,
            'from_county_id' => $fromRepo ? $fromRepo->county_id : $request->from_county_id,
            'from_district_id' => $fromRepo ? $fromRepo->district_id : $request->from_district_id,
            'from_fullname' => $fromRepo ? optional(Admin::find($fromRepo->admin_id))->fullname : $request->from_fullname,
            'from_phone' => $fromRepo ? $fromRepo->phone : $request->from_phone,
            'from_postal_code' => $fromRepo ? $fromRepo->postal_code : $request->from_postal_code,
            'from_address' => $fromRepo ? $fromRepo->address : $request->from_address,
            'from_location' => $fromRepo ? $fromRepo->location : $request->from_location,

            'status' => $request->status,

            'total_shipping_price' => $request->total_shipping_price,
            'total_discount' => $request->total_discount,
            'total_items_price' => $request->total_items_price,
            'total_items' => $request->total_items,
            'total_price' => $request->total_price,
        ]);

        if ($orderType == __('external')) {
            $this->authorize('create', [Admin::class, Variation::class]);
//            dd($request->all());
            foreach ($products as $idx => $product) {
                $products[$idx]['variation'] = Variation::create([
                    'name' => optional($request->database_products->where('id', $product['id'])->first())->name,
                    'category_id' => optional($request->database_products->where('id', $product['id'])->first())->category_id,
                    'in_repo' => $product['qty'],
                    'price' => $product['price'],
                    'pack_id' => $product['pack_id'],
                    'grade' => $product['grade'],
                    'product_id' => $product['id'],
                    'weight' => $product['pack_id'] == 1 ? 1 : $product['weight'],
                ]);
            }

        } else {
            foreach ($products as $idx => $product) {
                $products[$idx]['variation'] = $request->database_products->where('id', $product['id'])->first();
                $products[$idx]['variation']->in_repo -= $product['qty'];
                $products[$idx]['variation']->save();
            }
        }
        $items = [];
        foreach ($products as $item) {
            $variation = $item['variation'];
            $items[] = [
                'name' => $variation->name,
                'order_id' => $order->id,
                'variation_id' => $variation->id,
                'qty' => $item['qty'],
                'shipping_method_id' => $request->shipping_method_id,
                'shipping_id' => null,
                'repo_id' => $fromRepoId,
                'total_price' => $item['qty'] * $item['price'],
                'discount_price' => 0,
                'created_at' => Carbon::now(),
            ];
        }
        if (!DB::table('repository_order_items')->insert($items))
            return back()->withErrors(['message' => __('problem_in_create_order')]);

        $order->items = $items;

        $res = ['flash_status' => 'success', 'flash_message' => __('created_successfully')];
        Telegram::log(null, 'repo_order_created', $order);
        return to_route('admin.panel.repository.order.index')->with($res);

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
        $query = RepositoryOrder::query()->select('*');

        $myAgency = Agency::find($admin->agency_id);
        $agencies = $admin->allowedAgencies($myAgency)->select('id', 'name')->get();

//        $agencyIds = $myAgency->level == '0' ? $agencies->pluck('id')->merge([null]) : collect([$admin->agency_id]);// $agencies->pluck('id');
        $agencyIds = $agencies->pluck('id');
        if ($search)
            $query = $query->whereIn('status', collect(Variable::ORDER_STATUSES)->filter(fn($e) => str_contains(__($e['name']), $search))->pluck('name'));
        if ($status)
            $query = $query->where('status', $status);
        if ($isFromAgency)
            $query->whereIntegerInRaw('from_agency_id', $agencyIds);
        if ($isToAgency)
            $query->whereIntegerInRaw('to_agency_id', $agencyIds);

        return tap($query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page), function ($paginated) use ($agencies) {
            return $paginated->getCollection()->transform(
                function ($item) use ($agencies,) {
//                    $item->setRelation('agency', $agencies->where('id', $item->agency_id)->first());

                    return $item;
                }

            );
        });

    }

    public function edit(Request $request, $id)
    {
        $data = RepositoryOrder:: find($id);
        $admin = $request->user();
        $canEdit = $admin->can('edit', [Admin::class, $data, false]);

        return Inertia::render('Panel/Admin/Repository/Order/Edit', [
            'order_statuses' => Variable::ORDER_STATUSES,
            'data' => $data,
            'canEdit' => $canEdit,
        ]);
    }
}
