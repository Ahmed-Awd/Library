<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
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
            'owner_name'      => 'required|string|min:3|max:40',
            'owner_username'  => 'required|regex:/^[\w_]*$/|string|min:5|max:15|unique:users,username',
            'owner_email'     => 'required|email|unique:users,email',
            'default_delivery_time'     => 'nullable',
        ];
    }
}
