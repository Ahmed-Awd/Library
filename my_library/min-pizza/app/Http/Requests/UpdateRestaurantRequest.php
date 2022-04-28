<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRestaurantRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        $bypass = $this->restaurant->owner->id ?? "";
        return [
            'restaurant_name'      => 'nullable|string|min:3|max:25',
            'lat'        => 'numeric',
            'lng'        => 'numeric',
            'prepare_order_time'        => 'numeric',
            'address'         => 'nullable|string|max:240',
            'status_id'   => 'nullable|exists:restaurant_statuses,id',
            'city_id'   => 'nullable|exists:cities,id',
            'owner_name'      => 'nullable|string|min:3|max:40',
            'owner_email' => "required|email|unique:users,email,{$bypass}",
            'delivery_type'     => ['nullable',Rule::in(['percent', 'value','free','per_kilometer'])],
            'delivery_value'     => 'nullable',
            'password'     => 'string|confirmed||min:6|max:25',
            'delivery_kilometer'     => 'nullable',
            'min_order_price'     => 'nullable',
            'logo' => 'image|mimes:jpg,jpeg,png|max:3000',
            'image' => 'image|mimes:jpg,jpeg,png|max:3000',
            'company_name'     => 'nullable|string|min:3',
            'company_number'     => 'nullable|string',
            'ZIP_code'     => 'nullable',
            'vat'     => 'nullable',
            'country_code' => 'numeric',
            'phone' => [
                Rule::unique('users')
                       ->ignore($this->updated_user_id)
                       ->where('country_code', $this->country_code)
               ],
            'categories.*' => 'nullable|exists:categories,id',
            'phones' => 'nullable',
            'scheduling_order' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
