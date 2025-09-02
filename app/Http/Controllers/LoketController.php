<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Loket;
use App\Models\Poli;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\MKeluarga;
use App\Models\Agama;
use App\Models\Pekerjaan;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoketController extends Controller
{
    public function index()
    {
        // Default: tampilkan poli untuk kunjungan sakit
        $poliList = Poli::sakit()->aktif()->get(['kdPoli', 'nmPoli']);

        return Inertia::render('Loket/Index', [
            'loket' => Loket::with(['pasien', 'poli'])
                ->orderBy('tglKunjungan', 'desc')
                ->paginate(10),
            'poliList' => $poliList,
            'pasienId' => request('pasienId', null),
        ]);
    }

    public function create()
    {
        return Inertia::render('Loket/AddPasien', [
            'kecamatanList' => Kecamatan::where('NO_PROP', '35')
                ->where('NO_KAB', '10')
                ->orderBy('NAMA_KEC')
                ->get(),
            'defaultPropinsi' => 'JAWA TIMUR',
            'defaultKabupaten' => 'KAB. BANYUWANGI',
            'hubunganKeluargaList' => MKeluarga::all(),
            'agamaList' => Agama::orderBy('NO')->get(),
            'poliList' => Poli::all(),
            'pekerjaanList' => Pekerjaan::orderBy('NO')->get(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Dapatkan SEQ terakhir
            $lastSeq = Pasien::max('SEQ') ?? 0;
            $nextSeq = $lastSeq + 1;

            $formattedSeq = str_pad($nextSeq, 5, '0', STR_PAD_LEFT);
            $noMr = '01' . $formattedSeq;

            $data = $request->all();
            $data['SEQ'] = $nextSeq;
            $data['NO_MR'] = $noMr;

            $pasien = Pasien::create($data);

            DB::commit();

            return redirect()->route('loket.index')
                ->with('success', 'Pasien berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function search(Request $request)
    {
        $query = Pasien::query();

        if ($request->filled('nama')) {
            $query->where('NAMA_LGKP', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('nik')) {
            $query->where('NIK', $request->nik);
        }

        if ($request->filled('no_mr')) {
            $query->where('NO_MR', $request->no_mr);
        }

        if ($request->filled('no_bpjs')) {
            $query->where('noKartu', $request->no_bpjs);
        }

        $results = $query->with(['kecamatan', 'kelurahan'])
            ->limit(50)
            ->get();

        return Inertia::render('Loket/Search', [
            'results' => $results,
            'searchParams' => $request->all(),
            'kecamatanList' => Kecamatan::all(),
        ]);
    }

    public function apiSearch(Request $request)
    {
        $query = Pasien::query();

        if ($request->filled('no_mr')) {
            $query->where('NO_MR', $request->no_mr);
        }

        if ($request->filled('nik')) {
            $query->where('NIK', $request->nik);
        }

        if ($request->filled('no_bpjs')) {
            $query->where('noKartu', $request->no_bpjs);
        }

        $pasien = $query->first();

        if (!$pasien) {
            return response()->json(['error' => 'Pasien tidak ditemukan'], 404);
        }

        return response()->json($pasien);
    }

    public function register(Request $request)
    {
        $data = $request->all();

        Log::info('Data register:', $data);

        // Generate nomor urut
        $lastNoUrut = Loket::whereDate('tglKunjungan', $data['tglKunjungan'])
            ->max('noUrut') ?? 0;

        $data['noUrut'] = str_pad($lastNoUrut + 1, 4, '0', STR_PAD_LEFT);

        $data['createdBy'] = 'test_user';

        // Konversi boolean untuk database
        $data['kunjSakit'] = $data['jenisKunjungan'] === 'Kunjungan Sakit';
        $data['kunjBaru'] = $data['jenisPengunjung'] === 'Pengunjung Baru';

        // Set nilai default untuk field yang required
        $defaultValues = [
            'kelUmur' => 1,
            'umur' => 30,
            'jknPbi' => $data['kategori'],
            'statusPasien' => 'umum',
            'kdProvider' => $data['kategori'] === 'BPJS' ? 'BPJS' : '',
            'statusKartu' => $data['kategori'] === 'BPJS' ? 'Aktif' : '',
            'noKartu' => $data['kategori'] === 'BPJS' ? ($request->no_bpjs || '') : '',
            'kdKegiatan' => 1,
            'puskId' => 1,
            'unitId' => 1,
            'kategoriUnitId' => 1,
        ];

        // Gabungkan data dengan default values
        $data = array_merge($defaultValues, $data);

        // Simpan ke database
        try {
            $loket = Loket::create($data);

            Log::info('Loket created successfully:', $loket->toArray());

            return redirect()->route('loket.index')
                ->with('success', 'Pasien berhasil didaftarkan');
        } catch (\Exception $e) {
            Log::error('Error creating loket:', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Gagal mendaftarkan pasien: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function getProvinsiList()
    {
        $provinsi = Provinsi::orderBy('NAMA_PROP')->get(['NO_PROP', 'NAMA_PROP']);
        return response()->json($provinsi);
    }

    public function getKabupatenByProvinsi(Request $request)
    {
        $kabupaten = Kabupaten::where('NO_PROP', $request->provinsi)
            ->orderBy('NAMA_KAB')
            ->get(['NO_KAB', 'NAMA_KAB']);

        return response()->json($kabupaten);
    }

    public function getKecamatanList(Request $request)
    {
        $query = Kecamatan::query();

        $no_prop = $request->propinsi ?? '35';
        $query->where('NO_PROP', $no_prop);

        $no_kab = $request->kabupaten ?? '10';
        $query->where('NO_KAB', $no_kab);

        return response()->json(
            $query->orderBy('NAMA_KEC')
                ->get(['NO_KEC', 'NAMA_KEC'])
        );
    }

    public function getKelurahanByKecamatan(Request $request)
    {
        $kelurahan = Kelurahan::where('NO_KEC', $request->kecamatan)
            ->orderBy('NAMA_KEL')
            ->get(['NO_KEL', 'NAMA_KEL']);

        return response()->json($kelurahan);
    }

    public function getPoliByJenisKunjungan(Request $request)
    {
        if ($request->jenisKunjungan === 'Kunjungan Sakit') {
            $poliList = Poli::sakit()->aktif()->get(['kdPoli', 'nmPoli']);
        } else {
            $poliList = Poli::sehat()->aktif()->get(['kdPoli', 'nmPoli']);
        }

        return response()->json($poliList);
    }
}
