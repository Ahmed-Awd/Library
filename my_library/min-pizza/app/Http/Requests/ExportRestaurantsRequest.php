<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportRestaurantsRequest extends FormRequest
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
            'data.*' => 'nullable|in:id,name,owner_name,ratings_avg_rate,orders_count,orders_sum_total_amount',
            'type' => 'nullable|in:pdf,excel'
        ];
    }
}
