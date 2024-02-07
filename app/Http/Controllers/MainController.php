<?php

namespace App\Http\Controllers;

use App\Http\Helpers\SmsHelper;
use App\Http\Helpers\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class MainController extends Controller
{
    public function sendSms(Request $request)
    {
        if (SmsHelper::checkRepeatedSMS("$request->phone", 5))
            return response(['message' => sprintf(__('you_received_sms_in_n_minutes'), 5),], 401);
        $code = Util::generateRandomNumber(5);
        $res = (new SmsHelper())->Send("$request->phone", "$code", $request->type);
        if ($res) {
            SmsHelper::addCode($request->phone, $code);
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
