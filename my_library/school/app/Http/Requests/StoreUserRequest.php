<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name'        => 'required|string|min:3|max:40',
            'username'    => 'required|string|unique:users|min:6|max:25',
            'id_number'   => 'required|numeric|min:8|max:15|unique:users|',
            'email'       => 'required|email|unique:users|',
            'password'    => 'required|string|confirmed|min:6|max:25',
            'category_id' => 'required|exists:categories,id',
            'role'        => 'required|exists:roles,name',
//            'language_id' => 'required|exists:language,id',
            'branch_id'   => 'required|exists:branch,id',
            'phone'       => 'string',
            'nationality' => 'string',
            'address'     => 'string',
            'image'       => 'image|mimes:jpg,jpeg,png|max:3000'
        ];
    }
}
