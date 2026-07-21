<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PemeriksaanEKGRequest extends FormRequest
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
            'hr' => ['required', 'numeric'],
            'irama' => ['required', 'string'],
            'axis' => ['required', 'string'],
            'st' => ['required', 'string'],
            'qrs' => ['required', 'string'],
            'hasil_ekg' => ['required', 'string'],
        ];
    }
    public function messages(): array
    {
        return [
            'hr.required' => 'Heart Rate wajib diisi.',
            'irama.required' => 'Data pemeriksaan EKG wajib semua diisi',
            'axis.required' => 'Data pemeriksaan EKG wajib semua diisi',
            'st.required' => 'Data pemeriksaan EKG wajib semua diisi.',
            'qrs.required' => 'Data pemeriksaan EKG wajib semua diisi.',
            'hasil.required' => 'Kesimpulan EKG wajib diisi.',
        ];
    }
}
