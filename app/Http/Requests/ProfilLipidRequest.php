<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilLipidRequest extends FormRequest
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
            'kolesterol_total' => ['required', 'numeric'],
            'interpretasi_kolesterol_total' => ['required', 'string'],
            'ldl' => ['required', 'numeric'],
            'interpretasi_ldl' => ['required', 'string'],
            'hdl' => ['required', 'numeric'],
            'interpretasi_hdl' => ['required', 'string'],
            'trigliserida' => ['required', 'numeric'],
            'interpretasi_trigliserida' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'string' => ':attribute harus berupa teks.',
        ];
    }

    public function attributes(): array
    {
        return [
            'skriningId' => 'Data skrining',
            'kolesterol_total' => 'Kolesterol total',
            'interpretasi_kolesterol_total' => 'Interpretasi kolesterol total',
            'ldl' => 'Nilai LDL',
            'interpretasi_ldl' => 'Interpretasi LDL',
            'hdl' => 'Nilai HDL',
            'interpretasi_hdl' => 'Interpretasi HDL',
            'trigliserida' => 'Nilai trigliserida',
            'interpretasi_trigliserida' => 'Interpretasi trigliserida',
        ];
    }
}
