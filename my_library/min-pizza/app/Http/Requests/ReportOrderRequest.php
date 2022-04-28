<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportOrderRequest extends FormRequest
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
            'restaurant_id'   => 'nullable|exists:restaurants,id,deleted_at,NULL',
            'service_info_type'     => ['nullable',Rule::in(1, 0)],
            'order_status_id' => 'nullable',
            'range' => 'nullable'
        ];
    }
}
