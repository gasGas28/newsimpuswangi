<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Menyusun baris-baris data untuk export Klaster 3 PTM (Kardiovaskular & Metabolik)
 * dari skema SIMPUS asli (raw query builder).
 *
 * Gaya penulisan sengaja disamakan dengan RegisterHTService / RegisterDMService:
 * query -> ->map() nempelin properti tambahan langsung ke $row -> return Collection.
 * Tidak ada DTO/class perantara -- nama properti pada $row HARUS sama persis dengan
 * value di PtmKlaster3ExportService::COLUMN_MAP, karena export service baca
 * langsung dari situ.
 *
 * CATATAN PENTING (mohon dicek/disesuaikan):
 * 1. Kolom "riwayat_penyakit_keluarga" & "riwayat_penyakit_diri_*" di template
 *    cuma 1 sel bebas (bukan per-penyakit). Kolom kamu (r_keluarga_htn, r_keluarga_dm, dst)
 *    ada 4 flag terpisah untuk keluarga, dan 4 flag utk pribadi. Digabung jadi 1 string
 *    untuk keluarga, dan dipecah utk pribadi ke 3 slot (riwayat_penyakit_diri_1/2/3)
 *    karena template cuma sediakan 3 slot bebas. Kalau ada 4 penyakit positif,
 *    yang ke-4 otomatis tidak tertampung -- lihat packDiseaseList().
 * 2. Kolom PUMA (napas pendek, dahak, batuk, spirometri) TIDAK ADA di skema
 *    SIMPUS kamu saat ini, jadi dibiarkan null (sel akan kosong di Excel, bukan error).
 *    Edukasi (berhenti_merokok, aktivitas_fisik, diet) SUDAH diambil dari
 *    simpus_data_edukasi + master_edukasi_ptm (lihat buildEdukasiMap());
 *    kecuali "edukasi_asap_rokok" yang tetap null karena belum ada kode SNOMED
 *    yang cocok di master_edukasi_ptm.
 * 3. Template hanya menyediakan 1 pasang Diagnosis+Terapi yang benar-benar general;
 *    skema kamu cuma punya 1 field diagnosis_utama, jadi diagnosis_2/3 & terapi_2/3
 *    akan selalu kosong kecuali kamu punya sumber data tambahan.
 * 4. Nilai "Ya"/"Tidak" dsb HARUS persis sama dengan pilihan dropdown di sheet
 *    "Referensi" pada template. Cek dulu format asli kolom-kolom seperti
 *    status_merokok, gula, garam, dst di database kamu (0/1? 'Ya'/'Tidak'? boolean?)
 *    lalu sesuaikan normalizeYaTidak() di bawah supaya konversinya benar.
 */
class LaporanService
{
    /**
     * Entry point yang dipanggil controller: query utama -> gabungkan data edukasi -> mapping.
     *
     * @param  array{tanggal_mulai?: string, tanggal_selesai?: string}  $filters
     * @return Collection<int, object>
     */
    public function build(array $filters): Collection
    {
        return $this->mapRows($this->runMainQuery($filters));
    }

