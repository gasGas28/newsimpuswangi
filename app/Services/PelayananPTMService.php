<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\RuangLayanan\SimpusTindakan;
use App\Models\RuangLayanan\MasterDokter;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;
use App\Models\RuangLayanan\SkriningPTM\FaktorRisiko;
use App\Models\RuangLayanan\SkriningPTM\AssessmentPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusDiabetes;
use App\Models\RuangLayanan\SkriningPTM\SimpusPemeriksaanPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusHipertensi;
use App\Models\RuangLayanan\SkriningPTM\SimpusAsamUrat;
use App\Models\RuangLayanan\SkriningPTM\SimpusObesitas;
use App\Models\RuangLayanan\SkriningPTM\SimpusProfilLipid;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;

class PelayananPTMService
{
    public function getData($id, $idPoli)
    {
        $DataPasien = DB::table('simpus_loket as l')
            ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
            ->join('simpus_pelayanan as pel', 'l.idLoket', '=', 'pel.loketId')
            ->join('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
            ->leftJoin('unit_profiles as up', 'up.unit_id', '=', 'l.unitId')
            ->leftJoin('master_dokter as dokter', 'up.unit_id', '=', 'dokter.pusk_id')
            ->leftJoin('simpus_skrining_ptm as skrining', 'pel.idpelayanan', '=', 'skrining.idPelayanan')

            ->leftJoin('setup_kel as kel', function ($join) {
                $join->on('p.NO_KEL', '=', 'kel.NO_KEL')
                    ->on('p.NO_KEC', '=', 'kel.NO_KEC')
                    ->on('p.NO_KAB', '=', 'kel.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kel.NO_PROP');
            })

            ->leftJoin('setup_kec as kec', function ($join) {
                $join->on('p.NO_KEC', '=', 'kec.NO_KEC')
                    ->on('p.NO_KAB', '=', 'kec.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kec.NO_PROP');
            })

            ->leftJoin('setup_kab as kab', function ($join) {
                $join->on('p.NO_KAB', '=', 'kab.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kab.NO_PROP');
            })

            ->leftJoin('setup_prop as prop', 'p.NO_PROP', '=', 'prop.NO_PROP')


            ->where('l.kdPoli', $idPoli)
            ->where('l.idLoket', $id)

            ->select(
                'p.ID',
                'p.NO_MR',
                'p.NAMA_LGKP',
                'p.NIK',
                'p.IHS_NUMBER',
                'kel.nama_kel',
                'kec.nama_kec',
                'kab.nama_kab',
                'prop.nama_prop',
                'poli.nmPoli',
                'l.kdPoli',
                'p.alamat',
                'p.no_rt',
                'p.no_rw',
                'p.jenis_klmin',
                'l.umur',
                'l.umur_bulan',
                'l.umur_hari',
                'l.tglKunjungan',
                'l.idLoket',
                'l.kunjBaru',
                'l.idLoket',
                'pel.idpelayanan',
                'pel.sudahDilayani',
                'pel.startTime',
                'pel.progressTime',
                'up.nama_unit',
                'dokter.kdDokter',
                'dokter.nmDokter',
                'skrining.idSkrining',
            )
            ->first();


        return $DataPasien;
    }

    public function getMasterData()
    {
        $SimpusTindakan = SimpusTindakan::select('kdTindakan', 'nmTindakan', 'nmTindakanInd')
            ->where('deskripsi', 'icd9cm')
            ->groupBy('kdTindakan', 'nmTindakan', 'nmTindakanInd')
            ->get();

        $TenagaMedis = MasterDokter::select('nmDokter', 'kdDokter')
            ->where('profesi_id', [23, 24])
            ->get();


        // dd($TenagaMedis);
        // dd($SimpusTindakan);
        return [
            'tindakan' => $SimpusTindakan,
            'TenagaMedis' => $TenagaMedis
        ];
    }
    public function updateStatusPelayanan($idPelayanan, $idLoket, $status)
    {
        DB::table('simpus_pelayanan')
            ->where('idpelayanan', $idPelayanan)
            ->update([
                'sudahDilayani' => $status,
                'startTime' => now(),
            ]);
        SimpusSkriningPTM::firstOrCreate([
            'idSkrining' => (string) Str::uuid(),
            'idPelayanan' => $idPelayanan,
            'idLoket' => $idLoket,
            'status' => 'draft',
        ]);

        // dd($idLoket);
    }


    public function addKunjunganPTM($data)
    {
        $kunjungan = KunjunganPTM::updateOrCreate([
            'idSkrining' => $data['idSkrining'],
        ], [
            'idPelayanan' => $data['idPelayanan'],
            'idLoket' => $data['idLoket'],
            'nik_pasien' => $data['nik_pasien'],
            'tanggal_skrining' => $data['tanggal_skrining'],
            'id_petugas' => $data['id_petugas'],
            'fasyankes' => $data['fasyankes'],
            'jenis_kunjungan' => $data['jenis_kunjungan'],
            'keluhan_utama' => $data['keluhan_utama'],
        ]);

        return $kunjungan;
    }

    public function addFaktorRisiko($data)
    {
        $fakorRisiko = FaktorRisiko::updateOrCreate([
            'skriningID' => $data['idSkrining'],
        ], [
            'pelayananId' => $data['idpelayanan'] ?? null,
            'merokok' => $data['merokok'] ?? null,
            'status_merokok' => $data['status_merokok'] ?? null,
            'btg_rokok' => $data['btg_rokok'] ?? null,
            'lama_rokok' => $data['lama_rokok'] ?? null,
            'paparan_rokok' => $data['paparan_rokok'] ?? null,

            'gula' => $data['gula'] ?? null,
            'garam' => $data['garam'] ?? null,
            'minyak' => $data['minyak'] ?? null,
            'sayur' => $data['sayur'] ?? null,
            'aktivitas' => $data['aktivitas'] ?? null,
            'alkohol' => $data['alkohol'] ?? null,

            'r_pribadi_htn' => $data['r_pribadi_htn'] ?? null,
            'r_pribadi_dm' => $data['r_pribadi_dm'] ?? null,
            'r_pribadi_stroke' => $data['r_pribadi_stroke'] ?? null,
            'r_pribadi_jantung' => $data['r_pribadi_jantung'] ?? null,

            'r_keluarga_htn' => $data['r_keluarga_htn'] ?? null,
            'r_keluarga_dm' => $data['r_keluarga_dm'] ?? null,
            'r_keluarga_stroke' => $data['r_keluarga_stroke'] ?? null,
            'r_keluarga_jantung' => $data['r_keluarga_jantung'] ?? null,
        ]);

        return $fakorRisiko;
    }

    public function savePemeriksaanMetabolik(array $data): void
    {
        DB::transaction(function () use ($data) {
            $pemeriksaan = SimpusPemeriksaanPTM::firstOrCreate(
                [
                    'idSkrining' => $data['skriningId'],
                ]
            );
            $this->saveHipertensi($pemeriksaan->id, $data['hipertensi'] ?? []);
            $this->saveDiabetes($pemeriksaan->id, $data['diabetes'] ?? []);
            $this->saveObesitas($pemeriksaan->id, $data['obesitas'] ?? []);
            $this->saveAsamUrat($pemeriksaan->id, $data['obesitas'] ?? []);
            $this->saveProfilLipid($pemeriksaan->id, $data['obesitas'] ?? []);
        });
    }

    private function saveObesitas(int $pemeriksaanPTMId, array $data): void
    {
        if (empty(array_filter($data, fn($value) => $value !== null && $value !== ''))) {
            return;
        }

        SimpusObesitas::updateOrCreate(
            [
                'pemeriksaan_ptm_id' => $pemeriksaanPTMId,
            ],
            [
                'berat_badan' => $data['berat_badan'] ?? null,
                'tinggi_badan' => $data['tinggi_badan'] ?? null,
                'imt' => $data['imt'] ?? null,
                'interpretasi_imt' => $data['interpretasi_imt'] ?? null,
                'lingkar_pinggang' => $data['lingkar_pinggang'] ?? null,
                'interpretasi_lp' => $data['interpretasi_lp'] ?? null,
            ]
        );
    }

    private function saveDiabetes(int $pemeriksaanPTMId, array $data): void
    {
        if (empty(array_filter($data, fn($value) => $value !== null && $value !== ''))) {
            return;
        }

        SimpusDiabetes::updateOrCreate(
            [
                'pemeriksaan_ptm_id' => $pemeriksaanPTMId,
            ],
            [
                'gula_darah_puasa' => $data['gdp'] ?? null,
                'gula_darah_sewaktu' => $data['gds'] ?? null,
                'gula_darah_2_jam_pp' => $data['gd2pp'] ?? null,
                'hba1c' => $data['hba1c'] ?? null,
                'kategori_gula_darah_puasa' => $data['interpretasi_gdp'] ?? null,
                'kategori_gula_darah_sewaktu' => $data['interpretasi_gds'] ?? null,
                'kategori_gula_darah_2_jam_pp' => $data['interpretasi_gd2pp'] ?? null,
                'kategori_hba1c' => $data['interpretasi_hba1c'] ?? null,
            ]
        );
    }

    private function saveHipertensi(int $pemeriksaanPTMId, array $data): void
    {
        if (empty(array_filter($data, fn($value) => $value !== null && $value !== ''))) {
            return;
        }

        SimpusHipertensi::updateOrCreate(
            [
                'pemeriksaan_ptm_id' => $pemeriksaanPTMId,
            ],
            [
                'sistolik' => $data['sistolik'] ?? null,
                'tekanan_diastolik' => $data['tekanan_diastolik'] ?? null,
                'kategori_tekanan_darah' => $data['kategori_tekanan_darah'] ?? null,
                'suhu' => $data['suhu'] ?? null,
                'nadi' => $data['nadi'] ?? null,
                'pernapasan' => $data['pernapasan'] ?? null,
            ]
        );
    }
    private function saveAsamUrat(int $pemeriksaanPTMId, array $data): void
    {
        if (empty(array_filter($data, fn($value) => $value !== null && $value !== ''))) {
            return;
        }

        SimpusAsamUrat::updateOrCreate(
            [
                'pemeriksaan_ptm_id' => $pemeriksaanPTMId,
            ],
            [
                'asam_urat' => $data['asam_urat'] ?? null,
                'kategori_asam_urat' => $data['kategori_asam_urat'] ?? null,
            ]
        );
    }
    private function saveProfilLipid(int $pemeriksaanPTMId, array $data): void
    {
        if (empty(array_filter($data, fn($value) => $value !== null && $value !== ''))) {
            return;
        }

        SimpusProfilLipid::updateOrCreate(
            [
                'pemeriksaan_ptm_id' => $pemeriksaanPTMId,
            ],
            [
                'kolesterol_total' => $data['kolesterol_total'] ?? null,
                'hdl' => $data['hdl'] ?? null,
                'ldl' => $data['ldl'] ?? null,
                'trigliserida' => $data['trigliserida'] ?? null,
                'interpretasi_kolesterol_total' => $data['interpretasi_kolesterol_total'] ?? null,
                'interpretasi_hdl' => $data['interpretasi_hdl'] ?? null,
                'interpretasi_ldl' => $data['interpretasi_ldl'] ?? null,
                'interpretasi_trigliserida' => $data['interpretasi_trigliserida'] ?? null,
            ]
        );
    }

    public function addAssessmentPTM($data)
    {
        $skriningPtmId = $data['skrining_ptm_id'] ?? DB::table('skrining_ptm')
            ->where('idpelayanan', $data['idpelayanan'] ?? null)
            ->value('id');

        if (!$skriningPtmId) {
            throw ValidationException::withMessages([
                'skrining_ptm_id' => 'Data skrining PTM belum ditemukan. Simpan data kunjungan terlebih dahulu.',
            ]);
        }

        return AssessmentPTM::updateOrCreate([
            'skrining_ptm_id' => $skriningPtmId,
        ], [
            'masalah_hasil_skrining' => $data['masalah_hasil_skrining'] ?? null,
            'ringkasan_temuan' => $data['ringkasan_temuan'] ?? null,
            'diagnosis_utama' => $data['diagnosis_utama'] ?? null,
            'diagnosis_utama_saran' => $data['diagnosis_utama_saran'] ?? null,
            'status_klinis' => $data['status_klinis'] ?? null,
            'catatan_diagnosis' => $data['catatan_diagnosis'] ?? null,
            'kategori_risiko' => $data['kategori_risiko'] ?? null,
            'ringkasan_klinis' => $data['ringkasan_klinis'] ?? null,
            'catatan_assessment' => $data['catatan_assessment'] ?? null,
        ]);
    }
}
