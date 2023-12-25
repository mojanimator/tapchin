<?php


use App\Http\Controllers\Admin\PanelController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Helpers\Variable;
use App\Models\Article;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {

    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('admin.register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('admin.login-form');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('admin.login');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('admin.password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('admin.password.update');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('admin.password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('admin.password.store');

    Route::middleware(['auth:sanctum', /*'auth:admin',*/
        config('jetstream.auth_session'),
        'verified'])->prefix('panel')->group(function ($route) {


        Route::get('', [PanelController::class, 'index'])->name('admin.panel.index');

        PanelController::makeInertiaRoute('get', 'setting/index', 'panel.admin.setting.index', 'Panel/Admin/Setting/Index',
            [

            ]);
        PanelController::makeInertiaRoute('get', 'slider/index', 'panel.admin.slider.index', 'Panel/Admin/Slider/Index',
            [

            ]);
        PanelController::makeInertiaRoute('get', 'slider/create', 'panel.admin.slider.create', 'Panel/Admin/Slider/Create',
            [
                'sliderRatio' => Variable::RATIOS['slider'],
            ]);

        PanelController::makeInertiaRoute('get', 'notification/index', 'panel.admin.notification.index', 'Panel/Admin/Notification/Index',
            [

            ]);
        PanelController::makeInertiaRoute('get', 'notification/create', 'panel.admin.notification.create', 'Panel/Admin/Notification/Create',
            [

            ]);

        PanelController::makeInertiaRoute('get', 'ticket/index', 'panel.admin.ticket.index', 'Panel/Ticket/Index',
            [
                'statuses' => Variable::TICKET_STATUSES

            ]);
        PanelController::makeInertiaRoute('get', 'ticket/create', 'panel.admin.ticket.create', 'Panel/Ticket/Create',
            [
                'attachment_allowed_mimes' => implode(',.', Variable::TICKET_ATTACHMENT_ALLOWED_MIMES),
            ]);

        PanelController::makeInertiaRoute('get', 'user/index', 'panel.admin.user.index', 'Panel/User/Index',
            [

            ]);
        PanelController::makeInertiaRoute('get', 'user/create', 'panel.admin.user.create', 'Panel/User/Create',
            [
            ]);

        PanelController::makeInertiaRoute('get', 'message/index', 'panel.admin.message.index', 'Panel/Admin/Message/Index',
            [
                'statuses' => Variable::MESSAGE_STATUSES,
            ]);
        PanelController::makeInertiaRoute('get', 'message/create', 'panel.admin.message.create', 'Panel/Admin/Message/Create',
            [
            ]);
        PanelController::makeInertiaRoute('get', 'article/index', 'panel.admin.article.index', 'Panel/Admin/Article/Index',
            [
                'statuses' => Variable::STATUSES
            ]
        );
        PanelController::makeInertiaRoute('get', 'article/create', 'panel.admin.article.create', 'Panel/Admin/Article/Create',
            [
                'statuses' => Variable::STATUSES,
            ]
        );


    });
});
