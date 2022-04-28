<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignSettingRequest extends FormRequest
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
            'optional_courses_required'          => 'required|numeric|min:1|max:20',
            'max_optional_courses_per_semester'  => 'required|numeric|min:1|max:20',
            'allowed_failures'                   => 'required|numeric|min:1|max:20',
            'student_registration_of_subjects'   => 'required|boolean',
        ];
    }
}
