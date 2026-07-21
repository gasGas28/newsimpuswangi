<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiabetesRequest extends FormRequest
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
            'gdp' => ['required', 'numeric'],
            'interpretasi_gdp' => ['required', 'string'],
            'gds' => ['required', 'numeric'],
            'interpretasi_gds' => ['required', 'string'],
            'hba1c' => ['required', 'numeric'],
            'interpretasi_hba1c' => ['required', 'string'],
            'gd2pp' => ['required', 'numeric'],
            'interpretasi_gd2pp' => ['required', 'string'],
        ];
    }
        public function messages(): array
    {
        return [
            'gdp.required' => 'Data Gula Darah Puasa wajib diisi.',
            'gds.required' => 'Data Gula Darah Sewaktu wajib diisi.',
            'hba1c.required' => 'Data hba1c wajib diisi.',
            'gd2pp.required' => 'Data Gula Dara 2 Jam Pospradial wajib diisi.',
        ];
    }
}
