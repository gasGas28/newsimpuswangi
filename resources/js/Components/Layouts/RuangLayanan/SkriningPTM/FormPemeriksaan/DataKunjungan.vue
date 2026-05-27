<template>
  <div class="subjektif-form">
      <section class="subjektif-panel visit-panel">
        <div class="panel-header">
          <div>
            <h4><i class="bi bi-clipboard2-pulse"></i> Data Kunjungan</h4>
            <p>Encounter FHIR - periode, fasyankes, dan alasan kunjungan.</p>
          </div>
        </div>

        <div class="panel-body">
          <div class="visit-grid">
            <div class="form-field">
              <label class="form-label" for="tanggal_skrining">Tanggal Skrining</label>
              <input
                type="date"
                name="tanggal_skrining"
                id="tanggal_skrining"
                class="form-control"
                v-model="form.tanggal_skrining"
              />
            </div>

            <div class="form-field">
              <label class="form-label" for="jenis_kunjungan">Jenis Kunjungan</label>
              <!-- <select
              name="jenis_kunjungan"
              id="jenis_kunjungan"
              class="form-select"
              v-model="form.jenis_kunjungan"
            >
              <option value="">Pilih jenis kunjungan</option>
              <option value="skrining">Skrining</option>
              <option value="konsultasi">Konsultasi</option>
              <option value="kontrol">Kontrol</option>
              <option value="rujukan">Rujukan</option>
            </select> -->
              <input
                type="text"
                name="jenis_kunjungan"
                id="jenis_kunjungan"
                class="form-control"
                v-model="form.jenis_kunjungan"
                placeholder="Masukkan jenis kunjungan (misal: Skrining, Konsultasi, Kontrol, Rujukan)"
              />
            </div>

            <div class="form-field">
              <label class="form-label" for="dokter">Dokter</label>
              <div class="field-with-icon">
                <i class="bi bi-person-badge"></i>
                <input
                  type="text"
                  name="dokter"
                  id="dokter"
                  class="form-control"
                  v-model="form.dokter"
                  placeholder="Masukkan nama dokter"
                />
              </div>
            </div>

            <div class="form-field">
              <label class="form-label" for="fasyankes">Fasyankes</label>
              <div class="field-with-icon">
                <i class="bi bi-hospital"></i>
                <input
                  type="text"
                  name="fasyankes"
                  id="fasyankes"
                  class="form-control"
                  v-model="form.fasyankes"
                  placeholder="Masukkan nama fasyankes"
                />
              </div>
            </div>

            <div class="form-field span-full">
              <label class="form-label" for="keluhan_utama">Keluhan Utama</label>
              <textarea
                name="keluhan_utama"
                id="keluhan_utama"
                class="form-control"
                rows="4"
                v-model="form.keluhan_utama"
                placeholder="Tuliskan keluhan utama, riwayat singkat, atau alasan skrining"
              ></textarea>
            </div>
          </div>
        </div>
      </section>
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';

  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });

  const pasien = computed(() => props.DataPasien || {});
  const fasyankes = computed(() => valueOrDash(pasien.value.nama_unit));
  const jenisKunjungan = computed(() => {
    if (pasien.value.kunjBaru === 'false') {
      return 'Kunjungan Lama';
    } else if (pasien.value.kunjBaru === 'true') {
      return 'Kunjungan Baru';
    }
    return '';
  });
  const form = props.formData?.subjektif || {};

  if (!form.tanggal_skrining) {
    form.tanggal_skrining =
      toDateInput(props.DataPasien?.tglKunjungan) || new Date().toISOString().split('T')[0];
  }

  form.jenis_kunjungan = form.jenis_kunjungan || jenisKunjungan.value;
  form.dokter = form.dokter || '';
  form.fasyankes = form.fasyankes || fasyankes.value;
  form.keluhan_utama = form.keluhan_utama || '';

  function toDateInput(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (Number.isNaN(date.getTime())) return '';
    return date.toISOString().split('T')[0];
  }

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }
</script>

<style scoped>
  .subjektif-form {
    display: grid;
    gap: 18px;
  }

  .subjektif-toolbar,
  .panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
  }

  .subjektif-toolbar {
    padding-bottom: 16px;
    border-bottom: 1px solid #e5edf0;
  }

  .subjektif-kicker {
    margin: 0 0 4px;
    color: #64748b;
    font-size: 0.76rem;
    font-weight: 750;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .subjektif-toolbar h3 {
    margin: 0;
    color: #0f172a;
    font-size: 1.2rem;
    font-weight: 750;
  }

  .segmented-control {
    display: inline-flex;
    gap: 4px;
    padding: 4px;
    border: 1px solid #cfd9e3;
    border-radius: 8px;
    background: #f8fafc;
  }

  .segment-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 36px;
    padding: 7px 12px;
    border: 0;
    border-radius: 6px;
    background: transparent;
    color: #475569;
    font-size: 0.86rem;
    font-weight: 700;
  }

  .segment-button.active {
    background: #0f6b4c;
    color: #ffffff;
    box-shadow: 0 8px 18px rgba(15, 107, 76, 0.18);
  }

  .visit-panel {
    max-width: 100%;
  }

  .visit-grid {
    display: grid;
    grid-template-columns: 220px minmax(220px, 0.8fr) minmax(240px, 1fr) minmax(280px, 1fr);
    gap: 16px;
  }

  .subjektif-panel {
    overflow: hidden;
    border: 1px solid #d9e5df;
    border-radius: 8px;
    background: #ffffff;
  }

  .panel-header {
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

  .form-field {
    min-width: 0;
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

  .field-with-icon {
    position: relative;
  }

  .field-with-icon i {
    position: absolute;
    left: 13px;
    top: 50%;
    color: #64748b;
    transform: translateY(-50%);
  }

  .field-with-icon .form-control {
    padding-left: 38px;
  }

  @media (max-width: 992px) {
    .visit-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .visit-grid .form-field:nth-child(3),
    .visit-grid .form-field:nth-child(4) {
      grid-column: 1 / -1;
    }
  }

  @media (max-width: 576px) {
    .visit-grid {
      grid-template-columns: 1fr;
    }

    .visit-grid .form-field:nth-child(3),
    .visit-grid .form-field:nth-child(4),
    .span-full {
      grid-column: auto;
    }

    .panel-body {
      padding: 16px;
    }
  }
</style>