    /**
     * @param  array{tanggal_mulai?: string, tanggal_selesai?: string}  $filters
     * @return Collection<int, object>
     */
    private function runMainQuery(array $filters): Collection
    {
        // Catatan: join di bawah pakai CONVERT(...USING utf8mb4) COLLATE eksplisit
        // karena tabel-tabel yang terlibat punya charset/collation berbeda-beda
        // (ada yang utf8mb3, ada yang utf8mb4_general_ci, ada yang utf8mb4_unicode_ci),
        // yang bikin error "Illegal mix of collations" / "COLLATION is not valid
        // for CHARACTER SET" kalau dibandingkan langsung dengan '='.
        // CONVERT(col USING utf8mb4) memaksa charset ke utf8mb4 dulu (aman dari
        // charset asal apapun: mb3/mb4), baru di-COLLATE supaya seragam.
        // Solusi permanen (lebih disarankan): samakan collation semua tabel PTM
        // lewat migration ALTER TABLE ... CONVERT TO CHARACTER SET utf8mb4
        // COLLATE utf8mb4_unicode_ci, lalu join ini bisa dikembalikan ke bentuk biasa.
        $collate = 'utf8mb4_unicode_ci';
        $norm = fn (string $column) => DB::raw("CONVERT({$column} USING utf8mb4) COLLATE {$collate}");

        return DB::table('simpus_kunjungan_ptm')
            ->join('simpus_pelayanan', function ($join) use ($norm) {
                $join->on(
                    $norm('simpus_pelayanan.idpelayanan'),
                    '=',
                    $norm('simpus_kunjungan_ptm.idPelayanan')
                );
            })
            ->join('simpus_pasien', function ($join) use ($norm) {
                $join->on(
                    $norm('simpus_pasien.NIK'),
                    '=',
                    $norm('simpus_kunjungan_ptm.nik_pasien')
                );
            })
            ->join('simpus_diabetes', function ($join) use ($norm) {
                $join->on(
                    $norm('simpus_diabetes.skriningID'),
                    '=',
                    $norm('simpus_kunjungan_ptm.idSkrining')
                );
            })
            ->join('faktor_risiko_ptm', function ($join) use ($norm) {
                $join->on(
                    $norm('faktor_risiko_ptm.skriningID'),
                    '=',
                    $norm('simpus_kunjungan_ptm.idSkrining')
                );
            })
            ->join('simpus_obesitas', function ($join) use ($norm) {
                $join->on(
                    $norm('simpus_obesitas.skriningID'),
                    '=',
                    $norm('simpus_kunjungan_ptm.idSkrining')
                );
            })
            ->join('simpus_hipertensi', function ($join) use ($norm) {
                $join->on(
                    $norm('simpus_hipertensi.skriningID'),
                    '=',
                    $norm('simpus_kunjungan_ptm.idSkrining')
                );
            })
            ->join('simpus_profil_lipid', function ($join) use ($norm) {
                $join->on(
                    $norm('simpus_profil_lipid.skriningID'),
                    '=',
                    $norm('simpus_kunjungan_ptm.idSkrining')
                );
            })
            ->join('simpus_asam_urat', function ($join) use ($norm) {
                $join->on(
                    $norm('simpus_asam_urat.skriningID'),
                    '=',
                    $norm('simpus_kunjungan_ptm.idSkrining')
                );
            })
            ->join('simpus_kanker_iva', function ($join) use ($norm) {
                $join->on(
                    $norm('simpus_kanker_iva.skriningID'),
                    '=',
                    $norm('simpus_kunjungan_ptm.idSkrining')
                );
            })
            ->join('simpus_ekg', function ($join) use ($norm) {
                $join->on(
                    $norm('simpus_ekg.skriningID'),
                    '=',
                    $norm('simpus_kunjungan_ptm.idSkrining')
                );
            })
            ->join('simpus_status_ptm', function ($join) use ($norm) {
                $join->on(
                    $norm('simpus_ekg.skriningID'),
                    '=',
                    $norm('simpus_kunjungan_ptm.idSkrining')
                );
            })
            ->where('simpus_pelayanan.kdPoli', '006')
            ->when(
                $filters['tanggal_mulai'] ?? null,
                fn ($q, $v) => $q->whereDate('simpus_kunjungan_ptm.tanggal_skrining', '>=', $v)
            )
            ->when(
                $filters['tanggal_selesai'] ?? null,
                fn ($q, $v) => $q->whereDate('simpus_kunjungan_ptm.tanggal_skrining', '<=', $v)
            )
            ->select([
                'simpus_hipertensi.*',
                'faktor_risiko_ptm.*',
                'simpus_diabetes.*',
                'simpus_profil_lipid.*',
                'simpus_obesitas.*',
                'simpus_asam_urat.*',
                'simpus_ekg.*',
                'simpus_status_ptm.*',
                'simpus_kanker_iva.*',
                // nik & nama di-select PALING TERAKHIR supaya tidak ke-overwrite oleh
                // kolom bernama sama dari tabel lain
                'simpus_pasien.NIK as nik',
                'simpus_pasien.NAMA_LGKP as nama',
                'simpus_pelayanan.kdPoli as poli',
            ])
            ->orderBy('simpus_kunjungan_ptm.tanggal_skrining')
            ->get();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, object>  $rawRows  hasil dari runMainQuery()
     */
    public function mapRows(Collection $rawRows): Collection
    {
        $edukasiMap = $this->buildEdukasiMap($rawRows->pluck('skriningID')->filter()->unique()->values());

        return $rawRows->map(fn ($row) => $this->mapRow($row, $edukasiMap));
    }

    /**
     * Query terpisah untuk edukasi, karena simpus_data_edukasi bersifat 1:banyak
     * per skriningID (1 kunjungan bisa dapat lebih dari 1 jenis edukasi).
     * Kalau di-join langsung ke query utama, baris pasien bisa terduplikasi.
     *
     * Kode SNOMED yang dipetakan ke 4 kolom template (lihat MasterEdukasiSeeder):
     * - berhenti_merokok : 171207006
     * - aktivitas_fisik  : 409073007
     * - diet             : 183063000, 698360004, 710824005, 311401005
     * - asap_rokok       : TIDAK ADA kode yang cocok saat ini -> selalu null
     *
     * @param  \Illuminate\Support\Collection<int, mixed>  $skriningIds
     * @return array<string, array<int, string>>  skriningID => daftar kode_snomed yang diberikan
     */
    private function buildEdukasiMap(Collection $skriningIds): array
    {
        if ($skriningIds->isEmpty()) {
            return [];
        }

        $collate = 'utf8mb4_unicode_ci';
        $norm = fn (string $column) => DB::raw("CONVERT({$column} USING utf8mb4) COLLATE {$collate}");

        $rows = DB::table('simpus_data_edukasi')
            ->join('master_edukasi_ptm', function ($join) use ($norm) {
                $join->on(
                    $norm('master_edukasi_ptm.kode_snomed'),
                    '=',
                    $norm('simpus_data_edukasi.kode_snomed')
                );
            })
            ->whereIn('simpus_data_edukasi.skriningID', $skriningIds)
            ->select(['simpus_data_edukasi.skriningID', 'simpus_data_edukasi.kode_snomed'])
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $map[$r->skriningID][] = $r->kode_snomed;
        }

        return $map;
    }

