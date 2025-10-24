<?php

namespace App\Http\Controllers\RuangLayanan;

use App\Http\Controllers\Controller;
use App\Models\RuangLayanan\SimpusMasterObat;
use App\Models\RuangLayanan\SimpusPermohonanLab;
use App\Models\RuangLayanan\SimpusPoliFKTP;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class indexController extends Controller
{
    public function listPoli()
    {
        $listPoli = SimpusPoliFKTP::where('pelayanan', 'true')->get();
        return Inertia::render(
            'Ruang_Layanan/indexPoli',
            ['listPoli' => $listPoli]
        );
    }

    public function listPoliKluster($kluster)
    {


        if ($kluster == '2') {
            $listPoli = SimpusPoliFKTP::whereIn('kdPoli', [001, 003])->get();
            // dd($listPoli);
            return Inertia::render(
                'Ruang_Layanan/klusterPasien/kluster2',
                [
                    'listPoli' => $listPoli
                ]
            );

        } elseif ($kluster == '3') {
            $listPoli = SimpusPoliFKTP::whereIn('kdPoli', ['001', '008'])->get();
            // dd($listPoli);
            return Inertia::render(
                'Ruang_Layanan/klusterPasien/kluster3',
                [
                    'listPoli' => $listPoli
                ]
            );

        } else {
            $listPoli = SimpusPoliFKTP::whereIn('kdPoli', ['002', '005', '098',])->get();
            // dd($listPoli);
            return Inertia::render(
                'Ruang_Layanan/klusterPasien/kluster3',
                [
                    'listPoli' => $listPoli
                ]
            );

        }

    }

    public function paginasiSimpusDiagnosa(Request $request)
    {
        $DiagnosaMedis = DB::table('simpus_diagnosa')->paginate(10);

        $links = [];

        // Previous
        $links[] = [
            'label' => 'Previous',
            'url' => $DiagnosaMedis->previousPageUrl(),
            'active' => false,
        ];

        $lastPage = $DiagnosaMedis->lastPage();
        $current = $DiagnosaMedis->currentPage();

        // Always tampil halaman pertama
        $links[] = [
            'label' => 1,
            'url' => $DiagnosaMedis->url(1),
            'active' => $current === 1,
        ];

        // Tambah "..." kalau current jauh dari 1
        if ($current > 3) {
            $links[] = ['label' => '...', 'url' => null, 'active' => false];
        }

        // Window: current-1, current, current+1
        for ($i = max(2, $current - 1); $i <= min($lastPage - 1, $current + 1); $i++) {
            $links[] = [
                'label' => $i,
                'url' => $DiagnosaMedis->url($i),
                'active' => $current === $i,
            ];
        }

        // Tambah "..." kalau current jauh dari lastPage
        if ($current < $lastPage - 2) {
            $links[] = ['label' => '...', 'url' => null, 'active' => false];
        }

        // Always tampil halaman terakhir (kalau lebih dari 1)
        if ($lastPage > 1) {
            $links[] = [
                'label' => $lastPage,
                'url' => $DiagnosaMedis->url($lastPage),
                'active' => $current === $lastPage,
            ];
        }

        // Next
        $links[] = [
            'label' => 'Next',
            'url' => $DiagnosaMedis->nextPageUrl(),
            'active' => false,
        ];

        return response()->json([
            'data' => $DiagnosaMedis->items(),
            'meta' => [
                'current_page' => $DiagnosaMedis->currentPage(),
                'last_page' => $DiagnosaMedis->lastPage(),
                'per_page' => $DiagnosaMedis->perPage(),
                'total' => $DiagnosaMedis->total(),
            ],
            'links' => $links,
        ]);
    }

    public function paginasiSimpusTindakan(Request $request)
    {
        $search = $request->get('search');

        $query = DB::table('simpus_master_tindakan');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_tindakan', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            });
        }
        $MasterTindakan = $query->paginate(10);

        $links = [];

        // Previous
        $links[] = [
            'label' => 'Previous',
            'url' => $MasterTindakan->previousPageUrl(),
            'active' => false,
        ];

        $lastPage = $MasterTindakan->lastPage();
        $current = $MasterTindakan->currentPage();

        // Always tampil halaman pertama
        $links[] = [
            'label' => 1,
            'url' => $MasterTindakan->url(1),
            'active' => $current === 1,
        ];

        // Tambah "..." kalau current jauh dari 1
        if ($current > 3) {
            $links[] = ['label' => '...', 'url' => null, 'active' => false];
        }

        // Window: current-1, current, current+1
        for ($i = max(2, $current - 1); $i <= min($lastPage - 1, $current + 1); $i++) {
            $links[] = [
                'label' => $i,
                'url' => $MasterTindakan->url($i),
                'active' => $current === $i,
            ];
        }

        // Tambah "..." kalau current jauh dari lastPage
        if ($current < $lastPage - 2) {
            $links[] = ['label' => '...', 'url' => null, 'active' => false];
        }

        // Always tampil halaman terakhir (kalau lebih dari 1)
        if ($lastPage > 1) {
            $links[] = [
                'label' => $lastPage,
                'url' => $MasterTindakan->url($lastPage),
                'active' => $current === $lastPage,
            ];
        }

        // Next
        $links[] = [
            'label' => 'Next',
            'url' => $MasterTindakan->nextPageUrl(),
            'active' => false,
        ];

        return response()->json([
            'data' => $MasterTindakan->items(),
            'meta' => [
                'current_page' => $MasterTindakan->currentPage(),
                'last_page' => $MasterTindakan->lastPage(),
                'per_page' => $MasterTindakan->perPage(),
                'total' => $MasterTindakan->total(),
            ],
            'links' => $links,
        ]);
    }

    public function MasterObat(Request $request)
    {
        $search = $request->get('search');
        $query = SimpusMasterObat::where('AKTIF', '1');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }
        $MasterObat = $query->paginate(10);

        $links = [];

        // Previous
        $links[] = [
            'label' => 'Previous',
            'url' => $MasterObat->previousPageUrl(),
            'active' => false,
        ];

        $lastPage = $MasterObat->lastPage();
        $current = $MasterObat->currentPage();

        // Always tampil halaman pertama
        $links[] = [
            'label' => 1,
            'url' => $MasterObat->url(1),
            'active' => $current === 1,
        ];

        // Tambah "..." kalau current jauh dari 1
        if ($current > 3) {
            $links[] = ['label' => '...', 'url' => null, 'active' => false];
        }

        // Window: current-1, current, current+1
        for ($i = max(2, $current - 1); $i <= min($lastPage - 1, $current + 1); $i++) {
            $links[] = [
                'label' => $i,
                'url' => $MasterObat->url($i),
                'active' => $current === $i,
            ];
        }

        // Tambah "..." kalau current jauh dari lastPage
        if ($current < $lastPage - 2) {
            $links[] = ['label' => '...', 'url' => null, 'active' => false];
        }

        // Always tampil halaman terakhir (kalau lebih dari 1)
        if ($lastPage > 1) {
            $links[] = [
                'label' => $lastPage,
                'url' => $MasterObat->url($lastPage),
                'active' => $current === $lastPage,
            ];
        }

        // Next
        $links[] = [
            'label' => 'Next',
            'url' => $MasterObat->nextPageUrl(),
            'active' => false,
        ];

        return response()->json([
            'data' => $MasterObat->items(),
            'meta' => [
                'current_page' => $MasterObat->currentPage(),
                'last_page' => $MasterObat->lastPage(),
                'per_page' => $MasterObat->perPage(),
                'total' => $MasterObat->total(),
            ],
            'links' => $links,
        ]);

    }

    public function getPermohonanLaborat($idLoket)
    {
        $PermohonanLabs = SimpusPermohonanLab::where('loketId', $idLoket)->with('tenagaMedis')->get();
        return response()->json(
            [
                'PermohonanLabs' => $PermohonanLabs
            ]
        );
    }

}
