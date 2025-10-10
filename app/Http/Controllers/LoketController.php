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
use App\Services\LoketService;
use Carbon\Carbon;

class LoketController extends Controller
{
    protected $service;

    public function __construct(LoketService $service)
    {
        // NOTE: autentikasi/middleware ditunda sesuai permintaan
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $poliList = Poli::query()->where('kategori', 'sakit')->orWhere('tipe', 'sakit')->get(['kdPoli', 'nmPoli']);
        $loket = Loket::with(['pasien', 'poli'])
            ->orderBy('tglKunjungan', 'desc')
            ->paginate(10);

        return Inertia::render('Loket/Index', [
            'loket' => $loket,
            'poliList' => $poliList,
            'pasienId' => $request->query('pasienId', null),
            'message' => session('message'),
        ]);
    }

    // ajax_list -> sebagai API endpoint untuk DataTables
    public function ajaxList(Request $request)
    {
        $result = $this->service->datatable($request->all());
        return response()->json($result);
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
        DB::beginTransaction();

        try {
            $lastSeq = Pasien::max('SEQ') ?? 0;
            $nextSeq = $lastSeq + 1;
            $formattedSeq = str_pad($nextSeq, 5, '0', STR_PAD_LEFT);
            $noMr = '01' . $formattedSeq;

            $data = $request->all();
            $data['SEQ'] = $nextSeq;
            $data['NO_MR'] = $noMr;

            $pasien = Pasien::create($data);

            DB::commit();

            return redirect()->route('loket.index')->with('success', 'Pasien berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing pasien: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
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

        $results = $query->with(['kecamatan', 'kelurahan'])->limit(50)->get();

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

        // Generate nomor urut berdasarkan tanggal kunjungan
        $lastNoUrut = Loket::whereDate('tglKunjungan', $data['tglKunjungan'] ?? date('Y-m-d'))
            ->max('noUrut') ?? 0;

        $data['noUrut'] = str_pad($lastNoUrut + 1, 4, '0', STR_PAD_LEFT);

        $data['createdBy'] = auth()->user()->username ?? 'test_user';

        // Konversi boolean
        $data['kunjSakit'] = ($data['jenisKunjungan'] ?? '') === 'Kunjungan Sakit' ? 'true' : 'false';
        $data['kunjBaru'] = ($data['jenisPengunjung'] ?? '') === 'Pengunjung Baru' ? 'true' : 'false';

        $defaultValues = [
            'kelUmur' => 1,
            'umur' => 30,
            'jknPbi' => $data['kategori'] ?? null,
            'statusPasien' => 'umum',
            'kdProvider' => ($data['kategori'] ?? '') === 'BPJS' ? 'BPJS' : '',
            'statusKartu' => ($data['kategori'] ?? '') === 'BPJS' ? 'AKTIF' : '',
            'noKartu' => ($data['kategori'] ?? '') === 'BPJS' ? ($data['no_bpjs'] ?? '') : '',
            'kdKegiatan' => 1,
            'puskId' => 1,
            'unitId' => 1,
            'kategoriUnitId' => 1,
        ];

        $data = array_merge($defaultValues, $data);

        try {
            $loket = Loket::create($data);
            Log::info('Loket created successfully:', $loket->toArray());

            return redirect()->route('loket.index')->with('success', 'Pasien berhasil didaftarkan');
        } catch (\Exception $e) {
            Log::error('Error creating loket: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mendaftarkan pasien: ' . $e->getMessage())->withInput();
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

        return response()->json($query->orderBy('NAMA_KEC')->get(['NO_KEC', 'NAMA_KEC']));
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
        if (($request->jenisKunjungan ?? '') === 'Kunjungan Sakit') {
            $poliList = Poli::where('kategori', 'sakit')->orWhere('tipe', 'sakit')->get(['kdPoli', 'nmPoli']);
        } else {
            $poliList = Poli::where('kategori', 'sehat')->orWhere('tipe', 'sehat')->get(['kdPoli', 'nmPoli']);
        }

        return response()->json($poliList);
    }

    public function pasien($id)
    {
        $pasien = Pasien::findOrFail($id);
        return Inertia::render('Loket/Pasien', [
            'pasien' => $pasien,
        ]);
    }

    public function cetak_kartu($id)
    {
        $pasien = Pasien::findOrFail($id);
        $alamat = DB::table('unit_profiles')->where('unit_id', auth()->user()->unit ?? 1)->first();
        // Jika kamu ingin render blade untuk PDF; saat ini kita render page Inertia atau blade
        return Inertia::render('Loket/CetakKartu', [
            'pasien' => $pasien,
            'alamat' => $alamat,
        ]);
    }

    // barcode: return basic SVG as response (simple)
    public function gen_barcode($NO_MR)
    {
        // Simple Code39-like SVG (very minimal) - replace with real lib if needed
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="50"><rect width="200" height="50" fill="#fff"/><text x="10" y="30" font-family="monospace">' . e($NO_MR) . '</text></svg>';
        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
    }

    public function cetak_noAntrian()
    {
        return Inertia::render('Loket/CetakNo', [
            'menu' => 'Cetak'
        ]);
    }

    public function simpan(Request $request)
    {
        $data = $request->all();

        // Hitung umur/kelompok umur via service
        $this->service->calculateAgeGroups($data);

        // sanitize unset fields (mengikuti CI3 logic)
        unset($data['TGL_LHR'], $data['NO_MR'], $data['NIK']);

        $data['idLoket'] = \Illuminate\Support\Str::uuid()->toString();
        $data['puskId'] = auth()->user()->unit ?? 1;
        $data['createdBy'] = auth()->user()->username ?? 'system';

        try {
            DB::beginTransaction();
            DB::table('simpus_loket')->insert($data);

            // Insert pelayanan (simpus_pelayanan)
            $layanan = [
                'loketId' => $data['idLoket'],
                'idpelayanan' => \Illuminate\Support\Str::uuid()->toString(),
                'tglPelayanan' => $data['tglKunjungan'] ?? date('Y-m-d'),
                'kdPoli' => $data['kdPoli'] ?? null,
                'kdKegiatanPel' => $data['kdKegiatan'] ?? null,
                'kunjSakitPel' => $data['kunjSakit'] ?? 'false',
                'pelIdSebelum' => '0',
                'createdBy' => $data['createdBy'],
                'startTime' => now(),
            ];
            DB::table('simpus_pelayanan')->insert($layanan);

            // update pasien kdProvider & PHONE
            if (!empty($data['pasienId'])) {
                DB::table('simpus_pasien')->where('ID', $data['pasienId'])->update([
                    'kdProvider' => $data['kdProvider'] ?? null,
                    'PHONE' => $data['PHONE'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json(['bridging' => ($data['statusKartu'] ?? '') != '' ? ($data['kdProvider'] == ($this->pcareKodePpk() ?? '') ? $data['idLoket'] : 'no') : 'no', 'status' => 'success', 'message' => 'ok']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error simpan loket: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $this->service->calculateAgeGroups($data);

        $pel = [
            'tglPelayanan' => $data['tglKunjungan'] ?? null,
            'kdPoli' => $data['kdPoli'] ?? null,
            'kdKegiatanPel' => $data['kdKegiatan'] ?? null,
            'kunjSakitPel' => $data['kunjSakit'] ?? 'false',
            'modifiedDate' => now(),
            'modifiedBy' => auth()->user()->username ?? 'system',
        ];

        try {
            DB::beginTransaction();

            if (!empty($data['idpelayanan'])) {
                DB::table('simpus_pelayanan')->where('idpelayanan', $data['idpelayanan'])->update($pel);
            }

            unset($data['TGL_LHR'], $data['NO_MR'], $data['NIK'], $data['idpelayanan']);

            if (!empty($data['pasienId']) && isset($data['kdProvider'])) {
                DB::table('simpus_pasien')->where('ID', $data['pasienId'])->update(['kdProvider' => $data['kdProvider']]);
            }

            $data['modifiedDate'] = now();
            $data['modifiedBy'] = auth()->user()->username ?? 'system';

            DB::table('simpus_loket')->where('idLoket', $data['idLoket'])->update($data);

            DB::commit();

            return response()->json(['status' => 'done', 'message' => 'update']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update loket: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function hapus($id)
    {
        // Log delete info (user agent, ip, dll)
        $agent = request()->header('User-Agent', 'Unidentified User Agent');
        $qitem = DB::table('simpus_loket')->where('idLoket', $id)->first();

        if (!$qitem) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $del = [
            'puskId' => auth()->user()->unit ?? 1,
            'agent' => $agent,
            'platform' => php_uname('s'),
            'ip_address' => request()->ip(),
            'pasienId' => $qitem->pasienId,
            'loketId' => $qitem->idLoket,
            'kdPoli' => $qitem->kdPoli,
            'tglKunjungan' => $qitem->tglKunjungan,
            'deleteBy' => auth()->user()->username ?? 'system',
            'deleteDate' => now(),
        ];

        DB::table('log_loket_delete')->insert($del);
        DB::table('simpus_loket')->where('idLoket', $id)->delete();
        DB::table('simpus_pelayanan')->where('loketId', $id)->delete();

        return redirect()->route('loket.create')->with('success', 'Data berhasil dihapus');
    }

    public function cekrapid($pasienId, $tglKunjungan)
    {
        $tglcek = Carbon::parse($tglKunjungan)->format('Y-m-d');

        $cek = DB::selectOne("
            SELECT COUNT(loket.`puskId`) AS jmlRapid, up.nama_unit, tglKunjungan 
            FROM simpus_loket loket 
            INNER JOIN simpus_pelayanan pel ON pel.`loketId`=loket.`idLoket`
            INNER JOIN unit_profiles up ON up.`unit_id`=loket.puskId
            WHERE loket.`kdPoli`='095' 
              AND tglKunjungan BETWEEN DATE_SUB(?, INTERVAL 14 DAY) AND ? 
              AND loket.pasienId = ?
            ORDER BY tglKunjungan
            LIMIT 1
        ", [$tglcek, $tglcek, $pasienId]);

        $jmlRapid = $cek->jmlRapid ?? 0;
        $nama_unit = $cek->nama_unit ?? '';
        $tglRapid = $cek->tglKunjungan ?? null;

        if ($jmlRapid > 0) {
            return response()->json(['status' => 'pernah', 'message' => 'Pasien sudah pernah melakukan rapid tes di ' . $nama_unit . ' pada tanggal ' . Carbon::parse($tglRapid)->format('d-m-Y')]);
        }

        return response()->json(['status' => 'belum', 'message' => 'Pasien belum melakukan rapid tes']);
    }

    public function cek_beda_provider($pasienId, $tglKunjungan)
    {
        // Ambil kode PPK (placeholder function pcare)
        $PPK = $this->pcareKodePpk();
        $tglcek = Carbon::parse($tglKunjungan)->format('Y-m-d');

        $cek = DB::selectOne("
            SELECT COUNT(noUrut) AS bridging, pasienId FROM (
                SELECT a.`noUrut`, a.`pasienId`, a.`tglKunjungan`, a.`kdProvider`, a.`providerKartu`
                FROM simpus_loket a
                WHERE a.kdProvider <> '' AND a.kdProvider <> ?
                AND MONTH(a.`tglKunjungan`) = MONTH(?) 
                AND a.`puskId` = ?
                AND a.`pasienId` = ?
            ) prov GROUP BY pasienId
        ", [$PPK, $tglcek, auth()->user()->unit ?? 1, $pasienId]);

        $jmlbridging = $cek->bridging ?? 0;

        if ($jmlbridging > 0 && $jmlbridging <= 2) {
            return response()->json(['status' => 'pernah', 'message' => 'PERCOBAAN !! Pasien sudah pernah melakukan bridging ' . $jmlbridging . ' kali pada bulan ini']);
        } elseif ($jmlbridging >= 3) {
            return response()->json(['status' => 'gagal', 'message' => 'PERCOBAAN !! Pasien sudah pernah melakukan bridging 3 kali pada bulan ini']);
        } else {
            return response()->json(['status' => 'belum', 'message' => 'PERCOBAAN !! pasien belum bridging pada bulan ini']);
        }
    }

    // Placeholder: ambil kode PPK (sesuaikan dengan implementasi base_model->pcare)
    protected function pcareKodePpk()
    {
        // contoh: bisa ambil dari table config atau env
        return env('PCARE_KODE_PPK', 'KODE_PPK_DEFAULT');
    }

    // ======== REPORT methods ====================================================
    // Konversi report views: lap_reg_kunj_pas, lap_bulanan_data_kunj, dll.
    // Saya sediakan contoh satu; kamu bisa tambahkan lainnya menggunakan service/rawSelect bila perlu.

    public function lap_reg_kunj_pas($is_html, $unit, $unit_details, $tgl_awal, $tgl_akhir, $kel = null, $pusk = null)
    {
        $items = $this->service->rawSelect("SELECT * FROM simpus_loket WHERE tglKunjungan BETWEEN ? AND ? LIMIT 1000", [$tgl_awal, $tgl_akhir]);
        return view('laporan.loket.v_lap_reg_kunj_pas', [
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'is_html' => $is_html,
            'desa' => $kel,
            'items' => $items,
            'menu' => 'laporan'
        ]);
    }
}
