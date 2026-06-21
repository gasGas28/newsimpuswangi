<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThalasemiaRequest extends FormRequest
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
              // thalasemia: {
            //   hb: '',
            //   mcv: '',
            //   mch: '',
            //   rbc: '',
            //   rdw: '',
            // },
            'skriningId' => ['required'],
            'hb' => ['required', 'numeric'],
            'mcv' => ['required', 'numeric'],
            'mch' => ['required', 'numeric'],
            'rbc' => ['required', 'numeric'],
            'rdw' => ['required', 'numeric'],
        ];
    }
}
