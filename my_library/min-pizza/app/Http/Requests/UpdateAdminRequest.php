<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'string|min:3|max:50',
            'profile_photo_path' => 'nullable|mimes:jpg,jpeg,png|max:3000',
            'is_disabled' => 'nullable|boolean',
            'password' => 'nullable|string|confirmed||min:6|max:25',
            'country_code' => 'min:2|numeric',
            'phone' => 'min:4|numeric',
            'city_id' => 'exists:cities,id',
        ];
    }
}
