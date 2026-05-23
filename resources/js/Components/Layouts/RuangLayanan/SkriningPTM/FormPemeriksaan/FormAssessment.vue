<template>
  <div class="p-3">
    <h5 class="mb-4 fw-semibold text-success">
      Assessment - Diagnosis dan Kesimpulan Skrining PTM
    </h5>

    <div class="row g-3">
      <div class="col-md-12">
        <div class="bg-light rounded-3 p-3">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="fw-semibold mb-0">Masalah / Diagnosis Skrining</h6>
            <span class="badge bg-white text-success border">Condition</span>
          </div>

          <div class="row g-3 mt-2">
            <div class="col-md-4" v-for="item in daftarAssessment" :key="item.key">
              <div class="border rounded-3 bg-white p-3 h-100">
                <div class="form-check">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    :id="item.key"
                    v-model="form[item.key]"
                  />
                  <label class="form-check-label fw-semibold" :for="item.key">
                    {{ item.label }}
                  </label>
                </div>
                <div class="form-text mt-2">{{ item.system }} - {{ item.code }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="bg-light rounded-3 p-3">
          <h6 class="fw-semibold">Klasifikasi dan Tindak Lanjut</h6>
          <div class="row g-3 mt-2">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Kategori Risiko PTM</label>
              <select v-model="form.kategori_risiko_ptm" class="form-select">
                <option value="">Pilih kategori</option>
                <option value="rendah">Rendah</option>
                <option value="sedang">Sedang</option>
                <option value="tinggi">Tinggi</option>
                <option value="sangat_tinggi">Sangat tinggi / perlu evaluasi dokter</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Status Kasus</label>
              <select v-model="form.status_kasus" class="form-select">
                <option value="skrining_normal">Skrining dalam batas normal</option>
                <option value="suspek">Suspek / perlu konfirmasi</option>
                <option value="terdiagnosis">Sudah terdiagnosis</option>
                <option value="kontrol">Kontrol penyakit kronis</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Rujukan / Konsultasi</label>
              <select v-model="form.rencana_rujuk" class="form-select">
                <option value="tidak">Tidak</option>
                <option value="internal">Konsultasi internal puskesmas</option>
                <option value="fkrtl">Rujuk FKRTL</option>
                <option value="igd">Rujuk segera / IGD</option>
              </select>
            </div>

            <div class="col-md-12">
              <label class="form-label fw-semibold">Ringkasan Klinis</label>
              <textarea
                v-model="form.ringkasan_klinis"
                class="form-control"
                rows="3"
                placeholder="Ringkasan hasil skrining, diagnosis kerja, dan pertimbangan klinis"
              ></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="bg-white rounded-3 p-3 border">
          <h6 class="fw-semibold">Ringkasan Otomatis</h6>
          <div class="row g-3 mt-2">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Assessment Terpilih</label>
              <input
                :value="form.assessment_terpilih"
                type="text"
                class="form-control bg-light"
                readonly
              />
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Saran Kategori</label>
              <input
                :value="form.saran_kategori"
                type="text"
                class="form-control bg-light"
                readonly
              />
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Saran Tindak Lanjut</label>
              <input
                :value="form.saran_tindak_lanjut"
                type="text"
                class="form-control bg-light"
                readonly
              />
            </div>

            <div class="col-md-12">
              <label class="form-label fw-semibold">Catatan Assessment</label>
              <textarea
                v-model="form.catatan_assessment"
                class="form-control"
                rows="3"
                placeholder="Catatan tambahan assessment"
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

  const form = props.formData?.assessment || {};

  const daftarAssessment = [
    { key: 'obesitas', label: 'Obesitas / berat badan lebih', system: 'ICD-10', code: 'E66' },
    {
      key: 'hipertensi',
      label: 'Hipertensi / tekanan darah tinggi',
      system: 'ICD-10',
      code: 'I10',
    },
    { key: 'risiko_diabetes', label: 'Risiko prediabetes', system: 'ICD-10', code: 'R73.0' },
    { key: 'diabetes_melitus', label: 'Diabetes melitus', system: 'ICD-10', code: 'E11' },
    { key: 'dislipidemia', label: 'Dislipidemia', system: 'ICD-10', code: 'E78.5' },
    {
      key: 'risiko_kardiovaskular',
      label: 'Risiko penyakit kardiovaskular',
      system: 'SNOMED CT',
      code: '395112001',
    },
    {
      key: 'perilaku_berisiko',
      label: 'Perilaku berisiko PTM',
      system: 'SNOMED CT',
      code: '160573003',
    },
  ];
</script>
