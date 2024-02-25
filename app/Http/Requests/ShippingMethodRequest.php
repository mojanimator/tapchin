<?php

namespace App\Http\Requests;

use App\Models\Agency;
use App\Models\City;
use App\Models\Pack;
use App\Models\Variation;
use App\Models\Repository;
use App\Models\ShippingMethod;
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

class ShippingMethodRequest extends FormRequest
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
        $editMode = (bool)$this->id;
        $data = null;
        $request = $this;
        $tmp = [];
        $admin = $this->user();
        $myAgency = Agency::find($admin->agency_id);
        $allowedRepositories = Repository::whereIntegerInRaw('agency_id', $admin->allowedAgencies($myAgency)->pluck('id'))->pluck('id')->toArray();
        if ($editMode) {
            $data = ShippingMethod::find($this->id);
            $data = in_array(optional($data)->repo_id, $allowedRepositories) ? $data : null;
            $this->merge(["data" => $data]);

        }

        if (!$this->cmnd) {
            $repoId = $this->repo_id;
            $repo = Repository::find($repoId);
            $allowedCities = optional($repo)->cities ?? [];
            $allowedProducts = Variation::where('repo_id', $repoId)->whereNotNull('repo_id')->select('id', 'name', 'pack_id', 'grade', 'weight')->get();
            $this->name = $this->name ?? __('shipping') . '/' . optional($repo)->name;
            $this->merge(["agency_id" => optional($repo)->agency_id]);

            $tmp = array_merge($tmp, [
                'data' => [Rule::requiredIf($editMode),],
                'repo_id' => ['required', Rule::in($allowedRepositories)],
                'base_price' => ['required', 'integer', 'min:0'],
                'per_weight_price' => ['required', 'integer', 'min:0'],
                'min_order_weight' => ['required', 'integer', 'min:0'],
                'cities' => ['nullable', $repoId ? function ($attribute, $value, $fail) use ($allowedCities) {

                    if (array_diff($value, $allowedCities))
                        return $fail(sprintf(__("validator.allowed_repo_districts"), implode('/ ', City::whereIn('id', $allowedCities)->pluck('name')->toArray())));

                } : ''],
                'products' => ['nullable', $repoId ? function ($attribute, $value, $fail) use ($allowedProducts) {

                    if (array_diff($value, $allowedProducts->pluck('id')->toArray())) {
                        $packs = Pack::select('id', 'name')->get();
                        return $fail(sprintf(__("validator.allowed_repo_products"), implode('/ ', $allowedProducts->map(function ($e) use ($packs) {
                            return $e->name . '-' . optional($packs->where('id', $e->pack_id)->first())->name;
                        })->toArray())));
                    }
                } : ''],
                'name' => ['required', 'max:200'],
                'description' => ['nullable', 'max:2048'],
            ]);
        }
        if ($this->uploading)
            $tmp = array_merge($tmp, [

            ]);
        if ($this->cmnd)
            $tmp = array_merge($tmp, [
                'data' => [Rule::requiredIf($editMode),],
            ]);
        return $tmp;
    }

    public function messages()
    {

        return [
            'data.required' => __("access_denied"),

            'repo_id.required' => sprintf(__("validator.required"), __('repository')),
            'repo_id.in' => sprintf(__("validator.invalid"), __('repository')),


            'name.required' => sprintf(__("validator.required"), __('name')),
            'name.max' => sprintf(__("validator.max_len"), __('name'), 200, mb_strlen($this->name)),

            'base_price.required' => sprintf(__("validator.required"), __('base_price')),
            'base_price.min' => sprintf(__("validator.min"), __('base_price'), 0),

            'per_weight_price.required' => sprintf(__("validator.required"), __('per_weight_price')),
            'per_weight_price.min' => sprintf(__("validator.min"), __('per_weight_price'), 0),

            'min_order_weight.required' => sprintf(__("validator.required"), __('min_order_weight')),
            'min_order_weight.min' => sprintf(__("validator.min"), __('min_order_weight'), 0),

            'description.required' => sprintf(__("validator.required"), __('description')),
            'description.max' => sprintf(__("validator.max_len"), __('description'), 2048, mb_strlen($this->description)),


        ];
    }
}
