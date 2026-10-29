<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreOpdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('opd.create') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_opd' => ['required', 'string', 'max:30', Rule::unique('opds', 'kode_opd')],
            'nama_opd' => ['required', 'string', 'max:255'],
            'singkatan' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string'],
            'alamat' => ['nullable', 'string'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'is_active' => ['boolean'],
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
            'kode_opd.required' => 'Kode OPD wajib diisi.',
            'kode_opd.max' => 'Kode OPD maksimal 30 karakter.',
            'kode_opd.unique' => 'Kode OPD sudah digunakan.',
            'nama_opd.required' => 'Nama OPD wajib diisi.',
            'nama_opd.max' => 'Nama OPD maksimal 255 karakter.',
            'singkatan.max' => 'Singkatan maksimal 50 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'telepon.max' => 'Telepon maksimal 30 karakter.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'kode_opd' => trim((string) $this->input('kode_opd')),
            'email' => $this->filled('email') ? Str::lower((string) $this->input('email')) : null,
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
