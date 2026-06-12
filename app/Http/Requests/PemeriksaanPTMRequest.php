<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class PemeriksaanPTMRequest extends FormRequest
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
     */ public function rules(): array
    {
        return [
            'skriningId' => [
                'required',
            ],

            // Hipertensi
            'hipertensi.sistolik' => ['nullable', 'integer'],
            'hipertensi.tekanan_diastolik' => ['nullable', 'integer'],
            'hipertensi.kategori_tekanan_darah' => ['nullable', 'string'],
            'hipertensi.suhu' => ['nullable', 'numeric'],
            'hipertensi.nadi' => ['nullable', 'integer'],
            'hipertensi.pernapasan' => ['nullable', 'integer'],

            // Diabetes
            'diabetes.gdp' => ['nullable', 'numeric'],
            'diabetes.interpretasi_gdp' => ['nullable', 'string'],

            'diabetes.gds' => ['nullable', 'numeric'],
            'diabetes.interpretasi_gds' => ['nullable', 'string'],

            'diabetes.hba1c' => ['nullable', 'numeric'],
            'diabetes.interpretasi_hba1c' => ['nullable', 'string'],

            'diabetes.gd2pp' => ['nullable', 'numeric'],
            'diabetes.interpretasi_gd2pp' => ['nullable', 'string'],

            // Obesitas
            'obesitas.berat_badan' => ['nullable', 'numeric'],
            'obesitas.tinggi_badan' => ['nullable', 'numeric'],
            'obesitas.imt' => ['nullable', 'numeric'],
            'obesitas.interpretasi_imt' => ['nullable', 'string'],
            'obesitas.lingkar_pinggang' => ['nullable', 'numeric'],
            'obesitas.interpretasi_lingkar_pinggang' => ['nullable', 'string'],

            // Profil lipid
            'profil_lipid.kolesterol_total' => ['nullable', 'numeric'],
            'profil_lipid.interpretasi_kolesterol_total' => ['nullable', 'string'],

            'profil_lipid.ldl' => ['nullable', 'numeric'],
            'profil_lipid.interpretasi_ldl' => ['nullable', 'string'],

            'profil_lipid.hdl' => ['nullable', 'numeric'],
            'profil_lipid.interpretasi_hdl' => ['nullable', 'string'],

            'profil_lipid.trigliserida' => ['nullable', 'numeric'],
            'profil_lipid.interpretasi_trigliserida' => ['nullable', 'string'],

            // Asam urat
            'asam_urat.asam_urat' => ['nullable', 'numeric'],
            'asam_urat.interpretasi_asam_urat' => ['nullable', 'string'],
        ];
    }
}
