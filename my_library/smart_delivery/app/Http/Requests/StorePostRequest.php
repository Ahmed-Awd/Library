<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'translations' => 'array',
            'translations.*' => 'array',
            'translations.*.title' => 'required|string|max:60',
            'translations.*.content' => 'required'
        ];
    }
}
