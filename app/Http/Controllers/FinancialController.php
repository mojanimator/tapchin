<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Variable;
use App\Models\AdminFinancial;
use App\Models\Agency;
use App\Models\AgencyFinancial;
use App\Models\Order;
use App\Models\RepositoryOrder;
use App\Models\UserFinancial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class  FinancialController extends Controller
{
    public
    function searchMerged(Request $request)
    {
        $admin = $request->user();

        $search = $request->search;
        $page = $request->page ?: 1;
        $orderBy = $request->order_by ?: 'id';
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;
        $status = $request->status;
        $type = $request->type;


        $query1 = UserFinancial::query()->select(
            'id',
            'wallet',
            'card',
            'sheba',
            'created_at',
            'updated_at',
            'user_id AS owner_id',
            DB::raw('NULL as parent_debit'),
            DB::raw('NULL as agency_id'),

        );
        $query2 = AdminFinancial::query()->select(
            'id',
            'wallet',
            'card',
            'sheba',
            'created_at',
            'updated_at',
            'admin_id AS owner_id',
            'agency_id',
            DB::raw('NULL as parent_debit'),

        );
        $query3 = AgencyFinancial::query()->select(
            'id',
            'wallet',
            'card',
            'sheba',
            'created_at',
            'updated_at',

            'agency_id AS owner_id',
            'parent_debit',
            DB::raw('NULL as agency_id'),
        );
        if (!$admin->hasAccess('edit_setting')) {
            $myAgency = Agency::find($admin->agency_id);
            $agencyIds = $admin->allowedAgencies($myAgency)->pluck('id');
            //cant see users
            $query1->whereId(0);
            $query2->whereInegerInRaw('agency_id', $agencyIds);
            $query3->whereInegerInRaw('agency_id', $agencyIds);
        }


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
            return $res->get()->map(function ($e) {
                $e->statuses = $e->getAvailableStatuses();
                return $e;
            });
        return tap($res->paginate($paginate, ['*'], 'page', $page), function ($paginated) {
            return $paginated->getCollection()->transform(
                function ($item) {
                    $item->statuses = $item->getAvailableStatuses();
                    return $item;
                }

            );
        });
    }
}
