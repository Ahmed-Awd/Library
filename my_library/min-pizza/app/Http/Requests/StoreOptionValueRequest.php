<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOptionValueRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            "name" => "required|string|min:2|max:50",
            'price' => 'required|numeric|between:0.00,99999.99',
            'min_count' => 'integer',
            'max_count' => 'integer',
            "status" => "nullable",
            'option_category_id' => 'required|exists:option_categories,id',
        ];
    }
}
