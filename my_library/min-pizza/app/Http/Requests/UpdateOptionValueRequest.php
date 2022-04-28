<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOptionValueRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            "name" => "string|min:2|max:50",
            'price' => 'numeric|between:0.00,999.99',
            'min_count' => 'integer',
            'max_count' => 'integer',
            "status" => "nullable",
            'option_category_id' => 'exists:option_categories,id',
        ];
    }
}
