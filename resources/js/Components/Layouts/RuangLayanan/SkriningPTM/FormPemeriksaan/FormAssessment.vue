<template>
  <div class="assessment-form">
    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-activity"></i> Ringkasan Temuan</h4>
          <p>
            Temuan otomatis dari subjektif, objektif, dan status pasien sebagai bahan assessment.
          </p>
        </div>
      </div>

      <div class="panel-body">
        <div class="finding-list">
          <div class="finding-item" v-for="finding in findings" :key="finding.title">
            <i class="bi" :class="finding.icon"></i>
            <div>
              <strong>{{ finding.title }}</strong>
              <span>{{ finding.description }}</span>
            </div>
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
        <span class="status-pill" :class="{ complete: suggestedAssessmentKeys.length > 0 }">
          {{ suggestedAssessmentKeys.length }} saran
        </span>
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
              <small v-if="suggestedAssessmentKeys.includes(item.key)" class="suggestion-hint">
                Disarankan dari temuan otomatis
              </small>
            </span>
          </label>
        </div>
      </div>
    </section>

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
              <option v-for="item in daftarDiagnosisUtama" :key="item.value" :value="item.value" />
            </datalist>
            <span class="field-hint"
              >Pilih dari saran atau tulis diagnosis dengan kode ICD-10.</span
            >
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

    <div class="form-actions">
      <div class="save-status" :class="{ success: saveStatus === 'ready' }">
        {{ saveMessage }}
      </div>
      <button type="button" class="save-button" :disabled="isSaving" @click="saveAssessment">
        <i class="bi" :class="isSaving ? 'bi-arrow-repeat' : 'bi-save'"></i>
        <span>{{ isSaving ? 'Menyimpan...' : 'Simpan Assessment' }}</span>
      </button>
    </div>
  </div>
</template>

