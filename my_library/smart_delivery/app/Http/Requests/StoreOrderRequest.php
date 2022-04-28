<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'distance'          => 'numeric|min:0',
            'location'          => 'required',
            'customer_address'  => 'required|string|max:240',
            'total_price'       => 'required|numeric',
            'customer_mobile'   => 'required|string|min:3|max:25',
            'expected_time'     => 'required|after:' . now(),
            'building_no'     => 'nullable',
            'apartment_no'     => 'nullable',
            'comment'       => 'nullable',
            'all_distances'     => 'nullable',
        ];
    }
}
