<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Facades\DB;

class LaporanPTMService
{
    /**
     * Ambil data register skrining PTM dengan filter opsional.
     *
     * @param array $filters  ['tgl_awal', 'tgl_akhir', 'jenis_kelamin', 'kelompok_usia', 'no_kel']
     */
    public function getDataLaporan(array $filters = [])
    {
        $query = $this->baseQuery($filters, withDetailJoins: true);

        return $query->select(
            // Data Pasien
            'p.ID as pasienId',
            'p.NO_MR',
            'p.NAMA_LGKP',
            'p.NIK',
            'p.IHS_NUMBER',
            'p.jenis_klmin',
            'p.alamat',
            'p.no_rt',
            'p.no_rw',

            // Data Loket/Kunjungan
            'l.idLoket',
            'l.kdPoli',
            'l.umur',
            'l.umur_bulan',
            'l.umur_hari',
            'l.tglKunjungan',
            'l.kunjBaru',

            // Wilayah
            'kel.nama_kel',
            'kec.nama_kec',
            'kab.nama_kab',
            'prop.nama_prop',

            // Poli & Unit
            'poli.nmPoli',
            'up.nama_unit',

            // Pelayanan
            'pel.idpelayanan',
            'pel.sudahDilayani',
            'pel.startTime',
            'pel.progressTime',

            // Petugas
            'dokter.nmDokter',

            // Skrining PTM
            'skrining.idSkrining',
            'kunjungan.tanggal_skrining',
            'kunjungan.keluhan_utama',

            // Pemeriksaan fisik
            'obesitas.tinggi_badan',
            'obesitas.berat_badan',
            'obesitas.imt',
            'obesitas.lingkar_pinggang',
            'hipertensi.sistolik',
            'hipertensi.tekanan_diastolik',
            'hipertensi.nadi',
            'hipertensi.suhu',
            'hipertensi.pernapasan',

            // Faktor Risiko
            'frisiko.merokok',
            'frisiko.status_merokok',
            'frisiko.sayur',
            'frisiko.alkohol',

            // Hasil / status per penyakit — nama alias INI yang dipakai frontend
            'hipertensi.kategori_tekanan_darah as status_hipertensi',
            'obesitas.interpretasi_ptm as kategori_obesitas',

            'diabetes.gula_darah_sewaktu as gds',
            'diabetes.gula_darah_puasa as gdp',
            'diabetes.gula_darah_2_jam_pp as gd2jpp',
            'diabetes.hba1c',
            DB::raw($this->statusDiabetesCase() . ' as status_diabetes'),

            'asam_urat.asam_urat',
            'asam_urat.kategori_asam_urat',

            'profil_lipid.kolesterol_total',
            'profil_lipid.trigliserida',
            'profil_lipid.hdl',
            'profil_lipid.ldl',
        )
        ->orderByDesc('l.tglKunjungan')
        ->get();
    }

