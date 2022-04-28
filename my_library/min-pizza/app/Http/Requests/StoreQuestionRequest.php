<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            "question" => "required|string|min:5,max:400",
            "answer"   => "required|string|min:3,max:400",
        ];
    }
}
