<?php

namespace App\Http\Helpers;


use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;


class SmsHelper
{
    const  APIKey = "75f3092e3ed8167d7edd092e";
    const  SecretKey = "beheshti009351414815";
    const  LineNumber = "50002015700313";

    const TEMPLATE_REGISTER = "80451";
    const TEMPLATE_FORGET = "80451";
    const TEMPLATE_ORDER_STATUS = "81447";
    const TEMPLATE_NEW_ORDER = "81448";
    const TEMPLATE_TRANSACTION = "81449";

    public static function StringRandom($length = 16)
    {
        $pool = '0123456789';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    public static function deleteCode(mixed $phone)
    {
        DB::table('sms_verify')->where('phone', $phone)->delete();
    }

    public static function addCode($phone, $code, $limit = 1)
    {
        if (DB::table('sms_verify')->where('phone', $phone)->where('created_at', '>', Carbon::now()->subMinutes($limit))->exists())
            return 'repeat';
        self::deleteCode($phone);
        return DB::table('sms_verify')->insert(
            ['code' => $code, 'phone' => $phone]
        );
    }


    public static function checkRepeatedSMS($phone, $min)
    {
        return DB::table('sms_verify')->where('phone', $phone)->where('created_at', '>', Carbon::now()->subMinutes($min))->exists();
    }


    public function Send($number, $code, $type = self::TEMPLATE_FORGET)
    {

        try {
            date_default_timezone_set("Asia/Tehran");
            $APIKey = self::APIKey;
            $SecretKey = self::SecretKey;

            // message data
            switch ($type) {
                case self::TEMPLATE_NEW_ORDER:
                    $params = [
                        [
                            "Parameter" => "order_id",
                            "ParameterValue" => $code['order_id']
                        ]
                    ];
                    break;
                case self::TEMPLATE_ORDER_STATUS:
                    $params = [
                        [
                            "Parameter" => "order_id",
                            "ParameterValue" => $code['order_id']
                        ], [
                            "Parameter" => "status",
                            "ParameterValue" => $code['status']
                        ]
                    ];
                    break;
                case self::TEMPLATE_TRANSACTION:
                    $params = [
                        [
                            "Parameter" => "amount",
                            "ParameterValue" => $code['amount']
                        ], [
                            "Parameter" => "wallet",
                            "ParameterValue" => $code['wallet']
                        ]
                    ];
                    break;
                default:
                    $params = [
                        [
                            "Parameter" => "VerificationCode",
                            "ParameterValue" => $code['code']
                        ]
                    ];
                    break;
            }
            $data = array(
                "ParameterArray" => $params,
                "Mobile" => $number,
                "TemplateId" => $type
            );

            $SmsIR_UltraFastSend = (new SmsIR_UltraFastSend($APIKey, $SecretKey))->UltraFastSend($data);
            return $SmsIR_UltraFastSend;
        } catch (Exception $e) {
//            echo 'Error SendMessage : ' . $e->getMessage();
            return false;
        }
    }

}
