<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'username'     => 'string|min:6|max:25|unique:users,username,'.$this->user,
            'id_number'    => 'numeric|min:8|max:15|unique:users|',
            'email'        => 'email|unique:users|',
            'category_id'  => 'exists:categories,id',
            //'language_id' => 'required|exists:language,id',
            'branch_id'    => 'exists:branch,id',
            'phone'        => 'string',
            'address'      => 'string',
            'password'     => 'string|confirmed|min:6|max:25',
            'nationality'  => 'string',
            'image'        => 'image|mimes:jpg,jpeg,png|max:3000'
        ];
    }
}
