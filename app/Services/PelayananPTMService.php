<?php

namespace App\Services;

use App\Models\RuangLayanan\KIA\Alergi;
use Illuminate\Support\Facades\DB;
use App\Models\RuangLayanan\SimpusTindakan;
use App\Models\RuangLayanan\MasterDokter;
use App\Models\RuangLayanan\SimpusDataDiagnosa;
use App\Models\RuangLayanan\SimpusDiagnosa;
use App\Models\RuangLayanan\SimpusDiagnosaaa;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;
use App\Models\RuangLayanan\SkriningPTM\FaktorRisiko;
use App\Models\RuangLayanan\SkriningPTM\AssessmentPTM;
use App\Models\RuangLayanan\SkriningPTM\GangguanPendengaran;
use App\Models\RuangLayanan\SkriningPTM\GangguanPenglihatan;
use App\Models\RuangLayanan\SkriningPTM\SimpusDiabetes;
use App\Models\RuangLayanan\SkriningPTM\SimpusHipertensi;
use App\Models\RuangLayanan\SkriningPTM\SimpusAsamUrat;
use App\Models\RuangLayanan\SkriningPTM\SimpusEKG;
use App\Models\RuangLayanan\SkriningPTM\SimpusKankerIva;
use App\Models\RuangLayanan\SkriningPTM\SimpusKankerParu;
use App\Models\RuangLayanan\SkriningPTM\SimpusKolorektal;
use App\Models\RuangLayanan\SkriningPTM\SimpusObesitas;
use App\Models\RuangLayanan\SkriningPTM\SimpusProfilLipid;
use App\Models\RuangLayanan\SkriningPTM\SimpusThalasemia;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusStatusPTM;
use App\Models\RuangLayanan\SimpusMasterObat;

class PelayananPTMService
{
    public function getDataPasien($id, $idPoli)
    {
        $DataPasien = DB::table('simpus_loket as l')
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
            ->leftJoin('assessment_ptm as assessment', 'skrining.id', '=', 'assessment.skrining_ptm_id')
            ->leftJoin('simpus_ekg as ekg', 'skrining.idSkrining', '=', 'ekg.skriningID')
            ->leftJoin('simpus_kanker_iva as serviks', 'skrining.idSkrining', '=', 'serviks.skriningID')


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
                'p.alamat',
                'p.no_rt',
                'p.no_rw',
                'p.jenis_klmin',
                'l.kdPoli',
                'l.umur',
                'l.umur_bulan',
                'l.umur_hari',
                'l.idLoket',
                'l.tglKunjungan',
                'l.kunjBaru',
                'l.idLoket',
                'l.puskId',
                'kel.nama_kel',
                'kec.nama_kec',
                'kab.nama_kab',
                'prop.nama_prop',
                'poli.nmPoli',
                'pel.idpelayanan',
                'pel.sudahDilayani',
                'pel.startTime',
                'pel.progressTime',
                'up.nama_unit',
                'dokter.nmDokter',
                'kunjungan.*',
                'skrining.*',
                'frisiko.*',
                'hipertensi.*',
                'diabetes.*',
                'obesitas.*',
                'asam_urat.*',
                'profil_lipid.*',
                'pendengaran.*',
                'penglihatan.*',
                'assessment.*',
                'kolorektal.*',
                'paru.*',
                'ekg.*',
                'serviks.*',
            )
            ->first();
        // dd($DataPasien);

