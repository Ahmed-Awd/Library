<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

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
            'address_id'   => 'nullable|exists:addresses,id,deleted_at,NULL',
            'restaurant_id'   => 'required|exists:restaurants,id,deleted_at,NULL',
            'user_id'   => 'nullable|exists:users,id',
            'order_type'     => 'nullable',
            'scheduling_date'     => 'nullable',
            'scheduling_time'     => 'nullable',
            'payment_method'     => 'nullable',
            'phone'     => 'nullable',
            'platform'     => 'nullable',
            'service_info_type'     => 'required',
            'discount_code_id' => 'nullable|exists:discount_codes,id',
            'order_items' => 'array|required',
            'order_items.*' => 'array',
            'order_items.*.item_id' => 'required|exists:items,id,deleted_at,NULL',
            'order_items.*.quantity' => 'required',
            'order_items.*.note' => 'nullable',
            'order_items.*.primary_option_value_id' => 'nullable|exists:option_values,id,deleted_at,NULL',
            'order_items.*.primary_option_quantity' => 'nullable',
            'order_items.*.template_selected_options' => 'nullable',
            'order_items.*.template_selected_options.*.option_secondary_id' => 'nullable|exists:option_secondaries,id',
            'order_items.*.selected_options' => 'nullable',
            'order_items.*.selected_options.*.option_vlaue_id' => 'nullable|exists:option_values,id,deleted_at,NULL',
        ];
    }

    public function messages()
    {
        return [
            'order_items.*.selected_options.*.option_vlaue_id.exists' => Lang::get('messages.not_available'),
            'order_items.*.template_selected_options.*.option_secondary_id.exists'
            => Lang::get('messages.not_available'),

        ];
    }
}
