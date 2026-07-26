<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HipertensiRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'skriningId' => ['required'],
            'sistolik' => ['required', 'integer', 'min:60', 'max:600'],
            'diastolik' => ['required', 'integer', 'min:40', 'max:200'],
            'kategori_hipertensi' => ['nullable', 'string'],
            'suhu' => ['nullable', 'numeric', 'min:25', 'max:45'],
            'nadi' => ['nullable', 'integer', 'min:20', 'max:300'],
            'pernapasan' => ['nullable', 'integer', 'min:5', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'sistolik.required' => 'Tekanan sistolik wajib diisi.',
            'sistolik.integer' => 'Tekanan sistolik harus berupa angka.',
            'sistolik.min' => 'Tekanan sistolik minimal 60 mmHg.',
            'sistolik.max' => 'Tekanan sistolik maksimal 300 mmHg.',

            'diastolik.required' => 'Tekanan diastolik wajib diisi.',
            'diastolik.integer' => 'Tekanan diastolik harus berupa angka.',
            'diastolik.min' => 'Tekanan diastolik minimal 40 mmHg.',
            'diastolik.max' => 'Tekanan diastolik maksimal 200 mmHg.',

            'kategori_hipertensi.string' => 'Kategori hipertensi tidak valid.',

            'suhu.numeric' => 'Suhu tubuh harus berupa angka.',

            'nadi.integer' => 'Nadi harus berupa angka.',
            'nadi.min' => 'Nadi minimal 20 kali/menit.',
            'nadi.max' => 'Nadi maksimal 300 kali/menit.',

            'pernapasan.integer' => 'Frekuensi pernapasan harus berupa angka.',
            'pernapasan.min' => 'Frekuensi pernapasan minimal 5 kali/menit.',
            'pernapasan.max' => 'Frekuensi pernapasan maksimal 100 kali/menit.',
        ];
    }
}
