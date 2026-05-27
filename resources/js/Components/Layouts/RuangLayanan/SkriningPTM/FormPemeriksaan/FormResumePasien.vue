<template>
  <div class="resume-form">
    <section class="resume-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-check"></i> Ringkasan Skrining PTM</h4>
          <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
        </div>
        <span class="status-pill" :class="{ complete: completedItems === summaryItems.length }">
          {{ completedItems }}/{{ summaryItems.length }} lengkap
        </span>
      </div>

      <div class="panel-body">
        <div class="summary-grid">
          <div class="summary-item" v-for="item in summaryItems" :key="item.label">
            <div class="summary-label">{{ item.label }}</div>
            <div class="summary-value">{{ valueOrDash(item.value) }}</div>
          </div>
        </div>
      </div>
    </section>

    <section class="resume-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-activity"></i> Ringkasan Temuan</h4>
          <p>Temuan otomatis dari hasil objektif, faktor risiko, dan assessment.</p>
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

    <section class="resume-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-send-check"></i> Composition FHIR</h4>
          <p>Composition.type - LOINC 74213-0 untuk dokumen laporan skrining PTM.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="composition-grid">
          <div class="form-field">
            <label class="form-label" for="doc_num">Nomor Dokumen</label>
            <input id="doc_num" class="form-control bg-light" type="text" v-model="form.doc_num" readonly />
          </div>

          <div class="form-field">
            <label class="form-label" for="comp_date">Tanggal Komposisi</label>
            <input id="comp_date" class="form-control" type="date" v-model="form.comp_date" />
          </div>

          <div class="form-field">
            <label class="form-label" for="comp_status">Status Dokumen</label>
            <select id="comp_status" class="form-select" v-model="form.comp_status">
              <option value="preliminary">Preliminary</option>
              <option value="final">Final</option>
              <option value="amended">Amended</option>
            </select>
          </div>

          <div class="form-field span-full">
            <label class="form-label" for="comp_title">Judul Composition</label>
            <input
              id="comp_title"
              class="form-control"
              type="text"
              v-model="form.comp_title"
              placeholder="Ringkasan Skrining PTM"
            />
          </div>

          <div class="form-field span-full">
            <label class="form-label" for="catatan_penutup">Catatan Penutup</label>
            <textarea
              id="catatan_penutup"
              class="form-control"
              rows="4"
              v-model="form.catatan_penutup"
              placeholder="Catatan tambahan dokter/perawat sebelum pengiriman"
            ></textarea>
          </div>
        </div>

        <div class="fhir-preview">
          <div class="preview-header">
            <span>Resource yang dirangkum</span>
            <code>Encounter, QuestionnaireResponse, Observation, Condition, Procedure, CarePlan, Composition</code>
          </div>
          <pre>{{ compositionPreview }}</pre>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
  import { computed } from 'vue';

  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
    DataTindakan: Array,
  });

  const subjektif = computed(() => props.formData?.subjektif || {});
  const objektif = computed(() => props.formData?.objektif || {});
  const assessment = computed(() => props.formData?.assessment || {});
  const planning = computed(() => props.formData?.planning || {});
  const pasien = computed(() => props.DataPasien || {});
  const form = props.formData?.resume || (props.formData.resume = {});

  form.doc_num = form.doc_num || generateDocumentNumber();
  form.comp_date = form.comp_date || today();
  form.comp_status = form.comp_status || 'preliminary';
  form.comp_title = form.comp_title || 'Ringkasan Skrining PTM';
  form.catatan_penutup = form.catatan_penutup || '';

  const summaryItems = computed(() => [
    { label: 'Nama Pasien', value: pasien.value.NAMA_LGKP || pasien.value.nama || pasien.value.nmPasien },
    { label: 'NIK', value: pasien.value.nik || pasien.value.noKTP },
    { label: 'Tanggal Skrining', value: subjektif.value.tanggal_skrining || pasien.value.tglKunjungan },
    { label: 'Fasyankes', value: subjektif.value.fasyankes || pasien.value.nama_unit },
    { label: 'Pemeriksa', value: subjektif.value.dokter || pasien.value.dokter },
    { label: 'IMT', value: formatUnit(objektif.value.imt, 'kg/m2') },
    { label: 'Tekanan Darah', value: formatBloodPressure(objektif.value.td_s, objektif.value.td_d) },
    { label: 'Gula Darah Sewaktu', value: formatUnit(objektif.value.gd_sewaktu || objektif.value.gds, 'mg/dL') },
    { label: 'Kolesterol Total', value: formatUnit(objektif.value.koltot, 'mg/dL') },
    { label: 'Kategori Risiko PTM', value: labelize(assessment.value.kategori_risiko_ptm || objektif.value.kat_risiko) },
    { label: 'Status Kasus', value: labelize(assessment.value.status_kasus) },
    { label: 'Rencana Rujuk', value: labelize(assessment.value.rencana_rujuk) },
  ]);

  const completedItems = computed(() => summaryItems.value.filter((item) => hasValue(item.value)).length);

  const findings = computed(() => {
    const items = [];

    addFinding(items, objektif.value.td_interp?.includes('Hipertensi'), {
      icon: 'bi-exclamation-triangle-fill warning',
      title: 'Tekanan darah perlu perhatian',
      description: objektif.value.td_interp,
    });

    addFinding(items, ['Gemuk', 'Obesitas'].some((label) => objektif.value.imt_interp?.includes(label)), {
      icon: 'bi-exclamation-circle-fill warning',
      title: 'Status IMT meningkat',
      description: `IMT ${objektif.value.imt || '-'} - ${objektif.value.imt_interp}`,
    });

    const dmInterpretation = objektif.value.gd_sewaktu_i || objektif.value.gds_i || objektif.value.gd_puasa_i || objektif.value.hba1c_i;
    addFinding(items, dmInterpretation?.includes('DM') || assessment.value.diabetes_melitus, {
      icon: 'bi-droplet-fill danger',
      title: 'Indikasi diabetes melitus',
      description: dmInterpretation || 'Assessment diabetes melitus terpilih.',
    });

    addFinding(items, objektif.value.koltot_i === 'Tinggi' || assessment.value.dislipidemia, {
      icon: 'bi-capsule warning',
      title: 'Profil lipid abnormal',
      description: objektif.value.koltot_i || 'Assessment dislipidemia terpilih.',
    });

    addFinding(items, subjektif.value.status_merokok === 'current', {
      icon: 'bi-exclamation-diamond-fill warning',
      title: 'Faktor risiko merokok',
      description: `${subjektif.value.btg_rokok || 0} batang/hari selama ${subjektif.value.lama_rokok || 0} tahun.`,
    });

    addFinding(items, assessment.value.rencana_rujuk && assessment.value.rencana_rujuk !== 'tidak', {
      icon: 'bi-hospital-fill info',
      title: 'Perlu tindak lanjut rujukan',
      description: labelize(assessment.value.rencana_rujuk),
    });

    if (items.length === 0) {
      items.push({
        icon: 'bi-check-circle-fill success',
        title: 'Tidak ada temuan kritis',
        description: 'Belum ada temuan prioritas berdasarkan data yang sudah diisi.',
      });
    }

    return items;
  });

  const compositionPreview = computed(() =>
    JSON.stringify(
      {
        resourceType: 'Composition',
        status: form.comp_status,
        type: {
          coding: [
            {
              system: 'http://loinc.org',
              code: '74213-0',
              display: 'Skrining PTM',
            },
          ],
        },
        title: form.comp_title,
        date: form.comp_date,
        subject: {
          display: valueOrDash(summaryItems.value[0]?.value),
          identifier: valueOrDash(summaryItems.value[1]?.value),
        },
        section: [
          {
            title: 'Ringkasan Skrining PTM',
            text: { status: 'generated', div: `${completedItems.value}/${summaryItems.value.length} data utama lengkap` },
          },
          {
            title: 'Ringkasan Temuan',
            text: { status: 'generated', div: findings.value.map((item) => item.title).join('; ') },
          },
        ],
      },
      null,
      2,
    ),
  );

  function addFinding(items, condition, finding) {
    if (condition) items.push(finding);
  }

  function formatBloodPressure(systolic, diastolic) {
    if (!hasValue(systolic) || !hasValue(diastolic)) return '';
    return `${systolic}/${diastolic} mmHg`;
  }

  function formatUnit(value, unit) {
    if (!hasValue(value)) return '';
    return `${value} ${unit}`;
  }

  function hasValue(value) {
    return value !== undefined && value !== null && value !== '';
  }

  function valueOrDash(value) {
    return hasValue(value) ? value : '-';
  }

  function labelize(value) {
    if (!hasValue(value)) return '';
    return String(value)
      .replace(/_/g, ' ')
      .replace(/\b\w/g, (char) => char.toUpperCase());
  }

  function today() {
    return new Date().toISOString().split('T')[0];
  }

  function generateDocumentNumber() {
    return `PTM-${Date.now().toString(36).toUpperCase()}`;
  }
