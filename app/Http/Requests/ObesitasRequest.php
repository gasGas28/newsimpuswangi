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
            'berat_badan' => ['required', 'numeric', 'min:10', 'max:250'],
            'tinggi_badan' => ['required', 'numeric', 'min:70', 'max:250'],
            'imt' => ['required', 'numeric'],
            'interpretasi_imt' => ['required', 'string'],
            'lingkar_perut' => ['required', 'numeric'],
            'interpretasi_lp' => ['required', 'string'],
        ];
    }
    public function messages(): array
    {
        return [
            'berat_badan.required' => 'Berat badan wajib diisi.',
            'berat_badan.min' => 'Berat badan minimal 10 kg.',
            'berat_badan.max' => 'Berat badan maksimal 250 kg.',

            'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
            'tinggi_badan.min' => 'Tinggi badan minimal 70 cm.',
            'tinggi_badan.max' => 'Tinggi badan maksimal 250 cm.',

            'imt.required' => 'IMT tidak boleh kosong.',
            'imt.min' => 'Nilai IMT minimal 5.',
            'imt.max' => 'Nilai IMT maksimal 100.',

            'lingkar_perut.required' => 'Lingkar perut wajib diisi.',
            'lingkar_perut.min' => 'Lingkar perut minimal 30 cm.',
            'lingkar_perut.max' => 'Lingkar perut maksimal 250 cm.',

            'interpretasi_imt.required' => 'Interpretasi IMT tidak boleh kosong.',
            'interpretasi_lp.required' => 'Interpretasi lingkar perut tidak boleh kosong.',
        ];
    }
}
