<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRestaurantDistancesRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            "distances" => "required|array|min:2",
            "distances.*.up_to" => "required|numeric|min:1|max:500",
            "distances.*.price" => "required|numeric|min:1|max:5000",
        ];
    }
}
