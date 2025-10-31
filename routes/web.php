<?php

use App\Http\Controllers\Filter\FilterController;
use App\Http\Controllers\RuangLayanan\indexController;
use App\Http\Controllers\RuangLayanan\PoliBpUmumController;
use App\Http\Controllers\RuangLayanan\PoliGigiController;
use App\Http\Controllers\RuangLayananController;
use App\Http\Controllers\RuangLayanan\PoliKIAController;
use App\Http\Controllers\RuangLayanan\KematianController;
use App\Http\Controllers\RuangLayanan\AncController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TemplateController;
use Inertia\Inertia;
use App\Http\Controllers\Laporan\LaporanLoketController;
use App\Http\Controllers\Pasien\PasienController;
use App\Http\Controllers\Laporan\Rujukan\RujukanController;
use App\Http\Controllers\MalSehat\PTMController;
use App\Http\Controllers\Laporan\Kb\KbController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Laporan\Sanitasi\SanitasiController;
use App\Http\Controllers\Laporan\Ugd\UgdController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\RuangLayanan\KunjOnline\KunjOnlineController;
use App\Http\Controllers\RuangLayanan\LaboratoriumController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\Owner\PanelController;
use App\Http\Controllers\Auth\PasswordForceController;
use App\Http\Controllers\Owner\OwnerLogController;
use Illuminate\Session\TokenMismatchException;







Route::match(['GET', 'POST'], '/_csrf-debug', function (Request $r) {
    return response()->json([
        'method' => $r->method(),
        'has_cookie_xsrf' => $r->cookies->has('XSRF-TOKEN'),
        'has_cookie_sess' => $r->cookies->has(config('session.cookie')),
        'token_input' => $r->input('_token') ? 'YES' : 'NO',
        'token_header' => $r->header('X-CSRF-TOKEN') ? 'YES' : 'NO',
        'token_x_xsrf' => $r->header('X-XSRF-TOKEN') ? 'YES' : 'NO',
        'session_driver' => config('session.driver'),
        'session_id' => $r->session()->getId(),
        'host' => $r->getHost(),
        'origin' => $r->headers->get('Origin'),
        'referer' => $r->headers->get('Referer'),
    ]);
})->middleware('web');




// Protect home (wajib login)
Route::get('/', function () {
    return Inertia::render('Templete/Index');
})->middleware('auth')->name('home');
;


// Login
Route::get('/login', fn() => Inertia::render('Auth/Login'))->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':owner,kapus'])->get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':loket,owner,admin'])->get('/loket', fn() => Inertia::render('Loket/Index'))->name('loket.index');
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':pelayanan,owner,admin'])
    ->get('/ruang-layanan/poli', fn() => Inertia::render('RuangLayanan/Poli'))
    ->name('ruang-layanan.poli.alt');  // nama beda, tidak bentrok
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':owner,admin,loket,pelayanan'])->group(function () {
    // semua rute laporan
});
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':laborat,pelayanan'])->get('/laborat', fn() => Inertia::render('Laborat/Index'))->name('laborat.index');

// Protected
Route::middleware('auth')->group(function () {

    // Semua role boleh dashboard
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');

    // OWNER ONLY
    Route::middleware('role:owner')->group(function () {
        Route::get('/reports', fn() => Inertia::render('Reports/Index'))->name('reports.index');
    });
});

// LOGOUTT
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');




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
    // Route::get('/dev', 'dev')->name('filter.dev');
    // Route::get('/modal', 'modal')->name('filter.modal');
});

