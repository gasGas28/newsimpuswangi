<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKunjunganPTMRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idpelayanan' => 'required',
            'idLoket' => 'required',
            'nikPasien' => 'required',
            'tanggal_skrining' => 'required|date',
            'dokter' => 'required',
            'fasyankes' => 'required',
            'jenis_kunjungan' => 'required',
            'keluhan_utama' => 'required',

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
}
