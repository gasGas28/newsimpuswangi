<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PemeriksaanKolorektalRequest extends FormRequest
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
            'skriningId' => ['required', 'string'],
            'kkr1' => ['required', 'string'],
            'kkr2' => ['required', 'string'],
            'hasil_kkr' => ['required', 'string'],
            'colok_dubur' => ['required', 'string'],
            'darah_samar' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
        ];
    }

    public function attributes(): array
    {
        return [
            'skriningId' => 'Data skrining',
            'kkr1' => 'Hasil KKR 1',
            'kkr2' => 'Hasil KKR 2',
            'hasil_kkr' => 'Hasil KKR',
            'colok_dubur' => 'Hasil pemeriksaan colok dubur',
            'darah_samar' => 'Hasil pemeriksaan darah samar',
        ];
    }
}
