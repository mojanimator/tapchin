<?php

namespace App\Http\Helpers;

use App\Models\Article;
use App\Models\ArticleTransaction;
use App\Models\Banner;
use App\Models\BannerTransaction;
use App\Models\Business;
use App\Models\BusinessTransaction;
use App\Models\Message;
use App\Models\Podcast;
use App\Models\PodcastTransaction;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Site;
use App\Models\SiteTransaction;
use App\Models\Slider;
use App\Models\Text;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Variation;
use App\Models\Video;
use App\Models\VideoTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;

class Variable
{


    const LANGS = ['fa', 'en', 'ar'];

    const MARKETS = ['bazaar', 'myket', 'playstore', 'site'];
    const GATEWAYS = ['bazaar', 'myket', 'nextpay'];

    const USER_ROLES = ['us'];
    const ADMIN_ROLES = ['god', 'owner', 'admin'];
    const AGENCY_TYPES = [
        ['id' => 0, 'name' => 'central', 'level' => '0'],
        ['id' => 1, 'name' => 'zone_agency', 'level' => '1'],
        ['id' => 2, 'name' => 'province_agency', 'level' => '2'],
        ['id' => 3, 'name' => 'branch_agency', 'level' => '3']
//        ['name' => 'branch', 'code' => 4],
    ];
    const PRODUCT_UNITS = ['qty', 'kg', 'gr'];

    const ADMIN_ACCESS = ['all'];
    const GRADES = [1, 2];
    const PARTNERSHIP_TYPES = [
        ['name' => 'agency', 'color' => 'gray'],
        ['name' => 'farmer', 'color' => 'teal'],
        ['name' => 'gardener', 'color' => 'lemon'],

    ];

    const  STATUSES = [
        ["name" => 'inactive', "color" => 'gray'],
        ["name" => 'active', "color" => 'success'],
        ["name" => 'block', "color" => 'danger'],
    ];
    const  USER_STATUSES = [
        ["name" => 'inactive', "color" => 'gray'],
        ["name" => 'active', "color" => 'success'],
        ["name" => 'block', "color" => 'danger'],
    ];
    const  TICKET_STATUSES = [
        ["name" => 'review', "color" => 'danger'],
        ["name" => 'closed', "color" => 'gray'],
        ["name" => 'responded', "color" => 'success'],
    ];
    const  MESSAGE_STATUSES = [
        ["name" => 'order', "color" => 'teal'],
        ["name" => 'referral', "color" => 'blue'],
    ];
    const  ORDER_STATUSES = [

        ["name" => 'request', "color" => 'lemon'],
        ["name" => 'pending', "color" => 'danger'],
        ["name" => 'processing', "color" => 'teal'],
        ["name" => 'ready', "color" => 'green'],
        ["name" => 'sending', "color" => 'primary'],
        ["name" => 'delivered', "color" => 'success'],
        ["name" => 'canceled', "color" => 'gray'],
        ["name" => 'rejected', "color" => 'gray'],
        ["name" => 'refunded', "color" => 'gray'],
    ];
    const  SHIPPING_STATUSES = [

        ["name" => 'pending', "color" => 'danger'],
        ["name" => 'processing', "color" => 'sky'],
        ["name" => 'sending', "color" => 'blue'],
        ["name" => 'delivered', "color" => 'success'],
        ["name" => 'failed', "color" => 'danger'],
        ["name" => 'canceled', "color" => 'danger'],
        ["name" => 'refunded', "color" => 'gray'],

    ];
    const CATEGORIES = [
        ['name' => 'fruit', 'color' => 'teal'],
        ['name' => 'protein', 'color' => 'rose'],


    ];


    const SUCCESS_STATUS = 200;
    const ERROR_STATUS = 422;

    const DATA_TRANSACTION_TYPES = ['view', 'transfer'];
    const REF_TYPES = ['register',];
    const BANK_GATEWAY = 'nextpay';
    const PAY_TIMEOUT = 1;
    const BANNER_IMAGE_LIMIT_MB = 10;
    const SITE_IMAGE_LIMIT_MB = 4;
    const BUSINESS_IMAGE_LIMIT = 4;
    const TICKET_ATTACHMENT_MAX_LEN = 5;

