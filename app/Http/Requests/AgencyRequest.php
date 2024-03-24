<?php

namespace App\Http\Requests;

use App\Models\Agency;
use App\Models\City;
use Illuminate\Validation\Rules\File;
use App\Http\Helpers\Variable;
use App\Models\Business;
use App\Models\Category;
use App\Models\County;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use stdClass;

class AgencyRequest extends FormRequest
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
        $regexLocation = "/^[-+]?[0-9]{1,7}(\\.[0-9]+)?,[-+]?[0-9]{1,7}(\\.[0-9]+)?$/";
        $editMode = (bool)$this->id;
        $request = $this;
        $tmp = [];
        if (!$this->cmnd) {
            $typeId = $this->type_id ?? -1;
            $provinceId = $this->province_id ?? -1;
            $availableParents = [];
            $user = $this->user();
            $agency = Agency::find($user->agency_id) ?? (object)['level' => count(Variable::AGENCY_TYPES), 'province_id' => -1, 'id' => 0];
            $availableTypes = collect(Variable::AGENCY_TYPES)->where('level', '>', $agency->level)->pluck('id');

            if ($request->type_id == 1) {
                $this->merge([
                    'parent_id' => 1,
                ]);
            }

            if ($agency->level == 0) {
                if ($this->type_id == 1)
                    $availableParents = [1];
                elseif ($this->type_id == 2)
                    $availableParents = Agency::where('level', '1')->whereJsonContains('access', $this->province_id)->pluck('id');
                elseif ($this->type_id == 3)
                    $availableParents = Agency::where('level', '2')->where('province_id', $this->province_id)->pluck('id');

            } elseif ($agency->level == 1) {
                if ($this->type_id == 2)
                    $availableParents = Agency::where('id', $agency->id)->whereJsonContains('access', $this->province_id)->pluck('id');
                elseif ($this->type_id == 3)
                    $availableParents = Agency::where('level', '2')->whereIn('province_id', $agency->access)->where('province_id', $this->province_id)->pluck('id');

            } elseif ($agency->level == 2) {
                if ($this->type_id == 3)
                    $availableParents = Agency::where('id', $agency->id)->where('province_id', $this->province_id)->pluck('id');
            }

            $tmp = array_merge($tmp, [
                'type_id' => ['required', Rule::in($availableTypes)],
                'name' => ['required', 'max:200'],
                'phone' => ['required', "unique:agencies,phone,$this->id", 'max:20'],
                'address' => ['required', 'max:2048'],
                'province_id' => ['required', Rule::in(City::where('level', 1)->pluck('id'))],
                'county_id' => ['required', Rule::in(City::where('level', 2)->pluck('id'))],
                'postal_code' => ['required', 'max:20'],
                'supported_provinces' => ['required_if:type_id,1'],
                'location' => ['required', "regex:$regexLocation",],
                'parent_id' => ['required', Rule::in($availableParents)],
            ]);
        }
        if ($this->uploading)
            $tmp = array_merge($tmp, [

            ]);
        if ($this->cmnd)
            $tmp = array_merge($tmp, [
            ]);
        return $tmp;
    }

    public function messages()
    {

        return [
            'type_id.required' => sprintf(__("validator.required"), __('agency_type')),
            'type_id.in' => sprintf(__("validator.invalid"), __('agency_type')),

            'parent_id.required' => sprintf(__("validator.required"), __('parent_agency')),
            'parent_id.in' => sprintf(__("validator.invalid"), __('parent_agency')),

            'name.required' => sprintf(__("validator.required"), __('name')),
            'name.max' => sprintf(__("validator.max_len"), __('name'), 200, mb_strlen($this->name)),

            'phone.required' => sprintf(__("validator.required"), __('phone')),
            'phone.max' => sprintf(__("validator.max_len"), __('phone'), 20, mb_strlen($this->phone)),
            'phone.unique' => sprintf(__("validator.unique"), __('phone')),

            'address.required' => sprintf(__("validator.required"), __('address')),
            'address.max' => sprintf(__("validator.max_len"), __('address'), 2048, mb_strlen($this->address)),

            'province_id.required' => sprintf(__("validator.required"), __('province')),
            'province_id.in' => sprintf(__("validator.invalid"), __('province')),

            'county_id.required' => sprintf(__("validator.required"), __('county')),
            'county_id.in' => sprintf(__("validator.invalid"), __('county')),

            'postal_code.required' => sprintf(__("validator.required"), __('postal_code')),
            'postal_code.max' => sprintf(__("validator.max_len"), __('postal_code'), 20, mb_strlen($this->postal_code)),

            'supported_provinces.required_if' => sprintf(__("validator.required"), __('supported_provinces')),

            'location.required' => sprintf(__("validator.required"), __('location')),
            'location.regex' => sprintf(__("validator.invalid"), __('location')),


        ];
    }
}
