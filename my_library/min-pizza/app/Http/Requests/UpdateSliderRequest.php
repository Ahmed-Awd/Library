<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            "title" => "string|min:3|max:50",
            "content" => "string|min:3|max:300",
            'image' => 'image|mimes:jpg,jpeg,png|max:3000',
            'is_active' => "boolean"
        ];
    }
}
