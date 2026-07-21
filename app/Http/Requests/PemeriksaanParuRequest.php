<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PemeriksaanParuRequest extends FormRequest
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
            'skriningId' => ['required'],
            'kp1' => ['required', 'string'],
            'kp2' => ['required', 'string'],
            'kp3' => ['required', 'string'],
            'kp4' => ['required', 'string'],
            'kp5' => ['required', 'string'],
            'kp6' => ['required', 'string'],
            'kp7' => ['required', 'string'],
            'hasil_kkp' => ['required', 'string'],
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
            'skriningId' => 'Data skrining',
            'kp1' => 'Kuesioner Paru pertanyaan 1',
            'kp2' => 'Kuesioner Paru pertanyaan 2',
            'kp3' => 'Kuesioner Paru pertanyaan 3',
            'kp4' => 'Kuesioner Paru pertanyaan 4',
            'kp5' => 'Kuesioner Paru pertanyaan 5',
            'kp6' => 'Kuesioner Paru pertanyaan 6',
            'kp7' => 'Kuesioner Paru pertanyaan 7',
            'hasil_kkp' => 'Hasil Kuesioner Paru',
        ];
    }
}
