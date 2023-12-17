<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BusinessController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\HireController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectItemController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TextController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Helpers\Pay;
use App\Http\Helpers\SMSHelper;
use App\Http\Helpers\Telegram;
use App\Http\Helpers\Util;
use App\Http\Helpers\Variable;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Business;
use App\Models\Category;
use App\Models\County;
use App\Models\Notification;
use App\Models\Podcast;
use App\Models\Province;
use App\Models\Setting;
use App\Models\Site;
use App\Models\Text;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', function () {


    return;
    return \Illuminate\Support\Facades\Artisan::call('store:transactions');
    return (new ArticleController())->search(new Request([]));
    return;
    return (new PanelController())->chartLogs(new Request(['user_id' => 1, 'dateFrom' => '1401/06/01', 'dateTo' => '1402/06/24']));
//    File::makeDirectory(Storage::path("public/sites"), $mode = 0755,);
//    return Telegram::log(null, 'site_created', Site::find(2));

});
Route::get('panel')->name('panel');
Route::get('storage')->name('storage');
Route::get('storage/sites')->name('storage.sites');
Route::get('storage/users')->name('storage.users');
Route::get('storage/businesses')->name('storage.businesses');
Route::get('storage/podcasts')->name('storage.podcasts');
Route::get('storage/videos')->name('storage.videos');
Route::get('storage/banners')->name('storage.banners');
Route::get('storage/articles')->name('storage.articles');
Route::get('storage/tickets')->name('storage.tickets');
Route::get('storage/slides')->name('storage.slides');

Route::get('/', function (Request $request) {
    if ($r = $request->ref) {
        session(['ref' => $r]);
    }
    return Inertia::render('Main', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'heroText' => \App\Models\Setting::getValue('hero_main_page'),
        'slides' => \App\Models\Slider::where('is_active', true)->get(),
        'articles' => \App\Models\Article::where('status', 'active')->orderBy('id', 'desc')->take(12)->get(),
        'section1Header' => __('our_services'),
        'section1' => [
            ['header' => 'تحویل در محل', 'sub' => 'با پرداخت کرایه رفت و برگشت ماشین. کل بار را بخرید و در محل پول آن را پرداخت کنید', 'icon' => 'HomeModernIcon'],
            ['header' => 'کسب درآمد', 'sub' => 'در صورت همکاری در فروش محصولات ما، پورسانت خود را دریافت کنید', 'icon' => 'RocketLaunchIcon'],
            ['header' => 'پاسخگویی شبانه روزی', 'sub' => 'در هر زمان از شبانه روز کافی است از طریق دکمه های ثبت سفارش و همکاری در فروش با ما در ارتباط باشید', 'icon' => 'UsersIcon'],
            ['header' => 'مشاوره تخصصی', 'sub' => 'در صورت نیاز به مشاوره تخصصی درباره ساخت کارخانه و انواع دستگاه ها با ما تماس بگیرید', 'icon' => 'WrenchScrewdriverIcon'],
        ],
        'section2Header' => __('our_benefits'),
        'section2' => [
            ['header' => 'کود کبوتر فله', 'sub' => 'کودی است که بعد از جمع آوری به صورت فله به فروش می رسد', 'icon' => 'StarIcon'],
            ['header' => 'کود کبوتر پاک شده', 'sub' => 'در کود کبوتر پاک شده کلیه آشغالها جدا شده و به صورت یکدست دانه بندی شده عرضه می شود', 'icon' => 'StarIcon'],
            ['header' => 'کود کبوتر آسیاب شده', 'sub' => 'کود کبوتر آسیاب شده و به صورت پودری سریعتر و بهتر در آب حل می شود', 'icon' => 'StarIcon'],
            ['header' => 'کود کبوتر گرانولی', 'sub' => 'در کشت و صنعت و باغهای بزگ برای پخش کود کبوتر بهتر است از گرانولی کود کبوتر استفاده شود.', 'icon' => 'StarIcon'],
        ],
        'carouselImages' => [],
        'counts' => [
            'users' => ['icon' => 'UsersIcon', 'count' => User::count()],
            'articles' => ['icon' => 'PencilIcon', 'count' => Article::count()],
        ]
    ]);
})->name('/');

