<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverRequest extends FormRequest
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
            'phone' => 'numeric|digits_between:9,9',
            'city_id' => 'exists:cities,id',
            'type' => 'in:app,restaurant',
            'restaurant_id' => 'nullable|exists:restaurants,id',
        ];
    }
}
