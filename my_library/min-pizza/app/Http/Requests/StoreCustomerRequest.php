<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'name' => 'required|string|min:2,max:50',
            'email' => 'required|email|unique:users,email',
            'profile_photo_path' => 'image|mimes:jpg,jpeg,png|max:3000',
            'password' => 'required|string|confirmed||min:6|max:25',
            'country_code' => 'required|min:1|numeric',
            'phone' => 'required|numeric|digits_between:9,9|unique:users',
            'city_id' => 'required|exists:cities,id',
        ];
    }
}
