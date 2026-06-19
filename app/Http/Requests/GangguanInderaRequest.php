<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GangguanInderaRequest extends FormRequest
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
            //   visus_od: '',
            //   pinhole_od: '',
            //   visus_os: '',
            //   pinhole_os: '',

            // // Segmen Anterior & Katarak - Mata Kanan
            //   sa_od: '',
            //   rf_od: '',
            //   st_od: '',
            //   gio_od: '',

            // // Segmen Anterior & Katarak - Mata Kiri
            //   sa_os: '',
            //   rf_os: '',
            //   st_os: '',
            //   gio_os: '',
            // // Retinopati
            //   retino_od: 'false',
            //   retino_os: 'false',
            'skriningId' => ['required'],
            'penglihatan.visus_od' => ['nullable', 'string'],
            'penglihatan.visus_os' => ['nullable', 'string'],
            'penglihatan.pinhole_od' => ['nullable', 'string'],
            'penglihatan.pinhole_os' => ['nullable', 'string'],
            'penglihatan.sa_od' => ['nullable', 'string'],
            'penglihatan.sa_os' => ['nullable', 'string'],
            'penglihatan.st_od' => ['nullable', 'string'],
            'penglihatan.st_os' => ['nullable', 'string'],
            'penglihatan.rf_od' => ['nullable', 'string'],
            'penglihatan.rf_os' => ['nullable', 'string'],
            'penglihatan.gio_os' => ['nullable', 'string'],
            'penglihatan.gio_od' => ['nullable', 'string'],
            'penglihatan.retino_os' => ['nullable', 'string'],
            'penglihatan.retino_od' => ['nullable', 'string'],
            'pendengaran.tuli_kiri' => ['nullable', 'string'],
            'pendengaran.tuli_kanan' => ['nullable', 'string'],
            'pendengaran.omsk_kiri' => ['nullable', 'string'],
            'pendengaran.omsk_kanan' => ['nullable', 'string'],
            'pendengaran.serumen_kanan' => ['nullable', 'string'],
            'pendengaran.serumen_kiri' => ['nullable', 'string'],
            'pendengaran.presbi_kiri' => ['nullable', 'string'],
            'pendengaran.presbi_kanan' => ['nullable', 'string'],
            'pendengaran.bisik_kiri' => ['nullable', 'string'],
            'pendengaran.bisik_kanan' => ['nullable', 'string'],
        ];
    }
}
