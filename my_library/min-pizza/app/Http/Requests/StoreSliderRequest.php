<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSliderRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            "title" => "required|string|min:3|max:50",
            "content" => "required|string|min:3|max:300",
            'image' => 'image|mimes:jpg,jpeg,png|max:3000',
        ];
    }
}
