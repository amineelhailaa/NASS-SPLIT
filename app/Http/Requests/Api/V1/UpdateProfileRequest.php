<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'avatar' => 'sometimes|image|max:2028',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
