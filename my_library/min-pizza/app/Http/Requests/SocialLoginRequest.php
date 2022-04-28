<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialLoginRequest extends FormRequest
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
            "name" => "required|string|min:1|max:100",
            "social_id" => "required|string",
            "email" => "string|min:1|max:200",
            "social_type" => "required|string|in:facebook,google,apple",
            'notification_token' => 'string|min:3|max:200',
            'default_lang' => 'required|string|in:en,sv',
        ];
    }
}
