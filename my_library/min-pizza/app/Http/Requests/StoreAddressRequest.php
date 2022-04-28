<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {

        return [
            'title' => 'required|string|min:3|max:100',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'type' => 'required|string|in:home,work,other',
            'area' => 'nullable|string',
            'description' => 'nullable|string',
            'Apartment' => 'nullable',
            'ZIP_code' => 'nullable|string|min:3|max:15',
        ];
    }
}
