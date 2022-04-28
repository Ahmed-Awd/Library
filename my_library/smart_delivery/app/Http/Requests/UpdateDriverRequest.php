<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
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
            'name'         => 'string|min:3|max:40',
            'username'     => 'string|min:6|max:25|unique:users,username,' . $this->updated_user_id,
            'country_code' => 'digits_between:1,5',
            'driver_type' => 'nullable|in:app,freelancer',
            'personal_photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2000',
            'license_photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2000',
            'vehicle_photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2000',
            'vehicle_license_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2000',
            'phone' => [
                'digits_between:5,15',
                Rule::unique('users')
                       ->ignore($this->updated_user_id)
                       ->where('country_code', $this->country_code)
               ],
        ];
    }
}
