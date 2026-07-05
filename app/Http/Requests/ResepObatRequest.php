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
}
