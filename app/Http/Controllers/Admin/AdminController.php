<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Variable;
use App\Models\Admin;
use App\Models\Agency;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public
    function search(Request $request)
    {
        $admin = $request->user();
        $search = $request->search;
        $page = $request->page ?: 1;
        $orderBy = $request->order_by ?: 'id';
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;
        $agencyId = $request->agency_id;
        $query = Admin::query();
        $query = $query->select('*');
        if ($admin->role != 'god') {
            $agency = Agency::find($admin->agency_id);
            $allowedAgencies = $admin->allowedAgencies($agency)->pluck('id');
            $query = $query->whereIntegerInRaw('agency_id', $allowedAgencies);
        }
        if ($agencyId)
            $query = $query->where('agency_id', $agencyId);
        if ($search)
            $query = $query->where('name', 'like', "%$search%");

        return $query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page);
    }
}
