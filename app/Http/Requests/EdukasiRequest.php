<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EdukasiRequest extends FormRequest
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
            'idskrining' => ['required', 'string'],
            'kode_snomed' => ['required', 'string'],
            'keterangan' => ['nullable', 'string'],
            'procedureId' => ['nullable', 'string'],
        ];
    }
}