// Grup Loket
Route::prefix('pasien')->controller(PasienController::class)->group(function () {
    // Route::get('/', fn() => Inertia::render('Loket/Index'))->name('loket.index');
    Route::get('/', fn() => Inertia::render('Loket/AddPasien'))->name('loket.pasien');
    Route::get('/search', 'index')->name('loket.search');
    Route::get('/{id}/edit', 'edit')->name('loket.edit');
    Route::post('/{id}', 'update')->name('pasien.update');
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




Route::prefix('home')->group(function () {
    Route::get('/', [HomeController::class, 'index'])
        ->name('home.home');
});

// Grup Laporan

Route::prefix('laporan')
    ->middleware(['auth', 'role:owner,admin,loket'])
    ->group(function () {
        Route::get('loket', [\App\Http\Controllers\Laporan\LaporanLoketController::class, 'index'])->name('laporan.loket');
        Route::get('loket/tampilkan', [\App\Http\Controllers\Laporan\LaporanLoketController::class, 'tampil'])->name('laporan.loket.tampilkan-laporan-loket');

        Route::get('/rujukan', [\App\Http\Controllers\Laporan\Rujukan\RujukanController::class, 'index'])->name('laporan.rujukan');
        Route::inertia('umum', 'Laporan/Umum/Umum')->name('laporan.umum');
        Route::inertia('gigi', 'Laporan/Gigi/Gigi')->name('laporan.gigi');
        Route::inertia('kia', 'Laporan/Kia/Kia')->name('laporan.kia');
        Route::inertia('lab', 'Laporan/Lab/Lab')->name('laporan.lab');
        Route::match(['get', 'post'], '/laporan/kb', [\App\Http\Controllers\Laporan\Kb\KbController::class, 'index'])->name('laporan.kb');
        Route::get('/ugd', [\App\Http\Controllers\Laporan\Ugd\UgdController::class, 'index'])->name('laporan.ugd');
        Route::inertia('rawat-inap', 'Laporan/Rawat-inap/Rawat-inap')->name('laporan.rawat-inap');
        Route::inertia('kunjungan-sehat', 'Laporan/KunjunganSehat/Index')->name('laporan.kunjungan-sehat');
        Route::get('/sanitasi', [\App\Http\Controllers\Laporan\Sanitasi\SanitasiController::class, 'index'])->name('laporan.sanitasi');
        Route::get('/sanitasi/register', [\App\Http\Controllers\Laporan\Sanitasi\SanitasiController::class, 'registerSanitasi'])->name('laporan.sanitasi.register');
        Route::get('/sanitasi/sanitasi', [\App\Http\Controllers\Laporan\Sanitasi\SanitasiController::class, 'laporanSanitasi'])->name('laporan.sanitasi.laporan');
        Route::get('/sanitasi/kasus', [\App\Http\Controllers\Laporan\Sanitasi\SanitasiController::class, 'laporanKasus'])->name('laporan.sanitasi.kasus');
    });

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Route::prefix('laporan')->group(function () {
//     Route::get('loket', [LaporanLoketController::class, 'index'])->name('laporan.loket');
//     Route::get('loket/tampilkan', [LaporanLoketController::class, 'tampil'])->name('laporan.loket.tampilkan-laporan-loket');

// Route::get('/rujukan', [RujukanController::class, 'index']) ->name('laporan.rujukan'); // (atau .index) — samain sama yang dipakai di Navbar


//     Route::inertia('umum', 'Laporan/Umum/Umum')->name('laporan.umum');
//     Route::inertia('gigi', 'Laporan/Gigi/Gigi')->name('laporan.gigi');
//     Route::inertia('kia', 'Laporan/Kia/Kia')->name('laporan.kia');
//     Route::inertia('lab', 'Laporan/Lab/Lab')->name('laporan.lab');


// Route::match(['get','post'], '/laporan/kb', [KbController::class, 'index'])->name('laporan.kb');    // Route::inertia('kb', 'Laporan/Kb/Kb')->name('laporan.kb');


//     // Route::inertia('ugd', 'Laporan/Ugd/Ugd')->name('laporan.ugd');
//     Route::get('/ugd', [UgdController::class, 'index'])->name('laporan.ugd');



//     Route::inertia('rawat-inap', 'Laporan/Rawat-inap/Rawat-inap')->name('laporan.rawat-inap');
//     // Route::inertia('sanitasi', 'Laporan/Sanitasi/Sanitasi')->name('laporan.sanitasi');
// Route::inertia('kunjungan-sehat', 'Laporan/KunjunganSehat/Index')  ->name('laporan.kunjungan-sehat');
//     Route::get('/sanitasi', [SanitasiController::class, 'index'])->name('laporan.sanitasi');
//     Route::get('/sanitasi/register', [SanitasiController::class, 'registerSanitasi'])->name('laporan.sanitasi.register');
//     Route::get('/sanitasi/sanitasi', [SanitasiController::class, 'laporanSanitasi'])->name('laporan.sanitasi.laporan');
//     Route::get('/sanitasi/kasus', [SanitasiController::class, 'laporanKasus'])->name('laporan.sanitasi.kasus');




// });


// Grup Mal Sehat
Route::prefix('mal-sehat')->name('mal-sehat.')->group(function () {
    Route::inertia('/', 'MalSehat/Index')->name('index');

    // Kesling
    Route::prefix('kesling')->name('kesling.')->group(function () {
        Route::inertia('/', 'MalSehat/Kesling/Index')->name('index');
        Route::inertia('konseling-sanitasi', 'MalSehat/Kesling/KonselingSanitasi')->name('konseling');
        Route::inertia('pengukuran-kebugaran-haji', 'MalSehat/Kesling/PengukuranKebugaranHaji')->name('haji');
        Route::inertia('pengukuran-kebugaran-anak', 'MalSehat/Kesling/PengukuranKebugaranAnak')->name('anak');
        // Halaman detail "Belum Dilayani"
        Route::inertia('detail/{noUrut}', 'MalSehat/Kesling/DetailKonselingSanitasi')->name('detail');
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

        Route::get('pembiayaanjaminansehat', [\App\Http\Controllers\MalSehat\BiakesController::class, 'pembiayaanJaminanSehat'])
            ->name('pembiayaanjaminansehat');

        Route::get('pembiayaanjaminansehat/pelayanan/{no_mr}', [\App\Http\Controllers\MalSehat\BiakesController::class, 'pelayanan'])
            ->name('pembiayaanjaminansehat.pelayanan')
            ->middleware('web');
    });

    // Promkes
    Route::prefix('promkes')->name('promkes.')->group(function () {
        Route::inertia('/', 'MalSehat/Promkes/Index')->name('index');

        Route::get('kesehatanpeduliremaja', [\App\Http\Controllers\MalSehat\PromkesController::class, 'kesehatanPeduliRemaja'])
            ->name('kesehatanpeduliremaja');

        Route::get('kesehatanpeduliremaja/pelayanan/{no_mr}', [\App\Http\Controllers\MalSehat\PromkesController::class, 'pelayanan'])
            ->name('kesehatanpeduliremaja.pelayanan')
            ->middleware('web');

        // ✅ route untuk diagnosa
        Route::get('diagnosa/list', [\App\Http\Controllers\MalSehat\PromkesController::class, 'getDiagnosa'])
            ->name('diagnosa.list');

        // ✅ route untuk tindakan
        Route::get('tindakan/list', [\App\Http\Controllers\MalSehat\PromkesController::class, 'getTindakan'])
            ->name('tindakan.list');
    });

    // Lain-lain
    Route::inertia('home-visit', 'MalSehat/HomeVisit/Index')->name('home-visit');
    Route::inertia('sehat', 'MalSehat/Sehat/Index')->name('sehat');
    Route::inertia('sehat', 'MalSehat/Sehat/Index')->name('sehat');
    Route::inertia('sehat/pelayanan', 'MalSehat/Sehat/Pelayanan')->name('sehat.pelayanan');
    Route::inertia('rapid-test', 'MalSehat/RapidTest/Index')->name('rapid-test');
});

Route::prefix('ruang_layanan')->group(function () {
    // Menampilkan halaman poli
    Route::get('/simpus/poli', [indexController::class, 'listPoli'])->name('ruang-layanan.poli');
    Route::get('/simpus/poli/{kluster}', [indexController::class, 'listPoliKluster'])->name('ruang-layanan.poli-kluster');

    // Umum
    Route::get('/simpus/{idPoli?}/{kluster?}', [PoliBpUmumController::class, 'index'])->name('ruang-layanan.index');
    Route::get('/simpus/pelayanan/{id}/{idPoli}/{idPelayanan}', [PoliBpUmumController::class, 'pelayanan'])->name('ruang-layanan.pelayanan');

    //Surat
    Route::get('/simpus/pelayananDetail/surat-rujuk/{idPoli}/{idPelayanan}', [PoliBpUmumController::class, 'suratRujukList'])->name('ruang-layanan.surat-rujuk');
    Route::get('/simpus/pelayananDetail/surat-rujuk-form/{idPoli}/{idPelayanan}', [PoliBpUmumController::class, 'suratRujukForm'])->name('ruang-layanan.surat-rujuk-form');
    Route::post('/simpus/pelayananDetail/simpan-rujukan/{idPoli}', [PoliBpUmumController::class, 'simpanSuratRujuk'])->name('ruang-layanan.simpan-rujukan');
    Route::get('simpus/pelayananDetail/cetak-rujukan/{idSurat}', [PoliBpUmumController::class, 'cetakRujukan'])->name('ruang-layanan.cetak-rujukan');
    Route::get('simpus/pelayananDetail/surat-rujukan-form-edit/{idPoli}/{idPelayanan}/{idSurat}', [PoliBpUmumController::class, 'suratRujukForm'])->name('ruang-layanan.surat-rujuk-form-edit');
    Route::post('simpus/pelayananDetail/update-surat-rujukan/{idPoli}/{idSurat}', [PoliBpUmumController::class, 'simpanSuratRujuk'])->name('ruang-layanan.update-rujukan');
    Route::post('simpus/pelayananDetail/hapus-surat-rujukan/{idSurat}', [PoliBpUmumController::class, 'hapusSuratRujukan'])->name('ruang-layanan.hapus-surat-rujukan');

    Route::inertia('/simpus/umum/form-surat-keterangan', 'Ruang_Layanan/Umum/form_surat_keterangan')->name('ruang-layanan-umum.form-surat-keterangan');

    //Anamnesa
    Route::post('simpus/pelayanan/set-anamnesa-subjective/{idLoket}', [PoliBpUmumController::class, 'setAnamnesaSubjective'])->name('ruang-layanan.setAnamnesaSubjective');
    Route::post('simpus/umum/pelayanan/set-anamnesa-objective/{idAnam}', [PoliBpUmumController::class, 'setAnamnesaObjective'])->name('ruang-layanan.setAnamnesaObjective');

    //Tindakan
    Route::post('simpus/pelayanan/tindakan/{idPoli}/{idLoket}/{idPelayanan}', [PoliBpUmumController::class, 'setTindakan'])->name('ruang-layanan.simpan-Tindakan');
    Route::get('/master-tindakan', [indexController::class, 'paginasiSimpusTindakan'])->name('ruang-layanan.master-tindakan');
    Route::post('simpus/umum/pelayanan/mulaiPelayanan', [PoliBpUmumController::class, 'mulaiPemeriksaanPasien'])->name('ruang-layanan-umum.mulai-pemeriksaan-pasien');

    //Diagnosa
    Route::post('simpus/pelayanan/diagnosa-medis/{idLoket}/{idPelayanan}', [PoliBpUmumController::class, 'setDiagnosaMedis'])->name('ruang-layanan.set-diagnosa-medis');
    Route::post('simpus/diagnosa/diagnosa-keperawatan-simpan/{idLoket}/{idPelayanan}', [PoliBpUmumController::class, 'setDiagnosaKeperawatan'])->name(name: 'ruang-layanan.diagnosa-keperawatan');
    Route::get('/api/diagnosa-medis', [PoliBpUmumController::class, 'paginasi'])->name('api.diagnosa-medis');
    Route::delete('simpus/pelayanan/diagnosa-medis/{idDiagnosa}', [PoliBpUmumController::class, 'removeDiagnosaMedis'])->name('ruang-layanan.remove-diagnosa-medis');
    Route::delete('simpus/pelayanan/diagnosa-keperawatan{idDiagnosa}', [PoliBpUmumController::class, 'removeDiagnosaKeperawatan'])->name('ruang-layanan.remove-diagnosa-keperawatan');

    //Resep obat (Pengobatan Pasien)
    Route::post('simpus/pelayanan/resep-obat/{idLoket}/{idPelayanan}', [PoliBpUmumController::class, 'setResepObat'])->name('ruang-layanan.set-resep-obat');
    Route::post('simpus/pelayanan/detail-resep-obat/{idResep}/{idObat}', [PoliBpUmumController::class, 'setDetailResepObat'])->name('ruang-layanan.set-detail-resep');
    Route::delete('simpus/hapus-resep-obat/{idResepObat}', [PoliBpUmumController::class, 'hapusResepObat'])->name('ruang-layanan.hapus-resep-obat');
    Route::post('simpus/hapus-detail-resep-obat/{idDetailResepObat}', [PoliBpUmumController::class, 'hapusDetailResepObat'])->name('ruang-layanan.hapus-detail-resep-obat');

    //Simpan rujuk
    Route::post('simpus/pelayanan/simpan-rujuk/{idLoket}/{idPelayanan}', [PoliBpUmumController::class, 'simpanRujukan'])->name('ruang-layanan.simpanRujukan');
    Route::get('simpus/get-pelayanan/{idLoket}/{idPelayanan}', [PoliBpUmumController::class, 'getPelayanan'])->name('ruang-layanan.ambilPelayanan');
    Route::delete('simpus/pelayanan/hapus-rujuk/{idpelayanan}', [PoliBpUmumController::class, 'hapusRujuk'])->name('ruang-layanan.hapusRujuk');
    Route::get('simpus/pelayanan/batal-berobat-jalan/{idLoket}/{idpelayanan}', [PoliBpUmumController::class, 'batalBerobatJalan'])->name('ruang-layanan.batal-berobat-jalan');

    //Suket
    Route::get('simpus/pelayananDetail/surat-keterangan-list/{idPoli}/{idPelayanan}', [PoliBpUmumController::class, 'suketList'])->name('ruang-layanan.surat-keterangan-list');
    Route::get('simpus/pelayananDetail/create-surat-keterangan/{idPoli}/{idPelayanan}', [PoliBpUmumController::class, 'createSuratKeterangan'])->name('ruang-layanan.create-surat-keterangan');
    Route::post('simpus/pelayananDetail/simpan-surat-keterangan/{idPoli}', [PoliBpUmumController::class, 'simpanSuket'])->name('ruang-layanan.simpanSuket');
    Route::get('simpus/pelayananDetail/cetak-surat-keterangan/{idSurat}', [PoliBpUmumController::class, 'cetakSuket'])->name('ruang-layanan.cetak-suket');
    Route::post('simpus/pelayananDetail/hapus-surat-keterangan/{idSurat}', [PoliBpUmumController::class, 'hapusSuket'])->name('ruang-layanan.hapus-suket');
    Route::get('simpus/pelayananDetail/edit-surat-keterangan//{idPoli}/{idPelayanan}/{idSurat}', [PoliBpUmumController::class, 'editSuket'])->name('ruang-layanan.edit-suket');

    Route::post('simpus/update-surat-keterangan', [PoliBpUmumController::class, 'updateSuket'])->name('ruang-layanan.update-suket');
    Route::get('simpus/laborat/{idPoli}/{idLoket}/{idPelayanan}', [PoliBpUmumController::class, 'formLaborat'])->name('ruang-layanan.form-laborat');

    Route::post('simpus/umum/laborat/simpan-permohonan-lab/{idLoket}', [PoliBpUmumController::class, 'simpanPermohonanLab'])->name('ruang-layanan.simpan-permohonan-lab');
    Route::get('simpus/laborat/list-permohonan/{idLoket}', [indexController::class, 'getPermohonanLaborat'])->name('ruang-layanan.getPermonanLab');
    //riwayat pasien
    Route::get('simpus/riwayat-pasien/{idPoli}/{idPasien}', [PoliBpUmumController::class, 'riwayatPasien'])->name('ruang-layanan.riwayat-pasien');

    //CPPT
    Route::get('simpus/cppt/{idPoli}/{idPasien}', [PoliBpUmumController::class, 'getCppt'])->name('ruang-layanan.cppt');
    //UKK
    Route::get('simpus/get-ukk/{idLoket}', [PoliBpUmumController::class, 'getUKK'])->name('ruang-layanan.get-ukk');
    Route::post('simpus/simpan-ukk/{idLoket}', [PoliBpUmumController::class, 'simpanUkk'])->name('ruang-layanan.simpan-ukk');
    //Gigi
    Route::get('/simpus/gigi', [PoliGigiController::class, 'index'])->name('ruang-layanan.gigi');
    Route::get('/simpus/gigi/pelayanan/{id}', [PoliGigiController::class, 'pelayanan'])->name('ruang-layanan-gigi.pelayanan');
    Route::post('simpus/gigi/pelayanan/anamnesa-subjective', [PoliGigiController::class, 'setAnamnesaSubjective'])->name('ruang-layanan-gigi.setAnamnesaSubjective');
    Route::post('simpus/gigi/pelayanan/anamnesa-objective', [PoliGigiController::class, 'setAnamnesaObjective'])->name('ruang-layanan-gigi.setAnamnesaObjective');
    //Diagnosa
    Route::post('simpus/gigi/pelayanan/diagnosa-medis', [PoliGigiController::class, 'setDiagnosaMedis'])->name('ruang-layanan-gigi.diagnosa-medis');
    Route::delete('simpus/gigi/pelayanan/diagnosa-medis/{id}', [PoliGigiController::class, 'removeDiagnosaMedis'])->name('ruang-layanan-gigi.remove-diagnosa-medis');

    Route::post('simpus/gigi/pelayanan/planning-tindakan', [PoliGigiController::class, 'setPlanningTindakan'])->name('ruang-layanan-gigi.set-PlanningTindakan');
    Route::delete('simpus/gigi/pelayanan/planning-tindakan/{id}', [PoliGigiController::class, 'removePlanningTindakan'])->name('ruang-layanan-gigi.remove-data-tindakan');
    Route::post('simpus/gigi/pelayanan/planning-pengobatan', [PoliGigiController::class, 'setPlanningPengobatan'])->name('ruang-layanan-gigi.set-PlanningPengobatan');
    Route::post('simpus/gigi/pelayanan/planning-pengobatan-detail', [PoliGigiController::class, 'setPlanningPengobatandetail'])->name('ruang-layanan-gigi.set-PlanningPengobatanDetail');
    Route::get('/master-obat', [indexController::class, 'MasterObat'])->name('ruang-layanan.master-obat');

    //UGD
    Route::inertia('/simpus/ugd', 'Ruang_Layanan/UGD/pasien_poli')->name('ruang-layanan.ugd');
    Route::inertia('/simpus/ugd/pelayanan', 'Ruang_Layanan/UGD/pelayanan')->name('ruang-layanan-ugd.pelayanan');

    //KB
    Route::inertia('/simpus/kb', 'Ruang_Layanan/KB/pasien_poli')->name('ruang-layanan.kb');
    Route::inertia('/simpus/kb/pelayanan', 'Ruang_Layanan/KB/pelayanan')->name('ruang-layanan-kb.pelayanan');

    //Ranao 
    Route::inertia('/simpus/ranap', 'Ruang_Layanan/UGD/pasien_poli')->name('ruang-layanan.ranap');


    // 🔹 Kunjungan Online
    // 🔹 Kunjungan Online
    Route::get('simpus/kunjungan-online', [KunjOnlineController::class, 'index'])
        ->name('ruang-layanan.kunjungan-online');

    Route::get('simpus/kunjungan-online/pelayanan/{id}', [KunjOnlineController::class, 'pelayanan'])
        ->name('ruang-layanan.kunjungan-online.pelayanan');

    Route::post('simpus/kunjungan-online/pelayanan/anamnesa', [KunjOnlineController::class, 'setAnamnesa'])
        ->name('ruang-layanan-kunjungan-online.setAnamnesa');

    Route::post('simpus/kunjungan-online/pelayanan/anamnesa/objective', [KunjOnlineController::class, 'setAnamnesaObjective'])
        ->name('ruang-layanan-kunjungan-online.setAnamnesaObjective');

    Route::post('simpus/kunjungan-online/pelayanan/mulaiPelayanan', [KunjOnlineController::class, 'mulaiPemeriksaanPasien'])
        ->name('ruang-layanan-kunjungan-online.mulai-pemeriksaan-pasien');

    Route::post('simpus/kunjungan-online/pelayanan/diagnosa-medis', [KunjOnlineController::class, 'setDiagnosaMedis'])
        ->name('ruang-layanan-kunjungan-online.diagnosa-medis');

    // Halaman daftar surat rujukan (opsional)
    Route::get('simpus/kunjungan-online/surat-rujukan/{id}', [KunjOnlineController::class, 'suratRujukan'])
        ->name('ruang-layanan.kunjungan-online.surat-rujukan');

    // Tampilkan form Surat Rujukan
    Route::get('simpus/kunjungan-online/{id}/surat-rujukan/create', [KunjOnlineController::class, 'createSuratRujukan'])
        ->name('ruang-layanan.kunjungan-online.surat-rujukan.create');

    // Simpan Surat Rujukan
    Route::post('simpus/kunjungan-online/{id}/surat-rujukan', [KunjOnlineController::class, 'storeSuratRujukan'])
        ->name('ruang-layanan.kunjungan-online.surat-rujukan.store');

    // Riwayat Kesehatan (Medical Record) Pasien
    Route::get(
        'simpus/kunjungan-online/{id}/riwayat-kesehatan',
        [KunjOnlineController::class, 'riwayatKesehatan']
    )->name('ruang-layanan.kunjungan-online.riwayat-kesehatan');


    // CPPT (Catatan Perkembangan Pasien Terintegrasi)
    Route::get('simpus/kunjungan-online/{id}/cppt', [KunjOnlineController::class, 'cppt'])
        ->name('ruang-layanan.kunjungan-online.cppt');




    //Sanitasi
    Route::inertia('/simpus/sanitasi', 'Ruang_Layanan/Sanitasi/pasien_poli')->name('ruang-layanan.sanitasi');
    Route::inertia('/simpus/sanitasi/pelayanan', 'Ruang_Layanan/Sanitasi/pelayanan')->name('ruang-layanan.sanitasi.pelayanan');
    //Gizi
    Route::inertia('/simpus/gizi', 'Ruang_Layanan/Gizi/pasien_poli')->name('ruang-layanan.gizi');
    Route::inertia('/simpus/gizi/pelayanan', 'Ruang_Layanan/Gizi/pelayanan')->name('ruang-layanan.gizi.pelayanan');

    // Kia
    Route::inertia('/simpus/kia', 'Ruang_Layanan/KIA/index')->name('ruang-layanan.kia');

    //ANC
    Route::inertia('/simpus/kia/anc1', 'Ruang_Layanan/KIA/ANC/Index')->name('ruang-layanan.anc1');
    Route::get('/simpus/kia/anc', [AncController::class, 'index'])->name('ruang-layanan.anc');
    Route::get('/simpus/kia/anc/pelayanan/{id}/{idPoli}/{idPelayanan}', [AncController::class, 'pelayanan'])->name('ruang-layanan-anc.pelayanan');
    Route::post('simpus/kia/anc/pelayanan/', [AncController::class, 'setKunjunganANC'])->name('ruang-layanan-anc.kunjunganANC');
    Route::post('simpus/kia/anc/pelayanan/obstetri', [AncController::class, 'setObstetri'])->name('ruang-layanan-anc.obstetri');
    Route::post('simpus/kia/anc/pelayanan/DataDiagnosa', [AncController::class, 'setDataDiagnosa'])->name('ruang-layanan-anc.dataDiagnosa');
    Route::delete('simpus/kia/anc/pelayanan/DataDiagnosa/{id}', [AncController::class, 'hapusDataDiagnosa'])->name('diagnosa.destroy');
    Route::post('simpus/kia/anc/pelayanan/diagnosaKep', [AncController::class, 'setDataDiagnosaKep'])->name('ruang-layanan-anc.diagnosaKep');

    // Route::get('/simpus/kia/ruang-layanan', [PoliKIAController::class, 'index'])->name('ruang-layanan.kia');
    Route::get('/simpus/kia/pelayanan/{id}/{idPoli}/{idPelayanan}', [PoliKIAController::class, 'pelayanan'])->name('ruang-layanan-kia.pelayanan');
    // Route::get('/api/kia/cari-diagnosa', [PoliKIAController::class, 'searchDiagnosa'])->name('api.cari-diagnosa');




    //Rawat Inap
    Route::inertia('/simpus/rawat-inap', 'Ruang_Layanan/RawatInap/index')->name('ruang-layanan.rawat-inap');
    Route::inertia('/simpus/rawat-inap/penerimaan-pasien', 'Ruang_Layanan/RawatInap/PenerimaanPasien/pasien_poli')->name('ruang-layanan.rawat-inap.penerimaan-pasien');
    Route::inertia('/simpus/rawat-inap/perawatan', 'Ruang_Layanan/RawatInap/DataKeperawatan/DataRanapKeperawatan')->name('ruang-layanan.rawat-inap.perawatan');
    Route::inertia('/simpus/rawat-inap/pengeluaran', 'Ruang_Layanan/RawatInap/PasienKeluar/DataPasienKeluar')->name('ruang-layanan.rawat-inap.pengeluaran');
});

    //Laborat
    // Route::inertia('/simpus/laborat', 'Ruang_Layanan/Laborat/index')->name('ruang-layanan.laborat');
    Route::get('/simpus/laborat', [LaboratoriumController::class, 'index'])
        ->middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':laborat'])
        ->name('ruang-layanan.laborat');


    Route::get('/simpus/laborat/pemeriksaan/{loketId}', [LaboratoriumController::class, 'pemeriksaan'])
        ->middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':laborat'])
        ->name('ruang-layanan.laborat.pemeriksaan');

    Route::post('/simpus/laborat/set-waktu-sample', [LaboratoriumController::class, 'setWaktuSample'])
        ->middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':laborat'])
        ->name('ruang-layanan.laborat.setWaktuSample');

    Route::post('/simpus/laborat/update-nilai', [LaboratoriumController::class, 'updateNilaiLab'])
        ->middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':laborat'])
        ->name('ruang-layanan.laborat.updateNilaiLab');

    // === endpoint untuk modal master/paket & cetak ===
    Route::get('/simpus/laborat/paginasi-master-pemeriksaan', [LaboratoriumController::class, 'paginasiMasterPemeriksaan'])
        ->middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':laborat'])
        ->name('ruang-layanan.laborat.paginasiMasterPemeriksaan');

    Route::post('/simpus/laborat/permohonan/simpan', [LaboratoriumController::class, 'simpanPermohonan'])
        ->middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':laborat'])
        ->name('ruang-layanan.laborat.simpanPermohonan');

    Route::post('/simpus/laborat/pemeriksaan/simpan', [LaboratoriumController::class, 'pemeriksaanSimpan'])
        ->middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':laborat'])
        ->name('ruang-layanan.laborat.pemeriksaanSimpan');

    Route::post('/simpus/laborat/pemeriksaan/paket/{paket}', [LaboratoriumController::class, 'paketPemeriksaanSimpan'])
        ->middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':laborat'])
        ->name('ruang-layanan.laborat.paketPemeriksaanSimpan');

    Route::get(
        '/simpus/laborat/detail/{idPermohonan}',
        [LaboratoriumController::class, 'detail']
    )->name('ruang-layanan.laborat.detail');
