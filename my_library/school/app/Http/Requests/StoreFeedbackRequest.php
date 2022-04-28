<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
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
            'title'        => 'required|string|min:3|max:50',
            'description'  => 'required|string',
            'subject'      => 'required|string|min:5|max:100',
            'files'        => 'required|array|min:0',
            'files.*'      => 'required|mimes:jpg,jpeg,png,pdf|max:5000'
        ];
    }
}
