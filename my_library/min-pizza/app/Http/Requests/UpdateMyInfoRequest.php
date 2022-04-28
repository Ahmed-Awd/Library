<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMyInfoRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'string|min:3|max:50',
            'country_code' => 'min:2|numeric',
            'phone' => 'min:4|numeric',
            'city_id' => 'exists:cities,id',
            'profile_photo_path' => 'image|mimes:jpg,jpeg,png|max:3000',
            'default_lang' => 'string|in:en,sv'
        ];
    }
}