<script setup>
  import { computed, ref, watchEffect } from 'vue';

  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });

  const emit = defineEmits(['save-assessment']);
  const isSaving = ref(false);
  const saveStatus = ref('idle');
  const form = props.formData?.assessment || {};
  const autoSuggestedKeys = new Set();
  const subjektif = computed(() => props.formData?.subjektif || {});
  const objektif = computed(() => props.formData?.objektif || {});
  const statusPasien = computed(() => props.formData?.status_pasien || {});

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

  daftarAssessment.forEach((item) => {
    if (form[item.key] === undefined) form[item.key] = false;
  });

  form.diagnosis_utama = form.diagnosis_utama || '';
  form.status_klinis = form.status_klinis || 'active';
  form.diagnosis_sekunder = form.diagnosis_sekunder || '';
  form.ringkasan_klinis = form.ringkasan_klinis || '';
  form.catatan_assessment = form.catatan_assessment || '';

  const lipidDescription = computed(() => {
    const values = [
      objektif.value.koltot_i ? `Kolesterol total ${objektif.value.koltot_i}` : '',
      objektif.value.ldl_i ? `LDL ${objektif.value.ldl_i}` : '',
      objektif.value.hdl_i ? `HDL ${objektif.value.hdl_i}` : '',
      objektif.value.tg_i ? `Trigliserida ${objektif.value.tg_i}` : '',
    ].filter(Boolean);

    return values.length ? values.join(', ') : 'Assessment dislipidemia terpilih.';
  });

  const findings = computed(() => {
    const items = [];

    addFinding(items, objektif.value.td_interp?.includes('Hipertensi'), {
      icon: 'bi-exclamation-triangle-fill warning',
      title: 'Tekanan darah perlu perhatian',
      description: objektif.value.td_interp,
      suggests: ['hipertensi'],
    });

    addFinding(
      items,
      ['Gemuk', 'Obesitas'].some((label) => objektif.value.imt_interp?.includes(label)),
      {
        icon: 'bi-exclamation-circle-fill warning',
        title: 'Status IMT meningkat',
        description: `IMT ${objektif.value.imt || '-'} - ${objektif.value.imt_interp}`,
        suggests: ['obesitas'],
      }
    );

    addFinding(
      items,
      objektif.value.dm_interp === 'Diabetes' ||
        objektif.value.gd_sewaktu >= 200 ||
        objektif.value.gd_puasa >= 126 ||
        objektif.value.hba1c >= 6.5,
      {
        icon: 'bi-droplet-fill danger',
        title: 'Indikasi diabetes melitus',
        description: objektif.value.dm_interp || 'Nilai gula darah memenuhi kriteria diabetes.',
        suggests: ['diabetes_melitus'],
      }
    );

    addFinding(
      items,
      objektif.value.dm_interp === 'Prediabetes' ||
        between(objektif.value.gd_puasa, 100, 125) ||
        between(objektif.value.hba1c, 5.7, 6.4),
      {
        icon: 'bi-droplet-half warning',
        title: 'Risiko prediabetes',
        description: objektif.value.dm_interp || 'Nilai gula darah berada pada rentang risiko.',
        suggests: ['risiko_diabetes'],
      }
    );

    addFinding(
      items,
      objektif.value.koltot_i === 'Tinggi' ||
        objektif.value.ldl_i === 'Tinggi' ||
        objektif.value.tg_i === 'Tinggi' ||
        objektif.value.hdl_i === 'Rendah',
      {
        icon: 'bi-capsule warning',
        title: 'Profil lipid abnormal',
        description: lipidDescription.value,
        suggests: ['dislipidemia', 'risiko_kardiovaskular'],
      }
    );

    addFinding(
      items,
      subjektif.value.kategori_faktor_risiko === 'Risiko Tinggi' ||
        subjektif.value.status_merokok === 'current',
      {
        icon: 'bi-exclamation-diamond-fill warning',
        title: 'Perilaku berisiko PTM',
        description:
          subjektif.value.kategori_faktor_risiko ||
          `${subjektif.value.btg_rokok || 0} batang/hari selama ${subjektif.value.lama_rokok || 0} tahun.`,
        suggests: ['perilaku_berisiko'],
      }
    );

    addFinding(
      items,
      statusPasien.value.rencana_rujuk && statusPasien.value.rencana_rujuk !== 'tidak',
      {
        icon: 'bi-hospital-fill info',
        title: 'Perlu tindak lanjut rujukan',
        description: labelize(statusPasien.value.rencana_rujuk),
        suggests: ['risiko_kardiovaskular'],
      }
    );

    if (items.length === 0) {
      items.push({
        icon: 'bi-check-circle-fill success',
        title: 'Tidak ada temuan prioritas',
        description:
          'Belum ada temuan otomatis yang perlu dikonfirmasi sebagai masalah assessment.',
        suggests: [],
      });
    }

    return items;
  });

  const suggestedAssessmentKeys = computed(() => [
    ...new Set(findings.value.flatMap((finding) => finding.suggests || [])),
  ]);

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

  const saveMessage = computed(() => {
    if (saveStatus.value === 'ready') {
      return 'Data assessment siap disimpan.';
    }

    return 'Simpan setelah diagnosis dan ringkasan assessment selesai diisi.';
  });

  watchEffect(() => {
    suggestedAssessmentKeys.value.forEach((key) => {
      if (form[key] === false && !autoSuggestedKeys.has(key)) {
        form[key] = true;
        autoSuggestedKeys.add(key);
      }
    });

    form.ringkasan_temuan = findings.value.map((finding) => ({
      title: finding.title,
      description: finding.description,
      suggests: finding.suggests || [],
    }));
    form.assessment_terpilih = assessmentTerpilih.value;
    form.diagnosis_utama_saran = diagnosisUtamaSaran.value;
    form.saran_kategori = saranKategori.value;
  });

  const saveAssessment = () => {
    isSaving.value = true;

    emit('save-assessment', {
      DataPasien: props.DataPasien,
      assessment: props.formData?.assessment || {},
    });

    window.setTimeout(() => {
      isSaving.value = false;
      saveStatus.value = 'ready';
    }, 400);
  };

  function addFinding(items, condition, finding) {
    if (condition) items.push(finding);
  }

  function between(value, min, max) {
    const numericValue = Number(value);
    return Number.isFinite(numericValue) && numericValue >= min && numericValue <= max;
  }

  function hasValue(value) {
    return value !== undefined && value !== null && value !== '';
  }

  function labelize(value) {
    if (!hasValue(value)) return '';
    return String(value)
      .replace(/_/g, ' ')
      .replace(/\b\w/g, (char) => char.toUpperCase());
  }
</script>

<style scoped src="./FormPemeriksaan.css"></style>
