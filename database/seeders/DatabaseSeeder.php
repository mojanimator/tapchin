<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Http\Helpers\Util;
use App\Http\Helpers\Variable;
use App\Models\Agency;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\County;
use App\Models\Doc;
use App\Models\Notification;
use App\Models\Pack;
use App\Models\Payment;
use App\Models\Podcast;
use App\Models\PProduct;
use App\Models\Product;
use App\Models\Repository;
use App\Models\Site;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    private \Faker\Generator $faker;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Faker::create('fa_IR');
        if (DB::connection()->getDriverName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');


        $this->createAdmins(20);
        $this->createAgencies(20);
        $this->createPacks(20);
        $this->createRepositories(20);
        $this->createShippingMethods(20);
        $this->createProducts(20);


        return;

        $this->createSites(50);
        $this->createBusinesses(50);
        $this->createPodcasts(50);
        $this->createVideos(50);
        $this->createBanners(50);
        $this->createArticles(50);
        $this->createNotifications(50);
        $this->createPayments(50);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }

    private function createAdmins($count = 30)
    {

        DB::table('admins')->truncate();

        for ($i = 0; $i < $count; $i++) {
            $phone = $this->faker->numerify("091########");
            DB::table('admins')->insert([
                [
//                    'id' => 2,
                    'fullname' => $this->faker->name,
                    'phone' => $phone,
                    'phone_verified' => true,
                    'password' => Hash::make($phone),
                    'status' => 'active',
                    'role' => $i < 15 ? 'owner' : 'admin',
                    'access' => json_encode(['all']),
                ]
            ]);
        }

    }

    private
    function createAgencies($count = 30)
    {

        DB::table('agencies')->truncate();


        //section agencies
        $provinces1 = City::where('level', 1)->inRandomOrder()->take(5)->pluck('id');
        $provinces2 = City::where('level', 1)->whereNotIn('id', $provinces1)->inRandomOrder()->take(5)->pluck('id');
        DB::table('agencies')->insert([
            [
                'id' => 1,
                'name' => 'نمایندگی جنوب ایران',
                'access' => json_encode($provinces1),
                'has_shop' => false,
                'type' => Variable::AGENCY_TYPES[1]['code'],
                'owner_id' => 2,
                'province_id' => City::where('level', 1)->where('name', 'خوزستان')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'اهواز')->first()->id,
                'address' => 'کوی کارگر',
                'status' => 'active',
            ],
            [
                'id' => 2,
                'name' => 'نمایندگی مرکز ایران',
                'access' => json_encode($provinces2),
                'has_shop' => false,
                'type' => Variable::AGENCY_TYPES[1]['code'],
                'owner_id' => 3,
                'province_id' => City::where('level', 1)->where('name', 'تهران')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'تهران')->first()->id,
                'address' => 'میدان آزادی',
                'status' => 'active',

            ],
            [
                'id' => 3,
                'name' => 'نمایندگی انحصاری اصفهان',
                'access' => json_encode([City::where('level', 1)->where('name', 'اصفهان')->first()->id]),
                'has_shop' => false,
                'type' => Variable::AGENCY_TYPES[2]['code'],
                'owner_id' => 4,
                'province_id' => City::where('level', 1)->where('name', 'اصفهان')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'اصفهان')->first()->id,
                'address' => 'سه راه سیمین',
                'status' => 'active',

            ],
            [
                'id' => 4,
                'name' => 'نمایندگی انحصاری تهران',
                'access' => json_encode([City::where('level', 1)->where('name', 'تهران')->first()->id]),
                'has_shop' => false,
                'type' => Variable::AGENCY_TYPES[2]['code'],
                'owner_id' => 5,
                'province_id' => City::where('level', 1)->where('name', 'تهران')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'تهران')->first()->id,
                'address' => 'میدان آرژانتین',
                'status' => 'active',

            ],

            [
                'id' => 5,
                'name' => 'نمایندگی شعبه فیروزکوه',
                'access' => null,
                'has_shop' => true,
                'type' => Variable::AGENCY_TYPES[3]['code'],
                'owner_id' => 6,
                'province_id' => City::where('level', 1)->where('name', 'تهران')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'فیروزکوه')->first()->id,
                'address' => 'فیروزکوه',
                'status' => 'active',

            ],
            [
                'id' => 6,
                'name' => 'نمایندگی شعبه اسلامشهر',
                'access' => null,
                'has_shop' => true,
                'type' => Variable::AGENCY_TYPES[3]['code'],
                'owner_id' => 7,
                'province_id' => City::where('level', 1)->where('name', 'تهران')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'اسلام‌شهر')->first()->id,
                'address' => 'اسلامشهر',
                'status' => 'active',

            ],
            [
                'id' => 7,
                'name' => 'نمایندگی شعبه اصفهان',
                'access' => null,
                'has_shop' => true,
                'type' => Variable::AGENCY_TYPES[3]['code'],
                'owner_id' => 8,
                'province_id' => City::where('level', 1)->where('name', 'اصفهان')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'اصفهان')->first()->id,
                'address' => 'میدان شهدا',
                'status' => 'active',

            ],
            [
                'id' => 8,
                'name' => 'نمایندگی شعبه شهرضا',
                'access' => null,
                'has_shop' => true,
                'type' => Variable::AGENCY_TYPES[3]['code'],
                'owner_id' => 9,
                'province_id' => City::where('level', 1)->where('name', 'اصفهان')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'شاهین‌شهر')->first()->id,
                'address' => 'میدان شهدا',
                'status' => 'active',

            ],

        ]);


    }

    private function createPacks($count = 30)
    {

        DB::table('packs')->truncate();
        //section agencies
        DB::table('packs')->insert([
            [

                'name' => 'جعبه چوبی',
                'weight' => 500,
                'height' => 50,
                'length' => 80,
                'width' => 60,
                'price' => 0,

            ],
            [
                'name' => 'جعبه پلاستیکی',
                'weight' => 350,
                'height' => 50,
                'length' => 80,
                'width' => 60,
                'price' => 0,

            ],
            [
                'name' => 'گونی',
                'weight' => 100,
                'height' => 150,
                'length' => 80,
                'width' => 60,
                'price' => 0,
            ],
        ]);
    }

    private function createRepositories($count = 30)
    {


        DB::table('repositories')->truncate();
        //section agencies
        DB::table('repositories')->insert([
            [
                'id' => 1,
                'name' => 'انبار فیروزکوه',
                'agency_id' => 5,
                'province_id' => City::where('level', 1)->where('name', 'تهران')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'فیروزکوه')->first()->id,
                'address' => 'فیروزکوه',
                'location' => null,
                'status' => 'active',
                'cities' => json_encode(array_merge(City::where('parent_id', City::where('level', 2)->where('name', 'تهران')->first()->id)->take(20)->inRandomOrder()->pluck('id')->toArray(), [392, 61])),
            ],
            [
                'id' => 2,
                'name' => 'انبار اسلامشهر',
                'agency_id' => 6,
                'province_id' => City::where('level', 1)->where('name', 'تهران')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'اسلام‌شهر')->first()->id,
                'address' => 'اسلامشهر',
                'location' => null,
                'status' => 'active',
                'cities' => json_encode(array_merge(City::where('parent_id', City::where('level', 2)->where('name', 'تهران')->first()->id)->take(25)->inRandomOrder()->pluck('id')->toArray(), [686, 61])),


            ],
            [
                'id' => 3,
                'name' => 'انبار اصفهان',
                'agency_id' => 7,
                'province_id' => City::where('level', 1)->where('name', 'اصفهان')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'اصفهان')->first()->id,
                'address' => 'خیابان مشتاق',
                'location' => null,
                'status' => 'active',
                'cities' => json_encode(array_merge(City::where('parent_id', City::where('level', 2)->where('name', 'اصفهان')->first()->id)->take(20)->inRandomOrder()->pluck('id')->toArray(), [1543])),

            ],
            [
                'id' => 4,
                'name' => 'انبار شهرضا',
                'agency_id' => 8,
                'province_id' => City::where('level', 1)->where('name', 'اصفهان')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'اصفهان')->first()->id,
                'address' => 'میدان شهدا',
                'location' => null,
                'status' => 'active',
                'cities' => json_encode(array_merge(City::where('parent_id', City::where('level', 1)->where('name', 'اصفهان')->first()->id)->take(20)->inRandomOrder()->pluck('id')->toArray(), [1543])),

            ],

        ]);
    }

    private function createShippingMethods($count = 30)
    {


        DB::table('shipping_methods')->truncate();
        DB::table('shipping_methods')->insert(\App\Http\Helpers\Variable::getDefaultShippingMethods());

        //section agencies
        DB::table('shipping_methods')->insert([
            [
                'id' => 2,
                'repo_id' => 1,
                'products' => null,
                'cities' => null,
                'per_weight_price' => 5000,
                'base_price' => 10000,
                'free_from_price' => null,
                'description' => '',
                'name' => 'پخش فیروزکوه',
            ], [
                'id' => 3,
                'repo_id' => 1,
                'products' => json_encode(Product::where('repo_id', 1)->inRandomOrder()->take(4)->pluck('id')->toArray()),
                'cities' => json_encode(collect(Repository::where('id', 1)->first()->cities)->shuffle()->take(10)),
                'per_weight_price' => 0,
                'base_price' => 12000,
                'free_from_price' => null,
                'description' => '',
                'name' => 'پخش فیروزکوه',
            ], [
                'id' => 4,
                'repo_id' => 1,
                'products' => json_encode(Product::where('repo_id', 1)->inRandomOrder()->take(6)->pluck('id')->toArray()),
                'cities' => null,
                'per_weight_price' => 0,
                'base_price' => 15000,
                'free_from_price' => null,
                'description' => '',
                'name' => 'پخش ویژه فیروزکوه',
            ], [
                'id' => 5,
                'repo_id' => 2,
                'products' => null,
                'cities' => json_encode(collect(Repository::where('id', 1)->first()->cities)->shuffle()->take(10)),
                'per_weight_price' => 4000,
                'base_price' => 14000,
                'free_from_price' => null,
                'description' => '',
                'name' => 'پخش اسلامشهر',
            ],

        ]);
    }

    private function createProducts($count = 30)
    {
        DB::table('p_products')->truncate();
        DB::table('products')->truncate();
        File::deleteDirectory("storage/app/public/products");
        File::makeDirectory("storage/app/public/products");

        $repoIds = [1, 2, 3, 4];
        $prods = [
            'انگور',
            'لیمو',
            'تمشک',
            'پرتقال',
            'آناناس',
            'گلابی',
            'بلوبری',
            'انجیر',
            'توت فرنگی',
            'طالبی',
            'سیب',
            'زردآلو',
            'لیمو ترش',
            'گیلاس',
            'کیوی',
        ];
        foreach ($prods as $prod) {
            $packs = Pack::inRandomOrder()->take(2)->pluck('id');
            $pp = PProduct::create([
                'name' => $prod,
            ]);
            foreach (Repository::whereIn('id', $repoIds)->get() as $repo) {
                $p = Product::create([
                    'name' => $pp->name,
                    'parent_id' => $pp->id,
                    'grade' => $this->faker->randomElement(Variable::GRADES),
                    'agency_id' => $repo->agency_id,
                    'repo_id' => $repo->id,
                    'pack_id' => $packs[0],
                    'in_repo' => $this->faker->numberBetween(0, 50),
                    'in_shop' => $this->faker->numberBetween(0, 50),
                    'price' => $this->faker->randomElement([10000, 5000, 3000, 22000, 12000]),
                    'auction_price' => 0,
                    'in_auction' => false,
                    'weight' => $this->faker->randomElement([5.5, 10, 15, 20]),
                    'description' => $this->faker->realText($this->faker->numberBetween(256, 512)),
                ]);
                $this->makeGallery("products", $pp->id, $p->id);

                $p = Product::create([
                    'name' => $pp->name,
                    'parent_id' => $pp->id,
                    'grade' => $this->faker->randomElement(Variable::GRADES),
                    'agency_id' => $repo->agency_id,
                    'repo_id' => $repo->id,
                    'pack_id' => $packs[1],
                    'in_repo' => $this->faker->numberBetween(0, 50),
                    'in_shop' => $this->faker->numberBetween(0, 50),
                    'price' => $this->faker->randomElement([10000, 5000, 3000, 22000, 12000]),
                    'auction_price' => 0,
                    'in_auction' => false,
                    'weight' => $this->faker->randomElement([2.5, 10, 15, 20]),
                    'description' => $this->faker->realText($this->faker->numberBetween(256, 512)),
                ]);

                $this->makeGallery("products", $pp->id, $p->id);
            }
        }

    }

    private
    function createPayments($count = 30)
    {
        User::whereNotNull('id')->update(['wallet' => 0]);
        DB::table('payments')->truncate();
        DB::table('transactions')->truncate();
        for ($i = 0; $i < $count; $i++) {
            $ownerId = $this->faker->numberBetween(1, 2);
            $user = User::find($ownerId);
            $amount = $this->faker->randomElement([10000, 5000, 3000, 22000, 12000]);
            $orderId = "{$ownerId}-" . floor(microtime(true) * 1000);
            $data = Payment::create([
                'owner_id' => $ownerId,
                'order_id' => $orderId,
                'amount' => $amount,
                'is_success' => true,
                'transaction_id' => $this->faker->regexify("[A-Za-z0-9]{10}"),
                'title' => __('charge') . " $amount " . __('currency'),
                'type' => 'charge',
                'market' => $this->faker->randomElement(Variable::MARKETS),
                'gateway' => $this->faker->randomElement(Variable::GATEWAYS),
                'coupon' => null,
                'created_at' => Carbon::now()->subMinutes($this->faker->numberBetween(2000, 360000)),
            ]);

            $data = Transaction::create([
                'owner_id' => $data->owner_id,
                'amount' => $data->amount,
                'type' => $data->type,
                'title' => $data->title,
                'source_id' => $data->id
            ]);
            $user->wallet += $amount;

            if ($this->faker->randomElement([false, false, true,])) {
                $type = $this->faker->randomElement(['buy_article', 'buy_video', 'buy_podcast', 'buy_banner',]);
                $table = explode('_', $type)[1] . "s";
                $id = DB::table($table)->inRandomOrder()->first()?->id;
                $data = Transaction::create([
                    'owner_id' => $data->owner_id,
                    'amount' => $this->faker->randomElement([-1200, -4200, -3400, -2300, -11000]),
                    'type' => "{$type}_$id",
                    'title' => __($type) . " $id ",
                    'source_id' => null,
                    'created_at' => Carbon::now()->subMinutes($this->faker->numberBetween(2000, 360000)),

                ]);
            }
            $user->save();
        }
    }

    private
    function createNotifications($count = 30)
    {

        DB::table('notifications')->truncate();
        for ($i = 0; $i < $count; $i++) {
            $ownerId = $this->faker->numberBetween(1, 2);
            $element = $this->faker->randomElement(["article", "site", "business", "podcast", "banner", "video",]);
            $for = DB::table("{$element}" . ($element == 'business' ? 'es' : 's'))->where('owner_id', $ownerId)->inRandomOrder()->first()?->id;
            $description = $this->faker->realText($this->faker->numberBetween(128, 512));
            $type = $this->faker->randomElement(["pay", "ticket_answer", "{$element}_approve", "{$element}_reject"]);

            $data = Notification::create([
                'owner_id' => $ownerId,
                'type' => $type,
                'subject' => __($type),
                'description' => $description,
                'data_id' => !in_array($type, ['pay', 'ticket_answer']) ? $for : null,
                'created_at' => Carbon::now(),
            ]);
            $this->makeFile("articles", $data->id);

        }
    }

    private
    function createArticles($count = 30)
    {
        File::deleteDirectory("storage/app/public/articles");
        File::makeDirectory("storage/app/public/articles");
        DB::table('articles')->truncate();

        for ($i = 0; $i < $count; $i++) {

            $elements = $this->faker->randomElements(["text", "text", "podcast", "banner", "video", "text", "text", "podcast", "banner", "video", "text", "text", "podcast", "banner", "video",], $this->faker->numberBetween(3, 5));

            $content = [];
            $duration = 0;
            foreach ($elements as $element) {
                if ($element != 'text') {
                    $item = DB::table("{$element}s")->inRandomOrder()->first();
                    $content[] = ['id' => $item?->id, 'type' => $element, 'value' => $item?->name];
                    $duration += $item->duration ?? 0;
                } else {
                    $html = "<p><strong>تست متن</strong> " . $this->faker->realText($this->faker->numberBetween(60, 500)) . "</p><h2>" . $this->faker->realText($this->faker->numberBetween(10, 30)) . "</h2>";
                    $duration += Util::estimateReadingTime($html);
                    $content[] = ['id' => microtime(true), 'type' => $element, 'value' => $html];
                }
            }

            $title = $this->faker->realText($this->faker->numberBetween(60, 120));
            $status = $this->faker->randomElement(["active", "need_charge", "active", "review"]);
            $viewFee = $this->faker->numberBetween(0, 600);
            $data = Article::create([
                'title' => $title,
                'slug' => str_slug($title),
                'status' => $status,
                'view_fee' => $viewFee,
                'charge' => $status == 'need_charge' ? $viewFee - intval($viewFee / 3) : $viewFee + $this->faker->numberBetween(200, 500),
                'owner_id' => $this->faker->numberBetween(1, 2),
                'category_id' => $this->faker->randomElement(Category::pluck('id')),
                'author' => $this->faker->name,
                'duration' => $duration,
                'view' => $this->faker->numberBetween(0, 10),
                'lang' => 'fa',
                'content' => json_encode($content),
                'summary' => $this->faker->realText($this->faker->numberBetween(200, 1024)),
                'created_at' => Carbon::now(),
                'tags' => implode(",", $this->faker->randomElements(["مقاله", "متن", "تبلیغ", "مقاله", "مقاله", "تست",], $this->faker->numberBetween(0, 5))),
            ]);
            $this->makeFile("articles", $data->id);

        }
    }

    private
    function createBanners($count = 30)
    {
        File::deleteDirectory("storage/app/public/banners");
        File::makeDirectory("storage/app/public/banners");
        DB::table('banners')->truncate();

        for ($i = 0; $i < $count; $i++) {

            $name = $this->faker->company;
            $data = Banner::create([
                'name' => $name,
                'designer' => $this->faker->name,
                'slug' => str_slug($name),
                'status' => $this->faker->randomElement(["active", "active", "active", "review"]),
                'owner_id' => $this->faker->numberBetween(1, 2),
                'category_id' => $this->faker->randomElement(Category::pluck('id')),
                'view' => 0,
                'lang' => 'fa',
                'description' => $this->faker->realText($this->faker->numberBetween(200, 1024)),
                'created_at' => Carbon::now(),
                'tags' => implode(",", $this->faker->randomElements(["بنر", "بازدید", "صدا", "تست", "بنر", "بنر",], $this->faker->numberBetween(0, 5))),
            ]);
            $this->makeFile("banners", "$data->id");
            $this->makeFile("banners", "$data->id-file", null);
        }
    }

    private
    function createVideos($count = 30)
    {
        File::deleteDirectory("storage/app/public/videos");
        File::makeDirectory("storage/app/public/videos");
        DB::table('videos')->truncate();

        for ($i = 0; $i < $count; $i++) {

            $name = $this->faker->company;
            $data = Video::create([
                'name' => $name,
                'slug' => str_slug($name),
                'duration' => $this->faker->numberBetween(60, 250),
                'status' => $this->faker->randomElement(["active", "active", "active", "review"]),
                'owner_id' => $this->faker->numberBetween(1, 2),
                'category_id' => $this->faker->randomElement(Category::pluck('id')),
                'view' => 0,
                'lang' => 'fa',
                'description' => $this->faker->realText($this->faker->numberBetween(200, 1024)),
                'created_at' => Carbon::now(),
                'tags' => implode(",", $this->faker->randomElements(["ویدیو", "بازدید", "صدا", "تست", "تصویر", "ویدیو",], $this->faker->numberBetween(0, 5))),
            ]);
            $this->makeFile("videos", $data->id);
            $this->makeFile("videos", $data->id, '.mp4');
        }
    }

    private
    function createPodcasts($count = 30)
    {
        File::deleteDirectory("storage/app/public/podcasts");
        File::makeDirectory("storage/app/public/podcasts");
        DB::table('podcasts')->truncate();

        for ($i = 0; $i < $count; $i++) {

            $name = $this->faker->company;
            $data = Podcast::create([
                'name' => $name,
                'narrator' => $this->faker->name,
                'slug' => str_slug($name),
                'duration' => $this->faker->numberBetween(60, 250),
                'status' => $this->faker->randomElement(["active", "active", "active", "review"]),
                'owner_id' => $this->faker->numberBetween(1, 2),
                'category_id' => $this->faker->randomElement(Category::pluck('id')),
                'view' => 0,
                'lang' => 'fa',
                'description' => $this->faker->realText($this->faker->numberBetween(200, 1024)),
                'created_at' => Carbon::now(),
                'tags' => implode(",", $this->faker->randomElements(["پادکست", "بازدید", "صدا", "تست", "گوینده", "پادکست",], $this->faker->numberBetween(0, 5))),
            ]);
            $this->makeFile("podcasts", $data->id);
            $this->makeFile("podcasts", $data->id, '.mp3');
        }
    }

    private
    function makeGallery($type, $parent, $id)
    {
        if (!File::exists("storage/app/public/$type/$id")) {
//            Storage::makeDirectory("public/$type", 766);
            File::makeDirectory(Storage::path("public/$type/$id"), $mode = 0755,);
        }
        $path = storage_path("app/public/faker/$type/$parent.jpg");

        $file = new UploadedFile(
            $path,
            '1.' . File::extension($path),
            File::mimeType($path),
            null,
            true

        );
        copy($file->path(), (storage_path("app/public/$type/$id/1.jpg")   /*. $file->extension()*/));


    }

    private
    function createBusinesses($count = 30)
    {
        File::deleteDirectory("storage/app/public/businesses");
        File::makeDirectory("storage/app/public/businesses");

        DB::table('businesses')->truncate();
        $socials = [
            ['name' => 'تلگرام', 'value' => 't.me/develowper',],
            ['name' => 'ایتا', 'value' => 'eitaa.com/vartastudio',],
            ['name' => 'واتساپ', 'value' => 'wa.me/00989398793845',],
            ['name' => 'ایمیل', 'value' => 'moj2raj2@gmail.com',],
        ];
        for ($i = 0; $i < $count; $i++) {

            $name = $this->faker->company;
            $county = County::inRandomOrder()->first();

            $data = Business::create([
                'owner_id' => $this->faker->numberBetween(1, 2),
                'category_id' => $this->faker->randomElement(Category::pluck('id')),
                'province_id' => $county->province_id,
                'county_id' => $county->id,
                'name' => $name,
                'slug' => str_slug($name),
                'description' => $this->faker->realText($this->faker->numberBetween(200, 1024)),
                'phone' => $this->faker->numerify("09#########"),
                'tags' => implode(",", $this->faker->randomElements(["سایت", "بازدید", "تگ", "تست", "خدمات", "افزایش",], $this->faker->numberBetween(0, 5))),
                'status' => $this->faker->randomElement(["active", "active", "active", "review", "inactive"]),
                'lang' => 'fa',
                'view' => 0,
                'socials' => json_encode($this->faker->randomElements($socials, $this->faker->numberBetween(0, 4))),
                'created_at' => Carbon::now(),
            ]);
            $this->makeGallery("businesses", $data->id);
        }
    }

    private
    function createSites($count = 30)
    {
        File::deleteDirectory("storage/app/public/sites");
        File::makeDirectory("storage/app/public/sites");
        DB::table('sites')->truncate();

        for ($i = 0; $i < $count; $i++) {

            $name = $this->faker->company;
            $data = Site::create([
                'name' => $name,
                'slug' => str_slug($name),
                'link' => $this->faker->url(),

                'status' => $this->faker->randomElement(["view", "view", "view", "ready"]),
                'meta' => $this->faker->numberBetween(0, 5),
                'owner_id' => $this->faker->numberBetween(1, 2),
                'category_id' => $this->faker->randomElement(Category::pluck('id')),
                'view' => 0,
                'viewer' => 0,
                'charge' => $this->faker->numerify("####00"),
                'view_fee' => $this->faker->numerify("##0"),
                'lang' => 'fa',
                'description' => $this->faker->realText($this->faker->numberBetween(200, 1024)),
                'created_at' => Carbon::now(),
                'tags' => implode(",", $this->faker->randomElements(["سایت", "بازدید", "تگ", "تست", "خدمات", "افزایش",], $this->faker->numberBetween(0, 5))),
            ]);
            $this->makeFile("sites", $data->id);
        }
    }

    private
    function makeFile($type, $id, $extension = '.jpg')
    {
        $allFiles = array_filter(Storage::allFiles("public/faker/$type"), fn($e) => !$extension || str_contains($e, $extension));

        $path = storage_path('app/' . $allFiles[array_rand($allFiles)]);
        $extension = $extension ?? "." . File::extension($path);
        //profile picture
        $file = new UploadedFile(
            $path,
            File::name($path) . ($extension),
            File::mimeType($path),
            null,
            true

        );

        if (!File::exists("storage/app/public/$type")) {
//            Storage::makeDirectory("public/$type", 766);
            File::makeDirectory(Storage::path("public/$type"), $mode = 0755,);
        }

        copy($file->path(), (storage_path("app/public/$type/") . "$id$extension" /*. $file->extension()*/));

    }


    function makeGallery2($type, $id)
    {
        $fakeFiles = Storage::allFiles("public/faker/$type");
        if (!File::exists("storage/app/public/$type/$id")) {
//            Storage::makeDirectory("public/$type", 766);
            File::makeDirectory(Storage::path("public/$type/$id"), $mode = 0755,);
        }

        $rand = [1, 2, 3, 4][array_rand([1, 2, 3, 4])];

        for ($i = 0; $i < $rand; $i++) {

            $path = storage_path('app/' . $fakeFiles[array_rand($fakeFiles)]);

            //profile picture
            $file = new UploadedFile(
                $path,
                File::name($path) . '.' . File::extension($path),
                File::mimeType($path),
                null,
                true

            );
            $name = 1;
            $allFiles = Storage::allFiles("public/businesses/$id");

            foreach ($allFiles as $path) {
                if (str_contains($path, "/$name.jpg")) {
                    $name++;
                }
            }
            copy($file->path(), (storage_path("app/public/$type/$id/$name.jpg")   /*. $file->extension()*/));
        }
    }
}
