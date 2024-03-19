<?php

namespace App\Http\Controllers;

use App\Http\Helpers\SMSHelper;
use App\Http\Helpers\Telegram;
use App\Http\Helpers\Util;
use App\Http\Helpers\Variable;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Admin;
use App\Models\Podcast;
use App\Models\User;
use App\Models\UserFinancial;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{


    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        return Inertia::render('Panel/Profile/Edit', [
            'user_statuses' => Variable::USER_STATUSES,
            'data' => get_class($user)::with('financial')->find($user->id),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileRequest $request)
    {
        $request->user()->fill($request->validated());
        $user = $request->user();

        switch ($request->cmnd) {
            case   'upload-img':
                if (!$request->img) //  add extra image
                    return response()->json(['errors' => [__('file_not_exists')], 422]);

                Util::createImage($request->img, Variable::IMAGE_FOLDERS[User::class], $user->id);
//                $user->is_active = false;
//                $user->save();
                Telegram::log(null, 'user_edited', $user);
                return response()->json(['message' => __('updated_successfully')], 200);

            case 'password-reset':
                $password = $request->new_password;
                $user->password = Hash::make($password);
                SMSHelper::deleteCode($user->phone);
                $user->save();
                $res = ['flash_status' => 'success', 'flash_message' => __('updated_successfully')];
                return back()->with($res);
            case 'add-address':
                $address = $request->all();
                unset($address['cmnd']);
                $addresses = $user->addresses ?? [];
                $addresses[] = $address;
                $user->addresses = $addresses;
                $user->update(['addresses' => $addresses]);
                $res = ['flash_status' => 'success', 'flash_message' => __('updated_successfully')];
                if ($request->wantsJson())
                    return response()->json(['message' => __('updated_successfully'), 'addresses' => $addresses], Variable::SUCCESS_STATUS);
                return back()->with($res);
            case 'remove-address':
                $idx = $request->idx;
                $addresses = $user->addresses ?? [];
                if (!is_int($idx) || count($addresses) <= $idx) {
                    if ($request->wantsJson())
                        return response()->json(['message' => __('response_error')], Variable::ERROR_STATUS);
                    return back()->withErrors(['message' => __('response_error')]);
                }
                array_splice($addresses, $idx, 1);
                $user->update(['addresses' => $addresses]);
                $res = ['flash_status' => 'success', 'flash_message' => __('updated_successfully')];
                if ($request->wantsJson())
                    return response()->json(['message' => __('updated_successfully'), 'addresses' => $addresses], Variable::SUCCESS_STATUS);
                return back()->with($res);
        }
        User::whereId($user->id)->update([
            'fullname' => $request->fullname,
            'phone' => $request->phone,
        ]);
        UserFinancial::updateOrCreate(['user_id' => $user->id,],
            [
                'card' => $request->card,
                'sheba' => $request->sheba,
            ]);

        $res = ['extra' => ['wallet_active' => $user->wallet_active], 'flash_status' => 'success', 'flash_message' => __('updated_successfully')];
        Telegram::log(null, 'user_edited', $user);
        return back()->with($res);
        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
