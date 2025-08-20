<?php

use App\Http\Controllers\Filter\FilterController;
use App\Http\Controllers\RuangLayanan\PoliBpUmumController;
use App\Http\Controllers\RuangLayanan\PoliGigiController;
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

//Grup Farmasi
Route::prefix('farmasi')->group(function () {
    Route::get('/', fn() => Inertia::render('Farmasi/Index'));
    Route::get('/master', fn() => Inertia::render('Farmasi/MasterObat'));
    Route::get('/resep-langsung', fn() => Inertia::render('Farmasi/ResepLangsung'));
    Route::get('/pelayanan-resep', fn() => Inertia::render('Farmasi/PelayananResep'));
    Route::get('/laporan', fn() => Inertia::render('Farmasi/LaporanFarmasi'));
});

// Grup Filter
Route::prefix('filter')->controller(FilterController::class)->group(function () {
    Route::get('/', 'index')->name('filter');
    Route::get('/dev', 'dev')->name('filter.dev');
    Route::get('/modal', 'modal')->name('filter.modal');
});

// Grup Loket
Route::prefix('loket')->group(function () {
    Route::get('/', fn() => Inertia::render('Loket/Index'))->name('loket.index');
    Route::get('/pasien', fn() => Inertia::render('Loket/AddPasien'))->name('loket.pasien');
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
Route::prefix('mal-sehat')->name('mal-sehat.')->group(function () {
    Route::inertia('/', 'MalSehat/Index')->name('index');

    // Kesling
    Route::prefix('kesling')->name('kesling.')->group(function () {
        Route::inertia('/', 'MalSehat/Kesling/Index')->name('index');
        Route::inertia('konseling-sanitasi', 'MalSehat/Kesling/KonselingSanitasi')->name('konseling');
        Route::inertia('pengukuran-kebugaran-haji', 'MalSehat/Kesling/PengukuranKebugaranHaji')->name('haji');
        Route::inertia('pengukuran-kebugaran-anak', 'MalSehat/Kesling/PengukuranKebugaranAnak')->name('anak');
    });

    // Kesga
    Route::prefix('kesga')->name('kesga.')->group(function () {
        Route::inertia('/', 'MalSehat/Kesga/Index')->name('index');
        Route::inertia('konselingcatin', 'MalSehat/Kesga/KonselingCatin')->name('konselingcatin');
        Route::inertia('konselinghaji', 'MalSehat/Kesga/KonselingHaji')->name('konselinghaji');
        Route::inertia('konselingimunisasi', 'MalSehat/Kesga/KonselingImunisasi')->name('konselingimunisasi');
        Route::inertia('konselinganak', 'MalSehat/Kesga/KonselingAnak')->name('konselinganak');
        Route::inertia('konselingibu', 'MalSehat/Kesga/KonselingIbu')->name('konselingibu');
        Route::inertia('konselingkb', 'MalSehat/Kesga/KonselingKB')->name('konselingkb');
        Route::inertia('konsultasigizi', 'MalSehat/Kesga/KonsultasiGizi')->name('konsultasigizi');
        Route::inertia('konsultasilansia', 'MalSehat/Kesga/KonsultasiLansia')->name('konsultasilansia');
    });

    // PTM
    Route::prefix('ptm')->name('ptm.')->group(function () {
        Route::inertia('/', 'MalSehat/PTM/Index')->name('index');
        Route::inertia('konselingberhentimerokok', 'MalSehat/PTM/KonselingBerhentiMerokok')->name('konselingberhentimerokok');
        Route::inertia('skriningfaktorrisiko', 'MalSehat/PTM/SkriningFaktorRisiko')->name('skriningfaktorrisiko');
    });

    // P3M
    Route::prefix('p3m')->name('p3m.')->group(function () {
        Route::inertia('/', 'MalSehat/P3M/Index')->name('index');
        Route::inertia('konselinghivaids', 'MalSehat/P3M/KonselingHivAids')->name('konselinghivaids');
        Route::inertia('konselinglroa', 'MalSehat/P3M/KonselingLROA')->name('konselinglroa');
        Route::inertia('konselingtb', 'MalSehat/P3M/KonselingPenyakitTB')->name('konselingtb');
    });

    // Yankes Primer
    Route::prefix('yankes-primer')->name('yankes-primer.')->group(function () {
        Route::inertia('/', 'MalSehat/YankesPrimer/Index')->name('index');
        Route::inertia('kunjungankonsultasitradisional', 'MalSehat/YankesPrimer/KunjunganKonsultasiTradisional')->name('kunjungankonsultasitradisional');
        Route::inertia('kunjunganketerangansehat', 'MalSehat/YankesPrimer/KunjunganKeteranganSehat')->name('kunjunganketerangansehat');
    });

    // Farmasi
    Route::prefix('farmasi')->name('farmasi.')->group(function () {
        Route::inertia('/', 'MalSehat/Farmasi/Index')->name('index');
        Route::inertia('permintaanobat', 'MalSehat/Farmasi/PermintaanObat')->name('permintaanobat');
    });

    // Biakes
    Route::prefix('biakes')->name('biakes.')->group(function () {
        Route::inertia('/', 'MalSehat/Biakes/Index')->name('index');
        Route::inertia('pembiayaanjaminansehat', 'MalSehat/Biakes/PembiayaanJaminanSehat')->name('pembiayaanjaminansehat');
    });

    // Promkes
    Route::prefix('promkes')->name('promkes.')->group(function () {
        Route::inertia('/', 'MalSehat/Promkes/Index')->name('index');
        Route::inertia('kesehatanpeduliremaja', 'MalSehat/Promkes/KesehatanPeduliRemaja')->name('kesehatanpeduliremaja');
    });

    // Lain-lain
    Route::inertia('home-visit', 'MalSehat/HomeVisit/Index')->name('home-visit');
    Route::inertia('sehat', 'MalSehat/Sehat/Index')->name('sehat');
    Route::inertia('rapid-test', 'MalSehat/RapidTest/Index')->name('rapid-test');
});

Route::prefix('ruang_layanan')->group(function () {
    // Menampilkan halaman poli
    Route::get('/simpus/poli', [RuangLayananController::class, 'index'])->name('ruang-layanan.poli');

    // Umum
    Route::get('/simpus/umum', [PoliBpUmumController::class, 'index'])->name('ruang-layanan.umum');
    Route::get('/simpus/umum/pelayanan/{id}', [PoliBpUmumController::class, 'pelayanan'])->name('ruang-layanan-umum.pelayanan');
    Route::inertia('/simpus/umum/surat-keterangan', 'Ruang_Layanan/Umum/surat_keterangan')->name('ruang-layanan-umum.surat-keterangan');
    Route::inertia('/simpus/umum/form-surat-keterangan', 'Ruang_Layanan/Umum/form_surat_keterangan')->name('ruang-layanan-umum.form-surat-keterangan');
    Route::post('simpus/umum/pelayanan/anamnesa', [PoliBpUmumController::class, 'setAnamnesa'])->name('ruang-layanan-umum.setAnamnesa');
    Route::post('simpus/umum/pelayanan/anamnesa/objective', [PoliBpUmumController::class, 'setAnamnesaObjective'])->name('ruang-layanan-umum.setAnamnesaObjective');
    Route::post('simpus/umum/pelayanan/mulaiPelayanan', [PoliBpUmumController::class, 'mulaiPemeriksaanPasien'])->name('ruang-layanan-umum.mulai-pemeriksaan-pasien');
    Route::post('simpus/umum/pelayanan/diagnosa-medis', [PoliBpUmumController::class, 'setDiagnosaMedis'])->name('ruang-layanan-umum.diagnosa-medis');

    //Gigi
    Route::get('/simpus/gigi', [PoliGigiController::class, 'index'])->name('ruang-layanan.gigi');
    Route::get('/simpus/gigi/pelayanan/{id}', [PoliGigiController::class, 'pelayanan'])->name('ruang-layanan-gigi.pelayanan');

    //UGD
    Route::inertia('/simpus/ugd', 'Ruang_Layanan/UGD/pasien_poli')->name('ruang-layanan.ugd');
    Route::inertia('/simpus/ugd/pelayanan', 'Ruang_Layanan/UGD/pelayanan')->name('ruang-layanan-ugd.pelayanan');

    //KB
    Route::inertia('/simpus/kb', 'Ruang_Layanan/KB/pasien_poli')->name('ruang-layanan.kb');
    Route::inertia('/simpus/kb/pelayanan', 'Ruang_Layanan/KB/pelayanan')->name('ruang-layanan-kb.pelayanan');

    //Kunjungan Online
    Route::inertia('/simpus/kunjungan-online', 'Ruang_Layanan/KunjunganOnline/pasien_poli')->name('ruang-layanan.kunjungan-online');
    Route::inertia('/simpus/kunjungan-online/pelayanan', 'Ruang_Layanan/KunjunganOnline/pelayanan')->name('ruang-layanan.kunjungan-online.pelayanan');

    //Sanitasi
    Route::inertia('/simpus/sanitasi', 'Ruang_Layanan/Sanitasi/pasien_poli')->name('ruang-layanan.sanitasi');
    Route::inertia('/simpus/sanitasi/pelayanan', 'Ruang_Layanan/Sanitasi/pelayanan')->name('ruang-layanan.sanitasi.pelayanan');

    //Gizi
    Route::inertia('/simpus/gizi', 'Ruang_Layanan/Gizi/pasien_poli')->name('ruang-layanan.gizi');
    Route::inertia('/simpus/gizi/pelayanan', 'Ruang_Layanan/Gizi/pelayanan')->name('ruang-layanan.gizi.pelayanan');

    //Laborat
    Route::inertia('/simpus/laborat', 'Ruang_Layanan/Laborat/index')->name('ruang-layanan.laborat');
     
    //Rawat Inap
    Route::inertia('/simpus/rawat-inap', 'Ruang_Layanan/RawatInap/index')->name('ruang-layanan.rawat-inap');
    Route::inertia('/simpus/rawat-inap/penerimaan-pasien', 'Ruang_Layanan/RawatInap/PenerimaanPasien/pasien_poli')->name('ruang-layanan.rawat-inap.penerimaan-pasien');
    Route::inertia('/simpus/rawat-inap/perawatan', 'Ruang_Layanan/RawatInap/DataKeperawatan/DataRanapKeperawatan')->name('ruang-layanan.rawat-inap.perawatan');
    Route::inertia('/simpus/rawat-inap/pengeluaran', 'Ruang_Layanan/RawatInap/PasienKeluar/DataPasienKeluar')->name('ruang-layanan.rawat-inap.pengeluaran');

    Route::inertia('/nyoba', 'Ruang_Layanan/Umum/parent')->name('nyoba');
});