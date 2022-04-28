<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'string|min:3|max:100',
            'lat' => 'numeric',
            'lng' => 'numeric',
            'type' => 'string|in:home,work,other',
            'area' => 'nullable|string',
            'description' => 'nullable|string',
            'Apartment' => 'nullable',
            'ZIP_code' => 'nullable|string|min:3|max:15',
        ];
    }
}