Route::middleware(['auth:sanctum',
    config('jetstream.auth_session'),
    'verified'])->prefix('panel')->group(function ($route) {


    Route::get('', [PanelController::class, 'index'])->name('panel.index');

    Route::post('transaction/chart', [PanelController::class, 'chartLogs'])->name('transaction.chart');


//    PanelController::makeInertiaRoute('get', 'site/edit/{site}', 'panel.site.edit', 'Panel/Site/Edit', ['categories' => Site::categories('parents'), 'site_statuses' => Variable::SITE_STATUSES, 'site' => $tmp = Site::with('category')->find(request()->segment(count(request()->segments())))], 'can:edit,App\Models\User,App\Models\Site,"","' . $tmp . '"');


    PanelController::makeInertiaRoute('get', 'notification/index', 'panel.notification.index', 'Panel/Notification/Index',
        [

        ]
    );
    PanelController::makeInertiaRoute('get', 'ticket/index', 'panel.ticket.index', 'Panel/Ticket/Index',
        [
            'statuses' => Variable::TICKET_STATUSES

        ]);
    PanelController::makeInertiaRoute('get', 'ticket/create', 'panel.ticket.create', 'Panel/Ticket/Create',
        [
            'attachment_allowed_mimes' => implode(',.', Variable::TICKET_ATTACHMENT_ALLOWED_MIMES),
        ]);


    PanelController::makeInertiaRoute('get', 'profile/edit', 'panel.profile.edit', 'Panel/Profile/Edit',
        [
            'accesses' => []
        ]);
    PanelController::makeInertiaRoute('get', 'password/edit', 'panel.profile.password.edit', 'Panel/Profile/PasswordEdit',
        [
        ]);


    /**  Admin Panel **/
    Route::middleware(['can:create,App\Models\User,App\Models\User,""',])->prefix('admin')->group(function ($route) {

        Route::get('', [PanelController::class, 'admin'])->name('panel.admin.index');
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
                'categories' => Article::categories('parents'),
                'statuses' => Variable::STATUSES
            ]
        );
        PanelController::makeInertiaRoute('get', 'article/create', 'panel.admin.article.create', 'Panel/Admin/Article/Create',
            [
                'categories' => Category::all(),
                'statuses' => Variable::STATUSES,
            ]
        );
        Route::post('article/create', [ArticleController::class, 'create'])->name('panel.admin.article.create');
        Route::get('article/search', [ArticleController::class, 'searchPanel'])->name('panel.admin.article.search');
        Route::patch('article/update', [ArticleController::class, 'update'])->name('panel.admin.article.update');
        Route::get('article/{article}', [ArticleController::class, 'edit'])->name('panel.admin.article.edit');

        Route::patch('message/update', [MessageController::class, 'update'])->name('panel.admin.message.update');
        Route::post('message/create', [MessageController::class, 'create'])->name('panel.admin.message.create');
        Route::get('message/search', [MessageController::class, 'searchPanel'])->name('panel.admin.message.search');
        Route::get('message/{message}', [MessageController::class, 'edit'])->name('panel.admin.message.edit');

        Route::get('slider/edit/{slider}', [SliderController::class, 'edit'])->name('panel.admin.slider.edit');
        Route::post('slider/create', [SliderController::class, 'create'])->name('panel.admin.slider.create');
        Route::get('slider/search', [SliderController::class, 'searchPanel'])->name('panel.admin.slider.search');
        Route::patch('slider/update', [SliderController::class, 'update'])->name('panel.admin.slider.update');
        Route::get('setting/search', [SettingController::class, 'searchPanel'])->name('panel.admin.setting.search');
        Route::patch('setting/update', [SettingController::class, 'update'])->name('panel.admin.setting.update');
        Route::delete('setting/delete/{setting}', [SettingController::class, 'delete'])->name('panel.admin.setting.delete');
        Route::get('user/search', [UserController::class, 'searchPanel'])->name('panel.admin.user.search');
        Route::get('user/create', [UserController::class, 'new'])->name('panel.admin.user.new');
        Route::post('user/create', [UserController::class, 'create'])->name('panel.admin.user.create');
        Route::get('user/edit/{site}', [UserController::class, 'edit'])->name('panel.admin.user.edit');
        Route::patch('user/update', [UserController::class, 'update'])->name('panel.admin.user.update');
        Route::get('ticket/{ticket}', [TicketController::class, 'edit'])->name('panel.admin.ticket.edit');
        Route::patch('ticket/update', [TicketController::class, 'update'])->name('panel.admin.ticket.update');
        Route::post('ticket/create', [TicketController::class, 'create'])->name('panel.admin.ticket.create');
        Route::get('notification/{notification}', [NotificationController::class, 'edit'])->name('panel.admin.notification.edit');
        Route::patch('notification/update', [NotificationController::class, 'update'])->name('panel.admin.notification.update');
        Route::post('notification/create', [NotificationController::class, 'create'])->name('panel.admin.notification.create');


    });
});


Route::post('article/create', [ArticleController::class, 'create'])->name('article.create')->middleware('can:create,App\Models\User,App\Models\Article,""');

Route::post('ticket/create', [TicketController::class, 'create'])->name('ticket.create')->middleware('can:create,App\Models\User,App\Models\Ticket,""');


Route::middleware('throttle:6,1')->group(function () {
    Route::post('sms/send', [MainController::class, 'sendSms'])->name('sms.send.verification');
    Route::post('message/create', [MessageController::class, 'create'])->name('message.create');

});

Route::middleware(['auth:sanctum',
    config('jetstream.auth_session'),
    'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/reset-password', [ProfileController::class, 'resetPassword'])->name('profile.password.reset');

    Route::get('panel/notification/search', [NotificationController::class, 'searchPanel'])->name('panel.notification.search');
    Route::get('panel/ticket/search', [TicketController::class, 'searchPanel'])->name('panel.ticket.search');


    Route::get('notification/edit/{notification}', [NotificationController::class, 'edit'])->name('panel.notification.edit');
    Route::patch('notification/update', [NotificationController::class, 'update'])->name('notification.update');
    Route::delete('notification/delete/{notification}', [NotificationController::class, 'delete'])->name('panel.admin.notification.delete');

    Route::get('ticket/{ticket}', [TicketController::class, 'edit'])->name('panel.ticket.edit');
    Route::patch('ticket/update', [TicketController::class, 'update'])->name('ticket.update');

});


Route::get('/make_money', [MainController::class, 'makeMoneyPage'])->name('page.make_money');
Route::get('/prices', [MainController::class, 'pricesPage'])->name('page.prices');
Route::get('/help', [MainController::class, 'helpPage'])->name('page.help');
Route::get('/contact_us', [MainController::class, 'contactUsPage'])->name('page.contact_us');
Route::get('/exchange', [ExchangeController::class, 'index'])->name('exchange.index');


Route::get('/articles', [ArticleController::class, 'index'])->name('article.index');
Route::get('/article/search', [ArticleController::class, 'search'])->name('article.search');
Route::post('article/view', [ArticleController::class, 'increaseView'])->name('article.view');
Route::get('article/{article}', [ArticleController::class, 'view'])->name('article');


Route::get('language/{language}', function ($language) {
    session()->put('locale', $language);
    return;
})->name('language');

//
//require __DIR__ . '/auth.php';
