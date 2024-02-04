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
            if (!$agency || !in_array($agency->level, [0, 1, 2, 3]))
                $query = $query->whereId(0);
            elseif ($agency->level == 1) //access is province_id
                $query = $query->whereIn('agency_id', Agency::whereIn('province_id', $agency->access ?? [])->where('level', '>=', $agency->level)->pluck('id'));
            elseif ($agency->level == 2) //access is agency_id
                $query = $query->whereIn('agency_id', Agency::whereIn('id', $agency->access ?? [])->where('level', '>=', $agency->level)->pluck('id'));
            elseif ($agency->level == 3) //zone
                $query = $query->where('agency_id', $agency->id);


        }
        if ($agencyId)
            $query = $query->where('id', $agencyId);
        if ($search)
            $query = $query->where('name', 'like', "%$search%");

        return $query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page);
    }
}
