<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRestaurantStatusRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'status_id'   => 'required|exists:restaurant_statuses,id',
            'closing_time'   => 'nullable|numeric|max:120',
        ];
    }
}
