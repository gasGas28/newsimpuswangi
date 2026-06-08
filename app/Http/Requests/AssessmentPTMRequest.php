<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentPTMRequest extends FormRequest
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
            //
            'skrining_ptm_id' => 'nullable|exists:skrining_ptm,id',
            'idpelayanan' => 'required_without:skrining_ptm_id',
            'masalah_hasil_skrining' => 'nullable|array',
            'ringkasan_temuan' => 'nullable|array',
            'diagnosis_utama' => 'nullable|string',
            'diagnosis_utama_saran' => 'nullable|string',
            'status_klinis' => 'nullable|string|max:50',
            'catatan_diagnosis' => 'nullable|string',
            'kategori_risiko' => 'nullable|string|max:50',
            'ringkasan_klinis' => 'nullable|string',
            'catatan_assessment' => 'nullable|string',
        ];
    }
}
