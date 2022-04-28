<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountCodeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'restaurant_id' => 'nullable|exists:restaurants,id',
            "amount" => "numeric|min:1",
            "max_usage" => "numeric|min:1|max:99",
            "start_date" => "date",
            "end_date" => "date|after:start_date",
            "min_order_price" => "numeric|min:1",
        ];
    }
}
