<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignItemOptionsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'option_id' => 'required|exists:option_categories,id',
        ];
    }
}
