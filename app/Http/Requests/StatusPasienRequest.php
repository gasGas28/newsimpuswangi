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
}
