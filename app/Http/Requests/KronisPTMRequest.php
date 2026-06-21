<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KronisPTMRequest extends FormRequest
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
            //          iva: {
            //   inspekulo: '',
            //   iva: '',
            //   hpv: '',
            //   sadanis: '',
            //   usg_py: '',
            //   krioterapi: '',
            //   thermal: '',
            //   tca: '',
            //   kp1: '',
            //   kp2: '',
            //   kp3: '',
            //   kp4: '',
            //   kp5: '',
            //   kp6: '',
            //   kp7: '',
            //   hasil_kkp: '',
            //   kkr1: '',
            //   kkr2: '',
            //   hasil_kkr: '',
            //   colok_dubur: '',
            //   darah_samar: '',
            // },
            // thalasemia: {
            //   hb: '',
            //   mcv: '',
            //   mch: '',
            //   rbc: '',
            //   rdw: '',
            // },
            // ekg: {
            //   hr: '',
            //   irama: '',
            //   axis: '',
            //   st: '',
            //   qrs: '',
            //   hasil_ekg: '',
            // },
            'skriningId' => ['required'],
            'iva.inspekulo' => ['nullable', 'string']

            //
        ];
    }
}
