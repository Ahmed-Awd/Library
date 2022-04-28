<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectConfig extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'is_published'=> 'required',
            'total'=> 'required|numeric',
            'passing_grade'=> 'required|numeric',
            'columns'=> 'nullable|array',
            'columns.*.name'=> 'required_with:columns|string',
            'columns.*.score'=> 'required_with:columns|numeric',
        ];
    }
}
