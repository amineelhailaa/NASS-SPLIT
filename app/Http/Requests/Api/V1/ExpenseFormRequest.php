<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'category_id' => 'nullable|integer|exists:categories,id',
            'date' => 'required|date',
            'payer_id' => 'required|exists:memberships,id',
            'split_strategy' => 'required|in:equal,percentage,fixed',
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:5120'], // images or what ever
            'participants' => ['required', 'array', 'min:1'],
            'participants.*' => ['required', 'array'],
            'participants.*.membership_id' => ['required', 'integer', 'distinct', 'exists:memberships,id'],
            'participants.*.amount' => ['required_if:split_strategy,fixed', 'numeric', 'gt:0'],
            'participants.*.percentage' => ['required_if:split_strategy,percentage', 'numeric', 'gt:0', 'lte:100'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $strategy = $this->input('split_strategy');
            $participants = $this->input('participants', []);
            $amount = $this->input('amount');

            if (! is_array($participants) || ! is_numeric($amount)) {
                return;
            }

            if ($strategy === 'fixed') {
                $fixedTotalCents = 0;
                foreach ($participants as $participant) {
                    if (! isset($participant['amount']) || ! is_numeric($participant['amount'])) {
                        return;
                    }
                    $fixedTotalCents += $this->toCents($participant['amount']);
                }

                if ($fixedTotalCents !== $this->toCents($amount)) {
                    $validator->errors()->add(
                        'participants',
                        'For fixed split, the sum of participant amounts must equal the total amount.'
                    );
                }
            }

            if ($strategy === 'percentage') {
                $percentageTotalBasisPoints = 0;
                foreach ($participants as $participant) {
                    if (! isset($participant['percentage']) || ! is_numeric($participant['percentage'])) {
                        return;
                    }
                    $percentageTotalBasisPoints += $this->toPourcentageCent($participant['percentage']);
                }

                if ($percentageTotalBasisPoints !== 10000) {
                    $validator->errors()->add(
                        'participants',
                        'For percentage split, participant percentages must add up to 100.'
                    );
                }
            }
        });
    }

    private function toCents($value): int
    {
        return round(((float) $value) * 100);
    }

    private function toPourcentageCent($value): int
    {
        return round(((float) $value) * 100);
    }
}
