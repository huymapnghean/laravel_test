<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    public function messages() {
        return [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email phải có định dạng hợp lệ (bao gồm dấu @).',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
        ];
    }
}
