<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TemplateController;
use Inertia\Inertia;
use App\Http\Controllers\Laporan\LaporanLoketController;
use App\Http\Controllers\Laporan\Rujukan\RujukanController;
use App\Http\Controllers\Laporan\Kb\KbController;
use App\Http\Controllers\Auth\LoginController;


Route::get('/', function () {
    return Inertia::render('Templete/Index');
})->name('home');


// Login
Route::get('/login', fn () => Inertia::render('Auth/Login'))->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// Protected
Route::middleware('auth')->group(function () {

    // Semua role boleh dashboard
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    // OWNER ONLY
    Route::middleware('role:owner')->group(function () {
        Route::get('/reports', fn () => Inertia::render('Reports/Index'))->name('reports.index');
    });

    // (Opsional) nanti isi menu khusus role lain di sini:
    // Route::middleware('role:pelayanan,owner,kapus')->group(function () { ... });
    // Route::middleware('role:loket,owner,kapus')->group(function () { ... });
});






// Grup Admin
Route::prefix('admin')->group(function () {
    Route::get('/', fn() => Inertia::render('Admin/Index'))->name('admin.index');
});

// Grup Filter
Route::prefix('filter')->group(function () {
    Route::get('/filter', fn() => Inertia::render('Filter/Index'))->name('filter');
});

// Grup Loket
Route::prefix('loket')->group(function () {
    Route::get('/', fn() => Inertia::render('Loket/Index'))->name('loket.index');
    Route::get('/pasien', fn() => Inertia::render('Loket/Pasien'))->name('loket.pasien');
    Route::get('/search', fn() => Inertia::render('Loket/Search'))->name('loket.search');
});

// Grup Templete
Route::prefix('templete')->group(function () {
    Route::get('/button', fn() => Inertia::render('Templete/Button'))->name('templete.button');
    Route::get('/form', fn() => Inertia::render('Templete/Form'))->name('templete.form');
    Route::get('/table', fn() => Inertia::render('Templete/Table'))->name('templete.table');
    Route::get('/card', fn() => Inertia::render('Templete/Card'))->name('templete.card');
    Route::get('/pagination', fn() => Inertia::render('Templete/Pagination'))->name('templete.pagination');
});

// Grup Loket
Route::prefix('loket')->group(function () {
    Route::get('/', fn() => Inertia::render('Loket/Index'))->name('loket.index');
});

// Grup Laporan
Route::prefix('laporan')->group(function () {
    Route::get('loket', [LaporanLoketController::class, 'index'])->name('laporan.loket');
    Route::get('loket/tampilkan', [LaporanLoketController::class, 'tampil'])->name('laporan.loket.tampilkan-laporan-loket');

    // Route::inertia('rujukan', 'Laporan/Rujukan/Rujukan')->name('laporan.rujukan');
Route::get('/rujukan', [RujukanController::class, 'index']) ->name('laporan.rujukan'); // (atau .index) — samain sama yang dipakai di Navbar


    Route::inertia('umum', 'Laporan/Umum/Umum')->name('laporan.umum');
    Route::inertia('gigi', 'Laporan/Gigi/Gigi')->name('laporan.gigi');
    Route::inertia('kia', 'Laporan/Kia/Kia')->name('laporan.kia');
    Route::inertia('lab', 'Laporan/Lab/Lab')->name('laporan.lab');


Route::match(['get','post'], '/laporan/kb', [KbController::class, 'index'])->name('laporan.kb');    // Route::inertia('kb', 'Laporan/Kb/Kb')->name('laporan.kb');


    Route::inertia('ugd', 'Laporan/Ugd/Ugd')->name('laporan.ugd');
    Route::inertia('rawat-inap', 'Laporan/Rawat-inap/Rawat-inap')->name('laporan.rawat-inap');
    Route::inertia('sanitasi', 'Laporan/Sanitasi/Sanitasi')->name('laporan.sanitasi');
    Route::inertia('kunjungan-sehat', 'Laporan/Kunjungan-sehat/Kunjungan-sehat')->name('laporan.kunjungan-sehat');
});