// Paket dari parameter_uji
Route::get('/simpus/laborat/param/headers',
  [\App\Http\Controllers\RuangLayanan\LaboratoriumController::class, 'paramHeaders']
)->name('ruang-layanan.laborat.param.headers');

Route::get('/simpus/laborat/param/{header}/subheaders',
  [\App\Http\Controllers\RuangLayanan\LaboratoriumController::class, 'paramSubheaders']
)->whereNumber('header')
 ->name('ruang-layanan.laborat.param.subheaders');

Route::post('/simpus/laborat/param/{header}/simpan',
  [\App\Http\Controllers\RuangLayanan\LaboratoriumController::class, 'paramSimpan']
)->whereNumber('header')
 ->name('ruang-layanan.laborat.param.simpan');

Route::post('/simpus/laborat/tindakan/hapus',[LaboratoriumController::class, 'hapusTindakan'])->name('ruang-layanan.laborat.hapusTindakan');
// LIST semua parameter_uji (bisa search + filter paket) — paginated
Route::get('/simpus/laborat/param/browse',
  [\App\Http\Controllers\RuangLayanan\LaboratoriumController::class, 'paramBrowse']
)->name('ruang-layanan.laborat.param.browse');

// Simpan pilihan manual (by id_parameter[])
Route::post('/simpus/laborat/param/simpan-terpilih',
  [\App\Http\Controllers\RuangLayanan\LaboratoriumController::class, 'paramSimpanTerpilih']
)->name('ruang-layanan.laborat.param.simpanTerpilih');

    // Kia
    Route::inertia('/simpus/kia', 'Ruang_Layanan/KIA/index')->name('ruang-layanan.kia');

    //ANC
    Route::inertia('/simpus/kia/anc1', 'Ruang_Layanan/KIA/ANC/Index')->name('ruang-layanan.anc1');
    Route::get('/simpus/kia/anc', [AncController::class, 'index'])->name('ruang-layanan.anc');
    Route::get('/simpus/kia/anc/pelayanan/{id}/{idPoli}/{idPelayanan}', [AncController::class, 'pelayanan'])->name('ruang-layanan-anc.pelayanan');
    Route::post('simpus/kia/anc/pelayanan/', [AncController::class, 'setKunjunganANC'])->name('ruang-layanan-anc.kunjunganANC');
    Route::post('simpus/kia/anc/pelayanan/obstetri', [AncController::class, 'setObstetri'])->name('ruang-layanan-anc.obstetri');
    Route::post('simpus/kia/anc/pelayanan/DataDiagnosa', [AncController::class, 'setDataDiagnosa'])->name('ruang-layanan-anc.dataDiagnosa');
    Route::delete('simpus/kia/anc/pelayanan/DataDiagnosa/{id}', [AncController::class, 'hapusDataDiagnosa'])->name('diagnosa.destroy');
    Route::post('simpus/kia/anc/pelayanan/diagnosaKep', [AncController::class, 'setDataDiagnosaKep'])->name('ruang-layanan-anc.diagnosaKep');

    // Route::get('/simpus/kia/ruang-layanan', [PoliKIAController::class, 'index'])->name('ruang-layanan.kia');
    Route::get('/simpus/kia/pelayanan/{id}/{idPoli}/{idPelayanan}', [PoliKIAController::class, 'pelayanan'])->name('ruang-layanan-kia.pelayanan');
    
    // Route::get('/api/kia/cari-diagnosa', [PoliKIAController::class, 'searchDiagnosa'])->name('api.cari-diagnosa');
    // Kematian Maternal dan Perinatal
    Route::get('/simpus/kia/kematian', [KematianController::class, 'index'])->name('ruang-layanan.kematian');
    Route::get('/simpus/kia/kematian/pelayanan/{id}/{idPoli}/{idPelayanan}', [KematianController::class, 'pelayanan'])->name('ruang-layanan-kematian.pelayanan');


    Route::get(
        '/simpus/laborat/param/{header}/subheaders',
        [\App\Http\Controllers\RuangLayanan\LaboratoriumController::class, 'paramSubheaders']
    )->whereNumber('header')
        ->name('ruang-layanan.laborat.param.subheaders');

    Route::post(
        '/simpus/laborat/param/{header}/simpan',
        [\App\Http\Controllers\RuangLayanan\LaboratoriumController::class, 'paramSimpan']
    )->whereNumber('header')
        ->name('ruang-layanan.laborat.param.simpan');

    Route::post('/simpus/laborat/tindakan/hapus', [LaboratoriumController::class, 'hapusTindakan'])->name('ruang-layanan.laborat.hapusTindakan');
    // LIST semua parameter_uji (bisa search + filter paket) — paginated
    Route::get(
        '/simpus/laborat/param/browse',
        [\App\Http\Controllers\RuangLayanan\LaboratoriumController::class, 'paramBrowse']
    )->name('ruang-layanan.laborat.param.browse');

    // Simpan pilihan manual (by id_parameter[])
    Route::post(
        '/simpus/laborat/param/simpan-terpilih',
        [\App\Http\Controllers\RuangLayanan\LaboratoriumController::class, 'paramSimpanTerpilih']
    )->name('ruang-layanan.laborat.param.simpanTerpilih');



