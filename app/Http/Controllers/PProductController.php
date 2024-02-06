<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\PProduct;
use App\Models\Product;
use App\Models\Repository;
use Illuminate\Http\Request;

class PProductController extends Controller
{
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
        $repoId = $request->repo_id;

        $data = Product::join('p_products', function ($join) use ($repoId, $search) {
            $join->on('products.parent_id', '=', 'p_products.id')
                ->where('products.repo_id', $repoId)
                ->where(function ($query) use ($search) {
                    if ($search)
                        $query->where('products.name', 'like', "%$search%");
                });

        })->select('products.id', 'products.parent_id',
            'p_products.id as parent_id',
            'products.name as name',
            'p_products.name as parent_name',
            'products.pack_id as pack_id',
            'products.grade as grade',
            'products.price as price',
            'products.auction_price as auction_price',
            'products.auction_price as auction_price',
            'products.weight as weight',
            'products.in_auction as in_auction',
            'products.in_shop as in_shop',
            'products.parent_id as parent_id',

        )
            ->get();
        //add parents to items as row
        foreach ($data as $item) {
            if (!$data->where('parent_id', 0)->where('id', $item->parent_id)->first())
                $data->push(['id' => $item->parent_id, 'name' => $item->parent_name, 'has_child' => true, 'parent_id' => 0]);
        }
        $data->sortBy('products.name');
//            ->groupBy('parent_id')->map(function ($el, $idx) {
//
//                return ['id' => $idx, 'name' => $el[0]->parent_name, 'childs' => $el];
//            })->values();//

        return $data;
    }
}
