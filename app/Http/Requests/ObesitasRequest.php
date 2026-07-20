<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObesitasRequest extends FormRequest
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
            'berat_badan' => ['required', 'numeric'],
            'tinggi_badan' => ['required', 'numeric'],
            'imt' => ['required', 'numeric'],
            'interpretasi_imt' => ['required', 'string'],
            'lingkar_perut' => ['required', 'numeric'],
            'interpretasi_lp' => ['required', 'string'],
        ];
    }
        public function messages(): array
    {
        return [
            'berat_badan.required' => 'berat badan wajib diisi.',
            'tinggi_badan.required' => 'Tinggi badan wajib diidi.',
            'lingkar_perut.required' => 'Lingkar Perut wajib diisi.',
            'imt.required' => 'IMT tidak boleh kosong.',
            'interpretasi_lp.required' => 'Interpretasi Lingkar Perut tidak boleh kosong.',
        ];
    }
}
