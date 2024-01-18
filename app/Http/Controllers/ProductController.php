<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public
    function view(Request $request, $id)
    {
        $data = Product::where('id', $id)->with('repository')->first();

        return Inertia::render('Product/View', [
            'back_link' => url()->previous(),
            'data' => $data,
        ]);

    }

    public
    function search(Request $request)
    {
        //disable ONLY_FULL_GROUP_BY
//        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
//        $user = auth()->user();
        $search = $request->search;
        $inShop = $request->in_shop;
        $parentIds = $request->parent_ids;
        $cityId = $request->city_id;
        $provinceId = $request->province_id;
        $page = $request->page ?: 1;
        $orderBy = 'id';
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;
        $query = Product::join('repositories', function ($join) use ($inShop, $parentIds, $cityId, $provinceId) {
            $join->on('products.repo_id', '=', 'repositories.id')
                ->where('repositories.status', 'active')
                ->where(function ($query) use ($inShop) {
                    if ($inShop)
                        $query->where('products.in_shop', '>', 0);
                })->where(function ($query) use ($parentIds) {
                    if ($parentIds && is_array($parentIds) && count($parentIds) > 0)
                        $query->whereIntegerInRaw('products.parent_id', $parentIds);
                })->where(function ($query) use ($provinceId) {
                    if ($provinceId)
                        $query->where('repositories.province_id', $provinceId);
                })->where(function ($query) use ($cityId) {
                    if ($cityId)
                        $query->whereJsonContains('repositories.cities', intval($cityId));
                });

        })->select('products.id', 'products.parent_id',
            'repositories.id as repo_id',
            'products.name as name',
            'repositories.name as repo_name',
            'products.pack_id as pack_id',
            'products.grade as grade',
            'products.price as price',
            'products.auction_price as auction_price',
            'products.auction_price as auction_price',
            'products.weight as weight',
            'products.in_auction as in_auction',
            'products.in_shop as in_shop',
            'products.parent_id as parent_id',
            'repositories.province_id as province_id',

        )
            ->orderBy('products.updated_at', 'DESC')//
            //            ->orderByRaw("IF(articles.charge >= articles.view_fee, articles.view_fee, articles.id) DESC")
        ;

        if ($search)
            $query->where('products.name', 'like', "%$search%");

        $res = $query->paginate($paginate, ['*'], 'page', $page)//            ->getCollection()->groupBy('parent_id')
        ;
        return $res;
    }
}
