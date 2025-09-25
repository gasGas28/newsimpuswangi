<?php

namespace App\Http\Controllers\MalSehat;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\MalSehat\UnitProfile;
use App\Models\MalSehat\MasterAlergi;
use App\Models\MalSehat\MasterDokter;
use App\Models\MalSehat\MasterDiagnosaKasus;
use App\Models\MalSehat\SimpusPasien;

class PromkesController extends Controller
{
    public function kesehatanPeduliRemaja()
    {
        // ✅ tambahkan where untuk filter no_kec = 18
        $units = UnitProfile::where('id_unit', 1)
            ->select('nama_unit')
            ->get();

        // ✅ ambil pasien
        $pasien = SimpusPasien::where('ACTIVE', 1)
            ->select(
                'ID',                     // ✅ ambil id sebagai no urut
                'created',
                'NO_MR as no_mr',
                'NIK as nik',
                'NAMA_LGKP as nama',
                'ALAMAT as alamat',
                'NO_KEL as desa',
                'noKartu as bpjs'
            )
            ->limit(50)
            ->get();

        return Inertia::render('MalSehat/Promkes/KesehatanPeduliRemaja', [
            'units' => $units,
            'pasien' => $pasien
        ]);
    }

    public function pelayanan($no_mr)
    {

        // ✅ Ambil pasien sesuai no_mr
        $pasien = SimpusPasien::where('NO_MR', $no_mr)
        ->select(
            'NO_MR as no_mr',
            'NIK as nik',
            'NAMA_LGKP as nama',
            'ALAMAT as alamat',
            'NO_KEL as desa',
            'noKartu as bpjs',
            'PHONE as phone',
            'TGL_LHR as tgl_lahir',     // ⬅️ ambil tanggal lahir
            'JENIS_KLMIN as jenis_kelamin', // ⬅️ ambil jenis kelamin (1=L, 2=P)
            'created as tanggal'
            
        )
        ->first();

        if ($pasien) {
            // 🔹 Hitung umur (dalam tahun)
            $birthDate = new \DateTime($pasien->tgl_lahir);
            $today     = new \DateTime();
            $age       = $today->diff($birthDate)->y;

            // 🔹 Mapping jenis kelamin (1=Laki-laki, 2=Perempuan)
            $jk = ($pasien->jenis_kelamin == 1) ? 'L' : 'P';

            // Tambahkan ke objek agar bisa langsung dipakai di Vue
            $pasien->umur = $age . ' tahun';
            $pasien->jk   = $jk;
        }

        // alergi
        $alergiMakanan = MasterAlergi::where('category', 1)->get();
        $alergiObat    = MasterAlergi::where('category', 2)->get();

        // dokter
        $dokter = MasterDokter::select('idDokter', 'nmDokter', 'kdDokter')->get();

        // kasus
        $kasus         = MasterDiagnosaKasus::select('id', 'kasus')->get();

        // mapping jenis_kelamin ke huruf
        $pasien->jk = $pasien->jenis_kelamin == 1 ? 'L' : 'P';
        
        return Inertia::render('MalSehat/Promkes/Pelayanan', [
            'no_mr'         => $no_mr,
            'alergiMakanan' => $alergiMakanan,
            'alergiObat'    => $alergiObat,
            'dokter'        => $dokter,
            'kasus'         => $kasus,
            'pasien'        => $pasien
        ]);
    }

}
