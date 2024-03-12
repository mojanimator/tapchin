<?php

namespace App\Http\Controllers;

use App\eblagh\Helpers\Helper;
use App\Http\Helpers\Pay;
use App\Http\Helpers\Telegram;
use App\Http\Helpers\Variable;
use App\Models\Car;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    private $bazaar_client_id;
    private $bazaar_client_secret;
    private $myket_access_token;

    public function __construct()
    {
        $this->bazaar_client_id = env('EBLAGH_BAZAAR_CLIENT_ID');
        $this->bazaar_client_secret = env('EBLAGH_BAZAAR_CLIENT_SECRET');

        $this->myket_access_token = env('EBLAGH_MYKET_ACCESS_TOKEN');
    }

    public function payDone(Request $request)
    {
        $p = null;
        $status = 'error';
        $lang = 'en';
        $user_id = $request->user_id;
        $market = $request->market;
        $token = $request->token;
        $sku = $request->sku;
        $amount = $request->amount;
        $appVersion = $request->app_version;
        $info = $request->info;
        $order_id = $request->order_id ?? "$user_id\$$sku\$" . Carbon::now()->getTimestampMs();


        if (isset($market) && in_array($market, ['bazaar', 'myket'])) {

            if (!$this->checkPayment($sku, $token, $market)) {
                $response['status'] = 'danger';
                $response['message'] = 'اطلاعات ارسالی خرید صحیح نیست!';
                return $response;
            }
            $t = Transaction::create([

            ]);

            if ($t) {
                $status = 'success';
                $user = User::find($user_id);

            }
            return response()->json(['status' => $status]);
        } else {

            $response = Pay::confirmPay($request);

//            Telegram::sendMessage(Helper::$Dev[0], print_r($request->all(), true));
            $transactions = (!empty($response) && $response['status'] == 'success') ? Transaction::where('pay_id', $response['order_id'])->get() : [];
            $now = Carbon::now();
            foreach ($transactions as $transaction) {
                $transaction->info = $response['info'];
                $transaction->is_success = true;
                $transaction->payed_at = $now;
                $status = 'success';
                $token = $response['order_id'];
                $user = User::find($transaction->user_id);
                $user_id = $transaction->user_id;
                $transaction->save();
            }

            return view('layouts.payment')->with([
                'status' => $status,
                'lang' => 'fa',
                'pay_id' => $token,
                'amount' => $p->amount ?? 0,
                'type' => __('order') . " " . join(',', $transaction->pluck('for_id')),
                'link' => $market == 'bank' ? ('dabel://' . Variable::PACKAGE) : url('')
            ]);
        }


    }

    private function getCafeBazaarDiscountPayload($data)
    {
        $payload = [
            'price' => $data['price'],
            'package_name' => $data['package'],
            'sku' => $data['sku'],
            'exp' => Carbon::now()->addDays(30)->timestamp,
            'nonce' => random_int(100000, 9999999999),
//            'account_id' => ''
        ];

        $enc = JWT::encode($payload, env('EBLAGH_BAZAAR_JWT'), 'HS256');

        return $enc;

    }

    public function checkPayment($productId, $purchaseToken, $market)
    {
        if ($market == 'bazaar')
            return $this->checkCafePayment($productId, $purchaseToken);
        if ($market == 'myket')
            return $this->checkMyketPayment($productId, $purchaseToken);
    }

    public function checkMyketPayment($productId, $purchaseToken)
    {
//        $this->cafRefresh();
//        $setting = Setting::first();
        $response = Http::withHeaders([
            'X-Access-Token' => env('EBLAGH_MYKET_ACCESS_TOKEN')
        ])
            ->get("https://developer.myket.ir/api/applications/" . Helper::$PACKAGE . "/purchases/products/$productId/tokens/$purchaseToken");
        if ($response->status() == 200) {
            return true;
        } else {
            return false;
        }
    }

    public function checkCafePayment($productId, $purchaseToken)
    {
        $accessToken = $this->getBazaarToken(new Request());
        $response = Http::withHeaders([
            'access_token' => "$accessToken"
        ])
            ->get('https://pardakht.cafebazaar.ir/devapi/v2/api/validate/' . Helper::$PACKAGE . '/inapp/' . $productId . '/purchases/' . $purchaseToken . '/?access_token=' . $accessToken);

        if ($response->status() == 200) {
            return true;
        } else {
            return false;
        }
    }

    public function getFirstBazaarToken($code)
    {

        $redirect = "https://qr-image-creator.com/hamsignal/api/eblagh/payment/bazaar/token";
        //type this link in browser
        // "https://pardakht.cafebazaar.ir/devapi/v2/auth/authorize/?response_type=code&access_type=offline&redirect_uri=https://qr-image-creator.com/hamsignal/api/payment/bazaar/token&client_id=" . $this->bazaar_client_id;
        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post("https://pardakht.cafebazaar.ir/devapi/v2/auth/token/", [
            'grant_type' => 'authorization_code',
            'code' => "$code",
            'client_id' => $this->bazaar_client_id,
            'client_secret' => $this->bazaar_client_secret,
            'redirect_uri' => $redirect,
        ]);
        $response = $response->object() ?? null;
        if ($response && !empty($response->access_token)) {
            Setting::updateOrCreate(
                ['key' => 'EBLAGH_BAZAAR_ACCESS_TOKEN'],
                ['value' => $response->access_token]
            );
            Setting::updateOrCreate(
                ['key' => 'EBLAGH_BAZAAR_REFRESH_TOKEN'],
                ['value' => $response->refresh_token]
            );
            Setting::updateOrCreate(
                ['key' => 'EBLAGH_BAZAAR_EXPIRE'],
                ['value' => now()->addSeconds($response->expires_in)]
            );
            return $response->access_token;
        }
    }

    public function getBazaarToken(Request $request)
    {
        if ($request && $request->get('code')) {
            $this->getFirstBazaarToken($request->get('code'));
        }
        $access = Setting::firstOrNew(['key' => 'EBLAGH_BAZAAR_ACCESS_TOKEN']);
        $refresh = Setting::firstOrNew(['key' => 'EBLAGH_BAZAAR_REFRESH_TOKEN']);
        $expire = Setting::firstOrNew(['key' => 'EBLAGH_BAZAAR_EXPIRE']);

        //refresh token
        if ($refresh->value &&
            (!$expire->value || now()->gte(Carbon::createFromDate($expire->value)))) {

            $cafeRequest = Http::asForm()->post('https://pardakht.cafebazaar.ir/devapi/v2/auth/token/', [
                'grant_type' => 'refresh_token',
                'client_id' => $this->bazaar_client_id,
                'client_secret' => $this->bazaar_client_secret,
                'refresh_token' => $refresh->value,
            ]);
            $response = json_decode($cafeRequest->body());

            if ($response->access_token) {
                $access->value = $response->access_token;
                $expire->value = now()->addSeconds($response->expires_in);
                $access->save();
                $expire->save();
                return $response->access_token;
            }
        } //access token
        elseif ($access->value) {
            return $access->value;
        } //new manual access token
        else {
            //https://pardakht.cafebazaar.ir/devapi/v2/auth/authorize/?response_type=code&access_type=offline&redirect_uri=https://dabeladl.com/cafe&client_id=wVGLiWJMuFOkFSvS1vomKK2o9taKkR4yQgGMIkhn
            if (!request('code'))
                return Http::get('https://pardakht.cafebazaar.ir/devapi/v2/auth/authorize', ['response_type' => 'code', 'access_type' => 'offline', 'redirect_uri' => url('api/payment/bazaar/token'), 'client_id' => $this->bazaar_client_id]);
            $cafeRequest = Http::asForm()->post('https://pardakht.cafebazaar.ir/devapi/v2/auth/token/', [
                'grant_type' => 'authorization_code',
                'code' => request('code'),
                'client_id' => $this->bazaar_client_id,
                'client_secret' => $this->bazaar_client_secret,
                'redirect_uri' => url('api/payment/bazaar/token'),
            ]);
            $response = json_decode($cafeRequest->body());

            if (isset($response->access_token)) {
                $access->value = $response->access_token;
                $refresh->value = $response->refresh_token;
                $expire->value = now()->addSeconds($response->expires_in);
                $access->save();
                $refresh->save();
                $expire->save();
                return $response->access_token;
            }

        }
        return null;
    }
}
