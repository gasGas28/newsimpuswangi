<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\RuangLayanan\SimpusResepObat;


class LaporanService
{
    /**
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
        return DB::table('simpus_loket as l')
            ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
            ->join('simpus_pelayanan as pel', 'l.idLoket', '=', 'pel.loketId')
            ->join('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
            ->leftJoin('unit_profiles as up', 'up.unit_id', '=', 'l.unitId')
            ->leftJoin('simpus_skrining_ptm as skrining', 'pel.idpelayanan', '=', 'skrining.idPelayanan')
            ->leftJoin('simpus_kunjungan_ptm as kunjungan', 'kunjungan.idSkrining', '=', 'skrining.idSkrining')
            ->leftJoin('master_dokter as dokter', 'dokter.ihs_nakes', '=', 'kunjungan.id_petugas')
            ->leftJoin('faktor_risiko_ptm as frisiko', 'skrining.idSkrining', '=', 'frisiko.skriningID')
            ->leftJoin('simpus_hipertensi as hipertensi', 'skrining.idSkrining', '=', 'hipertensi.skriningID')
            ->leftJoin('simpus_diabetes as diabetes', 'skrining.idSkrining', '=', 'diabetes.skriningID')
            ->leftJoin('simpus_obesitas as obesitas', 'skrining.idSkrining', '=', 'obesitas.skriningID')
            ->leftJoin('simpus_asam_urat as asam_urat', 'skrining.idSkrining', '=', 'asam_urat.skriningID')
            ->leftJoin('simpus_profil_lipid as profil_lipid', 'skrining.idSkrining', '=', 'profil_lipid.skriningID')
            ->leftJoin('simpus_gangguan_pendengaran as pendengaran', 'skrining.idSkrining', '=', 'pendengaran.skriningID')
            ->leftJoin('simpus_gangguan_penglihatan as penglihatan', 'skrining.idSkrining', '=', 'penglihatan.skriningID')
            ->leftJoin('simpus_kolorektal as kolorektal', 'skrining.idSkrining', '=', 'kolorektal.skriningID')
            ->leftJoin('simpus_kanker_paru as paru', 'skrining.idSkrining', '=', 'paru.skriningID')
            ->leftJoin('simpus_ekg as ekg', 'skrining.idSkrining', '=', 'ekg.skriningID')
            ->leftJoin('simpus_kanker_iva as serviks', 'skrining.idSkrining', '=', 'serviks.skriningID')
            ->leftJoin('simpus_thalasemia as thalasemia', 'skrining.idSkrining', '=', 'thalasemia.skriningID')
            ->leftJoin('simpus_status_ptm as status', 'skrining.idSkrining', '=', 'status.skriningID')
            ->where('pel.kdPoli', '006')
            ->when(
                $filters['tanggal_mulai'] ?? null,
                fn($q, $v) => $q->whereDate('kunjungan.tanggal_skrining', '>=', $v)
            )
            ->when(
                $filters['tanggal_selesai'] ?? null,
                fn($q, $v) => $q->whereDate('kunjungan.tanggal_skrining', '<=', $v)
            )
            ->select([
                'hipertensi.*',
                'frisiko.*',
                'diabetes.*',
                'profil_lipid.*',
                'obesitas.*',
                'asam_urat.*',
                'ekg.*',
                'status.*',
                'serviks.*',
                'p.NIK as nik',
                'p.NAMA_LGKP as nama',
                'pel.kdPoli as poli',
                'pel.idpelayanan as idPelayanan',
            ])
            ->orderBy('kunjungan.tanggal_skrining')
            ->get();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, object>  $rawRows  hasil dari runMainQuery()
     */
    public function mapRows(Collection $rawRows): Collection
    {
        $pelayananIds = $rawRows->pluck('idPelayanan')->filter()->unique()->values();

        $edukasiMap  = $this->buildEdukasiMap($rawRows->pluck('skriningID')->filter()->unique()->values());
        $diagnosaMap = $this->buildDiagnosaMap($pelayananIds);
        $resepMap    = $this->buildResepMap($pelayananIds);

        return $rawRows->map(fn($row) => $this->mapRow($row, $edukasiMap, $diagnosaMap, $resepMap));
    }

