<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportOrdersRequest extends FormRequest
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
            'data.' => 'array',
            'data.*' => 'nullable|in:order_number,created_at,address,user,restaurant,driver,total_amount,status,code',
            'type' => 'nullable|in:pdf,excel',
            'range' => 'nullable',
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'service_info_type' => 'nullable|in:0,1',
        ];
    }
}
