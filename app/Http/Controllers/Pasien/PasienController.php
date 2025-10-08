<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Filter\SetupKabupaten;
use App\Models\Filter\SetupProvinsi;
use App\Models\Filter\SimpusPasien;
use App\Models\Pasien\Agama;
use App\Models\Pasien\HubKeluarga;
use App\Models\Pasien\Kabupaten;
use App\Models\Pasien\Kecamatan;
use App\Models\Pasien\Kelurahan;
use App\Models\Pasien\MasterPekerjaan;
use App\Models\Pasien\Pasien;
use App\Models\Pasien\Provinsi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PasienController extends Controller
{
    public function index()
    {
        $pasien = Pasien::query()
            ->leftJoin('setup_kec', function ($join) {
                $join->on('setup_kec.NO_KEC', '=', 'simpus_pasien.NO_KEC')
                    ->on('setup_kec.NO_KAB', '=', 'simpus_pasien.NO_KAB')
                    ->on('setup_kec.NO_PROP', '=', 'simpus_pasien.NO_PROP');
            })
            ->leftJoin('setup_kel', function ($join) {
                $join->on('setup_kel.NO_KEL', '=', 'simpus_pasien.NO_KEL')
                    ->on('setup_kel.NO_KEC', '=', 'simpus_pasien.NO_KEC')
                    ->on('setup_kel.NO_KAB', '=', 'simpus_pasien.NO_KAB')
                    ->on('setup_kel.NO_PROP', '=', 'simpus_pasien.NO_PROP');
            })
            ->select(
                'simpus_pasien.*',
                'setup_kec.NAMA_KEC as nama_kecamatan',
                'setup_kel.NAMA_KEL as nama_kelurahan',
            )
            ->get();

        return Inertia::render('Loket/SearchPasien', [
            'pasien' => $pasien,
        ]);
    }
    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        $agama = Agama::all();
        $pekerjaan = MasterPekerjaan::all();
        $keluarga = HubKeluarga::all();
        $provinsi = Provinsi::all();
        $kabupaten = Kabupaten::all();
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        return Inertia::render('Loket/EditPasien', [
            'pasien' => $pasien,
            'agama' => $agama,
            'pekerjaan' => $pekerjaan,
            'hubKeluarga' => $keluarga,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:60',
            'nik' => 'required|string|max:16',
            'alamat' => 'required|string|max:160',
            'tanggal_lahir' => 'required|date',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
        ]);

        $pasien = Pasien::findOrFail($id);
        $pasien->update([
            'NAMA_LGKP' => $request->nama,
            'NIK' => $request->nik,
            'ALAMAT' => $request->alamat,
            'TGL_LHR' => $request->tanggal_lahir,
            'NO_KAB' => $request->kabupaten,
            'NO_KEC' => $request->kecamatan,
            'NO_KEL' => $request->kecamatan,
        ]);

        return Inertia::location(route('loket.search'));
    }
}
