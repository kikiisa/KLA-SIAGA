<?php

namespace App\Http\Requests;

use App\Models\EvaluationPeriod;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEvaluationPeriodStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $period = $this->route('period');

        if (! $period instanceof EvaluationPeriod) {
            return false;
        }

        $permission = EvaluationPeriod::STATUS_PERMISSIONS[$this->input('status')] ?? null;

        if ($permission === null) {
            return false;
        }

        return (bool) $this->user()?->can($permission);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $period = $this->route('period');

        $allowed = $period instanceof EvaluationPeriod
            ? $period::STATUS_TRANSITIONS[$period->status] ?? []
            : [];

        return [
            'status' => ['required', 'string', Rule::in($allowed)],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status tujuan wajib dipilih.',
            'status.in' => 'Perubahan status periode tidak valid.',
        ];
    }
}
