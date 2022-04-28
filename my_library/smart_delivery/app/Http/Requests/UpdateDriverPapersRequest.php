<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverPapersRequest extends FormRequest
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
            'personal_photo'        => 'string|min:1',
            'license_photo'         => 'string|min:1',
            'vehicle_photo'         => 'string|min:1',
            'vehicle_license_photo' => 'string|min:1',
        ];
    }
}
