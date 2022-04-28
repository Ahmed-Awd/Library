<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopicRequest extends FormRequest
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
            'name'         => 'required|string|min:3|max:200',
            'description'  => 'required|string',
            'multi_branch' => 'required|boolean',
            'parent_id'   => 'exists:topics,id',
            'subject_id'   => 'exists:subjects,id',
        ];
    }
}
