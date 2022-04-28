<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'store_name'      => 'required|string|min:3|max:25',
            'location'        => 'required',
            'address'         => 'required|string|max:240',
            'activity_type'   => 'required|exists:types,id',
            'town'            => 'required|exists:towns,id',
            'owner_name'      => 'required|string|min:3|max:40',
            'owner_username'  => 'required|string|min:5|max:12|unique:users,username,' . $this->updated_user_id,
            'owner_email'     => 'required|email|unique:users,email,' . $this->updated_user_id,
            'default_delivery_time'     => 'nullable',
        ];
    }
}
