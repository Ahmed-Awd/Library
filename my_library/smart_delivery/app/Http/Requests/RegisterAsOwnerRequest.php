<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterAsOwnerRequest extends FormRequest
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
            'store_name'      => 'required|string|min:3|max:25',
            'location'        => 'required',
            'address'         => 'required|string|max:240',
            'activity_type'   => 'required|exists:types,id',
            'town'            => 'required|exists:towns,id',
            'password'          => 'required|string|confirmed|min:6|max:25',
            'owner_name'      => 'required|string|min:3|max:40',
            'owner_username'  => 'required|string|min:5|max:15|unique:users,username',
            'owner_email'     => 'required|email|unique:users,email',
            'default_delivery_time'     => 'nullable',
            'country_code' => 'required',
            'phone' => [
                'required',
                Rule::unique('users')
                    ->where('country_code', $this->country_code)
            ],
        ];
    }
}
