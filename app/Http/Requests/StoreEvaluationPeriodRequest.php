<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEvaluationPeriodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('period.create') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('evaluation_periods', 'code')],
            'name' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
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
            'code.required' => 'Kode periode wajib diisi.',
            'code.max' => 'Kode periode maksimal 50 karakter.',
            'code.unique' => 'Kode periode sudah digunakan.',
            'name.required' => 'Nama periode wajib diisi.',
            'name.max' => 'Nama periode maksimal 255 karakter.',
            'year.required' => 'Tahun evaluasi wajib diisi.',
            'year.integer' => 'Tahun evaluasi harus berupa angka.',
            'year.min' => 'Tahun evaluasi minimal :min.',
            'year.max' => 'Tahun evaluasi maksimal :max.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Tanggal mulai tidak valid.',
            'end_date.required' => 'Batas pengumpulan wajib diisi.',
            'end_date.date' => 'Batas pengumpulan tidak valid.',
            'end_date.after_or_equal' => 'Batas pengumpulan tidak boleh sebelum tanggal mulai.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => trim((string) $this->input('code')),
            'year' => (int) $this->input('year'),
        ]);
    }
}
