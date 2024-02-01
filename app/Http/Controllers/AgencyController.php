<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Telegram;
use App\Http\Helpers\Variable;
use App\Http\Requests\AgencyRequest;
use App\Models\Admin;
use App\Models\Agency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgencyController extends Controller
{

    public function edit(Request $request, $id)
    {

        $data = Agency::with('owner:id,fullname,phone,agency_id')->find($id);
        $this->authorize('edit', [Admin::class, $data]);

        $data->type_id = $data->level;
        $data->supported_provinces = $data->level == '1' ? $data->access : [];

        return Inertia::render('Panel/Admin/Agency/Edit', [
            'statuses' => Variable::STATUSES,
            'data' => $data,
            'parent_agencies' => Agency::whereNot('level', Variable::AGENCY_TYPES[count(Variable::AGENCY_TYPES) - 1]['level'])->whereNotNull('level')->select('id', 'name', 'province_id', 'level', 'access')->get(),

        ]);
    }

    public function update(AgencyRequest $request)
    {
        $response = ['message' => __('response_error')];
        $errorStatus = Variable::ERROR_STATUS;
        $successStatus = Variable::SUCCESS_STATUS;
        $id = $request->id;
        $cmnd = $request->cmnd;
        $data = Agency::find($id);
        if (!starts_with($cmnd, 'bulk'))
            $this->authorize('edit', [Admin::class, $data]);

        if ($cmnd) {
            switch ($cmnd) {
                case 'inactive':
                    $data->status = 'inactive';
                    $data->save();
                    return response()->json(['message' => __('updated_successfully'), 'status' => $data->status,], $successStatus);

                case 'activate':
                    $data->status = 'active';
                    $data->save();
                    return response()->json(['message' => __('updated_successfully'), 'status' => $data->status,], $successStatus);


            }
        } elseif ($data) {


            $request->merge([
                'level' => strval($request->type_id),
                'access' => $request->type_id == 1 && $request->supported_provinces ? $request->supported_provinces : [],

            ]);


//            $data->name = $request->tags;
//            $data->tags = $request->tags;
//            dd($request->tags);
            if ($data->update($request->all())) {

                $res = ['flash_status' => 'success', 'flash_message' => __('updated_successfully')];
//                dd($request->all());
                Telegram::log(null, 'agency_edited', $data);
            } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
            return back()->with($res);
        }

        return response()->json($response, $errorStatus);
    }

    protected function create(Request $request)
    {
//        $user = $request->user();


        $request->merge([

            'status' => 'active',
            'level' => strval($request->type_id),
            'access' => $request->type_id == 1 && $request->supported_provinces ? $request->supported_provinces : [],

        ]);

        $data = Agency::create($request->all());

        if ($data) {
            if ($data->level == '3') {
                //add branch to parent access
                $parentAgency = Agency::find($data->parent_id);
                if ($parentAgency && $parentAgency->level == '2') {
                    $access = $parentAgency->access ?? [];
                    if (!in_array($data->id, $access)) {
                        $access[] = $data->id;
                        $parentAgency->access = $access;
                        $parentAgency->save();
                    }
                }
            }

            $res = ['flash_status' => 'success', 'flash_message' => __('created_successfully')];

//            SMSHelper::deleteCode($phone);
            Telegram::log(null, 'agency_created', $data);
        } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
        return to_route('admin.panel.agency.index')->with($res);

    }

    public
    function searchPanel(Request $request)
    {
        $admin = $request->user();
        $search = $request->search;
        $page = $request->page ?: 1;
        $orderBy = $request->order_by ?: 'id';
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;
        $query = Agency::query();
        $query = $query->select('*');


        if ($admin->role != 'god') {
            $agency = Agency::find($admin->id);
            if (!$agency)
                $query->where('id', 0);
            elseif ($agency->level == '1')
                $query->whereIn('province_id', $agency->access);
            elseif ($agency->level == '2')
                $query->whereIn('id', $agency->access);
            elseif ($agency->level == '3')
                $query->where('id', $agency->id);

        }
        if ($search)
            $query = $query->where('name', 'like', "%$search%");

        return $query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page);
    }


}
