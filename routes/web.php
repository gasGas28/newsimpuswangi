<?php

use App\Http\Controllers\RuangLayananController;
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
