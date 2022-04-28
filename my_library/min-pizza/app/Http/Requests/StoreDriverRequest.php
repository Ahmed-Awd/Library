<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDriverRequest extends FormRequest
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

        $rules = [
            'name' => 'required|string|min:2|max:70',
            'email' => 'required|email|unique:users',
            'profile_photo_path' => 'nullable|mimes:jpg,jpeg,png|max:3000',
            'is_disabled' => 'nullable|boolean',
            'password' => 'required|string|confirmed||min:6|max:25',
            'country_code' => 'required|min:1|numeric',
            'phone' => 'required|digits_between:9,9|numeric|unique:users',
            'city_id' => 'required|exists:cities,id',
            'type' => 'required|in:app,restaurant',
            'restaurant_id' => 'nullable|exists:restaurants,id'
        ];
        return $rules;
    }
}
