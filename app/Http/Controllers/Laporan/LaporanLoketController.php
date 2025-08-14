<?php

// app/Http/Controllers/Laporan/LaporanLoketController.php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Laporan\Loket;
use App\Models\Laporan\Pasien;

class LaporanLoketController extends Controller
{
    public function index()
    {
        return Inertia::render('Laporan/Loket/Loket');
    }

public function tampil(Request $request)
{
    // Ambil query parameter dari Vue
    $jenis = $request->query('jenis');
    $tglAwal = $request->query('tglAwal');
    $tglAkhir = $request->query('tglAkhir');
    $unit = $request->query('unit');
    $subunit = $request->query('subunit');
    $desa = $request->query('desa');

    // Ambil data Loket + relasi Pasien
    $query = Loket::with('pasien')->latest('tglKunjungan');

    // Filter tanggal kunjungan
    if ($tglAwal && $tglAkhir) {
        $query->whereBetween('tglKunjungan', [$tglAwal, $tglAkhir]);
    }

    // Filter unit jika dipilih
    if ($unit && $unit !== '- Pilih -') {
        $query->where('unit', $unit);
    }

    // Filter sub unit jika dipilih
    if ($subunit && $subunit !== '- Pilih -') {
        $query->where('subunit', $subunit);
    }

    // Filter desa jika dipilih
    if ($desa && $desa !== '- SEMUA -') {
        $query->where('desa', $desa);
    }

    // Ambil 50 data terbaru
    $dataKunjungan = $query->take(50)->get()->map(function ($loket) {
        $pasien = $loket->pasien;

        return [
            'tanggal_kunjungan' => $loket->tglKunjungan,
            'nama' => $pasien?->NAMA_LGKP ?? '-',
            'ALAMAT' => $pasien?->ALAMAT ?? '-',
            'no_bpjs' => $loket->noKartu,
            'no_pasien' => $loket->pasienId,
            'jenis_kelamin' => $pasien?->JENIS_KLMIN == 1 ? 'L' : ($pasien?->JENIS_KLMIN == 2 ? 'P' : '-'),
            'golongan_umur' => $this->hitungGolonganUmur($loket->umur),
            'status' => strtoupper($loket->kunjBaru) === 'BARU' ? 'BARU' : 'LAMA',
        ];
    });

    return Inertia::render('Laporan/Loket/Tampilkan-laporan-loket/Tampilkan-laporan-loket', [
        'jenis' => $jenis,
        'dataKunjungan' => $dataKunjungan,
    ]);
}


    private function hitungGolonganUmur($umur)
    {
        if ($umur < 1) return '<1';
        elseif ($umur <= 4) return '1-4';
        elseif ($umur <= 9) return '5-9';
        elseif ($umur <= 14) return '10-14';
        elseif ($umur <= 19) return '15-19';
        elseif ($umur <= 44) return '20-44';
        elseif ($umur <= 54) return '45-54';
        elseif ($umur <= 59) return '55-59';
        elseif ($umur <= 69) return '60-69';
        else return '>70';
    }
}
