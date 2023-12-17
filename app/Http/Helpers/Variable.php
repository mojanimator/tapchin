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
use App\Models\Setting;
use App\Models\Site;
use App\Models\SiteTransaction;
use App\Models\Slider;
use App\Models\Text;
use App\Models\Ticket;
use App\Models\User;
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
    const ROLES = ['us', 'ad', 'go', 'op'];
    const  TICKET_STATUSES = [
        ["name" => 'review', "color" => 'danger'],
        ["name" => 'closed', "color" => 'gray'],
        ["name" => 'responded', "color" => 'success'],
    ];
    const  MESSAGE_STATUSES = [
        ["name" => 'order', "color" => 'teal'],
        ["name" => 'referral', "color" => 'blue'],
    ];
    const CATEGORIES = [
        ['name' => 'industry_mining',],
        ['name' => 'estate',],
        ['name' => 'trading',],
        ['name' => 'business',],
        ['name' => 'it',],
        ['name' => 'tutorial',],
        ['name' => 'car',],
        ['name' => 'personal_stuff',],
        ['name' => 'home_stuff',],
        ['name' => 'employment',],
        ['name' => 'agriculture',],
        ['name' => 'wearing',],
        ['name' => 'travel_entertainment',],
        ['name' => 'legal',],

    ];


    const SUCCESS_STATUS = 200;
    const ERROR_STATUS = 422;

    const DATA_TRANSACTION_TYPES = ['view', 'transfer'];
    const REF_TYPES = ['register',];
    const BANK_GATEWAY = 'nextpay';
    const BANNER_IMAGE_LIMIT_MB = 10;
    const SITE_IMAGE_LIMIT_MB = 4;
    const BUSINESS_IMAGE_LIMIT = 4;
    const TICKET_ATTACHMENT_MAX_LEN = 5;

    const TICKET_ATTACHMENT_ALLOWED_MIMES = ['jpeg', 'jpg', 'png', 'txt', 'pdf'];
    const BANNER_ALLOWED_MIMES = ['jpeg', 'jpg', 'png'];


    const MIN_SELL_PRICE = 5000;
    const PODCAST_ALLOWED_MIMES = ['mp3', 'mpga'];
    const VIDEO_ALLOWED_MIMES = ['mp4',];
    const LOGS = [72534783];
    const PAGINATE = [24, 50, 100];
    const IMAGE_FOLDERS = [
        Article::class => 'articles',
        Ticket::class => 'tickets',
        User::class => 'users',
        Slider::class => 'slides',
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


    const  STATUSES = [
        ["name" => 'active', "color" => 'success'],
        ["name" => 'inactive', "color" => 'danger'],
    ];
    const NOTIFICATION_LIMIT = 5;
    const RATIOS = ['slider' => 1.8];

    static function getAdmins()
    {
        return [
            ['id' => 1, 'fullname' => 'حسن نژاد', 'phone' => '09132258738', 'telegram_id' => '1021078930', 'wallet_active' => false,
                'role' => 'us', 'email' => 'jafar.hasannejhad@gmail.com', 'password' => Hash::make('o9132258738'), 'email_verified_at' => Carbon::now(), 'created_at' => Carbon::now(), 'phone_verified' => true, 'ref_id' => 'metakar'
            ],
            ['id' => 2, 'fullname' => 'رجبی', 'phone' => '09018945844', 'telegram_id' => '72534783', 'wallet_active' => false,
                'role' => 'ad', 'email' => 'moj2raj2@gmail.com', 'password' => Hash::make('gX4ntH4RtIg$'), 'email_verified_at' => Carbon::now(), 'created_at' => Carbon::now(), 'phone_verified' => true, 'ref_id' => 'develowper'
            ],
        ];
    }

    static function getSettings()
    {
        return [
            ['key' => 'hero_main_page', 'value' => __('hero_main_page'), "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_telegram', 'value' => 'hasannejhad', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_whatsapp', 'value' => '00989132258738', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_email', 'value' => 'info@koodkabotar.com', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_phone', 'value' => '09132258738', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_address', 'value' => 'گیلان - رشت', "created_at" => \Carbon\Carbon::now(),],

        ];
    }


}
