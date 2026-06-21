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
}
