<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResepObatRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'loketId' => ['required', 'string'],
            'pelayananId' => ['required', 'string'],
            'kategori' => ['required', 'string'],
            'jenis' => ['nullable', 'string'],
            'jumlah' => ['nullable', 'numeric'],
            'frekuensi' => ['nullable', 'numeric'],
            'intervalJam' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string'],
            'waktu' => ['nullable', 'string'],
            'kondisi' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string'],
            'nama' => ['required', 'string'],
            'unit' => ['required', 'numeric'],
            'obat_id' => ['required', 'numeric'],
            'nama_poli' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'numeric' => ':attribute harus berupa angka.',
        ];
    }

    public function attributes(): array
    {
        return [
            'loketId' => 'Loket',
            'pelayananId' => 'Pelayanan',
            'kategori' => 'Kategori obat',
            'jenis' => 'Jenis obat',
            'jumlah' => 'Jumlah',
            'frekuensi' => 'Frekuensi',
            'intervalJam' => 'Interval jam',
            'status' => 'Status',
            'waktu' => 'Waktu pemberian',
            'kondisi' => 'Kondisi pemberian',
            'catatan' => 'Catatan',
            'nama' => 'Nama obat',
            'unit' => 'Satuan obat',
            'obat_id' => 'Obat',
            'nama_poli' => 'Nama poli',
        ];
    }
}
