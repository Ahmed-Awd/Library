<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreYearRequest extends FormRequest
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
            'academic_year_title'        => 'required|string',
            'academic_start_date'    => 'required|date',
            'academic_end_date'    => 'required|date',
            'show_in_list'    => 'required',
            'semesters'   => 'required|min:1',
            'semesters.*.sem_num'   => 'required|numeric',
            'semesters.*.sem_start_date'   => 'required|date_format:Y-m-d H:i:s',
            'semesters.*.sem_end_date'   => 'required|date_format:Y-m-d H:i:s'
        ];
    }
}
