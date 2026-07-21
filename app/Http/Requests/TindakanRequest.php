<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TindakanRequest extends FormRequest
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
            'idpelayanan' => ['required', 'string'],
            'kode_tindakan' => ['required', 'string'],
            'nama_tindakan' => ['required', 'string'],
            'loketId' => ['required', 'string'],
            'nama_tindakan_ind' => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string'],
            'kdPoli' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
        ];
    }

    public function attributes(): array
    {
        return [
            'idpelayanan' => 'Data pelayanan',
            'kode_tindakan' => 'Kode tindakan',
            'nama_tindakan' => 'Nama tindakan',
            'loketId' => 'Loket',
            'nama_tindakan_ind' => 'Nama tindakan (Indonesia)',
            'keterangan' => 'Keterangan',
            'kdPoli' => 'Kode poli',
        ];
    }
}