// =================== HALAMAN OWNER (Inertia) ===================
Route::get('/owner', [PanelController::class, 'index'])
    ->middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':owner'])
    ->name('owner.panel');

// =================== API OWNER (session-based) =================
Route::prefix('api/owner')
    ->middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':owner'])
    ->group(function () {
        Route::get('/roles', [OwnerController::class, 'roles']);
        Route::get('/puskesmas', [OwnerController::class, 'puskesmas']);
        Route::get('/users', [OwnerController::class, 'users']);
        Route::post('/users', [OwnerController::class, 'storeUser']); // tambah user
        Route::patch('/users/{id}/roles', [OwnerController::class, 'updateRoles']);
        Route::post('/users/{id}/force-logout', [OwnerController::class, 'forceLogout']);
        Route::get('/online-users', [OwnerController::class, 'onlineUsers']); // opsional
    });
Route::post('/auth/password/force-update', [PasswordForceController::class, 'update'])
    ->middleware(['auth'])
    ->name('auth.password.force-update');
// =================== API OWNER (session-based) =================
Route::prefix('api/owner')
    ->middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':owner'])
    ->group(function () {
        Route::get('/roles', [OwnerController::class, 'roles']);
        Route::get('/puskesmas', [OwnerController::class, 'puskesmas']);
        Route::get('/users', [OwnerController::class, 'users']);
        Route::post('/users', [OwnerController::class, 'storeUser']); // tambah user
        Route::patch('/users/{id}/roles', [OwnerController::class, 'updateRoles']);
        Route::post('/users/{id}/force-logout', [OwnerController::class, 'forceLogout']);
        Route::get('/online-users', [OwnerController::class, 'onlineUsers']); // opsional
    });
Route::post('/auth/password/force-update', [PasswordForceController::class, 'update'])
    ->middleware(['auth'])
    ->name('auth.password.force-update');
// =================== JSON + PAGE: Log Hapus Pasien (Loket) ===================
Route::middleware(['auth', \App\Http\Middleware\Auth\CheckRole::class . ':owner'])
    ->group(function () {
        // JSON endpoint (dipakai halaman khusus log)
        Route::get('/owner/loket-delete-logs', [OwnerLogController::class, 'loketDeletes'])
            ->middleware('throttle:60,1')
            ->name('owner.loket-delete-logs');

        // Halaman khusus log (Inertia)
        Route::get('/owner/logs/loket-delete', function () {
            return Inertia::render('Owner/LoketDeleteLogs');
        })->name('owner.logs.loket');

        // ⬇️ ini yang baru
        Route::patch('/users/{id}/password-changed', [OwnerController::class, 'updatePasswordChanged'])
            ->name('owner.users.password_changed');
    });
Route::delete('/users/{id}', [OwnerController::class, 'destroyUser'])
    ->name('owner.users.destroy'); // ⬅️ beri nama


Route::get('/cek-db', function () {
    $tables = DB::select('SHOW TABLES');
    return response()->json($tables);
});
