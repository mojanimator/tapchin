<?php

namespace App\Http\Requests;

use App\Http\Helpers\Variable;
use App\Models\Admin;
use App\Models\Agency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
        $user = $this->user();
        $tmp = [];
        if ($user instanceof Admin)
            array_merge($tmp, [
                'agency_id' => ['required', Rule::in($user->allowedAgencies(Agency::find($user->agency_id))->pluck('id')),],
            ]);
//        if ($this->cmnd == 'status') {
//            $tmp = array_merge($tmp, [
//                'status' => ['required', Rule::in(collect(Variable::ORDER_STATUSES)->pluck('name'))],
//            ]);
//        }

        return $tmp;
    }
}
