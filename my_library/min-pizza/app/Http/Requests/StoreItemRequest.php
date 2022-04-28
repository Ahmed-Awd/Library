<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            "name" => "required|string|min:2|max:120",
            "description" => "string",
            'image' => 'image|mimes:jpg,jpeg,png|max:3000',
            'alcohol_percentage' => 'numeric|min:0|max:99',
            'tax_id' => 'exists:taxes,id',
            'price' => 'required|numeric',
            'currency' => 'required|string|min:1|max:8',
            'option_template_id'   => 'nullable|exists:option_templates,id',
            'option_id' => 'nullable|array|exists:option_categories,id',
        ];
    }
}
