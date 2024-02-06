<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Variable;
use App\Models\Admin;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShippingMethodController extends Controller
{


    public function edit(Request $request, $id)
    {

        $data = ShippingMethod::with('agency:id,name,level')->find($id);
        $this->authorize('edit', [Admin::class, $data]);

        return Inertia::render('Panel/Admin/Shipping/Method/Edit', [
            'statuses' => Variable::STATUSES,
            'data' => $data,

        ]);
    }
}