    /**
     * Nempelin properti baru ke $row dengan nama PERSIS sama seperti key di
     * PtmKlaster3ExportService::COLUMN_MAP, supaya export service tinggal baca
     * langsung tanpa mapping lagi.
     *
     * @param  array<string, array<int, string>>  $edukasiMap  hasil buildEdukasiMap()
     */
    private function mapRow(object $row, array $edukasiMap = []): object
    {
        [$diri1, $diri2, $diri3] = $this->packDiseaseList([
            'Hipertensi' => $row->r_pribadi_htn ?? null,
            'Diabetes Melitus' => $row->r_pribadi_dm ?? null,
            'Stroke' => $row->r_pribadi_stroke ?? null,
            'Jantung' => $row->r_pribadi_jantung ?? null,
        ]);

        $row->riwayat_penyakit_keluarga = $this->joinDiseaseList([
            'Hipertensi' => $row->r_keluarga_htn ?? null,
            'Diabetes Melitus' => $row->r_keluarga_dm ?? null,
            'Stroke' => $row->r_keluarga_stroke ?? null,
            'Jantung' => $row->r_keluarga_jantung ?? null,
        ]);
        $row->riwayat_penyakit_diri_1 = $diri1;
        $row->riwayat_penyakit_diri_2 = $diri2;
        $row->riwayat_penyakit_diri_3 = $diri3;

        $row->merokok_status = $this->normalizeYaTidak($row->status_merokok ?? $row->merokok ?? null);
        $row->merokok_batang_per_hari = $this->toIntOrNull($row->btg_rokok ?? null);
        $row->merokok_lama_tahun = $this->toIntOrNull($row->lama_rokok ?? null);
        $row->terpapar_asap_rokok = $row->paparan_rokok ?? null;

        $row->napas_pendek = $this->normalizeYaTidak($row->napas_pendek ?? null);
        $row->dahak = $this->normalizeYaTidak($row->dahak ?? null);
        $row->batuk = $this->normalizeYaTidak($row->batuk ?? null);
        $row->spirometri = $this->normalizeYaTidak($row->spirometri ?? null);

        $row->konsumsi_gula_berlebih = $this->normalizeYaTidak($row->gula ?? null);
        $row->konsumsi_garam_berlebih = $this->normalizeYaTidak($row->garam ?? null);
        $row->konsumsi_minyak_berlebih = $this->normalizeYaTidak($row->minyak ?? null);
        $row->kurang_sayur_buah = $this->normalizeYaTidak($row->sayur ?? null);
        $row->kurang_aktivitas_fisik = $this->normalizeYaTidak($row->aktivitas ?? null);
        $row->konsumsi_alkohol = $this->normalizeYaTidak($row->alkohol ?? null);

        $row->tinggi_badan = $this->toFloatOrNull($row->tinggi_badan ?? null);
        $row->berat_badan = $this->toFloatOrNull($row->berat_badan ?? null);
        $row->lingkar_perut = $this->toFloatOrNull($row->lingkar_pinggang ?? null);
        $row->sistolik = $this->toIntOrNull($row->sistolik ?? null);
        $row->diastolik = $this->toIntOrNull($row->tekanan_diastolik ?? null);

        $row->gds = $this->toIntOrNull($row->gula_darah_sewaktu ?? null);
        $row->gdp = $this->toIntOrNull($row->gula_darah_puasa ?? null);
        $row->gd2jampp = $this->toIntOrNull($row->gula_darah_2_jam_pp ?? null);
        $row->hba1c = $this->toFloatOrNull($row->hba1c ?? null);

        // kolesterol_total, hdl, ldl, trigliserida sudah ada nama kolomnya sama persis
        // dari simpus_profil_lipid.*, jadi tidak perlu di-mapping ulang.

        $row->usg_payudara = $row->usg ?? null;
        // iva, hpv_dna, sadanis sudah sama persis nama kolomnya, tidak perlu di-mapping ulang.

        $row->diagnosis_1 = $row->diagnosis_utama ?? null;
        $row->terapi_1 = $row->catatan_diagnosis ?? null;
        $row->diagnosis_2 = null;
        $row->terapi_2 = null;
        $row->diagnosis_3 = null;
        $row->terapi_3 = null;

        // Edukasi: berdasarkan kode_snomed yang tercatat di simpus_data_edukasi
        // untuk skriningID ini (lihat buildEdukasiMap()). Kalau skriningID tidak
        // ditemukan sama sekali di $edukasiMap, berarti belum ada data edukasi
        // tercatat untuk kunjungan ini -> tetap null (bukan "Tidak"), supaya beda
        // makna antara "belum ada data" vs "sudah dicek, tidak diberikan".
        $kodeEdukasi = $edukasiMap[$row->skriningID ?? null] ?? null;

        $row->edukasi_berhenti_merokok = $this->edukasiFlag($kodeEdukasi, ['171207006']);
        $row->edukasi_aktivitas_fisik = $this->edukasiFlag($kodeEdukasi, ['409073007']);
        $row->edukasi_diet = $this->edukasiFlag($kodeEdukasi, ['183063000', '698360004', '710824005', '311401005']);
        $row->edukasi_asap_rokok = $this->edukasiFlag($kodeEdukasi, ['225323000']);

        $row->ekg = $row->kesimpulan_ekg ?? null;
        $row->rujuk = $row->rujukan ?? null;

        // dd($row);

        return $row;
    }