</script>

<style scoped>
  .resume-form {
    display: grid;
    gap: 18px;
  }

  .resume-panel {
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

  .status-pill {
    display: inline-flex;
    align-items: center;
    min-height: 30px;
    padding: 5px 10px;
    border: 1px solid #facc15;
    border-radius: 999px;
    background: #fefce8;
    color: #854d0e;
    font-size: 0.78rem;
    font-weight: 750;
  }

  .status-pill.complete {
    border-color: #86efac;
    background: #f0fdf4;
    color: #166534;
  }

  .summary-grid,
  .composition-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }

  .summary-item,
  .form-field {
    min-width: 0;
    padding: 14px;
    border: 1px solid #edf2f7;
    border-radius: 8px;
    background: #ffffff;
  }

  .summary-label {
    margin-bottom: 6px;
    color: #64748b;
    font-size: 0.76rem;
    font-weight: 750;
    text-transform: uppercase;
  }

  .summary-value {
    color: #0f172a;
    font-size: 0.94rem;
    font-weight: 750;
    overflow-wrap: anywhere;
  }

  .finding-list {
    display: grid;
    gap: 12px;
  }

  .finding-item {
    display: flex;
    gap: 12px;
    padding: 14px;
    border: 1px solid #edf2f7;
    border-radius: 8px;
    background: #ffffff;
  }

  .finding-item i {
    margin-top: 2px;
    font-size: 1.1rem;
  }

  .finding-item strong,
  .finding-item span {
    display: block;
  }

  .finding-item strong {
    color: #0f172a;
    font-size: 0.92rem;
  }

  .finding-item span {
    margin-top: 3px;
    color: #64748b;
    font-size: 0.84rem;
  }

  .success {
    color: #16a34a;
  }

  .warning {
    color: #d97706;
  }

  .danger {
    color: #dc2626;
  }

  .info {
    color: #2563eb;
  }

  .span-full {
    grid-column: 1 / -1;
  }

  .form-label {
    margin-bottom: 6px;
    color: #334155;
    font-size: 0.86rem;
    font-weight: 700;
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
    resize: vertical;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #16a36f;
    box-shadow: 0 0 0 0.2rem rgba(22, 163, 111, 0.14);
  }

  .fhir-preview {
    margin-top: 18px;
    overflow: hidden;
    border: 1px solid #dbeafe;
    border-radius: 8px;
    background: #f8fafc;
  }

  .preview-header {
    display: grid;
    gap: 6px;
    padding: 12px 14px;
    border-bottom: 1px solid #dbeafe;
    color: #1e3a8a;
    font-size: 0.82rem;
    font-weight: 750;
  }

  .preview-header code {
    color: #334155;
    font-size: 0.76rem;
    white-space: normal;
  }

  pre {
    max-height: 300px;
    margin: 0;
    padding: 14px;
    color: #0f172a;
    font-size: 0.78rem;
    white-space: pre-wrap;
  }

  @media (max-width: 992px) {
    .summary-grid,
    .composition-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 576px) {
    .summary-grid,
    .composition-grid {
      grid-template-columns: 1fr;
    }

    .panel-body {
      padding: 16px;
    }
  }
</style>
