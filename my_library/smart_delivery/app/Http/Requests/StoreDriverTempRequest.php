<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverTempRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'nullable',
            'phone' => 'nullable',
            'country_code' => 'nullable',
            'personal_photo' => 'nullable',
            'license_photo' => 'nullable',
            'vehicle_photo' => 'nullable',
            'vehicle_license_photo' => 'nullable',
           ];
    }
}
