<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReorderRequest extends FormRequest
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
            'service_info_type'     => 'required|in:1,0',
            'address_id'   => 'nullable|exists:addresses,id,deleted_at,NULL',
            'user_id'   => 'nullable|exists:users,id',
            'discount_code_id' => 'nullable|exists:discount_codes,id',
            'payment_method'     => 'nullable',
            'phone'     => 'nullable',
            'scheduling_date'     => 'nullable',
            'scheduling_time'     => 'nullable',
        ];
    }
}
