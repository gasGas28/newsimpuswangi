<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PemeriksaanKankerIvaRequest extends FormRequest
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
            'skriningId' => 'required',
            'inspekulo' => 'required|string',
            'iva' => 'required',
            'hpv' => 'required',
            'sadanis' => 'required',
            'usg_py' => 'required',
            'krioterapi' => 'boolean',
            'thermal' => 'boolean',
            'tca' => 'boolean',
            'rujuk_serviks' => 'boolean',
        ];
    }
}
