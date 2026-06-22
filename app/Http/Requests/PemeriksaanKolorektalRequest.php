<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PemeriksaanKolorektalRequest extends FormRequest
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
            // kkr1: '',
            //     kkr2: '',
            //     hasil_kkr: '',
            //     colok_dubur: '',
            //     darah_samar: '',
            'skriningId' => ['required', 'string'],
            'kkr1' => ['required', 'string'],
            'kkr2' => ['required', 'string'],
            'hasil_kkr' => ['required', 'string'],
            'colok_dubur' => ['required', 'string'],
            'darah_samar' => ['required', 'string'],
        ];
    }
}
