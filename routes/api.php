<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VariationController;
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


Route::any('payment/done', [TransactionController    ::class, 'payDone'])->name('api.user.payment.done');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::get('payment/bazaar/token', [PaymentController::class, 'getBazaarToken'])->name('v2.payment.bazaar.token');


    Route::any('payment/done', [PaymentController::class, 'payDone'])->name('api.user.payment.done');


    Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
        Route::get('settings', [UserController::class, 'settings'])->name('api.user.settings');

        Route::post('logout', [UserController::class, 'logout']);
        Route::get('user/info', [UserController::class, 'info'])->name('api.user.info');

        Route::get('/variation/search', [VariationController::class, 'search'])->name('variation.search');
        Route::get('/variation/{id}/{name}', [VariationController::class, 'view'])->name('variation.view');

        Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');

        Route::patch('/profile/update', [ProfileController::class, 'update'])->name('user.panel.profile.update');
        Route::patch('/profile/reset-password', [ProfileController::class, 'resetPassword'])->name('user.panel.profile.password.reset');


        Route::post('payment/create', [PaymentController::class, 'create'])->name('api.user.payment.create');
        Route::get('payment/transactions/search', [PaymentController::class, 'transactions'])->name('api.user.payment.transaction.search');
        Route::post('payment/buy', [PaymentController::class, 'buy'])->name('api.user.payment.buy');
        Route::post('user/changepassword', [UserController::class, 'changePassword'])->name('api.user.user.password.change');
        Route::post('user/updateemail', [UserController::class, 'updateEmail'])->name('api.user.user.email.update');
        Route::post('user/updateavatar', [UserController::class, 'updateAvatar'])->name('api.user.user.avatar.update');
        Route::post('user/update', [UserController::class, 'update'])->name('api.user.user.update');
        Route::post('user/bookmark', [UserController::class, 'bookmark'])->name('api.user.user.bookmark');

        Route::get('sms/activation', [UserController::class, 'sendActivationCode'])->name('api.user.sms.activation')->middleware('throttle:sms_limit');

        Route::get('tutorial/search', [TutorialController::class, 'searchApi'])->name('api.user.api.user.tutorial.search');

    });
    Route::middleware('throttle:sms_limit')->group(function () {
        Route::post('user/register', [UserController::class, 'register'])->name('v2.api.user.user.register');
        Route::post('user/forget', [UserController::class, 'forget'])->name('v2.api.user.user.forget');
        Route::post('user/preAuth', [UserController::class, 'preAuth'])->name('v2.api.user.user.preAuth');
        Route::post('user/login', [UserController::class, 'login'])->name('v2.api.user.user.login');
        Route::post('/adv/click', [AdvController::class, 'click'])->name('api.user.adv.click');
        Route::get('/adv/search', [AdvController::class, 'search'])->name('api.user.adv.search');

    });
});
