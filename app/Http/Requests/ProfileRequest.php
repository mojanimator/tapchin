<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\File;
use App\Http\Helpers\Variable;
use App\Models\Business;
use App\Models\Category;
use App\Models\County;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user = auth()->user();
        $editMode = (bool)optional($user)->id;
        $request = $this;
        $tmp = [];
        $phoneChanged = optional($user)->phone != $this->phone;
        $roles = array_column(Variable::ACCESS, 'role');
        if (!$this->cmnd || $this->cmnd == 'register')
            $tmp = array_merge($tmp, [
                'accesses' => ['nullable', 'array', 'max:' . count($roles),],
                'accesses.*' => ['distinct', Rule::in($roles)],
                'fullname' => ['required', 'min:3', 'max:100',/* Rule::unique('users', 'fullname')->ignore(optional($user)->id)*/],
                'card' => ['nullable', 'digits:16', Rule::unique('users', 'card')->ignore(optional($user)->id)],
                'phone' => ['required', 'numeric', 'digits:11', 'regex:/^09[0-9]+$/', Rule::unique('users', 'phone')->ignore(optional($user)->id)],
                'phone_verify' => [Rule::requiredIf(function () use ($request, $user, $phoneChanged, $editMode) {
                    return $phoneChanged
                        || !$user || (!$editMode && $request->phone != $user->phone);
                }), !$user || (!$editMode && $request->phone != $user->phone) ? Rule::exists('sms_verify', 'code')->where(function ($query) use ($request) {
                    return $query->where('phone', $request->phone);
                }) : '',],
            ]);


        if ($this->cmnd == 'upload-img')
            $tmp = array_merge($tmp, [
                'img' => ['required', 'base64_image_size:' . Variable::SITE_IMAGE_LIMIT_MB * 1024, 'base64_image_mime:' . implode(",", Variable::SITE_ALLOWED_MIMES)],

            ]);
        if ($this->cmnd == 'password-reset') {

            if (!$user)
                $tmp = array_merge($tmp, [
                    'phone' => ['required', 'numeric', 'digits:11', 'regex:/^09[0-9]+$/', Rule::exists('users', 'phone')],
                ]);
            $tmp = array_merge($tmp, [
                'phone_verify' => ['required', Rule::exists('sms_verify', 'code')->where('phone', $user ? $user->phone : $this->phone)],
                'new_password' => ['required', 'min:6', 'confirmed', 'regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'],

            ]);
        }
        if ($this->cmnd == 'register')

            $tmp = array_merge($tmp, [
                'password' => ['required', 'min:6', 'confirmed', 'regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'],


            ]);
        return $tmp;
    }

    public function messages()
    {

        return [

            'accesses.array' => sprintf(__("validator.invalid"), __('accesses')),
            'accesses.*.distinct' => sprintf(__("validator.invalid"), __('accesses')),
            'accesses.*.in' => sprintf(__("validator.invalid"), __('accesses')),


            'fullname.required' => sprintf(__("validator.required"), __('fullname')),
            'fullname.max' => sprintf(__("validator.max_len"), __('fullname'), 100, mb_strlen($this->fullname)),
            'fullname.min' => sprintf(__("validator.min_len"), 3, mb_strlen($this->fullname)),

            'phone.required' => sprintf(__("validator.required"), __('phone')),
            'phone.unique' => sprintf(__("validator.unique"), __('phone')),
            'phone.numeric' => sprintf(__("validator.numeric"), __('phone')),
            'phone_verify.required' => sprintf(__("validator.required"), __('phone_verify')),
            'phone_verify.exists' => sprintf(__("validator.invalid"), __('phone_verify')),
            'phone.exists' => __('user_phone_not_found'),


            'card.digits' => sprintf(__("validator.digits"), __('card'), 16),

            'img.required' => sprintf(__("validator.required"), __('image')),
            'img.base64_image_size' => sprintf(__("validator.max_size"), __("image"), Variable::SITE_IMAGE_LIMIT_MB),
            'img.base64_image_mime' => sprintf(__("validator.invalid_format"), __("image"), implode(",", Variable::SITE_ALLOWED_MIMES)),

            'password.required' => sprintf(__("validator.required"), __('password')),
            'password.regex' => sprintf(__("validator.password_regex"),),
            'password.confirmed' => sprintf(__("validator.password_confirmed"),),
            'new_password.required' => sprintf(__("validator.required"), __('new_password')),
            'new_password.min' => sprintf(__("validator.min_len"), 6, mb_strlen($this->new_password)),
            'new_password.regex' => sprintf(__("validator.password_regex"),),
            'new_password.confirmed' => sprintf(__("validator.password_confirmed"),),

        ];
    }
}
