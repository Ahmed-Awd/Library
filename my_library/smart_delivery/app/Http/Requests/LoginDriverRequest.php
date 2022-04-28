<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginDriverRequest extends FormRequest
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
            'phone'                   => 'required|min:3|max:16',
            'country_code'            => 'required',
            'password'                => 'required|string|min:3|max:100',
            'notification_token'      => 'string|min:3|max:200',
            'os_type'                 => 'in:android,ios',
            'language'                => 'string|in:en,ar,tr',
        ];
    }
}
