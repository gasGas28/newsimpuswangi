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
            'kolesterol_total' => ['required', 'numeric', 'max:1000'],
            'interpretasi_kolesterol_total' => ['required', 'string'],
            'ldl' => ['required', 'numeric', 'max:1000'],
            'interpretasi_ldl' => ['required', 'string'],
            'hdl' => ['required', 'numeric', 'max:1000'],
            'interpretasi_hdl' => ['required', 'string'],
            'trigliserida' => ['required', 'numeric', 'max:1000'],
            'interpretasi_trigliserida' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kolesterol_total.required' => 'Kolesterol total wajib diisi.',
            'kolesterol_total.numeric' => 'Kolesterol total harus berupa angka.',
            'kolesterol_total.max' => 'Kolesterol total maksimal 1000 mg/dL.',

            'interpretasi_kolesterol_total.required' => 'Interpretasi kolesterol total wajib diisi.',
            'interpretasi_kolesterol_total.string' => 'Interpretasi kolesterol total tidak valid.',

            'ldl.required' => 'Kolesterol LDL wajib diisi.',
            'ldl.numeric' => 'Kolesterol LDL harus berupa angka.',
            'ldl.max' => 'Kolesterol LDL maksimal 1000 mg/dL.',

            'interpretasi_ldl.required' => 'Interpretasi LDL wajib diisi.',
            'interpretasi_ldl.string' => 'Interpretasi LDL tidak valid.',

            'hdl.required' => 'Kolesterol HDL wajib diisi.',
            'hdl.numeric' => 'Kolesterol HDL harus berupa angka.',
            'hdl.max' => 'Kolesterol HDL maksimal 1000 mg/dL.',

            'interpretasi_hdl.required' => 'Interpretasi HDL wajib diisi.',
            'interpretasi_hdl.string' => 'Interpretasi HDL tidak valid.',

            'trigliserida.required' => 'Trigliserida wajib diisi.',
            'trigliserida.numeric' => 'Trigliserida harus berupa angka.',
            'trigliserida.max' => 'Trigliserida maksimal 1000 mg/dL.',

            'interpretasi_trigliserida.required' => 'Interpretasi trigliserida wajib diisi.',
            'interpretasi_trigliserida.string' => 'Interpretasi trigliserida tidak valid.',
        ];
    }
}
