<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserAPIRequest extends APIRequest
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
            'name'                  => 'min:3|max:255',
            'email'                 => 'email|max:255|unique:users,id,'.$this->get('id'),
            'password'              => 'min:6',
            'password_confirmation' => 'required_with:password|same:password'
        ];
    }
}
