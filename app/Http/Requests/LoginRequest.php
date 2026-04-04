<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function remember(): bool
    {
        return $this->boolean('remember');
    }
}
