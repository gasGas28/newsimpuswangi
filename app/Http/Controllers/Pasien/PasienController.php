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
use Illuminate\Support\Facades\Validator;
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

        // dd($pasien);

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
        $kabupaten = Kabupaten::where('NO_PROP', $pasien->NO_PROP)->get();

        // Filter kecamatan berdasarkan provinsi + kabupaten pasien
        $kecamatan = Kecamatan::where('NO_PROP', $pasien->NO_PROP)
            ->where('NO_KAB', $pasien->NO_KAB)
            ->get();

        // Filter kelurahan berdasarkan provinsi + kabupaten + kecamatan pasien
        $kelurahan = Kelurahan::where('NO_PROP', $pasien->NO_PROP)
            ->where('NO_KAB', $pasien->NO_KAB)
            ->where('NO_KEC', $pasien->NO_KEC)
            ->get();

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
        $validated = Validator::make($request->all(), [
            'nama' => 'required|string|max:60',
            'nik' => 'required|string|max:16',
            'alamat' => 'required|string|max:160',
            'tanggal_lahir' => 'required|date',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
        ])->validate();

        $pasien = Pasien::findOrFail($id);
        $pasien->update([
            'NAMA_LGKP' => $validated['nama'],
            'NIK' => $validated['nik'],
            'ALAMAT' => $validated['alamat'],
            'TGL_LHR' => $validated['tanggal_lahir'],
            'NO_PROP' => $validated['provinsi'],
            'NO_KAB' => $validated['kabupaten'],
            'NO_KEC' => $validated['kecamatan'],
            'NO_KEL' => $validated['kelurahan'],
        ]);


        return Inertia::location(route('loket.search'));
    }
}
