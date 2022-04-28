<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRestaurantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'restaurant_name'      => 'required|string|min:3|max:25',
            'lat'        => 'required|numeric',
            'lng'        => 'required|numeric',
            'address'         => 'required|string|max:240',
            'city_id'   => 'required|exists:cities,id',
            'status_id'   => 'required|exists:restaurant_statuses,id',
            'owner_name'      => 'required|string|min:3|max:40',
            'owner_email'     => 'required|email|unique:users,email',
            'password'     => 'required|string|confirmed||min:6|max:25',
            'delivery_type'     => ['required',Rule::in(['percent', 'value','free','per_kilometer'])],
            'delivery_value'     => 'nullable',
            'delivery_kilometer'     => 'nullable',
            'scheduling_order'     => ['nullable',Rule::in([false,true,0,1])],
            'min_order_price'     => 'nullable',
            'logo' => 'image|mimes:jpg,jpeg,png|max:3000',
            'image' => 'image|mimes:jpg,jpeg,png|max:3000',
            'company_name'     => 'nullable|string|min:3',
            'company_number'     => 'nullable|string',
            'ZIP_code'     => 'nullable',
            'prepare_order_time'     => 'required|numeric|min:1,max:120',
            'vat'     => 'nullable',
            'country_code' => 'required|numeric',
            'phone' => [
                'required',
                Rule::unique('users')
                       ->where('country_code', $this->country_code)
               ],
            'categories.*' => 'required|exists:categories,id',
            'phones' => 'nullable|array',
            'is_active' => 'required|boolean',
        ];
    }
}
