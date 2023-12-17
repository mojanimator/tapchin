<?php

namespace App\Http\Helpers;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class Pay
{
    const  GATEWAY = Variable::BANK_GATEWAY;

    const LINKS = [
        'nextpay' => [
            'PAY' => 'https://nextpay.org/nx/gateway/payment/',
            'TOKEN' => 'https://nextpay.org/nx/gateway/token',
            'VERIFY' => 'https://nextpay.org/nx/gateway/verify',

        ],
    ];
    const PAYMENT_LINK = self::LINKS[self::GATEWAY]['PAY'];
    const TOKEN_LINK = self::LINKS[self::GATEWAY]['TOKEN'];
    const VERIFY_LINK = self::LINKS[self::GATEWAY]['VERIFY'];

    public static function makeUrl($order_id, $amount, $phone, $fullname, $user_id, $for_type, $market, $data_id = null, $coupon = null)
    {

        switch (self::GATEWAY) {
            case    'nextpay':
                $params = ['api_key' => env('NEXTPAY_API'),
                    'order_id' => $order_id,
                    'amount' => $amount,
                    'callback_uri' => route('payment.confirm'),
                    'currency' => 'IRT',
                    'customer_phone' => $phone,
                    'payer_name' => $fullname,
                    'auto_verify' => false,
                    'custom_json_fields' => json_encode(['user_id' => $user_id, 'data_id' => $data_id, 'type' => $for_type, 'coupon' => $coupon]),];
                try {
                    $response = Http::post(self::TOKEN_LINK, $params);
                } catch (Exception $e) {
                    return response()->json(['errors' => ['error' => [__('check_network_and_retry')]]], 422);

                }
                $response = $response->object() ?? null;

                if ($response && $response->code == -1) { //send user to bank page
                    Payment::create([
                        'title' => __('charge') . " $amount " . __('currency'),

                        'order_id' => $order_id,
                        'type' => $for_type,
                        'owner_id' => $user_id,
                        'gateway' => self::GATEWAY,
                        'market' => $market,
                        'amount' => $amount,
                    ]);
                    return response()->json(['url' => self::PAYMENT_LINK . $response->trans_id, 'message' => __('redirecting_to_payment')], 200);
                } else {
                    if (!$response)
                        return response()->json(['errors' => ['error' => [__('check_network_and_retry')]]], 422);

                    $message = self::MESSAGES[self::GATEWAY][$response->code];

                    return response()->json(['errors' => ['error' => [$message]]], 422);

                }
                break;
//        return redirect(self::PAYMENT_LINK);
        }
    }

    public static function confirm(Request $request)
    {

        $market = $request->market;
        $user = auth()->user();

        $payment = null;
        if (isset($market) && $market == 'bazaar' || $market == 'myket') {
            $orderId = "{$user->id}-" . floor(microtime(true) * 1000);

            $payment = Payment::create([
                'title' => __('charge') . " $request->amount " . __('currency'),
                'transaction_id' => $request->token_id,
                'order_id' => $orderId,
                'type' => $request->type,
                'amount' => $request->amount,
                'owner_id' => $user->id,
                'coupon' => $request->coupon,
                'is_success' => true,
                'market' => $market,
            ]);

            $payment->code = 0;//success
        }

        if (!isset($market) || $market == 'site') {
            //nextpay
            if (isset($request->np_status)) {
                if ($request->np_status == 'Unsuccessful') {
                    Payment::where("order_id", $request->order_id)->delete();
                    $res = ['flash_status' => 'danger', 'flash_message' => __('pay_failed')];
                    return back()->with($res);

                } else {
                    $payment = self::nextpayConfirm($request);
                }
            }

            if ($payment && $payment->code == 0) { //verify success

                $user = $user ?? User::find($payment->user_id);

                $tmp = explode('_', $payment->type);
                if (!$user /*|| count($tmp) < 2*/) {
                    $res = ['flash_status' => 'danger', 'flash_message' => __('pay_failed')];
                    return back()->with($res);
                }
//                $payType = $tmp[1]; //charge
                $user->wallet += $payment->amount;
                $user->save();
                $transaction = Transaction::create([
                    'source_id' => $payment->id,
                    'title' => $payment->title,
                    'type' => $payment->type,
                    'amount' => $payment->amount,
                    'owner_id' => $payment->owner_id,
                    'coupon' => $payment->coupon,
                ]);
                Telegram::log(null, 'transaction_created', $transaction);

//                if (!auth()->user())
//                    auth()->login($user);

                //response json to application
                if ($payment->gateway == 'bazaar' || $payment->gateway == 'myket')
                    return response()->json(['status' => 'success']);


                return Inertia::render('Payment/Factor', [
                    'payment' => $payment,
                    'title' => __('charge') . " $payment->amount " . __('currency'),
                    'time' => "$payment->created_at",
                    'amount' => "$payment->amount",
                    'status' => $payment->is_success ? 'success' : 'danger',
                    'url' => route('/'),
                    'order_id' => "$payment->order_id",
                    'track_id' => "$payment->transaction_id",
                    'message' => __('success_payment'),

                ]);
            } else {

                if ($payment && ($payment->gateway == 'bazaar' || $payment->gateway == 'myket'))
                    return response()->json(['status' => 'danger']);

                return Inertia::render('Payment/Factor', [
                    'payment' => [],
                    'message' => __('fail_payment'),
                    'status' => 'danger',
                    'title' => '---',
                    'time' => null,
                    'amount' => $request->amount ?? '---',
                    'url' => route('/'),
                    'order_id' => '---',
                    'track_id' => '---',
                ]);
            }
        }
    }

