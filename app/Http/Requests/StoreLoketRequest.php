<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreLoketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pasienId'       => ['required', 'uuid', 'exists:simpus_pasien,ID'],
            'tglKunjungan'   => ['required', 'date'],
            'jenisPengunjung'=> ['nullable', 'in:Pengunjung Baru,Pengunjung Lama'],
            'jenisKunjungan' => ['nullable', 'in:Kunjungan Sakit,Kunjungan Sehat'],
            'kategori'       => ['nullable', 'in:BPJS,NON_BPJS'],
            'wilayah'        => ['nullable', 'string', 'max:100'],
            'kodeTKP'        => ['nullable', 'string', 'max:20'],
            'kdPoli'         => ['nullable', 'string', 'max:20'],
            'kdKegiatan'     => ['nullable', 'string', 'max:20'],
            'kdProvider'     => ['nullable', 'string', 'max:20'],
            'PHONE'          => ['nullable', 'string', 'max:20'],
            'statusKartu'    => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'pasienId.required'     => 'Pasien wajib dipilih.',
            'pasienId.exists'       => 'Data pasien tidak ditemukan.',
            'tglKunjungan.required' => 'Tanggal kunjungan wajib diisi.',
        ];
    }
}