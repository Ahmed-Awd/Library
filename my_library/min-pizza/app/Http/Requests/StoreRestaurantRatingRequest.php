<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRestaurantRatingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }



    public function rules()
    {
        return [
            'rate'      => 'required|integer|between:0,6',
            'comment'     => 'nullable|string|min:5|max:200',
        ];
    }
}
