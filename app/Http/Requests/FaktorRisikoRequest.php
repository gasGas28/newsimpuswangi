<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaktorRisikoRequest extends FormRequest
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
            'idSkrining' => 'required|uuid',
            'idpelayanan' => 'required|uuid',
            'merokok' => 'required|string',
            'status_merokok' => 'required|string',
            'btg_rokok' => 'nullable|integer|min:0',
            'lama_rokok' => 'nullable|integer|min:0',
            'paparan_rokok' => 'nullable|string',

            'gula' => 'nullable|string',
            'garam' => 'nullable|string',
            'minyak' => 'nullable|string',
            'sayur' => 'nullable|string',
            'aktivitas' => 'nullable|string',
            'alkohol' => 'nullable|string',

            'r_pribadi_htn' => 'nullable|boolean',
            'r_pribadi_dm' => 'nullable|boolean',
            'r_pribadi_stroke' => 'nullable|boolean',
            'r_pribadi_jantung' => 'nullable|boolean',

            'r_keluarga_htn' => 'nullable|boolean',
            'r_keluarga_dm' => 'nullable|boolean',
            'r_keluarga_stroke' => 'nullable|boolean',
            'r_keluarga_jantung' => 'nullable|boolean',

            'obat' => 'nullable|string',
            'kesiapan' => 'nullable|string',
            'dukung' => 'nullable|string',

            'skor_faktor_risiko' => 'nullable|integer|min:0',
            'kategori_faktor_risiko' => 'nullable|string',
            'detail_faktor_risiko' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'merokok.required' => 'Status merokok wajib diisi.',
            'status_merokok.required' => 'Status merokok wajib diisi.',
            'btg_rokok.min' => 'Jumlah batang rokok harus berupa angka positif.',
            'lama_rokok.min' => 'Lama merokok harus berupa angka positif.',
            'paparan_rokok.string' => 'Paparan rokok harus berupa teks.',
            'obat.string' => 'Obat harus berupa teks.',
        ];
    }
    public function attributes(): array
    {
        return [
            'merokok' => 'status merokok',
            'status_merokok' => 'status merokok',
            'btg_rokok' => 'jumlah batang rokok per hari',
            'lama_rokok' => 'lama merokok (tahun)',
            'paparan_rokok' => 'paparan rokok di lingkungan sekitar',
            'obat' => 'obat yang sedang dikonsumsi',
        ];
    }
}
