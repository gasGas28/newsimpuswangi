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
})->name('home');

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
    Route::inertia('loket', 'Laporan/Loket/Loket')->name('laporan.loket');
    Route::inertia('rujukan', 'Laporan/Rujukan/Rujukan')->name('laporan.rujukan');
    Route::inertia('umum', 'Laporan/Umum/Umum')->name('laporan.umum');
    Route::inertia('gigi', 'Laporan/Gigi/Gigi')->name('laporan.gigi');
    Route::inertia('kia', 'Laporan/Kia/Kia')->name('laporan.kia');
    Route::inertia('lab', 'Laporan/Lab/Lab')->name('laporan.lab');
    Route::inertia('kb', 'Laporan/Kb/Kb')->name('laporan.kb');
    Route::inertia('ugd', 'Laporan/Ugd/Ugd')->name('laporan.ugd');
    Route::inertia('rawat-inap', 'Laporan/Rawat-inap/Rawat-inap')->name('laporan.rawat-inap');
    Route::inertia('sanitasi', 'Laporan/Sanitasi/Sanitasi')->name('laporan.sanitasi');
    Route::inertia('kunjungan-sehat', 'Laporan/Kunjungan-sehat/Kunjungan-sehat')->name('laporan.kunjungan-sehat');
});

// Grup Mal Sehat
// Halaman utama MAL SEHAT
Route::get('/mal-sehat', fn () => Inertia::render('MalSehat/Index'))->name('mal-sehat');
Route::get('/mal-sehat/kesling', fn () => Inertia::render('MalSehat/Kesling/Index'))->name('mal-sehat.kesling');
Route::get('/mal-sehat/kesling/konseling-sanitasi', fn () => Inertia::render('MalSehat/Kesling/KonselingSanitasi'))->name('mal-sehat.kesling.konseling');
Route::get('/mal-sehat/kesling/pengukuran-kebugaran-haji', fn () => Inertia::render('MalSehat/Kesling/PengukuranKebugaranHaji'))->name('mal-sehat.kesling.haji');
Route::get('/mal-sehat/kesling/pengukuran-kebugaran-anak', fn () => Inertia::render('MalSehat/Kesling/PengukuranKebugaranAnak'))->name('mal-sehat.kesling.anak');
// Halaman utama Kesga
Route::get('/mal-sehat/kesga', fn () => Inertia::render('MalSehat/Kesga/Index'))->name('mal-sehat.kesga');
// Detail tiap layanan
Route::get('/mal-sehat/kesga/konselingcatin', fn () => Inertia::render('MalSehat/Kesga/KonselingCatin'))->name('mal-sehat.kesga.konselingcatin');
Route::get('/mal-sehat/kesga/konselinghaji', fn () => Inertia::render('MalSehat/Kesga/KonselingHaji'))->name('mal-sehat.kesga.konselinghaji');
Route::get('/mal-sehat/kesga/konselingimunisasi', fn () => Inertia::render('MalSehat/Kesga/KonselingImunisasi'))->name('mal-sehat.kesga.konselingimunisasi');
Route::get('/mal-sehat/kesga/konselinganak', fn () => Inertia::render('MalSehat/Kesga/KonselingAnak'))->name('mal-sehat.kesga.konselinganak');
Route::get('/mal-sehat/kesga/konselingibu', fn () => Inertia::render('MalSehat/Kesga/KonselingIbu'))->name('mal-sehat.kesga.konselingibu');
Route::get('/mal-sehat/kesga/konselingkb', fn () => Inertia::render('MalSehat/Kesga/KonselingKB'))->name('mal-sehat.kesga.konselingkb');
Route::get('/mal-sehat/kesga/konsultasigizi', fn () => Inertia::render('MalSehat/Kesga/KonsultasiGizi'))->name('mal-sehat.kesga.konsultasigizi');
Route::get('/mal-sehat/kesga/konsultasilansia', fn () => Inertia::render('MalSehat/Kesga/KonsultasiLansia'))->name('mal-sehat.kesga.konsultasilansia');
// Halaman utama PTM
Route::get('/mal-sehat/ptm', fn () => Inertia::render('MalSehat/PTM/Index'))->name('mal-sehat.ptm');
// Detail tiap layanan PTM
Route::get('/mal-sehat/ptm/konselingberhentimerokok', fn () => Inertia::render('MalSehat/PTM/KonselingBerhentiMerokok'))->name('mal-sehat.ptm.konselingberhentimerokok');
Route::get('/mal-sehat/ptm/skriningfaktorrisiko', fn () => Inertia::render('MalSehat/PTM/SkriningFaktorRisiko'))->name('mal-sehat.ptm.skriningfaktorrisiko');
//Halaman utama P3M
Route::get('/mal-sehat/p3m', fn () => Inertia::render('MalSehat/P3M/Index'))->name('mal-sehat.p3m');
// Detail tiap layanan P3M
Route::get('/mal-sehat/p3m/konselinghivaids', fn () => Inertia::render('MalSehat/P3M/KonselingHivAids'))->name('mal-sehat.p3m.konselinghivaids');
Route::get('/mal-sehat/p3m/konselinglroa', fn () => Inertia::render('MalSehat/P3M/KonselingLROA'))->name('mal-sehat.p3m.konselinglroa');
Route::get('/mal-sehat/p3m/konselingtb', fn () => Inertia::render('MalSehat/P3M/KonselingPenyakitTB'))->name('mal-sehat.p3m.konselingtb');
//Halaman utama Yankes Primer
Route::get('/mal-sehat/yankes-primer', fn () => Inertia::render('MalSehat/YankesPrimer/Index'))->name('mal-sehat.yankes-primer');
//Detail tiap layanan Yankes Primer
Route::get('/mal-sehat/yankes-primer/kunjungankonsultasitradisional', fn () => Inertia::render('MalSehat/YankesPrimer/KunjunganKonsultasiTradisional'))->name('mal-sehat.yankes-primer.kunjungankonsultasitradisional');
Route::get('/mal-sehat/yankes-primer/kunjunganketerangansehat', fn () => Inertia::render('MalSehat/YankesPrimer/KunjunganKeteranganSehat'))->name('mal-sehat.yankes-primer.kunjunganketerangansehat');
//Halaman utama Farmasi
Route::get('/mal-sehat/farmasi', fn () => Inertia::render('MalSehat/Farmasi/Index'))->name('mal-sehat.farmasi');
//Detail tiap layanan Farmasi
Route::get('/mal-sehat/farmasi/permintaanobat', fn () => Inertia::render('MalSehat/Farmasi/PermintaanObat'))->name('mal-sehat.farmasi.permintaanobat');
//Halaman utama Biakes
Route::get('/mal-sehat/biakes', fn () => Inertia::render('MalSehat/Biakes/Index'))->name('mal-sehat.biakes');
//Detail tiap layanan Biakes
Route::get('/mal-sehat/biakes/pembiayaanjaminansehat', fn () => Inertia::render('MalSehat/Biakes/PembiayaanJaminanSehat'))->name('mal-sehat.biakes.pembiayaanjaminansehat');
// Halaman utama Promkes & PM
Route::get('/mal-sehat/promkes', fn () => Inertia::render('MalSehat/Promkes/Index'))->name('mal-sehat.promkes');
// Detail tiap layanan Promkes & PM
Route::get('/mal-sehat/promkes/kesehatanpeduliremaja', fn () => Inertia::render('MalSehat/Promkes/KesehatanPeduliRemaja'))->name('mal-sehat.promkes.kesehatanpeduliremaja');
//Halaman Home Visit
Route::get('/mal-sehat/home-visit', fn () => Inertia::render('MalSehat/HomeVisit/Index'))->name('mal-sehat.home-visit');
//Halaman Sehat
Route::get('/mal-sehat/sehat', fn () => Inertia::render('MalSehat/Sehat/Index'))->name('mal-sehat.sehat');
//Halaman Rapid test
Route::get('/mal-sehat/rapid-test', fn () => Inertia::render('MalSehat/RapidTest/Index'))->name('mal-sehat.rapid-test');

Route::get('/simpus/poli', [RuangLayananController::class, 'index']);
Route::get('/simpus/umum', [RuangLayananController::class, 'dataPasienPoli']);
Route::get('/simpus/pelayanan', [RuangLayananController::class, 'layanan']);