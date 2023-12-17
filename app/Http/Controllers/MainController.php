<?php

namespace App\Http\Controllers;

use App\Http\Helpers\SMSHelper;
use App\Http\Helpers\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class MainController extends Controller
{
    public function sendSms(Request $request)
    {
        if ($request->type != 'forget' && DB::table($request->table)->where('phone', "$request->phone")->where('phone_verified', true)->exists())
            return response(['message' => __('phone_is_repeated'),], 401);
        $code = Util::generateRandomNumber(5);
        $res = (new SMSHelper())->sendOTPSMS("$request->phone", "$code", $request->type);
        if ($res) {
            SMSHelper::addCode($request->phone, $code);
            return response(['message' => __('sms_send_to_phone')]);
        }
        return response(['message' => __('sms_send_to_phone')]);
    }

    public
    function makeMoneyPage()
    {

        return Inertia::render('MakeMoney', [
        ]);

    }

    public
    function contactUsPage()
    {

        return Inertia::render('ContactUs', [
        ]);

    }
}
