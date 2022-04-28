<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeItemOfferRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            "item_id" => "required|exists:items,id",
            "new_price" => "required|numeric",
            "start_date" => "required|date",
            "end_date" => "required|date|after:start_date",
            "rank" => "nullable|numeric|min:1|max:500",
        ];
    }
}