    const TICKET_ATTACHMENT_ALLOWED_MIMES = ['jpeg', 'jpg', 'png', 'txt', 'pdf'];
    const BANNER_ALLOWED_MIMES = ['jpeg', 'jpg', 'png'];
    const PRODUCT_IMAGE_LIMIT_MB = 10;
    const VARIATION_IMAGE_LIMIT = 5;

    const PRODUCT_ALLOWED_MIMES = ['jpeg', 'jpg', 'png'];


    const MIN_SELL_PRICE = 5000;
    const PODCAST_ALLOWED_MIMES = ['mp3', 'mpga'];
    const VIDEO_ALLOWED_MIMES = ['mp4',];
    const LOGS = [72534783, 1212754313];
    const PAGINATE = [24, 50, 100];
    const IMAGE_FOLDERS = [
        Article::class => 'articles',
        Ticket::class => 'tickets',
        User::class => 'users',
        Slider::class => 'slides',
        Product::class => 'products',
        Variation::class => 'variations',
    ];
    const NOTIFICATION_TYPES = [
        "business_approve",
        "business_reject",
        "site_approve",
        "site_reject",
        "text_approve",
        "text_reject",
        "article_approve",
        "article_reject",
        "banner_approve",
        "banner_reject",
        "video_approve",
        "video_reject",
        "podcast_approve",
        "podcast_reject",
        "pay",
        "access_change",
        "ticket_answer"
    ];

    const PROJECT_ITEMS = [
        ['name' => 'podcast', 'color' => 'sky',],
        ['name' => 'video', 'color' => 'purple',],
        ['name' => 'banner', 'color' => 'orange',],
        ['name' => 'text', 'color' => 'rose',]

    ];


    const NOTIFICATION_LIMIT = 5;
    const CITY_ID = 61; //تجریش
    const RATIOS = ['slider' => 1.8];

    static $CITIES = [];
    public static $BANK = 'zarinpal';

    static function getPaymentMethods()
    {
        return [
            ['name' => __('bank_payment'), 'selected' => true]
        ];
    }

    static function getAdmins()
    {
        return [

            ['id' => 1, 'fullname' => 'مدیر', 'phone' => '09018945844', 'status' => 'active', 'role' => 'owner', 'agency_id' => 7, 'agency_level' => '0',
                'access' => json_encode(['all']), 'email' => 'moj2raj2@gmail.com', 'password' => Hash::make('gX4ntH4RtIg$'), 'email_verified_at' => Carbon::now(), 'created_at' => Carbon::now(), 'phone_verified' => true,
            ],
        ];
    }

    static function getUsers()
    {
        return [

            ['id' => 1, 'fullname' => 'رجبی', 'phone' => '09018945844', 'status' => 'active', 'ref_id' => 'develowper',
                'access' => json_encode(['all']), 'email' => 'moj2raj2@gmail.com', 'password' => Hash::make('o7615564351'), 'email_verified_at' => Carbon::now(), 'created_at' => Carbon::now(), 'phone_verified' => true,
            ],
            ['id' => 2, 'fullname' => 'داریوش بهشتی', 'phone' => '09351414815', 'status' => 'active', 'ref_id' => 'dabel',
                'access' => json_encode(['all']), 'email' => null, 'password' => Hash::make('Dd20552055'), 'email_verified_at' => Carbon::now(), 'created_at' => Carbon::now(), 'phone_verified' => true,
            ],
        ];
    }

    static function getSettings()
    {
        return [
            ['key' => 'hero_main_page', 'value' => __('hero_main_page'), "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_telegram', 'value' => 'lord2095', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_whatsapp', 'value' => '00989351414815', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_email', 'value' => 'info@tapchin.ir', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_phone', 'value' => '09351414815', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_address', 'value' => __('social_address'), "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'order_reserve_minutes', 'value' => 30, "created_at" => \Carbon\Carbon::now(),],

        ];
    }

    public static function getDefaultShippingMethods()
    {
        return [
            [
                'id' => 1,
                'repo_id' => null,
                'products' => null,
                'cities' => null,
                'min_order_weight' => 0,
                'per_weight_price' => 0,
                'base_price' => 0,
                'free_from_price' => null,
                'description' => 'مشتری با مراجعه به انبار، کالای خود را دریافت می نماید',
                'name' => 'دریافت حضوری از انبار',
            ],

        ];
    }

}
