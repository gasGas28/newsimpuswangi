<template>
  <div class="p-3">
    <h5 class="mb-4 fw-semibold text-success">Subjektif - Riwayat dan Faktor Risiko PTM</h5>

    <div class="row g-3">
      <div class="col-md-12">
        <div class="bg-light rounded-3 p-3">
          <h6 class="fw-semibold">Data Kunjungan</h6>
          <div class="row g-3 mt-2">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Tanggal Skrining</label>
              <input v-model="form.tanggal_kunjungan" type="date" class="form-control" />
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Pemeriksa</label>
              <input
                v-model="form.dokter_pemeriksa"
                type="text"
                class="form-control"
                placeholder="Nama dokter/perawat/bidan"
              />
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Jenis Kunjungan</label>
              <select v-model="form.jenis_kunjungan" class="form-select">
                <option value="baru">Baru</option>
                <option value="ulang">Ulang</option>
              </select>
            </div>

            <div class="col-md-12">
              <label class="form-label fw-semibold">Keluhan Utama / Anamnesis</label>
              <textarea
                v-model="form.keluhan_utama"
                class="form-control"
                rows="3"
                placeholder="Keluhan, riwayat singkat, dan alasan skrining"
              ></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="bg-light rounded-3 p-3">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="fw-semibold mb-0">Faktor Risiko PTM - Questionnaire Q0013</h6>
            <span class="badge bg-white text-success border">QuestionnaireResponse</span>
          </div>

          <div class="row g-3 mt-2">
            <div class="col-md-6" v-for="item in faktorRisiko" :key="item.key">
              <label class="form-label fw-semibold">{{ item.label }}</label>
              <select v-model="form[item.key]" class="form-select">
                <option v-for="option in item.options" :key="option.value" :value="option.value">
                  {{ option.label }}
                </option>
              </select>
              <div class="form-text">Q0013 linkId {{ item.linkId }}</div>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Status Merokok</label>
              <select v-model="form.status_merokok" class="form-select">
                <option value="tidak_merokok">Tidak merokok</option>
                <option value="merokok">Merokok</option>
              </select>
              <div class="form-text">Observation LOINC 72166-2, Q0013 linkId 1.4</div>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Rata-rata Batang Rokok per Hari</label>
              <input
                v-model.number="form.jumlah_batang_rokok_per_hari"
                type="number"
                min="0"
                class="form-control"
              />
              <div class="form-text">Q0013 linkId 1.2</div>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Lama Merokok</label>
              <div class="input-group">
                <input v-model.number="form.lama_merokok_tahun" type="number" min="0" class="form-control" />
                <span class="input-group-text">tahun</span>
              </div>
              <div class="form-text">Q0013 linkId 1.3</div>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Riwayat Hipertensi</label>
              <select v-model="form.riwayat_hipertensi" class="form-select">
                <option value="tidak">Tidak</option>
                <option value="ya_aktif">Ya, sedang/aktif</option>
                <option value="ya_dahulu">Ya, dahulu</option>
                <option value="tidak_tahu">Tidak tahu</option>
              </select>
              <div class="form-text">Condition riwayat pribadi</div>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Riwayat Diabetes Melitus</label>
              <select v-model="form.riwayat_diabetes" class="form-select">
                <option value="tidak">Tidak</option>
                <option value="ya_aktif">Ya, sedang/aktif</option>
                <option value="ya_dahulu">Ya, dahulu</option>
                <option value="tidak_tahu">Tidak tahu</option>
              </select>
              <div class="form-text">Condition riwayat pribadi</div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Riwayat PTM Pribadi Lain</label>
              <textarea
                v-model="form.riwayat_pribadi_lain"
                class="form-control"
                rows="3"
                placeholder="Dislipidemia, stroke, penyakit jantung, kanker, ginjal kronis, dll."
              ></textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Riwayat PTM Keluarga</label>
              <textarea
                v-model="form.riwayat_keluarga"
                class="form-control"
                rows="3"
                placeholder="Hipertensi, diabetes, stroke, penyakit jantung, kanker, dll."
              ></textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Obat yang Sedang Dikonsumsi</label>
              <textarea
                v-model="form.riwayat_obat"
                class="form-control"
                rows="2"
                placeholder="Obat antihipertensi, antidiabetes, statin, atau obat lain"
              ></textarea>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Kesiapan Berubah</label>
              <select v-model="form.kesiapan_berubah" class="form-select">
                <option value="tidak_siap">Tidak siap</option>
                <option value="ragu">Ragu</option>
                <option value="siap">Siap</option>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Dukungan Keluarga</label>
              <select v-model="form.dukung_keluarga" class="form-select">
                <option value="kurang">Kurang</option>
                <option value="cukup">Cukup</option>
                <option value="baik">Baik</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="bg-white rounded-3 p-3 border">
          <h6 class="fw-semibold">Ringkasan Subjektif</h6>
          <div class="row g-3 mt-2">
            <div class="col-md-3">
              <label class="form-label fw-semibold">Jumlah Faktor Risiko</label>
              <input :value="form.jumlah_faktor_risiko" type="text" class="form-control bg-light" readonly />
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Kategori Perilaku</label>
              <input :value="form.kategori_perilaku" type="text" class="form-control bg-light" readonly />
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Faktor Positif</label>
              <input :value="form.faktor_positif" type="text" class="form-control bg-light" readonly />
            </div>

            <div class="col-md-12">
              <label class="form-label fw-semibold">Catatan Subjektif</label>
              <textarea
                v-model="form.catatan_subjektif"
                class="form-control"
                rows="3"
                placeholder="Catatan tambahan untuk anamnesis dan konseling"
              ></textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });

  const form = props.formData?.subjektif || {};

  const yaTidakHarian = [
    { value: 'tidak', label: 'Tidak' },
    { value: 'ya_tidak_setiap_hari', label: 'Ya, tidak setiap hari' },
    { value: 'ya_setiap_hari', label: 'Ya, setiap hari' },
  ];

  const faktorRisiko = [
    {
      key: 'pernah_merokok',
      label: 'Apakah peserta pernah merokok?',
      linkId: '1.1',
      options: [
        { value: 'tidak', label: 'Tidak' },
        { value: 'ya', label: 'Ya' },
      ],
    },
    {
      key: 'paparan_asap_rokok',
      label: 'Terpapar asap rokok orang lain dalam 1 bulan terakhir?',
      linkId: '1.5',
      options: yaTidakHarian,
    },
    {
      key: 'gula_berlebih',
      label: 'Konsumsi gula > 4 sendok makan sehari?',
      linkId: '1.6',
      options: yaTidakHarian,
    },
    {
      key: 'garam_berlebih',
      label: 'Konsumsi garam > 1 sendok teh sehari?',
      linkId: '1.7',
      options: yaTidakHarian,
    },
    {
      key: 'minyak_berlebih',
      label: 'Konsumsi minyak > 5 sendok makan sehari?',
      linkId: '1.8',
      options: yaTidakHarian,
    },
    {
      key: 'kurang_sayur_buah',
      label: 'Makan sayur/buah kurang dari 5 porsi sehari?',
      linkId: '1.9',
      options: yaTidakHarian,
    },
    {
      key: 'aktivitas_fisik_kurang',
      label: 'Aktivitas fisik kurang dari 30 menit/hari atau 150 menit/minggu?',
      linkId: '1.10',
      options: yaTidakHarian,
    },
    {
      key: 'alkohol_1_bulan',
      label: 'Konsumsi alkohol dalam 1 bulan terakhir?',
      linkId: '1.11',
      options: [
        { value: 'tidak', label: 'Tidak' },
        { value: 'ya', label: 'Ya' },
      ],
    },
  ];
</script>
