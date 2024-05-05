<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Intervention\Image\Gd\Font;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\Typography\FontFactory;

class FileController extends Controller
{
    public function index(Request $request)
    {

        $admin = $request->user();
        $card = Image::make(Storage::path('public/files/card.jpg'));
        $agency = Agency::find($admin->agency_id);
        if (!Storage::exists("public/files/agencies"))
            Storage::createDirectory("public/files/agencies");
        if (!Storage::exists("public/files/agencies/$admin->agency_id"))
            Storage::createDirectory("public/files/agencies/$admin->agency_id");
        $width = $card->width();
        $height = $card->height();
        $font = function (Font $font) {
//            $fontPath = resource_path('fonts/iransans/iransans.ttf');
//            $fontPath = resource_path('fonts/parastoo/web/Parastoo.ttf');
//            $fontPath = resource_path('fonts/sahel/Sahel.ttf');
//            $fontPath = resource_path('fonts/samim/Samim.ttf');
            $fontPath = resource_path('fonts/shabnam/Shabnam-Bold.ttf');

            $font->file($fontPath);
            $font->size(30);
            $font->color('000');
            $font->align('right');
            $font->valign('middle');
        };
        $persian_text = \PersianRender\PersianRender::render("$agency->name");
        $card->text("$persian_text", $width - 40, $height / 2 + 20, $font);
        $card->text("$agency->phone", $width - 40, $height / 2 + 70, $font);
        $card->save(Storage::path("public/files/agencies/$admin->agency_id/card.jpg"));

        return Inertia::render('Panel/Admin/File/Index', [
            'images' => [
                url("storage/files/agencies/$admin->agency_id/card.jpg"),
                url("storage/files/card_back.jpg"),
                url("storage/files/adv_A5.jpg"),
            ],

        ]);
    }
}
