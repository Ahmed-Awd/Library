<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_name'     => 'nullable|string|min:3|max:25',
            'customer_address'  => 'nullable|string|max:240',
            'total_price'       => 'nullable|numeric',
            'customer_mobile'   => 'nullable|string|min:3|max:25',
            'expected_time'     => 'nullable',
            'building_no'     => 'nullable',
            'apartment_no'     => 'nullable',
            'comment'       => 'nullable',
        ];
    }
}
