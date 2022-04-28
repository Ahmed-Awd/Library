<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecialNotificationRequest extends FormRequest
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
            "users" => 'required|array|min:1',
            "users.*" => 'required|exists:users,id',
            "subject" => 'required|string|min:3|max:50',
            "body" => 'required|string|min:5|max:250',
        ];
    }
}
