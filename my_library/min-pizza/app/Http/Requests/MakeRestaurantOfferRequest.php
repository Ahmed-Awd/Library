<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeRestaurantOfferRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            "restaurant_id" => "required|exists:restaurants,id",
            "rate" => "required|numeric|min:1,max:99",
            "start_date" => "required|date",
            "end_date" => "required|date|after:start_date",
            "rank" => "nullable|numeric|min:1|max:500",
        ];
    }
}
