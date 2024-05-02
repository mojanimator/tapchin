<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Pay;
use App\Http\Helpers\Telegram;
use App\Http\Helpers\Variable;
use App\Http\Requests\FinancialRequest;
use App\Models\Admin;
use App\Models\AdminFinancial;
use App\Models\Agency;
use App\Models\AgencyFinancial;
use App\Models\Order;
use App\Models\RepositoryOrder;
use App\Models\Transaction;
use App\Models\UserFinancial;
use Carbon\Carbon;
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


        $query1 = UserFinancial::query()->join('users', function ($join) {
            $join->on('users.id', '=', 'user_financials.user_id');
        })
            ->select(
                'user_financials.wallet As wallet',
                'user_financials.card As card',
                'user_financials.sheba As sheba',
                DB::raw('NULL as agency_id'),
                'users.id As id',
                'users.fullname As name',

                DB::raw('"user" as type'),
            );

        $query2 = AdminFinancial::query()->join('admins', function ($join) {
            $join->on('admins.id', '=', 'admin_financials.admin_id');
        })
            ->select(
                'admin_financials.wallet As wallet',
                'admin_financials.card As card',
                'admin_financials.sheba As sheba',
                'admins.agency_id As agency_id',
                'admins.id As id',
                'admins.fullname As name',
                DB::raw('"admin" as type'),
            );
        $query3 = AgencyFinancial::query()->join('agencies', function ($join) {
            $join->on('agencies.id', '=', 'agency_financials.agency_id');
        })->select(
            'agency_financials.wallet',
            'agency_financials.card',
            'agency_financials.sheba',
            'agencies.id As agency_id',
            'agencies.id As id',
            'agencies.name As name',
            DB::raw('"agency" as type'),
        );
        if (!$admin->hasAccess('edit_setting')) {
            $myAgency = Agency::find($admin->agency_id);
            $agencyIds = $admin->allowedAgencies($myAgency)->pluck('id');
            //cant see users
            $query1->where('users.id', 0);
            $query2->whereIntegerInRaw('agency_id', $agencyIds);
            $query3->whereIntegerInRaw('agency_id', $agencyIds);
        }


        if ($search) {
            $query1->where('users.fullname', 'like', "%$search%")->orWhere('users.phone', 'like', "%$search");
            $query2->where('admins.fullname', 'like', "%$search%")->orWhere('admins.phone', 'like', "%$search");
            $query3->where('agencies.name', 'like', "%$search%")->orWhere('agencies.phone', 'like', "%$search");
        }


        $res = $query1->union($query2)->union($query3)->orderBy($orderBy, $dir);

        return $res->paginate($paginate, ['*'], 'page', $page);


    }

    public function update(FinancialRequest $request)
    {
        $response = ['message' => __('response_error')];
        $errorStatus = Variable::ERROR_STATUS;
        $successStatus = Variable::SUCCESS_STATUS;
        $id = $request->id;
        $cmnd = $request->cmnd;
        $amount = $request->amount;
        $type = $request->type;
        $user = $request->user();
        $userType = $user instanceof Admin ? 'admin' : 'user';
        $data = Variable::FINANCIALS [$type]::where("{$type}_id", $id)->firstOrCreate(["{$type}_id" => $id]);
        if (!starts_with($cmnd, 'bulk'))
            $this->authorize('edit', [get_class($user), $data]);
        if ($type) {
            $model = Variable::TRANSACTION_MODELS[$type]::find($id);
            $modelName = $model->name ?? $model->fullname;
        }
        if ($cmnd) {
            switch ($cmnd) {
                case  'settlement' :
                    if ($data->wallet < $amount)
                        return response()->json(['message' => sprintf(__('validator.max_amount'), __('settlement'), $data->wallet, $amount)], $errorStatus);

                    $t = Transaction::create([
                        'title' => sprintf(__('settlement_*_*_*'), number_format($amount), __($type), "$modelName ($id)"),
                        'type' => 'settlement',
                        'for_type' => $type,
                        'for_id' => $id,
                        'from_type' => 'agency',
                        'from_id' => 1,
                        'to_type' => $type,
                        'to_id' => $id,
                        'is_success' => true,
                        'info' => null,
                        'coupon' => null,
                        'payed_at' => Carbon::now(),
                        'amount' => $amount,
                        'pay_id' => null,
                    ]);
                    if ($t) {
                        $data->wallet -= $amount;
                        $data->save();
                        $t->user = $user;
                        Telegram::log(null, 'transaction_created', $t);
                    }
                    return response()->json(['message' => __('updated_successfully'), 'wallet' => $data->wallet], $successStatus);

                case  'charge' :


                    $t = Transaction::create([
                        'title' => sprintf(__('charge_*_*_*'), number_format($amount), __($type), "$modelName ($id)"),
                        'type' => 'charge',
                        'for_type' => $type,
                        'for_id' => $id,
                        'from_type' => 'agency',
                        'from_id' => 1,
                        'to_type' => $type,
                        'to_id' => $id,
                        'is_success' => true,
                        'info' => null,
                        'coupon' => null,
                        'payed_at' => Carbon::now(),
                        'amount' => $amount,
                        'pay_id' => null,
                    ]);
                    if ($t) {
                        $data->wallet += $amount;
                        $data->save();
                        $t->user = $user;
                        Telegram::log(null, 'transaction_created', $t);
                    }
                    return response()->json(['message' => __('updated_successfully'), 'wallet' => $data->wallet], $successStatus);
                case 'buy-charge':

                    $description = sprintf(__('buy_charge_*_*_*'), number_format($amount), __($type), "$modelName ($id)");

                    $response = Pay::makeUri(Carbon::now()->getTimestampMs(), "{$amount}0", $user->fullname, $user->phone, $user->email, $description, $user->id, Variable::$BANK);
                    if ($response['status'] != 'success')
                        return response()->json(['status' => 'danger', 'message' => $response['message']], Variable::ERROR_STATUS);

                    $t = Transaction::where('for_type', $type)
                        ->where('type', 'charge')
                        ->where('for_id', $user->id)
                        ->where('from_type', $userType)
                        ->where('from_id', $user->id)
                        ->where('to_type', 'agency')
                        ->where('to_id', 1)
                        ->where('payed_at', null)->first();
                    if ($t) $t->update(['pay_id' => $response['order_id'], 'amount' => $amount,]);
                    else {
                        $t = Transaction::create([
                            'title' => $description,
                            'type' => "charge",
                            'pay_gate' => Variable::$BANK,
                            'for_type' => $type,
                            'for_id' => $user->id,
                            'from_type' => $userType,
                            'from_id' => $user->id,
                            'to_type' => 'agency',
                            'to_id' => 1,
                            'info' => null,
                            'coupon' => null,
                            'payed_at' => null,
                            'amount' => $amount,
                            'pay_id' => $response['order_id'],
                        ]);
                    }
                    return response(['status' => $data->status, 'message' => __('redirect_to_payment_page'), 'url' => $response['url']], Variable::SUCCESS_STATUS);


            }
        } elseif ($data) {


        }

        return response()->json($response, $errorStatus);
    }
}
