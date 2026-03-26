<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'category_id' => 'nullable|', //exists:categories,id adding it after
            'date' => 'required|date',
            'payer_id' => 'required|exists:memberships,id',
            'split_strategy' => 'required|in:equal,percentage,fixed',
            'participants' => ['required', 'array', 'min:1'],
            'participants.*' => ['required', 'array'],
            'participants.*.membership_id' => ['required', 'integer','exists:memberships,id'],
            'participants.*.amount' => ['required_if:split_strategy,fixed', 'numeric', 'gt:0'],
            'participants.*.percentage' => ['required_if:split_strategy,percentage', 'numeric', 'gt:0', 'lte:100'],
        ];
    }
}
