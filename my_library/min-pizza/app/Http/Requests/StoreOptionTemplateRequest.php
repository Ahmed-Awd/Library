<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOptionTemplateRequest extends FormRequest
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
            'name'      => 'required|string|min:3|max:25',
            'primary_option_id'   => 'required|exists:option_categories,id',
            'option_secondaries' => 'array',
            'option_secondaries.*' => 'array',
            'option_secondaries.*.secondary_option_id' => 'required|exists:option_categories,id',
            'option_secondaries.*.primary_option_value_id' => 'required|exists:option_values,id',
            'option_secondaries.*.secondary_option_value_id' => 'required|exists:option_values,id',
            'option_secondaries.*.price' => 'nullable',
            'option_secondaries.*.use_default' => 'required',
        ];
    }
}
