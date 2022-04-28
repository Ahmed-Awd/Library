<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'name'         => 'required|string|min:3|max:200',
            'percentage'   => 'required|numeric|min:1|max:99',
        ];
    }
}
