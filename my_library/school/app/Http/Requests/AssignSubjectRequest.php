<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignSubjectRequest extends FormRequest
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
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'required|exists:subjects,id',
            'is_mandatory' => 'required|boolean',
            'semester' => 'required|exists:academics_semesters,id'
        ];
    }
}
