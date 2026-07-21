<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusPasienRequest extends FormRequest
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
            'kondisi_keluar' => ['required', 'string'],
            'cara_keluar' => ['required', 'string'],
            'jadwal_kontrol' => ['required', 'date'],
            'rencana_rujuk' => ['required', 'string'],
            'transport' => ['required', 'string'],
        ];
    }
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'date' => ':attribute harus berupa tanggal yang valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'skriningId' => 'Data skrining',
            'kondisi_keluar' => 'Kondisi keluar',
            'cara_keluar' => 'Cara keluar',
            'jadwal_kontrol' => 'Jadwal kontrol',
            'rencana_rujuk' => 'Rencana rujuk',
            'transport' => 'Transport',
        ];
    }
}
