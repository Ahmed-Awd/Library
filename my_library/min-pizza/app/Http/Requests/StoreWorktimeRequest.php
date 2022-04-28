<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorktimeRequest extends FormRequest
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
            'Worktimes' => 'required|array|min:1',
            'Worktimes.*.day_id' => 'required|exists:days,id',
            'Worktimes.*.open_from' => 'required',
            'Worktimes.*.open_to' => 'required',
            'Worktimes.*.takeaway' => 'required|boolean',
            'Worktimes.*.delivery' => 'required|boolean',
            'Worktimes.*.status' => 'required|boolean',

        ];
    }
}
