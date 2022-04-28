<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOwnerInfo extends FormRequest
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
            'store_name'                => 'required|string|min:3|max:25',
            'lat'                       => 'required|numeric',
            'lng'                       => 'required|numeric',
            'type_id'                   => 'required|exists:types,id',
            'town_id'                   => 'required|exists:towns,id',
            'owner_name'                => 'required|string|min:3|max:40',
            'address'                   => 'required|string|min:3|max:220',
            'default_delivery_time'     => 'required|numeric|min:1|max:5000',
        ];
    }
}
