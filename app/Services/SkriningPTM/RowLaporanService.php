<?php

namespace App\Services\SkriningPTM;

/**
 * Representasi 1 baris data pada template "Klaster_3_PTM.xlsx"
 * (sheet: "Gangguan Kardiovaskular, Metabo").
 *
 * Nama properti sengaja dibuat 1:1 dengan konsep pada template asli,
 * supaya gampang ditelusuri balik ke kolom Excel-nya lewat
 * PtmKlaster3ExportService::COLUMN_MAP.
 *
 * DTO ini TIDAK tahu-menahu soal Eloquent / relasi model.
 * Yang menyusun nilai-nilainya adalah PtmKlaster3DataBuilder.
 */
class RowLaporanService
{
    public function __construct(
        // A-B Identitas individu
        public readonly string $nik,
        public readonly string $nama,

        // C-F Riwayat penyakit
        public readonly ?string $riwayatPenyakitKeluarga = null,
        public readonly ?string $riwayatPenyakitDiri1 = null,
        public readonly ?string $riwayatPenyakitDiri2 = null,
        public readonly ?string $riwayatPenyakitDiri3 = null,

        // G-N Aktivitas merokok
        public readonly ?string $merokokStatus = null,           // Ya / Tidak
        public readonly ?int $merokokBatangPerHari = null,
        public readonly ?int $merokokLamaTahun = null,
        public readonly ?string $terpaparAsapRokok = null,       // Ya setiap hari / Tidak setiap hari / Tidak
        public readonly ?string $pumaNapasPendek = null,         // Ya / Tidak
        public readonly ?string $pumaDahak = null,
        public readonly ?string $pumaBatuk = null,
        public readonly ?string $pumaSpirometri = null,

        // O-T Faktor risiko konsumsi & gaya hidup
        public readonly ?string $konsumsiGulaBerlebih = null,     // Ya setiap hari / Tidak setiap hari / Tidak
        public readonly ?string $konsumsiGaramBerlebih = null,
        public readonly ?string $konsumsiMinyakBerlebih = null,
        public readonly ?string $kurangSayurBuah = null,
        public readonly ?string $kurangAktivitasFisik = null,
        public readonly ?string $konsumsiAlkohol = null,

        // U-Y Pemeriksaan dasar
        public readonly ?float $tinggiBadanCm = null,
        public readonly ?float $beratBadanKg = null,
        public readonly ?float $lingkarPerutCm = null,
        public readonly ?int $sistolik = null,
        public readonly ?int $diastolik = null,

        // Z-AC Gula darah
        public readonly ?int $gds = null,
        public readonly ?int $gdp = null,
        public readonly ?int $gd2JamPp = null,
        public readonly ?float $hba1c = null,

        // AD-AG Profil lipid
        public readonly ?int $kolesterolTotal = null,
        public readonly ?int $hdl = null,
        public readonly ?int $ldl = null,
        public readonly ?int $trigliserida = null,

        // AH-AK Leher rahim & payudara (khusus wanita)
        public readonly ?string $iva = null,                     // IVA Negatif/Positif/Curiga Kanker
        public readonly ?string $hpvDna = null,
        public readonly ?string $sadanis = null,                 // Curiga Kanker/Ditemukan Benjolan/Normal
        public readonly ?string $usgPayudara = null,              // Non Simple Cyst/Normal/Simple Cyst

        // AL-AQ Diagnosis & tatalaksana (maks 3 masalah)
        public readonly ?string $diagnosis1 = null,
        public readonly ?string $terapi1 = null,
        public readonly ?string $diagnosis2 = null,
        public readonly ?string $terapi2 = null,
        public readonly ?string $diagnosis3 = null,
        public readonly ?string $terapi3 = null,

        // AR-AU Edukasi (Ya/Tidak)
        public readonly ?string $edukasiBerhentiMerokok = null,
        public readonly ?string $edukasiAsapRokok = null,
        public readonly ?string $edukasiDiet = null,
        public readonly ?string $edukasiAktivitasFisik = null,

        // AV-AW EKG & rujukan
        public readonly ?string $ekg = null,                      // Normal / Abnormal
        public readonly ?string $rujuk = null,                    // Puskesmas / Rumah Sakit / Tidak
    ) {
    }

    /**
     * Array asosiatif dengan key yang sama persis dengan value pada
     * PtmKlaster3ExportService::COLUMN_MAP.
     */
    public function toArray(): array
    {
        return [
            'nik' => $this->nik,
            'nama' => $this->nama,
            'riwayat_penyakit_keluarga' => $this->riwayatPenyakitKeluarga,
            'riwayat_penyakit_diri_1' => $this->riwayatPenyakitDiri1,
            'riwayat_penyakit_diri_2' => $this->riwayatPenyakitDiri2,
            'riwayat_penyakit_diri_3' => $this->riwayatPenyakitDiri3,
            'merokok_status' => $this->merokokStatus,
            'merokok_batang_per_hari' => $this->merokokBatangPerHari,
            'merokok_lama_tahun' => $this->merokokLamaTahun,
            'terpapar_asap_rokok' => $this->terpaparAsapRokok,
            'puma_napas_pendek' => $this->pumaNapasPendek,
            'puma_dahak' => $this->pumaDahak,
            'puma_batuk' => $this->pumaBatuk,
            'puma_spirometri' => $this->pumaSpirometri,
            'konsumsi_gula_berlebih' => $this->konsumsiGulaBerlebih,
            'konsumsi_garam_berlebih' => $this->konsumsiGaramBerlebih,
            'konsumsi_minyak_berlebih' => $this->konsumsiMinyakBerlebih,
            'kurang_sayur_buah' => $this->kurangSayurBuah,
            'kurang_aktivitas_fisik' => $this->kurangAktivitasFisik,
            'konsumsi_alkohol' => $this->konsumsiAlkohol,
            'tinggi_badan' => $this->tinggiBadanCm,
            'berat_badan' => $this->beratBadanKg,
            'lingkar_perut' => $this->lingkarPerutCm,
            'sistolik' => $this->sistolik,
            'diastolik' => $this->diastolik,
            'gds' => $this->gds,
            'gdp' => $this->gdp,
            'gd2jampp' => $this->gd2JamPp,
            'hba1c' => $this->hba1c,
            'kolesterol_total' => $this->kolesterolTotal,
            'hdl' => $this->hdl,
            'ldl' => $this->ldl,
            'trigliserida' => $this->trigliserida,
            'iva' => $this->iva,
            'hpv_dna' => $this->hpvDna,
            'sadanis' => $this->sadanis,
            'usg_payudara' => $this->usgPayudara,
            'diagnosis_1' => $this->diagnosis1,
            'terapi_1' => $this->terapi1,
            'diagnosis_2' => $this->diagnosis2,
            'terapi_2' => $this->terapi2,
            'diagnosis_3' => $this->diagnosis3,
            'terapi_3' => $this->terapi3,
            'edukasi_berhenti_merokok' => $this->edukasiBerhentiMerokok,
            'edukasi_asap_rokok' => $this->edukasiAsapRokok,
            'edukasi_diet' => $this->edukasiDiet,
            'edukasi_aktivitas_fisik' => $this->edukasiAktivitasFisik,
            'ekg' => $this->ekg,
            'rujuk' => $this->rujuk,
        ];
    }
}
