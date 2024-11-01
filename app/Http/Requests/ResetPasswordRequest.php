<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
            'otp' => 'required|integer',
        ];
    }

    public function authorize()
    {
        return true; // Allow all users to make this request
    }
}