    /**
     * @param  \Illuminate\Support\Collection<int, mixed>  $skriningIds
     * @return array<string, array<int, string>>  skriningID => daftar kode_snomed yang diberikan
     */
    private function buildEdukasiMap(Collection $skriningIds): array
    {
        if ($skriningIds->isEmpty()) {
            return [];
        }

        $rows = DB::table('simpus_data_edukasi')
            ->join('master_edukasi_ptm', 'master_edukasi_ptm.kode_snomed', '=', 'simpus_data_edukasi.kode_snomed')
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
     * @param  \Illuminate\Support\Collection<int, mixed>  $pelayananIds
     * @return array<string, \Illuminate\Support\Collection<int, object>>  pelayananId => daftar resep obat
     */
    private function buildResepMap(Collection $pelayananIds): array
    {
        if ($pelayananIds->isEmpty()) {
            return [];
        }

        return SimpusResepObat::select(
            'simpus_resep_obat.*',
            'simpus_resep_detail.obat_id',
            'simpus_master_obat.NAMA',
            'simpus_master_obat.SATUAN',
        )
            ->join('simpus_resep_detail', 'simpus_resep_obat.id_resep', '=', 'simpus_resep_detail.resep_id')
            ->join('simpus_master_obat', 'simpus_resep_detail.obat_id', '=', 'simpus_master_obat.OBAT_ID')
            ->whereIn('simpus_resep_obat.pelayananId', $pelayananIds)
            ->get()
            ->groupBy('pelayananId')
            ->all();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, mixed>  $pelayananIds
     * @return array<string, \Illuminate\Support\Collection<int, object>>  pelayananId => daftar diagnosa
     */
    private function buildDiagnosaMap(Collection $pelayananIds): array
    {
        if ($pelayananIds->isEmpty()) {
            return [];
        }

        return DB::table('simpus_data_diagnosa')->whereIn('pelayananId', $pelayananIds)
            ->get()
            ->groupBy('pelayananId')
            ->all();
    }

    /**
     * @param  array<string, array<int, string>>  $edukasiMap  hasil buildEdukasiMap()
     */
    private function mapRow(object $row, array $edukasiMap = [], array $diagnosaMap = [], array $resepMap = []): object
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

        $row->merokok_status = $row->merokok ?? null;
        $row->merokok_batang_per_hari = $this->toIntOrNull($row->btg_rokok ?? null);
        $row->merokok_lama_tahun = $this->toIntOrNull($row->lama_rokok ?? null);
        $row->terpapar_asap_rokok = $row->paparan_rokok ?? null;

        $row->napas_pendek = $row->napas_pendek ?? null;
        $row->dahak = $row->dahak ?? null;
        $row->batuk = $row->batuk ?? null;
        $row->spirometri = $row->spirometri ?? null;

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

        $row->usg_payudara = $row->usg ?? null;

        $diagnosaList = $diagnosaMap[$row->idPelayanan ?? null] ?? collect();
        $resepList    = $resepMap[$row->idPelayanan ?? null] ?? collect();


        $row->diagnosis_1 = $diagnosaList->get(0)?->nmDiagnosa ?? null;
        $row->terapi_1 = $resepList->get(0)?->NAMA ?? null;
        $row->diagnosis_2 = $diagnosaList->get(1)?->nmDiagnosa ?? null;
        $row->terapi_2 = $resepList->get(1)?->NAMA ?? null;
        $row->diagnosis_3 = $diagnosaList->get(2)?->nmDiagnosa ?? null;
        $row->terapi_3 = $resepList->get(2)?->NAMA ?? null;


        $kodeEdukasi = $edukasiMap[$row->skriningID ?? null] ?? null;

        $row->edukasi_berhenti_merokok = $this->edukasiFlag($kodeEdukasi, ['171207006']);
        $row->edukasi_aktivitas_fisik = $this->edukasiFlag($kodeEdukasi, ['409073007']);
        $row->edukasi_diet = $this->edukasiFlag($kodeEdukasi, ['183063000', '698360004', '710824005', '311401005']);
        $row->edukasi_asap_rokok = $this->edukasiFlag($kodeEdukasi, ['225323000']);
        $row->ekg = $row->kesimpulan_ekg ?? null;
        $row->rujuk = $row->rujukan ?? null;

        return $row;
    }

    /**
     * Gabungkan flag penyakit yang truthy jadi 1 string "A, B" (untuk kolom keluarga).
     *
     * @param  array<string, mixed>  $flags  label => nilai flag (truthy/falsy)
     */
    private function joinDiseaseList(array $flags): ?string
    {
        $labels = array_keys(array_filter($flags, fn($v) => $this->isTruthy($v)));

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
        $labels = array_keys(array_filter($flags, fn($v) => $this->isTruthy($v)));

        return [
            $labels[0] ?? null,
            $labels[1] ?? null,
            $labels[2] ?? null,
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
