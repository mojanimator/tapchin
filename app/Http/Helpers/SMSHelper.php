<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\DB;
use Medianasms\Client;
use Medianasms\Errors\Error;
use Medianasms\Errors\HttpException;
use Medianasms\Errors\ResponseCodes;

class SMSHelper
{
    private $apiKey, $client, $server_number, $register_pattern, $forget_password_pattern;

    public function __construct()
    {
        $this->register_pattern = '8v0sqgbhws7s5u6';
        $this->forget_password_pattern = 'tkchniox2rcubuy';
//        $this->server_number = '+985000125475';
        $this->server_number = '+983000505';
//        $this->server_number = '+9850004150400040';
//        $this->server_number = '+9850004150001232';
        $this->apiKey = env('SMS_API');
        $this->client = new \IPPanel\Client("0MLDSWA9mxVNXDHfR5go2GyXMFCU_Kx92b3AwK8jgHc=");

    }

    const URL = "https://ippanel.com/services.jspd";

    public static function deleteCode(mixed $phone)
    {
        DB::table('sms_verify')->where('phone', $phone)->delete();
    }

    public static function addCode($phone, $code)
    {
        self::deleteCode($phone);
        DB::table('sms_verify')->insert(
            ['code' => $code, 'phone' => $phone]
        );
    }

    public function sendOTPSMS($to, $msg, $cmnd = 'register')
    {
//        if ($to == "09018945844" || $to == "9018945844") return;
        $name = "متاکار";
        $pattern = $this->register_pattern;
        $code = null;

        $patternVariables = [
            "name" => "string",
            "code" => "integer",
        ];
        if ($cmnd == 'forget' || $cmnd == 'verification') {
            $pattern = $this->forget_password_pattern;
            $send = "رمز یکبار مصرف: " . "%code%" . PHP_EOL . "%name%";
        } else {

            $send = "خوش آمدید: کد تایید شما " . "%code%" . PHP_EOL . "%name%";
        }
//
//        try {
//            $code = $this->client->createPattern("$send", "otp $msg send to $to",
//                $patternVariables, '%', False);
//        } catch (\IPPanel\Errors\Error $e) {
//            echo $e->getMessage();
//        } catch (\IPPanel\Errors\HttpException $e) {
//            echo $e->getMessage();
//        }
//        echo $code;
        $patternValues = [
//            "name" => $name,
            "code" => "$msg",
        ];
//        if ($code) {
        $messageId = null;
        try {
            $messageId = $this->client->sendPattern(
                "$pattern",    // pattern code
                $this->server_number,      // originator
                "$to",  // recipient
                $patternValues  // pattern values
            );

        } catch (\IPPanel\Errors\Error $e) {
//            Telegram::sendMessage(Variable::LOGS[0], $e->getMessage());
            Eitaa::logAdmins($e->getMessage(), 'sms_fail');
        } catch (\IPPanel\Errors\HttpException $e) {
//            Telegram::sendMessage(Variable::LOGS[0], $e->getMessage());
            Eitaa::logAdmins($e->getMessage(), 'sms_fail');
        }
//        Telegram::sendMessage(Helper::$logs[0], $messageId);

//        echo $messageId;
//        }
        return (bool)$messageId;
    }

    public function Send($phone, string $msg)
    {

    }

    public function getCredit()
    {
        return $this->client->getCredit();
    }

    /**
     * @param $messageId string returns from send
     * @return object
     */
    public function getMessageInfo($messageId)
    {
        try {
            $message = $this->client->getMessage($messageId);
        } catch (\IPPanel\Errors\Error $e) {

        } catch (\IPPanel\Errors\HttpException $e) {
        }
        // get message status
        // get message cost
        // get message payback
        return (object)['state' => $message->state, 'cost' => $message->cost, 'returnCost' => $message->returnCost];

    }

    public function getMessageStatus($messageId)
    {
        $statuses = [];
        $paginationInfo = (object)['total' => 0];
        try {
            list($statuses, $paginationInfo) = $this->client->fetchStatuses($messageId, 0, 10);
        } catch (\IPPanel\Errors\Error $e) {
        } catch (\IPPanel\Errors\HttpException $e) {
        }
        return ['total' => $paginationInfo->total, 'statuses' => $statuses];


//        foreach ($statuses as status)
//          $status->recipient, $status->status
//

    }

}
