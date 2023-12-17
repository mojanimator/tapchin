<?php

namespace App\Http\Middleware;

use App\Http\Helpers\Variable;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $socials = Setting::where('key', 'like', 'social_%')->get();
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'isAdmin' => in_array(optional($request->user())->role, ['ad', 'go']),

            'location' => $request->url(),
//            'user' => optional(auth()->user())->only(['id', 'fullname', 'username',]),
            'locale' => function () {
                return app()->getLocale();
            },
            'langs' => Variable::LANGS,
            'images' => asset('assets/images') . '/',
            'language' => function () {
                if (!file_exists(lang_path('/' . app()->getLocale() . '.json'))) {
                    return [];
                }
                return json_decode(file_get_contents(
                        lang_path(
                            app()->getLocale() . '.json'))
                    , true);
            },
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => [
                'message' => fn() => $request->session()->get('flash_message'),
                'status' => fn() => $request->session()->get('flash_status'),
            ],
            'extra' => fn() => $request->session()->get('extra'),
            'pageItems' => Variable::PAGINATE,
            'socials' => [
                'whatsapp' => "https://wa.me/" . optional($socials->where('key', 'social_whatsapp')->first())->value,
                'telegram' => "https://t.me/" . optional($socials->where('key', 'social_telegram')->first())->value,
                'phone' => optional($socials->where('key', 'social_phone')->first())->value,
                'email' => optional($socials->where('key', 'social_email')->first())->value,
                'address' => optional($socials->where('key', 'social_address')->first())->value,
            ],
        ]);
    }
}