    public static function nextpayConfirm($result)
    {

        $params = [
            'api_key' => env('NEXTPAY_API'),
            'trans_id' => $result->trans_id,
//            'order_id' => $result->order_id,
            'amount' => $result->amount,
            'currency' => 'IRT',
        ];
        if (!Payment::where("order_id", $result->order_id)->exists()) return null;

        $response = Http::post(self::LINKS['nextpay']['VERIFY'], $params);
        $response = $response->object() ?? null;

        if (!$response) return null;
        if ($response && $response->code == 0) { //verify success
            $payment = Payment::where('order_id', $response->order_id)->first();
            if (!$payment)
                return null;
            else {

//                $payment->amount = $response->amount;
                $payment->transaction_id = $response->Shaparak_Ref_Id;
//                $payment->card_holder = $response->card_holder;
                $payment->is_success = true;
                $payment->info = $response->custom;
                $payment->save();
                $payment->custom = $response->custom;
                $payment->code = $response->code;
                return $payment;
            }
        }
        return null;

    }

    public
    function IAPPurchase(Request $request)
    {

        $type = $request->type;
        $id = $request->id;
        $month = $request->month;
        $coupon = $request->coupon;
        $phone = $request->phone;
        $market = $request->market;
        $price = $request->price;

        $user = auth()->user() ?: auth('api')->user();
        if ($user && !$phone)
            $phone = $user->phone;
        if (!$user)
            $user = \App\Models\User::where('phone', $phone)->first();
        if (!$user)
            return response()->json(['errors' => ['error' => ['کاربر نامعتبر است']]], 422);

        $order_id = uniqid();
        while (\App\Models\Payment::where('order_id', $order_id)->exists())
            $order_id = uniqid();
        $v = $price ?: Setting::firstOrNew(['key' => "${type}_${month}_price"])->value;
        $price = $v != null ? $v : -1;

        if ($price == -1)
            return response()->json(['errors' => ['error' => ['نوع اشتراک نامعتبر است']]], 422);

        $c = \App\Models\Coupon::where('code', $coupon)->first();
        $price = self::makeDiscount($price, $c);

        if (strpos($type, 'player') !== false)
            $data = Player::find($id);
        elseif (strpos($type, 'coach') !== false)
            $data = Coach::find($id);
        elseif (strpos($type, 'club') !== false)
            $data = Club::find($id);
        elseif (strpos($type, 'shop') !== false)
            $data = Shop::find($id);

        if (!isset($data))
            return response()->json(['errors' => ['error' => ['موردی یافت نشد.']]], 422);

        if ($price == 0) {
            $now = \Carbon\Carbon::now();


            $time = $data->expires_at != null && $now->timestamp < $data->expires_at ? \Carbon\Carbon::parse($data->expires_at)->addDays($month * 30) : $now->addDays($month * 30);
            $data->active = false;
            $data->expires_at = $time;
            $data->save();

            if ($c && $c->user_id != null && $c->user_id == $user->id && $c->used_at == null) {
                $c->used_at = \Carbon\Carbon::now();
                $c->save();
            }
            $payment = \App\Models\Payment::create([
                'agency_id' => $user->agency_id,
                'province_id' => $data->province_id,
                'token_id' => null,
                'order_id' => $order_id,
                'amount' => 0,
                'user_id' => $user->id,
                'pay_for' => "${type}_${month}",
                'pay_for_id' => $id,
                'created_at' => $now,
                'coupon_id' => isset($c->id) ? $c->id : null,

            ]);
            (new SMS())->deleteActivationSMS($phone);
//            \App\Models\Ref::where('invited_id', $user->id)->where('invited_purchase_type', null)->update(['invited_purchase_type' => array_flip(Helper::$refMap)[$type], 'invited_purchase_months' => $month]);
            Telegram::log(Helper::$TELEGRAM_GROUP_ID, 'payment', $payment);
            redirect("/panel/$type/edit/$id")->with('success-alert', 'پرداخت شما با موفقیت انجام شد و در صف فعالسازی قرار گرفت');
            return response()->json(['url' => url("panel/$type/edit/$id")], 200);

        }


        if ($market == 'bazaar') {

            $token = $this->getCafeBazaarDiscountToken([
                'sku' => "${type}_${month}_price",
                'price' => $price * 10
            ]);

            return response()->json([
                'dynamicPriceToken' => $token,
                'sku' => "${type}_${month}_price",
                'rsa' => env('BAZAAR_RSA'),
                'agency_id' => $user->agency_id,
                'province_id' => $data->province_id,
                'order_id' => $order_id,
                'pay_for' => "${type}_${month}",
                'pay_for_id' => $id,
                'coupon_id' => isset($c->id) ? $c->id : null,
                'market' => 'bazaar',
                'amount' => $price,
            ], 200);

        } else {

            return \NextPay::makePay((object)[
                'payer_name' => $user->name ? $user->name . ' ' . $user->family : $user->username,
                'customer_phone' => $user->phone,
                'phone' => $phone,
                'order_id' => $order_id,
                'amount' => $price,
                'data_id' => $id,
                'user_id' => $user->id,
                'pay_for' => "${type}_${month}",
                'data_province_id' => $data->province_id,
                'coupon_id' => isset($c->id) ? $c->id : null,
                'user_agency_id' => $user->agency_id,
                'market' => $market,
            ]);

        }
    }


