<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('senderror', [\App\Http\Controllers\Controller::class, 'sendError']);
Route::post('/bot/getupdates', [BotController::class, 'getupdates']);
Route::post('/bot/sendmessage', [BotController::class, 'sendmessage']);
Route::get('/bot/getme', [BotController::class, 'myInfo']);

Route::post('/chat/broadcast', [App\Http\Controllers\PushController::class, 'broadcast'])->name('chat.broadcast');
Route::post('/chat/chatsupporthistory', [App\Http\Controllers\PushController::class, 'chatSupportHistory'])->name('chat.support.history');


Route::any('payment/done', [TransactionController    ::class, 'payDone'])->name('eblagh.payment.done');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('')->group(function () {

    Route::get('payment/bazaar/token', [PaymentController::class, 'getBazaarToken'])->name('v2.payment.bazaar.token');


    Route::any('payment/done', [PaymentController::class, 'payDone'])->name('eblagh.payment.done');


    Route::middleware(['auth:sanctum', 'scopes:user'])->group(function () {

        Route::post('logout', 'AppAPIController@logout');
        Route::get('getuser', 'AppAPIController@getUser');
        Route::get('like', 'AppAPIController@like');
        Route::get('settings', [UserController::class, 'settings'])->name('eblagh.settings');
        Route::post('logout', [UserController::class, 'logout']);
        Route::get('user/info', [UserController::class, 'info'])->name('eblagh.user.info');

        Route::post('payment/create', [PaymentController::class, 'create'])->name('eblagh.payment.create');
        Route::get('payment/transactions/search', [PaymentController::class, 'transactions'])->name('eblagh.payment.transaction.search');
        Route::post('payment/buy', [PaymentController::class, 'buy'])->name('eblagh.payment.buy');
        Route::post('user/changepassword', [UserController::class, 'changePassword'])->name('eblagh.user.password.change');
        Route::post('user/updateemail', [UserController::class, 'updateEmail'])->name('eblagh.user.email.update');
        Route::post('user/updateavatar', [UserController::class, 'updateAvatar'])->name('eblagh.user.avatar.update');
        Route::post('user/update', [UserController::class, 'update'])->name('eblagh.user.update');
        Route::post('user/bookmark', [UserController::class, 'bookmark'])->name('eblagh.user.bookmark');

        Route::get('sms/activation', [UserController::class, 'sendActivationCode'])->name('eblagh.sms.activation')->middleware('throttle:sms_limit');

        Route::get('tutorial/search', [TutorialController::class, 'searchApi'])->name('eblagh.api.tutorial.search');

    });
    Route::middleware('throttle:sms_limit')->group(function () {
        Route::post('user/register', [UserController::class, 'register'])->name('v2.api.user.register');
        Route::post('user/forget', [UserController::class, 'forget'])->name('v2.api.user.forget');
        Route::post('user/preAuth', [UserController::class, 'preAuth'])->name('v2.api.user.preAuth');
        Route::post('user/login', [UserController::class, 'login'])->name('v2.api.user.login');
        Route::post('/adv/click', [AdvController::class, 'click'])->name('api.adv.click');
        Route::get('/adv/search', [AdvController::class, 'search'])->name('api.adv.search');

    });
});
