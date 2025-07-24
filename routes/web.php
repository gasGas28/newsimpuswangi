<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TemplateController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Templete/Index');
});

// Grup route untuk template components
Route::get('/button', function () {
    return Inertia::render('Templete/Button');
})->name('button');

Route::get('/form', function () {
    return Inertia::render('Templete/Form');
})->name('form');

Route::get('/table', function () {
    return Inertia::render('Templete/Table');
})->name('table');

Route::get('/card', function () {
    return Inertia::render('Templete/Card');
})->name('card');

Route::get('/pagination', function () {
    return Inertia::render('Templete/Pagination');
})->name('pagination');



/// FAIZZ TES
Route::get('/laporan/loket', function () {
    return Inertia::render('Laporan/Loket/Loket');})->name('laporan.loket');

Route::get('/laporan/rujukan', function () {
    return Inertia::render('Laporan/Rujukan/Rujukan');})->name('laporan.rujukan');

Route::get('/laporan/umum', function () {
    return Inertia::render('Laporan/Umum/Umum');})->name('laporan.umum');


Route::get('/laporan/gigi', function () {
    return Inertia::render('Laporan/Gigi/Gigi');})->name('laporan.gigi');

Route::get('/laporan/kia', function () {
    return Inertia::render('Laporan/Kia/Kia');})->name('laporan.kia');

Route::get('/laporan/lab', function () {
    return Inertia::render('Laporan/Lab/Lab');})->name('laporan.lab');

Route::get('/laporan/kb', function () {
    return Inertia::render('Laporan/Kb/Kb');})->name('laporan.kb');

Route::get('/laporan/ugd', function () {
    return Inertia::render('Laporan/Ugd/Ugd');})->name('laporan.ugd');

Route::get('/laporan/Rawat-inap', function () {
    return Inertia::render('Laporan/Rawat-inap/Rawat-inap');})->name('laporan.Rawat-inap');

Route::get('/laporan/sanitasi', function () {
    return Inertia::render('Laporan/Sanitasi/Sanitasi');})->name('laporan.sanitasi');

Route::get('/laporan/kunjungan-sehat', function () {
    return Inertia::render('Laporan/Kunjungan-sehat/Kunjungan-sehat');})->name('laporan.kunjungan-sehat');

// // DASHBOARD pakai ProductController
// Route::get('/dashboard', [ProductController::class, 'index'])
//     ->middleware(['auth', 'role:owner,admin,pelayanan'])
//     ->name('dashboard');

// Route::post('/products', [ProductController::class, 'store'])
//     ->middleware(['auth', 'role:owner,admin,pelayanan']);

// Route::delete('/products/{id}', [ProductController::class, 'destroy'])
//     ->middleware(['auth', 'role:owner,admin,pelayanan']);


// // LOGIN & REGISTER tanpa role default
// Route::get('/login', fn() => Inertia::render('Login'))
//     ->name('login')
//     ->middleware('guest');

// Route::get('/register', fn() => Inertia::render('Register'))
//     ->name('register')
//     ->middleware('guest');
// Route::post('/login', function (Request $request) {
//     $credentials = $request->validate([
//         'email' => ['required', 'email'],
//         'password' => ['required'],
//         // reCAPTCHA divalidasi di middleware, jadi gak perlu tulis di sini lagi
//     ]);

//     if (Auth::attempt($credentials)) {
//         $request->session()->regenerate();
//         return redirect()->intended('/dashboard');
//     }

//     return back()->withErrors([
//         'email' => 'Email atau password salah.',
//     ]);
// })->middleware(['guest', 'verify.recaptcha']);


// Route::post('/register', function (Request $request) {
//     $validated = $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|email|unique:users,email',
//         'password' => 'required|confirmed|min:6',
//     ]);

//     // tanpa set role, biarkan NULL atau migration default
//     $user = User::create([
//         'name' => $validated['name'],
//         'email' => $validated['email'],
//         'password' => bcrypt($validated['password']),
//     ]);

//     Auth::login($user);
//     return redirect('/dashboard');
// })->middleware('guest');

// // LOGOUT
// Route::post('/logout', function () {
//     Auth::logout();
//     request()->session()->invalidate();
//     request()->session()->regenerateToken();
//     return redirect('/login');
// })->middleware('auth');
