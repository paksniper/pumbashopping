<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidLoginForm extends FormRequest
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
            'admin_email' => 'required|email:rfc,dns|exists:admins,email',
            'admin_password' => 'required|min:5'
        ];
    }

    public function messages()
    {
        return [
            'admin_email.exists' => 'Provided email does not exist in system',
            'admin_password.exists' => 'Your password is incorrect'
        ];
    }
}