    /**
     * Hitung statistik ringkasan dari data laporan PTM.
     *
     * @param array $filters  Filter yang sama dengan getDataLaporan()
     */
    public function getStatistikRingkasan(array $filters = []): array
    {
        $query = $this->baseQuery($filters, withDetailJoins: true);

        $row = $query->selectRaw("
            COUNT(DISTINCT skrining.idSkrining)   AS total_skrining,
            COUNT(DISTINCT hipertensi.id)          AS total_hipertensi,
            COUNT(DISTINCT diabetes.id)            AS total_diabetes,
            COUNT(DISTINCT obesitas.id)            AS total_obesitas,
            COUNT(DISTINCT asam_urat.id)           AS total_asam_urat,
            COUNT(DISTINCT profil_lipid.id)        AS total_profil_lipid
        ")->first();

        return [
            'total_skrining'     => (int) ($row->total_skrining     ?? 0),
            'total_hipertensi'   => (int) ($row->total_hipertensi   ?? 0),
            'total_diabetes'     => (int) ($row->total_diabetes     ?? 0),
            'total_obesitas'     => (int) ($row->total_obesitas     ?? 0),
            'total_asam_urat'    => (int) ($row->total_asam_urat    ?? 0),
            'total_profil_lipid' => (int) ($row->total_profil_lipid ?? 0),
        ];
    }

    /**
     * Query dasar + filter yang dipakai bersama oleh getDataLaporan() & getStatistikRingkasan().
     * Menghindari duplikasi logika join & filter di dua tempat berbeda.
     */
    protected function baseQuery(array $filters, bool $withDetailJoins = false)
    {
        $query = DB::table('simpus_loket as l')
            ->join('simpus_pasien as p',      'l.pasienId',      '=', 'p.ID')
            ->join('simpus_pelayanan as pel', 'l.idLoket',       '=', 'pel.loketId')
            ->join('simpus_poli_fktp as poli','poli.kdPoli',     '=', 'l.kdPoli')
            ->leftJoin('unit_profiles as up', 'up.unit_id',      '=', 'l.unitId')
            ->leftJoin('simpus_skrining_ptm as skrining',   'pel.idpelayanan',      '=', 'skrining.idPelayanan')
            ->leftJoin('simpus_kunjungan_ptm as kunjungan', 'kunjungan.idSkrining', '=', 'skrining.idSkrining')
            ->leftJoin('master_dokter as dokter',           'dokter.ihs_nakes',     '=', 'kunjungan.id_petugas')
            ->leftJoin('faktor_risiko_ptm as frisiko',      'skrining.idSkrining',  '=', 'frisiko.skriningID')
            ->leftJoin('simpus_hipertensi as hipertensi',   'skrining.idSkrining',  '=', 'hipertensi.skriningID')
            ->leftJoin('simpus_diabetes as diabetes',       'skrining.idSkrining',  '=', 'diabetes.skriningID')
            ->leftJoin('simpus_obesitas as obesitas',       'skrining.idSkrining',  '=', 'obesitas.skriningID')
            ->leftJoin('simpus_asam_urat as asam_urat',     'skrining.idSkrining',  '=', 'asam_urat.skriningID')
            ->leftJoin('simpus_profil_lipid as profil_lipid','skrining.idSkrining', '=', 'profil_lipid.skriningID')
            ->leftJoin('setup_kel as kel', function ($join) {
                $join->on('p.NO_KEL',  '=', 'kel.NO_KEL')
                     ->on('p.NO_KEC',  '=', 'kel.NO_KEC')
                     ->on('p.NO_KAB',  '=', 'kel.NO_KAB')
                     ->on('p.NO_PROP', '=', 'kel.NO_PROP');
            })
            ->leftJoin('setup_kec as kec', function ($join) {
                $join->on('p.NO_KEC',  '=', 'kec.NO_KEC')
                     ->on('p.NO_KAB',  '=', 'kec.NO_KAB')
                     ->on('p.NO_PROP', '=', 'kec.NO_PROP');
            })
            ->leftJoin('setup_kab as kab', function ($join) {
                $join->on('p.NO_KAB',  '=', 'kab.NO_KAB')
                     ->on('p.NO_PROP', '=', 'kab.NO_PROP');
            })
            ->leftJoin('setup_prop as prop', 'p.NO_PROP', '=', 'prop.NO_PROP')
            ->where('l.kdPoli', '006')
            ->whereNotNull('skrining.idSkrining');

        // ── Filter Tanggal (pakai jam penuh agar tgl_akhir tidak terpotong di 00:00:00) ──
        if (!empty($filters['tgl_awal']) && !empty($filters['tgl_akhir'])) {
            $query->whereBetween('l.tglKunjungan', [
                $filters['tgl_awal']  . ' 00:00:00',
                $filters['tgl_akhir'] . ' 23:59:59',
            ]);
        } elseif (!empty($filters['tgl_awal'])) {
            $query->where('l.tglKunjungan', '>=', $filters['tgl_awal'] . ' 00:00:00');
        } elseif (!empty($filters['tgl_akhir'])) {
            $query->where('l.tglKunjungan', '<=', $filters['tgl_akhir'] . ' 23:59:59');
        }

        // ── Filter Jenis Kelamin ──
        // 1 = Laki-laki, 2 = Perempuan (sesuai kolom jenis_klmin di simpus_pasien)
        if (!empty($filters['jenis_kelamin'])) {
            if ($filters['jenis_kelamin'] === 'L') {
                $query->where('p.jenis_klmin', 1);
            } elseif ($filters['jenis_kelamin'] === 'P') {
                $query->where('p.jenis_klmin', 2);
            }
        }

        // ── Filter Kelompok Usia ──
        if (!empty($filters['kelompok_usia'])) {
            match ($filters['kelompok_usia']) {
                '15-19' => $query->whereBetween('l.umur', [15, 19]),
                '20-44' => $query->whereBetween('l.umur', [20, 44]),
                '45-59' => $query->whereBetween('l.umur', [45, 59]),
                '60+'   => $query->where('l.umur', '>=', 60),
                default => null,
            };
        }

        // ── Filter Desa / Kelurahan ──
        if (!empty($filters['no_kel'])) {
            $query->where('p.NO_KEL', $filters['no_kel']);
        }

        return $query;
    }

    /**
     * Ekspresi SQL CASE untuk kategori diabetes berdasarkan hasil GDS/GDP/HbA1c.
     * Ambang batas standar skrining PTM Kemenkes — sesuaikan bila pedoman berubah.
     */
    protected function statusDiabetesCase(): string
    {
        return "
            CASE
                WHEN diabetes.hba1c >= 6.5 OR diabetes.gula_darah_puasa >= 126 OR diabetes.gula_darah_sewaktu >= 200
                    THEN 'Diabetes'
                WHEN diabetes.hba1c >= 5.7 OR diabetes.gula_darah_puasa >= 100
                    THEN 'Prediabetes'
                WHEN diabetes.gula_darah_sewaktu IS NOT NULL
                     OR diabetes.gula_darah_puasa IS NOT NULL
                     OR diabetes.hba1c IS NOT NULL
                    THEN 'Normal'
                ELSE NULL
            END
        ";
    }
}