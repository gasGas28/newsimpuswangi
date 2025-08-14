<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
            'g-recaptcha-response' => ['required','string'],
        ]);

        // verifikasi ke Google
        $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $data['g-recaptcha-response'],
            'remoteip' => $request->ip(),
        ])->json();

        if (!($resp['success'] ?? false)) {
            throw ValidationException::withMessages([
                'captcha' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
            ]);
        }

            if (auth()->attempt(['username' => $data['username'], 'password' => $data['password']])) {
                $request->session()->regenerate();

                // Langsung ke /laporan/loket
                return response()->json([
                    'ok' => true,
                    'redirect' => route('laporan.loket'),
                ]);
            }


        throw ValidationException::withMessages([
            'username' => 'Username atau password salah.',
        ]);
    }
}
