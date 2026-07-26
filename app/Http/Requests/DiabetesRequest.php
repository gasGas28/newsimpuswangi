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
            'gdp' => ['required', 'numeric', 'min:40', 'max:650'],
            'interpretasi_gdp' => ['required', 'string'],
            'gds' => ['required', 'numeric', 'min:40', 'max:650'],
            'interpretasi_gds' => ['required', 'string'],
            'hba1c' => ['required', 'numeric', 'max:15'],
            'interpretasi_hba1c' => ['required', 'string'],
            'gd2pp' => ['required', 'numeric', 'min:40', 'max:650'],
            'interpretasi_gd2pp' => ['required', 'string'],
        ];
    }
    public function messages(): array
    {
        return [
            'gdp.required' => 'Data Gula Darah Puasa wajib diisi.',
            'gdp.min' => 'GDP minimal 40 mg/dL.',
            'gdp.max' => 'GDP maksimal 650 mg/dL.',

            'gds.required' => 'Data Gula Darah Sewaktu wajib diisi.',
            'gds.min' => 'GDS minimal 40 mg/dL.',
            'gds.max' => 'GDS maksimal 650 mg/dL.',

            'hba1c.required' => 'Data HbA1c wajib diisi.',
            'hba1c.min' => 'HbA1c minimal 3%.',
            'hba1c.max' => 'HbA1c maksimal 15%.',

            'gd2pp.required' => 'Data Gula Darah 2 Jam Postprandial wajib diisi.',
            'gd2pp.min' => 'GD2PP minimal 40 mg/dL.',
            'gd2pp.max' => 'GD2PP maksimal 650 mg/dL.',
        ];
    }
}
