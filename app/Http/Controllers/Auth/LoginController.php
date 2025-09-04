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
    $roles = auth()->user()->roleNames();

    $to = match (true) {
        in_array('owner', $roles)      => route('dashboard'),
        in_array('admin', $roles)      => route('laporan.loket'),        // admin bebas, pilih default laporan
        in_array('loket', $roles)      => route('loket.index'),
        in_array('pelayanan', $roles)  => route('ruang-layanan.poli'),
        in_array('kapus', $roles)      => route('dashboard'),
        default                        => route('login'),
    };

    return response()->json(['ok' => true, 'redirect' => $to]);
}



        throw ValidationException::withMessages([
            'username' => 'Username atau password salah.',
        ]);
    }
}
