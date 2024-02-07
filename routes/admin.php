<?php


use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PanelController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\PProductController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\ShippingMethodController;
use App\Http\Helpers\Variable;
use App\Models\Agency;
use App\Models\Article;
use App\Models\PProduct;
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

    Route::middleware(['auth:sanctum', 'auth:admin',
        config('jetstream.auth_session'),
        'verified'])->prefix('panel')->group(function ($route) {


        Route::get('', [PanelController::class, 'index'])->name('admin.panel.index');

        PanelController::makeInertiaRoute('get', 'setting/index', 'admin.panel.setting.index', 'Panel/Admin/Setting/Index',
            [

            ]);
        PanelController::makeInertiaRoute('get', 'slider/index', 'admin.panel.slider.index', 'Panel/Admin/Slider/Index',
            [

            ]);
        PanelController::makeInertiaRoute('get', 'slider/create', 'admin.panel.slider.create', 'Panel/Admin/Slider/Create',
            [
                'sliderRatio' => Variable::RATIOS['slider'],
            ]);

        PanelController::makeInertiaRoute('get', 'notification/index', 'admin.panel.notification.index', 'Panel/Admin/Notification/Index',
            [

            ]);
        PanelController::makeInertiaRoute('get', 'notification/create', 'admin.panel.notification.create', 'Panel/Admin/Notification/Create',
            [

            ]);

        PanelController::makeInertiaRoute('get', 'ticket/index', 'admin.panel.ticket.index', 'Panel/Ticket/Index',
            [
                'statuses' => Variable::TICKET_STATUSES

            ]);
        PanelController::makeInertiaRoute('get', 'ticket/create', 'admin.panel.ticket.create', 'Panel/Ticket/Create',
            [
                'attachment_allowed_mimes' => implode(',.', Variable::TICKET_ATTACHMENT_ALLOWED_MIMES),
            ]);

        PanelController::makeInertiaRoute('get', 'user/index', 'admin.panel.user.index', 'Panel/User/Index',
            [

            ]);
        PanelController::makeInertiaRoute('get', 'user/create', 'admin.panel.user.create', 'Panel/User/Create',
            [
            ]);

        PanelController::makeInertiaRoute('get', 'message/index', 'admin.panel.message.index', 'Panel/Admin/Message/Index',
            [
                'statuses' => Variable::MESSAGE_STATUSES,
            ]);
        PanelController::makeInertiaRoute('get', 'message/create', 'admin.panel.message.create', 'Panel/Admin/Message/Create',
            [
            ]);
        PanelController::makeInertiaRoute('get', 'article/index', 'admin.panel.article.index', 'Panel/Admin/Article/Index',
            [
                'statuses' => Variable::STATUSES
            ]
        );
        PanelController::makeInertiaRoute('get', 'article/create', 'admin.panel.article.create', 'Panel/Admin/Article/Create',
            [
                'statuses' => Variable::STATUSES,
            ]
        );


        PanelController::makeInertiaRoute('get', 'transaction/index', 'admin.panel.financial.transaction.index', 'Panel/Admin/Financial/Index',
            [
            ]
        );


        Route::get('admin/search', [AdminController::class, 'search'])->name('admin.panel.admin.search');


        PanelController::makeInertiaRoute('get', 'agency/index', 'admin.panel.agency.index', 'Panel/Admin/Agency/Index',
            [
            ]
        );
        PanelController::makeInertiaRoute('get', 'agency/create', 'admin.panel.agency.create', 'Panel/Admin/Agency/Create',
            [
                'parent_agencies' => Agency::whereNot('level', Variable::AGENCY_TYPES[count(Variable::AGENCY_TYPES) - 1]['level'])->whereNotNull('level')->select('id', 'name', 'province_id', 'level', 'access')->get(),
            ]
        );

        Route::get('agency/search', [AgencyController::class, 'searchPanel'])->name('admin.panel.agency.search');
        Route::patch('agency/update', [AgencyController::class, 'update'])->name('admin.panel.agency.update');
        Route::post('agency/create', [AgencyController::class, 'create'])->name('admin.panel.agency.create')->middleware("can:create,App\Models\Admin,App\Models\Agency,'1'");
        Route::get('agency/{agency}', [AgencyController::class, 'edit'])->name('admin.panel.agency.edit');


        PanelController::makeInertiaRoute('get', 'repository/index', 'admin.panel.repository.index', 'Panel/Admin/Repository/Index',
            [

            ]
        );

        PanelController::makeInertiaRoute('get', 'repository/create', 'admin.panel.repository.create', 'Panel/Admin/Repository/Create',
            [
            ]
        );

        Route::get('repository/search', [RepositoryController::class, 'searchPanel'])->name('admin.panel.repository.search');
        Route::patch('repository/update', [RepositoryController::class, 'update'])->name('admin.panel.repository.update');
        Route::post('repository/create', [RepositoryController::class, 'create'])->name('admin.panel.repository.create')->middleware("can:create,App\Models\Admin,App\Models\Repository,'1'");
        Route::get('repository/{repository}', [RepositoryController::class, 'edit'])->name('admin.panel.repository.edit');


        PanelController::makeInertiaRoute('get', 'shipping/method/index', 'admin.panel.shipping.method.index', 'Panel/Admin/Shipping/Method/Index',
            []
        );
        PanelController::makeInertiaRoute('get', 'shipping/method/create', 'admin.panel.shipping.method.create', 'Panel/Admin/Shipping/Method/Create',
            [
                'help' => __('help.shipping_method'),

            ]
        );
        Route::get('shipping/method/search', [ShippingMethodController::class, 'searchPanel'])->name('admin.panel.shipping.method.search');
        Route::patch('shipping/method/update', [ShippingMethodController::class, 'update'])->name('admin.panel.shipping.method.update');
        Route::post('shipping/method/create', [ShippingMethodController::class, 'create'])->name('admin.panel.shipping.method.create')->middleware("can:create,App\Models\Admin,App\Models\ShippingMethod,'1'");
        Route::get('shipping/method/{shipping_method}', [ShippingMethodController::class, 'edit'])->name('admin.panel.shipping.method.edit');

        Route::get('p_product/search', [PProductController::class, 'searchPanel'])->name('admin.panel.p_product.search');
    });

});
