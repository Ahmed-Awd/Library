<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountCodeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            "type" => "required|string|in:rate,fixed",
            'user_id'   => 'required|exists:users,id',
            'restaurant_id' => 'nullable|exists:restaurants,id',
            "amount" => "required|numeric|min:1",
            "max_usage" => "required|numeric|min:1|max:99",
            "min_order_price" => "required|numeric|min:1",
            "start_date" => "required|date",
            "end_date" => "required|date|after:start_date",
        ];
    }
}