        return $DataPasien;
    }

    public function getMasterData(string $idPelayanan)
    {
        $SimpusTindakan = SimpusTindakan::select('kdTindakan', 'nmTindakan', 'nmTindakanInd')
            ->where('deskripsi', 'icd9cm')
            ->groupBy('kdTindakan', 'nmTindakan', 'nmTindakanInd')
            ->get();

        $TenagaMedis = MasterDokter::select('nmDokter', 'ihs_nakes', 'kdDokter')
            ->where('profesi_id', [19])
            ->get();
        $diagnosa = SimpusDiagnosaaa::whereNotNull('F3')->get();
        $AlergiMakanan = Alergi::where('category', 1)->get();
        $AlergiObat = Alergi::where('category', 2)->get();
        $DataDiagnosa = SimpusDataDiagnosa::where('pelayananId', $idPelayanan)->get();
        $DataObat = SimpusMasterObat::where('AKTIF', 1)->get();

        // dd($diagnosa);

        // dd($DataSkrining);
        return [
            'tindakan' => $SimpusTindakan,
            'TenagaMedis' => $TenagaMedis,
            'Diagnosa' => $diagnosa,
            'AlergiMakanan' => $AlergiMakanan,
            'AlergiObat' => $AlergiObat,
            'DataDiagnosa' => $DataDiagnosa,
            'DataObat' => $DataObat,
        ];
    }
    public function updateStatusPelayanan(string $idPelayanan, string $status)
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
            'status' => 'arrived',
        ]);
        // dd($idLoket);
    }


    public function addKunjunganPTM($data)
    {
        $kunjungan = KunjunganPTM::updateOrCreate([
            'idSkrining' => $data['idSkrining'],
        ], [
            'idPelayanan' => $data['idPelayanan'],
            'nik_pasien' => $data['nik_pasien'],
            'tanggal_skrining' => $data['tanggal_skrining'],
            'id_petugas' => $data['id_petugas'],
            'fasyankes' => $data['fasyankes'],
            'jenis_kunjungan' => $data['jenis_kunjungan'],
            'keluhan_utama' => $data['keluhan_utama'],
            'patient_id' => $data['patient_id']
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

            'obat' => $data['obat'] ?? null,
            'kesiapan' => $data['kesiapan'] ?? null,
            'dukung' => $data['dukung'] ?? null,

            'r_pribadi_htn' => $data['r_pribadi_htn'] ?? null,
            'r_pribadi_dm' => $data['r_pribadi_dm'] ?? null,
            'r_pribadi_stroke' => $data['r_pribadi_stroke'] ?? null,
            'r_pribadi_jantung' => $data['r_pribadi_jantung'] ?? null,

            'r_keluarga_htn' => $data['r_keluarga_htn'] ?? null,
            'r_keluarga_dm' => $data['r_keluarga_dm'] ?? null,
            'r_keluarga_stroke' => $data['r_keluarga_stroke'] ?? null,
            'r_keluarga_jantung' => $data['r_keluarga_jantung'] ?? null,

            'skor_faktor_risiko' => $data['skor_faktor_risiko'] ?? null,
            'kategori_faktor_risiko' => $data['kategori_faktor_risiko'] ?? null,
            'detail_faktor_risiko' => $data['detail_faktor_risiko'] ?? null,
        ]);

        return $fakorRisiko;
    }

    public function savePemeriksaanMetabolik(array $data): void
    {
        DB::transaction(function () use ($data) {
            $pemeriksaan = SimpusSkriningPTM::updateOrCreate(
                [
                    'idSkrining' => $data['skriningId'],
                ]
            );
            // $this->saveHipertensi($pemeriksaan->idSkrining, $data['hipertensi'] ?? []);
            // $this->saveDiabetes($pemeriksaan->idSkrining, $data['diabetes'] ?? []);
            // $this->saveObesitas($pemeriksaan->idSkrining, $data['obesitas'] ?? []);
            // $this->saveAsamUrat($pemeriksaan->idSkrining, $data['asam_urat'] ?? []);
            // $this->saveProfilLipid($pemeriksaan->idSkrining, $data['profil_lipid'] ?? []);
        });
    }

    public function saveGangguanIndera(array $data): void
    {
        DB::transaction(function () use ($data) {
            $pemeriksaan = SimpusSkriningPTM::updateOrCreate(
                [
                    'idSkrining' => $data['skriningId'],
                ]
            );
            $this->savePenglihatan($pemeriksaan->idSkrining, $data['penglihatan'] ?? []);
            $this->savePendengaran($pemeriksaan->idSkrining, $data['pendengaran'] ?? []);
        });
    }

    public function saveThalasemia($data)
    {
        $thalasemia = SimpusThalasemia::updateOrCreate(
            [
                'skriningID' => $data['skriningId']
            ],
            [
                'hemoglobin' => $data['hb'],
                'mcv' => $data['mcv'],
                'mch' => $data['mch'],
                'eritrosit' => $data['rbc'],
                'rdw' => $data['rdw'],
            ]
        );
        return $thalasemia;
    }

    public function saveObesitas($data)
    {
        SimpusObesitas::updateOrCreate(
            [
                'skriningID' => $data['skriningId'],
            ],
            [
                'berat_badan' => $data['berat_badan'] ?? null,
                'tinggi_badan' => $data['tinggi_badan'] ?? null,
                'imt' => $data['imt'] ?? null,
                'interpretasi_ptm' => $data['interpretasi_imt'] ?? null,
                'lingkar_pinggang' => $data['lingkar_perut'] ?? null,
                'interpretasi_lp' => $data['interpretasi_lp'] ?? null,
            ]
        );
    }
    public function saveKankerParu($data)
    {
        $kankerParu = SimpusKankerParu::updateOrCreate(
            [
                'skriningID' => $data['skriningId']
            ],
            [
                'kuesioner1' => $data['kp1'],
                'kuesioner2' => $data['kp2'],
                'kuesioner3' => $data['kp3'],
                'kuesioner4' => $data['kp4'],
                'kuesioner5' => $data['kp5'],
                'kuesioner6' => $data['kp6'],
                'kuesioner7' => $data['kp7'],
                'hasil_kuesioner' => $data['hasil_kkp'],
            ]
        );
        return $kankerParu;
    }
    public function saveKolorektal($data)
    {
        $kolorektal = SimpusKolorektal::updateOrCreate(
            [
                'skriningID' => $data['skriningId']
            ],
            [
                'question1' => $data['kkr1'],
                'question2' => $data['kkr2'],
                'result' => $data['hasil_kkr'],
                'colok_dbr' => $data['colok_dubur'],
                'darah_samar' => $data['darah_samar'],
            ]
        );
        return $kolorektal;
    }
    public function saveKankerServiks(array $data)
    {
        // Kalau IVA bukan positif, kosongkan tindak lanjut
        if (($data['iva'] ?? null) !== 'positif') {
            $data['krioterapi'] = false;
            $data['thermal'] = false;
            $data['tca'] = false;
            $data['rujuk_serviks'] = false;
        }

        $kanker = SimpusKankerIva::updateOrCreate(
            ['skriningID' => $data['skriningId']],
            [
                'inspekulo' => $data['inspekulo'] ?? null,
                'iva' => $data['iva'] ?? null,
                'hpv_dna' => $data['hpv'] ?? null,
                'sadanis' => $data['sadanis'] ?? null,
                'usg' => $data['usg_py'] ?? null,
                'krioterapi' => $data['krioterapi'] ?? false,
                'thermal' => $data['thermal'] ?? false,
                'tca' => $data['tca'] ?? false,
                'rujuk_serviks' => $data['rujuk_serviks'] ?? false,
            ]
        );
        return $kanker;
    }
    public function saveEKG($data)
    {
        $ekg = SimpusEKG::updateOrCreate(
            [
                'skriningID' => $data['skriningId']
            ],
            [
                'hr' => $data['hr'],
                'irama' => $data['irama'],
                'axis' => $data['axis'],
                'segmen_st' => $data['st'],
                'qrs' => $data['qrs'],
                'kesimpulan_ekg' => $data['hasil_ekg'],
            ]
        );
        return $ekg;
    }

    private function savePenglihatan(string $skriningID, array $data): void
    {
        if (empty(array_filter($data, fn($value) => $value !== null && $value !== ''))) {
            return;
        }

        GangguanPenglihatan::updateOrCreate(
            [
                'skriningID' => $skriningID,
            ],
            [
                'visus_od' => $data['visus_od'] ?? null,
                'visus_os' => $data['visus_os'] ?? null,
                'pinhole_od' => $data['pinhole_od'] ?? null,
                'pinhole_os' => $data['pinhole_os'] ?? null,
                'anterior_os' => $data['sa_os'] ?? null,
                'anterior_od' => $data['sa_od'] ?? null,
                'shadow_os' => $data['st_os'] ?? null,
                'shadow_od' => $data['st_od'] ?? null,
                'refleks_os' => $data['rf_os'] ?? null,
                'refleks_od' => $data['rf_od'] ?? null,
                'glaukoma_os' => $data['gio_os'] ?? null,
                'glaukoma_od' => $data['gio_od'] ?? null,
                'retinopati_os' => $data['retino_os'] ?? null,
                'retinopati_od' => $data['retino_od'] ?? null,
            ]
        );
        // dd($data);
    }
    private function savePendengaran(string $skriningID, array $data): void
    {
        if (empty(array_filter($data, fn($value) => $value !== null && $value !== ''))) {
            return;
        }

        GangguanPendengaran::updateOrCreate(
            [
                'skriningID' => $skriningID,
            ],
            [
                'tuli_kiri' => $data['tuli_kiri'] ?? null,
                'tuli_kanan' => $data['tuli_kanan'] ?? null,
                'omsk_kiri' => $data['omsk_kiri'] ?? null,
                'omsk_kanan' => $data['omsk_kanan'] ?? null,
                'serumen_kiri' => $data['serumen_kiri'] ?? null,
                'serumen_kanan' => $data['serumen_kanan'] ?? null,
                'presbi_kiri' => $data['presbi_kiri'] ?? null,
                'presbi_kanan' => $data['presbi_kanan'] ?? null,
                'bisik_kiri' => $data['bisik_kiri'] ?? null,
                'bisik_kanan' => $data['bisik_kanan'] ?? null,
            ]
        );
    }


    public function saveDiabetes(array $data): void
    {

        SimpusDiabetes::updateOrCreate(
            [
                'skriningID' => $data['skriningId'],
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

    public function saveHipertensi(array $data): void
    {

        SimpusHipertensi::updateOrCreate(
            [
                'skriningID' => $data['skriningId'],
            ],
            [
                'sistolik' => $data['sistolik'] ?? null,
                'tekanan_diastolik' => $data['diastolik'] ?? null,
                'kategori_tekanan_darah' => $data['kategori_hipertensi'] ?? null,
                'suhu' => $data['suhu'] ?? null,
                'nadi' => $data['nadi'] ?? null,
                'pernapasan' => $data['pernapasan'] ?? null,
            ]
        );
    }
    public function saveAsamUrat(array $data): void
    {

        SimpusAsamUrat::updateOrCreate(
            [
                'skriningID' => $data['skriningId'],
            ],
            [
                'asam_urat' => $data['asam_urat'] ?? null,
                'kategori_asam_urat' => $data['interpretasi_asam_urat'] ?? null,
            ]
        );
    }
    public function saveProfilLipid(array $data): void
    {

        SimpusProfilLipid::updateOrCreate(
            [
                'skriningID' => $data['skriningId'],
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

    public function saveStatusPasien($data)
    {
        if (empty(array_filter($data, fn($value) => $value !== null && $value !== ''))) {
            return;
        }
        $status = SimpusStatusPTM::updateOrCreate(
            [
                'skriningID' => $data['skriningId'],
                'cara_keluar' => $data['cara_keluar'],
                'kondisi_pasien' => $data['kondisi_keluar'],
                'jadwal_kontrol' => $data['jadwal_kontrol'],
                'rujukan' => $data['rencana_rujuk'],
                'transportasi' => $data['transport'],
            ]
        );
        return $status;
    }

    public function addAssessmentPTM($data)
    {
        $skriningPtmId = $data['skrining_ptm_id'] ?? DB::table('simpus_skrining_ptm')
            ->where('idPelayanan', $data['idpelayanan'] ?? null)
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
