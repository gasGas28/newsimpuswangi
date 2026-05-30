<template>
  <div class="assessment-form">
    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-pulse"></i> Diagnosis Klinis</h4>
          <p>Condition FHIR - diagnosis utama, status klinis, dan diagnosis sekunder.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="diagnosis-form-grid">
          <div class="form-field diagnosis-main-field">
            <label class="form-label" for="diagnosis_utama">Diagnosis Utama</label>
            <input
              id="diagnosis_utama"
              v-model="form.diagnosis_utama"
              name="diagnosis_utama"
              type="text"
              class="form-control"
              list="diagnosis-utama-list"
              placeholder="Misal: Z13.6 - Skrining PTM / E11 - DM Tipe 2"
            />
            <datalist id="diagnosis-utama-list">
              <option
                v-for="item in daftarDiagnosisUtama"
                :key="item.value"
                :value="item.value"
              />
            </datalist>
            <span class="field-hint">Pilih dari saran atau tulis diagnosis dengan kode ICD-10.</span>
          </div>

          <div class="form-field">
            <label class="form-label" for="status_klinis">Status Klinis</label>
            <select
              id="status_klinis"
              v-model="form.status_klinis"
              name="status_klinis"
              class="form-select"
            >
              <option value="active">Aktif</option>
              <option value="recurrence">Berulang</option>
              <option value="remission">Remisi</option>
              <option value="resolved">Selesai / teratasi</option>
            </select>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="diagnosis_sekunder">Diagnosis Sekunder / Komorbid</label>
            <textarea
              id="diagnosis_sekunder"
              v-model="form.diagnosis_sekunder"
              name="diagnosis_sekunder"
              class="form-control"
              rows="3"
              placeholder="Diagnosis tambahan atau komorbid dengan kode ICD-10 bila ada"
            ></textarea>
          </div>
        </div>
      </div>
    </section>

    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-check"></i> Masalah Teridentifikasi</h4>
          <p>Konfirmasi masalah PTM dari hasil subjektif, objektif, dan pemeriksaan penunjang.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="diagnosis-grid">
          <label
            v-for="item in daftarAssessment"
            :key="item.key"
            class="diagnosis-option"
            :class="{ checked: form[item.key] }"
            :for="item.key"
          >
            <input
              :id="item.key"
              v-model="form[item.key]"
              class="form-check-input"
              type="checkbox"
            />
            <span>
              <strong>{{ item.label }}</strong>
              <small>{{ item.system }} - {{ item.code }}</small>
            </span>
          </label>
        </div>
      </div>
    </section>

    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-journal-check"></i> Ringkasan Assessment</h4>
          <p>Rangkuman otomatis dari diagnosis dan masalah yang teridentifikasi.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="summary-grid">
          <div class="summary-item">
            <span>Diagnosis Utama</span>
            <strong>{{ form.diagnosis_utama || diagnosisUtamaSaran }}</strong>
          </div>

          <div class="summary-item">
            <span>Masalah Terpilih</span>
            <strong>{{ assessmentTerpilih }}</strong>
          </div>

          <div class="summary-item">
            <span>Saran Kategori</span>
            <strong>{{ saranKategori }}</strong>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="ringkasan_klinis">Ringkasan Klinis</label>
            <textarea
              id="ringkasan_klinis"
              v-model="form.ringkasan_klinis"
              name="ringkasan_klinis"
              class="form-control"
              rows="3"
              placeholder="Ringkasan hasil skrining, diagnosis kerja, dan pertimbangan klinis"
            ></textarea>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="catatan_assessment">Catatan Assessment</label>
            <textarea
              id="catatan_assessment"
              v-model="form.catatan_assessment"
              name="catatan_assessment"
              class="form-control"
              rows="3"
              placeholder="Catatan tambahan assessment"
            ></textarea>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
  import { computed, watchEffect } from 'vue';

  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });

  const form = props.formData?.assessment || {};

  const daftarDiagnosisUtama = [
    { value: 'Z13.6 - Skrining penyakit kardiovaskular' },
    { value: 'E66 - Obesitas / berat badan lebih' },
    { value: 'I10 - Hipertensi esensial' },
    { value: 'R73.0 - Risiko prediabetes' },
    { value: 'E11 - Diabetes melitus tipe 2' },
    { value: 'E78.5 - Dislipidemia' },
  ];

  const daftarAssessment = [
    { key: 'obesitas', label: 'Obesitas / berat badan lebih', system: 'ICD-10', code: 'E66' },
    { key: 'hipertensi', label: 'Hipertensi / tekanan darah tinggi', system: 'ICD-10', code: 'I10' },
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

  daftarAssessment.forEach((item) => {
    if (form[item.key] === undefined) form[item.key] = false;
  });

  form.diagnosis_utama = form.diagnosis_utama || '';
  form.status_klinis = form.status_klinis || 'active';
  form.diagnosis_sekunder = form.diagnosis_sekunder || '';
  form.ringkasan_klinis = form.ringkasan_klinis || '';
  form.catatan_assessment = form.catatan_assessment || '';

  const selectedAssessments = computed(() => daftarAssessment.filter((item) => form[item.key]));

  const assessmentTerpilih = computed(() => {
    const selected = selectedAssessments.value.map((item) => item.label);
    return selected.length ? selected.join(', ') : 'Belum ada masalah teridentifikasi';
  });

  const diagnosisUtamaSaran = computed(() => {
    if (form.diabetes_melitus) return 'E11 - Diabetes melitus tipe 2';
    if (form.hipertensi) return 'I10 - Hipertensi esensial';
    if (form.dislipidemia) return 'E78.5 - Dislipidemia';
    if (form.risiko_diabetes) return 'R73.0 - Risiko prediabetes';
    if (form.obesitas) return 'E66 - Obesitas / berat badan lebih';
    return 'Z13.6 - Skrining penyakit kardiovaskular';
  });

  const saranKategori = computed(() => {
    if (form.diabetes_melitus || form.risiko_kardiovaskular) return 'Tinggi';
    if (form.hipertensi || form.dislipidemia || form.risiko_diabetes) return 'Sedang';
    if (form.obesitas || form.perilaku_berisiko) return 'Rendah';
    return 'Belum ada saran';
  });

  watchEffect(() => {
    form.assessment_terpilih = assessmentTerpilih.value;
    form.diagnosis_utama_saran = diagnosisUtamaSaran.value;
    form.saran_kategori = saranKategori.value;
  });
</script>

<style scoped>
  .assessment-form {
    display: grid;
    gap: 18px;
  }

  .assessment-panel {
    overflow: hidden;
    border: 1px solid #d9e5df;
    border-radius: 8px;
    background: #ffffff;
  }

  .panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    padding: 18px 20px;
    border-bottom: 1px solid #e5edf0;
    background: #f8fafc;
  }

  .panel-header h4 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    color: #0f3d2e;
    font-size: 1rem;
    font-weight: 750;
  }

  .panel-header p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 0.86rem;
  }

  .panel-body {
    padding: 20px;
  }

  .diagnosis-form-grid,
  .diagnosis-grid,
  .summary-grid {
    display: grid;
    gap: 16px;
    align-items: start;
  }

  .diagnosis-form-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }

  .diagnosis-main-field {
    grid-column: span 2;
  }

  .diagnosis-grid,
  .summary-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }

  .form-field,
  .diagnosis-option,
  .summary-item {
    min-width: 0;
    padding: 14px;
    border: 1px solid #edf2f7;
    border-radius: 8px;
    background: #ffffff;
  }

  .diagnosis-option {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    min-height: 86px;
    color: #334155;
    cursor: pointer;
  }

  .diagnosis-option.checked {
    border-color: #16a36f;
    background: #effaf5;
    color: #0f6b4c;
  }

  .diagnosis-option strong,
  .diagnosis-option small,
  .summary-item span,
  .summary-item strong {
    display: block;
  }

  .diagnosis-option strong,
  .summary-item strong {
    color: #0f172a;
    font-size: 0.9rem;
    line-height: 1.45;
  }

  .diagnosis-option small {
    margin-top: 6px;
    color: #64748b;
    font-size: 0.78rem;
    font-weight: 700;
  }

  .summary-item {
    min-height: 88px;
    background: #f8fafc;
  }

  .summary-item span {
    margin-bottom: 7px;
    color: #64748b;
    font-size: 0.76rem;
    font-weight: 750;
    text-transform: uppercase;
  }

  .form-check-input {
    margin-top: 3px;
  }

  .note-field {
    grid-column: 1 / -1;
  }

  .form-label {
    margin-bottom: 6px;
    color: #334155;
    font-size: 0.86rem;
    font-weight: 700;
  }

  .field-hint {
    display: block;
    margin-top: 6px;
    color: #64748b;
    font-size: 0.78rem;
    font-weight: 650;
  }

  .form-control,
  .form-select {
    width: 100%;
    min-height: 42px;
    border: 1px solid #cfd9e3;
    border-radius: 8px;
    color: #0f172a;
  }

  textarea.form-control {
    min-height: 92px;
    resize: vertical;
  }

  .form-control:focus,
  .form-select:focus,
  .form-check-input:focus {
    border-color: #16a36f;
    box-shadow: 0 0 0 0.2rem rgba(22, 163, 111, 0.14);
  }

  .form-check-input:checked {
    border-color: #16a36f;
    background-color: #16a36f;
  }

  @media (max-width: 992px) {
    .diagnosis-form-grid,
    .diagnosis-grid,
    .summary-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .diagnosis-main-field {
      grid-column: 1 / -1;
    }
  }

  @media (max-width: 576px) {
    .diagnosis-form-grid,
    .diagnosis-grid,
    .summary-grid {
      grid-template-columns: 1fr;
    }

    .panel-body {
      padding: 16px;
    }
  }
</style>
