<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThalasemiaRequest extends FormRequest
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
            'hb' => ['required', 'numeric'],
            'mcv' => ['required', 'numeric'],
            'mch' => ['required', 'numeric'],
            'rbc' => ['required', 'numeric'],
            'rdw' => ['required', 'numeric'],
        ];
    }
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
        ];
    }

    public function attributes(): array
    {
        return [
            'skriningId' => 'Data skrining',
            'hb' => 'Nilai Hb',
            'mcv' => 'Nilai MCV',
            'mch' => 'Nilai MCH',
            'rbc' => 'Nilai RBC',
            'rdw' => 'Nilai RDW',
        ];
    }
}
