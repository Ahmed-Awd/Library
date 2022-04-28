<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOutSourceRequest extends FormRequest
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
            'name'         => 'required|string|min:3|max:40',
            'username'     => 'required|regex:/^[\w_]*$/|string|min:5|max:15|unique:users,username,',
            'email'        => 'required|email|unique:users,email',
            'country_code' => 'required',
            'phone' => [
                'required',
                Rule::unique('users')
                       ->where('country_code', $this->country_code)
               ],
        ];
    }
}