    const MESSAGES = [
        'nextpay' => [
            0 => 'پرداخت تکمیل و با موفقیت انجام شده است',
            -1 => 'منتظر ارسال تراکنش و ادامه پرداخت',
            -2 => 'پرداخت رد شده توسط کاربر یا بانک',
            -3 => 'پرداخت در حال انتظار جواب بانک',
            -4 => 'پرداخت لغو شده است',
            -20 => 'کد api_key ارسال نشده است',
            -21 => 'کد trans_id ارسال نشده است',
            -22 => 'مبلغ ارسال نشده',
            -23 => 'لینک ارسال نشده',
            -24 => 'مبلغ صحیح نیست',
            -25 => 'تراکنش قبلا انجام و قابل ارسال نیست',
            -26 => 'مقدار توکن ارسال نشده است',
            -27 => 'شماره سفارش صحیح نیست',
            -28 => 'مقدار فیلد سفارشی [custom_json_fields] از نوع json نیست',
            -29 => 'کد بازگشت مبلغ صحیح نیست',
            -30 => 'مبلغ کمتر از حداقل پرداختی است',
            -31 => 'صندوق کاربری موجود نیست',
            -32 => 'مسیر بازگشت صحیح نیست',
            -33 => 'کلید مجوز دهی صحیح نیست',
            -34 => 'کد تراکنش صحیح نیست',
            -35 => 'ساختار کلید مجوز دهی صحیح نیست',
            -36 => 'شماره سفارش ارسال نشد است',
            -37 => 'شماره تراکنش یافت نشد',
            -38 => 'توکن ارسالی موجود نیست',
            -39 => 'کلید مجوز دهی موجود نیست',
            -40 => 'کلید مجوزدهی مسدود شده است',
            -41 => 'خطا در دریافت پارامتر، شماره شناسایی صحت اعتبار که از بانک ارسال شده موجود نیست',
            -42 => 'سیستم پرداخت دچار مشکل شده است',
            -43 => 'درگاه پرداختی برای انجام درخواست یافت نشد',
            -44 => 'پاسخ دریافت شده از بانک نامعتبر است',
            -45 => 'سیستم پرداخت غیر فعال است',
            -46 => 'درخواست نامعتبر',
            -47 => 'کلید مجوز دهی یافت نشد [حذف شده]',
            -48 => 'نرخ کمیسیون تعیین نشده است',
            -49 => 'تراکنش مورد نظر تکراریست',
            -50 => 'حساب کاربری برای صندوق مالی یافت نشد',
            -51 => 'شناسه کاربری یافت نشد',
            -52 => 'حساب کاربری تایید نشده است',
            -60 => 'ایمیل صحیح نیست',
            -61 => 'کد ملی صحیح نیست',
            -62 => 'کد پستی صحیح نیست',
            -63 => 'آدرس پستی صحیح نیست و یا بیش از ۱۵۰ کارکتر است',
            -64 => 'توضیحات صحیح نیست و یا بیش از ۱۵۰ کارکتر است',
            -65 => 'نام و نام خانوادگی صحیح نیست و یا بیش از ۳۵ کاکتر است',
            -66 => 'تلفن صحیح نیست',
            -67 => 'نام کاربری صحیح نیست یا بیش از ۳۰ کارکتر است',
            -68 => 'نام محصول صحیح نیست و یا بیش از ۳۰ کارکتر است',
            -69 => 'آدرس ارسالی برای بازگشت موفق صحیح نیست و یا بیش از ۱۰۰ کارکتر است',
            -70 => 'آدرس ارسالی برای بازگشت ناموفق صحیح نیست و یا بیش از ۱۰۰ کارکتر است',
            -71 => 'موبایل صحیح نیست',
            -72 => 'بانک پاسخگو نبوده است لطفا با نکست پی تماس بگیرید',
            -73 => 'مسیر بازگشت دارای خطا میباشد یا بسیار طولانیست',
            -90 => 'بازگشت مبلغ بدرستی انجام شد',
            -91 => 'عملیات ناموفق در بازگشت مبلغ',
            -92 => 'در عملیات بازگشت مبلغ خطا رخ داده است',
            -93 => 'موجودی صندوق کاربری برای بازگشت مبلغ کافی نیست',
            -94 => 'کلید بازگشت مبلغ یافت نشد',
        ]
    ];
}