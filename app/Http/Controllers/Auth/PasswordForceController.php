<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordForceController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'new_password' => ['required','string','min:8','confirmed'],
        ], [], ['new_password' => 'password baru']);

        $user = $request->user();

        // Tolak kalau sama dgn password lama
        if (Hash::check($request->input('new_password'), $user->password)) {
            return response()->json([
                'ok' => false,
                'message' => 'Password baru tidak boleh sama dengan password lama.'
            ], 422);
        }

        DB::table('users')->where('id', $user->id)->update([
            'old_password'            => $user->password,                 // opsional: simpan hash lama
            'password'                => Hash::make($request->input('new_password')),
            'forgotten_password_time' => time(),                          // pakai kolom yg sudah ada
        ]);

        // Bersihkan flag & perbarui sesi
        $request->session()->forget('force_password_change');
        $request->session()->regenerate();

        return response()->json(['ok' => true]);
    }
}
