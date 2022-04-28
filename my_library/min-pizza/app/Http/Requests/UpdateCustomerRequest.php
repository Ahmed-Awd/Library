<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'string|min:2',
            'country_code' => 'min:2|numeric',
            'password'     => 'string|confirmed||min:6|max:25',
            'phone' => 'numeric|digits_between:9,9',
            'city_id' => 'exists:cities,id',
        ];
    }
}
