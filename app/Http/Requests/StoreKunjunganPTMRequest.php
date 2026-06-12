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
            'idSkrining' => 'required|uuid',
            'idPelayanan' => 'required|uuid',
            'idLoket' => 'required|uuid',
            'nik_pasien' => 'required|string|max:16',
            'tanggal_skrining' => 'required|date',
            'id_petugas' => 'required|string|max:50',
            'fasyankes' => 'required|string|max:50',
            'jenis_kunjungan' => 'required|string|max:50',
            'keluhan_utama' => 'required|string|max:200'
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_skrining.required' => 'Tanggal skrining wajib diisi.',
            'id_petugas.required' => 'Dokter atau petugas wajib dipilih.',
            'fasyankes.required' => 'Fasyankes wajib diisi.',
            'jenis_kunjungan.required' => 'Jenis kunjungan wajib diisi.',
            'keluhan_utama.required' => 'Keluhan utama wajib diisi.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id_petugas' => 'dokter/petugas',
            'tanggal_skrining' => 'tanggal skrining',
            'keluhan_utama' => 'keluhan utama',
        ];
    }
}
