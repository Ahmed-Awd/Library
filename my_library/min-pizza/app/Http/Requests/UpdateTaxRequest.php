<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'name'         => 'string|min:3|max:200',
            'percentage'   => 'numeric|min:1|max:99',
        ];
    }
}
