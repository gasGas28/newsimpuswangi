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
            'sistolik' => ['required', 'integer'],
            'diastolik' => ['required', 'integer'],
            'kategori_hipertensi' => ['required', 'string'],
            'suhu' => ['nullable', 'numeric'],
            'nadi' => ['nullable', 'integer'],
            'pernapasan' => ['nullable', 'integer'],

        ];
    }

    public function messages(): array
    {
        return [
            'sistolik.required' => 'Tanggal skrining wajib diisi.',
            'diastolik.required' => 'Dokter atau petugas wajib dipilih.',
            'suhu.required' => 'Fasyankes wajib diisi.',
            'nadi.required' => 'Jenis kunjungan wajib diisi.',
            'pernapasan.required' => 'Keluhan utama wajib diisi.',
        ];
    }

    public function attributes(): array
    {
        return [
            'sistolik' => 'dokter/petugas',
            'diastolik' => 'tanggal skrining',
            'suhu' => 'keluhan utama',
            'nadi' => 'keluhan utama',
            'pernapasan' => 'keluhan utama',
        ];
    }
}
