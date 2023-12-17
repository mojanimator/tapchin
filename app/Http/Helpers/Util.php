<?php

namespace App\Http\Helpers;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Spatie\Browsershot\Browsershot;
use Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot;

class Util
{
    public static function createFile(array|\Illuminate\Http\UploadedFile|null $file, string $type, $name)
    {
        if (!Storage::exists("public/$type")) {
            File::makeDirectory(Storage::path("public/$type"), $mode = 0755,);
        }
        $file->move(storage_path("app/public/$type"), "$name." . $file->extension());
    }

    static function createImage($img, $type, $name = null, $folder = null)
    {

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        if (!Storage::exists("public/$type")) {
            File::makeDirectory(Storage::path("public/$type"), $mode = 0755,);
        }
        $source = imagecreatefromstring($image_base64);
        if (!$name && $folder) { //is gallery
            if (!Storage::exists("public/$type/$folder"))
                File::makeDirectory(Storage::path("public/$type/$folder"), $mode = 0755,);
            $allFiles = Storage::allFiles("public/$type/$folder");
            $name = 1;
            foreach ($allFiles as $path) {
                if (str_contains($path, "/$name.jpg")) {
                    $name++;
                }
            }
            $type = "$type/$folder";
        }

//        imagetruecolortopalette($source, false, 16);
        $imageSave = imagejpeg($source, storage_path("app/public/$type/$name.jpg"), 80);
        imagedestroy($source);
        return $imageSave;
        return "/storage/$type/$name.jpg";
//        file_put_contents(storage_path("app/public/$type_id/$image->id.jpg"), $image_base64);


    }

    static function createScreenshot($url, $type, $name)
    {
        return true;
        if (!Storage::exists("public/$type")) {
            File::makeDirectory(Storage::path("public/$type"), $mode = 0755,);
        }
        try {
            return Browsershot::url($url)
                ->setOption('landscape', true)
                //            ->windowSize(3840, 2160)
                ->waitUntilNetworkIdle()
                ->save(storage_path("app/public/$type/$name.jpg"));
        } catch (CouldNotTakeBrowsershot $e) {
            return false;
        }


    }

    static function validate_base64($base64data, array $allowedMime)
    {


        // strip out data uri scheme information (see RFC 2397)
        if (strpos($base64data, ';base64') !== false) {
            list(, $base64data) = explode(';', $base64data);
            list(, $base64data) = explode(',', $base64data);
        }

        // strict mode filters for non-base64 alphabet characters
        if (base64_decode($base64data, true) === false) {
            return false;
        }

        // decoding and then reeconding should not change the data
        if (base64_encode(base64_decode($base64data)) !== $base64data) {
            return false;
        }

        $binaryData = base64_decode($base64data);

        // temporarily store the decoded data on the filesystem to be able to pass it to the fileAdder

        $tmpFile = tempnam(sys_get_temp_dir(), 'medialibrary');
//    $tmpFile = tmpfile();
//    $path = stream_get_meta_data($tmpFile)['uri'];
        file_put_contents($tmpFile, $binaryData);

        // guard Against Invalid MimeType
        $allowedMime = array_flatten($allowedMime);

        // no allowedMimeTypes, then any type would be ok
        if (empty($allowedMime)) {
            return true;
        }

        // Check the MimeTypes
        $validation = Validator::make(
            ['file' => new  File($tmpFile)],
            ['file' => 'mimes:' . implode(',', $allowedMime)]
        );

        $res = !$validation->fails();

        unlink($tmpFile);

        return $res;
    }

    static function e2f($str)
    {
        $eng = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $per = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        return str_replace($eng, $per, $str);
    }

    static function f2e($str)
    {
        $eng = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $per = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        return str_replace($per, $eng, $str);
    }

    static function array_flatten($array, $depth = INF): array
    {
        return Arr::flatten($array, $depth);
    }

    static function randomString($length = 5, $original)
    {
        return substr(str_shuffle($original), 0, $length);
    }

    static function wipeCaches()
    {
        $exitCode = Artisan::call('cache:clear');
        echo 'cache:clear ' . "$exitCode | " . PHP_EOL;
        $exitCode = Artisan::call('route:clear');
        echo 'route:clear ' . "$exitCode | " . PHP_EOL;
        $exitCode = Artisan::call('view:clear');
        echo 'view:clear ' . "$exitCode | " . PHP_EOL;
//    $exitCode = Artisan::call('view:cache');
//    echo 'view:cache' . "$exitCode | "  . PHP_EOL;
//    $exitCode = Artisan::call('route:cache');
//    echo 'route:cache' . "$exitCode | "  . PHP_EOL;
        $exitCode = Artisan::call('config:clear');
        echo 'config:clear ' . "$exitCode | " . PHP_EOL;
//    $exitCode = Artisan::call('config:cache');
//    echo 'config:cache' . "$exitCode | "  . PHP_EOL;
        $exitCode = Artisan::call('optimize');
        echo 'optimize ' . "$exitCode | " . PHP_EOL;

    }

    public
    static function generateRandomNumber($length = 8)
    {
        $random = "";
        srand((double)microtime() * 1000000);
        $data = "123456123456789071234567890890";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }

    static function estimateReadingTime($content = '', $wpm = 250)
    {
        $clean_content = strip_tags($content);
        $word_count = str_word_count($clean_content, 0, 'ابپتثجچ‌حخدذرز‌ژس‌شصضطظعغفقکگلمنوهیءآاًهٔه');
        $time = ceil($word_count / $wpm);
        return $time;
    }

}
