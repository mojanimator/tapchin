<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Http\Helpers\Util;
use App\Http\Helpers\Variable;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Business;
use App\Models\Category;
use App\Models\County;
use App\Models\Doc;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Podcast;
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

    private function createPayments($count = 30)
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

    private function createNotifications($count = 30)
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

    private function createArticles($count = 30)
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

    private function createBanners($count = 30)
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

    private function createVideos($count = 30)
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

    private function createPodcasts($count = 30)
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

    private function createBusinesses($count = 30)
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

    private function createSites($count = 30)
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

    private function makeFile($type, $id, $extension = '.jpg')
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

    private function makeGallery($type, $id)
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