    /**
     * Gabungkan flag penyakit yang truthy jadi 1 string "A, B" (untuk kolom keluarga).
     *
     * @param  array<string, mixed>  $flags  label => nilai flag (truthy/falsy)
     */
    private function joinDiseaseList(array $flags): ?string
    {
        $labels = array_keys(array_filter($flags, fn ($v) => $this->isTruthy($v)));

        return $labels === [] ? null : implode(', ', $labels);
    }

    /**
     * Pecah flag penyakit yang truthy jadi maks 3 slot terpisah.
     *
     * @param  array<string, mixed>  $flags  label => nilai flag (truthy/falsy)
     * @return array{0: ?string, 1: ?string, 2: ?string}
     */
    private function packDiseaseList(array $flags): array
    {
        $labels = array_keys(array_filter($flags, fn ($v) => $this->isTruthy($v)));

        return [
            $labels[0] ?? null,
            $labels[1] ?? null,
            $labels[2] ?? null,
            // catatan: kalau ada label ke-4, akan hilang -- lihat komentar di atas class
        ];
    }

    /**
     * @param  array<int, string>|null  $kodeYangDiberikan  daftar kode_snomed edukasi utk 1 skriningID
     * @param  array<int, string>  $kodeTarget  kode-kode yang dianggap masuk kategori ini
     */
    private function edukasiFlag(?array $kodeYangDiberikan, array $kodeTarget): ?string
    {
        if ($kodeYangDiberikan === null) {
            return null; // belum ada data edukasi sama sekali utk kunjungan ini
        }

        $adaYangCocok = count(array_intersect($kodeYangDiberikan, $kodeTarget)) > 0;

        return $adaYangCocok ? 'Ya' : 'Tidak';
    }

    private function isTruthy(mixed $v): bool
    {
        if ($v === null) {
            return false;
        }

        if (is_bool($v)) {
            return $v;
        }

        return in_array(mb_strtolower((string) $v), ['1', 'ya', 'true', 'y'], true);
    }

    /**
     * Konversi berbagai kemungkinan format boolean di database (0/1, 'Ya'/'Tidak', dsb)
     * jadi string "Ya"/"Tidak" sesuai pilihan dropdown template.
     * SESUAIKAN kalau format asli di database kamu berbeda.
     */
    private function normalizeYaTidak(mixed $v): ?string
    {
        if ($v === null || $v === '') {
            return null;
        }

        return $this->isTruthy($v) ? 'Ya' : 'Tidak';
    }

    private function toIntOrNull(mixed $v): ?int
    {
        return ($v === null || $v === '') ? null : (int) $v;
    }

    private function toFloatOrNull(mixed $v): ?float
    {
        return ($v === null || $v === '') ? null : (float) $v;
    }
}
