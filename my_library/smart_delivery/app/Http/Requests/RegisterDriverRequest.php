<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterDriverRequest extends FormRequest
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
            'name'              => 'required|string|min:3|max:40',
            'username'          => 'required|string|min:5|max:15|unique:users,username',
            'password'          => 'required|string|confirmed|min:6|max:25',
            'town'              => 'required|exists:towns,id',
            'personal_photo'        => 'required|string',
            'license_photo'         => 'required|string',
            'vehicle_photo'         => 'required|string',
            'vehicle_license_photo' => 'required|string',
            'country_code' => 'required',
            'phone' => [
                'required',
                Rule::unique('users')
                       ->where('country_code', $this->country_code)
               ],
        ];
    }
}
