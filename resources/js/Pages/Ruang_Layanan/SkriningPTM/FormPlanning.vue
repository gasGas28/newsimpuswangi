<template>
  <div class="planning-form">
    <div class="planning-toolbar">
      <div>
        <p class="planning-kicker">Planning</p>
        <h3>Rencana Tindakan dan Pengobatan</h3>
      </div>

      <div class="segmented-control" role="tablist" aria-label="Planning">
        <button
          type="button"
          class="segment-button"
          :class="{ active: activeFormPlanning === 'tindakan' }"
          @click="toggleForm('tindakan')"
        >
          <i class="bi bi-person-check"></i>
          <span>Tindakan</span>
        </button>
        <button
          type="button"
          class="segment-button"
          :class="{ active: activeFormPlanning === 'pengobatan' }"
          @click="toggleForm('pengobatan')"
        >
          <i class="bi bi-capsule"></i>
          <span>Pengobatan</span>
        </button>
      </div>
    </div>

    <FormTindakan
      v-if="activeFormPlanning === 'tindakan'"
      :DataPasien="props.DataPasien"
      :DataTindakan="props.DataTindakan"
    />

    <FormPengobatan
      v-if="activeFormPlanning === 'pengobatan'"
      :DataObat="props.DataObat"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import FormTindakan from './FormPemeriksaan/FormTindakan.vue';
import FormPengobatan from './FormPemeriksaan/FormPengobatan.vue';

const props = defineProps({
  DataPasien: Object,
  tindakan: Array,
  DataTindakan: Array,
  DataObat: Array,
});

const activeFormPlanning = ref('tindakan');
const toggleForm = (form) => {
  activeFormPlanning.value = form;
};
</script>

<style>

.planning-form { display: grid; gap: 18px; }

.planning-toolbar,
.panel-header,
.panel-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
}

.planning-toolbar {
  padding-bottom: 16px;
  border-bottom: 1px solid #e5edf0;
}

.planning-kicker {
  margin: 0 0 4px;
  color: #64748b;
  font-size: 0.76rem;
  font-weight: 750;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.planning-toolbar h3 {
  margin: 0;
  color: #0f172a;
  font-size: 1.2rem;
  font-weight: 750;
}

/* Segmented control */
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
  gap: 8px;
  min-height: 36px;
  padding: 7px 12px;
  border: 0;
  border-radius: 6px;
  background: transparent;
  color: #475569;
  font-size: 0.86rem;
  font-weight: 700;
  cursor: pointer;
}

.segment-button.active {
  background: #0f6b4c;
  color: #fff;
  box-shadow: 0 8px 18px rgba(15,107,76,0.18);
}

/* Tipe toggle resep */
.resep-type-toggle {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 4px;
}

.type-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  padding: 16px;
  border: 2px solid #d9e5df;
  border-radius: 10px;
  background: #ffffff;
  color: #475569;
  cursor: pointer;
  transition: all 0.15s;
}

.type-btn i { font-size: 1.4rem; }
.type-btn span { font-size: 0.9rem; font-weight: 750; }
.type-btn small { font-size: 0.75rem; color: #94a3b8; }

.type-btn.active {
  border-color: #0f6b4c;
  background: #effaf5;
  color: #0f6b4c;
}

.type-btn.active small { color: #5eab84; }

/* Panel */
.planning-panel {
  overflow: hidden;
  margin-bottom: 18px;
  border: 1px solid #d9e5df;
  border-radius: 8px;
  background: #fff;
}

.panel-header {
  padding: 18px 20px;
  border-bottom: 1px solid #e5edf0;
  background: #f8fafc;
}

.panel-header.compact { padding-block: 15px; }

.panel-header h4 {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: #0f3d2e;
  font-size: 1rem;
  font-weight: 750;
}

.panel-header p { margin: 5px 0 0; color: #64748b; font-size: 0.86rem; }

.panel-body { padding: 20px; }

.panel-footer {
  justify-content: flex-end;
  padding: 16px 20px;
  border-top: 1px solid #e5edf0;
  background: #fbfdff;
}

/* Form grid */
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 16px;
}

.action-form-grid { grid-template-columns: 1fr; }
.form-field { min-width: 0; }
.span-2 { grid-column: span 2; }

.form-label {
  display: block;
  margin-bottom: 6px;
  color: #334155;
  font-size: 0.86rem;
  font-weight: 700;
}

.required { color: #dc2626; margin-left: 2px; }

.form-control,
.form-select {
  min-height: 42px;
  border: 1px solid #cfd9e3;
  border-radius: 8px;
  color: #0f172a;
}

textarea.form-control { resize: vertical; }

.form-control:focus,
.form-select:focus {
  border-color: #16a36f;
  box-shadow: 0 0 0 0.2rem rgba(22,163,111,0.14);
  outline: none;
}

/* Pilih obat button */
.btn-pilih-obat {
  display: flex;
  align-items: center;
  gap: 10px;
  width: 100%;
  min-height: 42px;
  padding: 10px 14px;
  border: 1.5px dashed #a7c4b5;
  border-radius: 8px;
  background: #f8fafc;
  color: #64748b;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.15s;
}

.btn-pilih-obat:hover {
  border-color: #16a36f;
  background: #f0faf5;
  color: #0f6b4c;
}

/* Obat terpilih */
.obat-selected-wrap {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 10px 14px;
  border: 1px solid #a7f3d0;
  border-radius: 8px;
  background: #f0fdf4;
}

.obat-selected-info {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
  min-width: 0;
}

.obat-selected-nama {
  font-weight: 650;
  color: #0f172a;
  font-size: 0.875rem;
}

.racikan-actions {
  display: flex;
  gap: 8px;
  flex-shrink: 0;
}

.btn-ganti {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  height: 30px;
  padding: 0 10px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  background: #fff;
  color: #475569;
  font-size: 0.78rem;
  font-weight: 650;
  cursor: pointer;
  white-space: nowrap;
}

.btn-ganti:hover { background: #f1f5f9; }

/* Racikan list */
.racikan-list {
  margin-bottom: 14px;
  padding: 12px 14px;
  border: 1px solid #bbf7d0;
  border-radius: 8px;
  background: #f0fdf4;
}

.racikan-label {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 10px;
  color: #166534;
  font-size: 0.82rem;
  font-weight: 750;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.racikan-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 6px 0;
  border-bottom: 1px solid #dcfce7;
}

.racikan-item:last-child { border-bottom: 0; }
.racikan-nama { flex: 1; font-size: 0.875rem; font-weight: 600; color: #0f172a; }

.racikan-remove {
  display: grid;
  place-items: center;
  width: 24px;
  height: 24px;
  border: 0;
  border-radius: 50%;
  background: #fee2e2;
  color: #dc2626;
  font-size: 0.85rem;
  cursor: pointer;
}

.tambah-racikan-wrap { margin-bottom: 0; }

.divider {
  margin: 18px 0;
  border-top: 1px dashed #d1fae5;
}

/* Puyer section */
.puyer-section {
  padding: 16px;
  border: 1px solid #cdeedd;
  border-radius: 8px;
  background: #f6fbf8;
}

.section-subtitle {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 14px;
  color: #0f6b4c;
  font-size: 0.9rem;
  font-weight: 750;
}

/* Dosis grid */
.input-unit {
  display: grid;
  grid-template-columns: 1fr auto;
  align-items: center;
  gap: 8px;
}

.input-unit span,
.dose-grid span,
.dose-grid-single span {
  color: #64748b;
  font-size: 0.85rem;
  font-weight: 650;
  white-space: nowrap;
}

.dose-grid {
  display: grid;
  grid-template-columns: 80px auto 80px auto;
  align-items: center;
  gap: 8px;
}

.dose-grid-single {
  display: grid;
  grid-template-columns: 80px auto;
  align-items: center;
  gap: 8px;
}

/* Check list */
.check-list { display: flex; flex-wrap: wrap; gap: 8px; }

.check-item {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  min-height: 34px;
  padding: 6px 10px;
  border: 1px solid #d9e5df;
  border-radius: 7px;
  background: #fff;
  color: #334155;
  font-size: 0.84rem;
  font-weight: 650;
  cursor: pointer;
}

/* Action check grid */
.action-check-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
  margin-top: 6px;
}

.action-check-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  min-height: 86px;
  padding: 13px;
  border: 1px solid #d9e5df;
  border-radius: 8px;
  background: #fff;
  color: #334155;
  cursor: pointer;
}

.action-check-item.checked {
  border-color: #16a36f;
  background: #effaf5;
  color: #0f6b4c;
}

.action-check-item input { flex: 0 0 auto; margin-top: 3px; }
.action-check-item strong { display: block; font-size: 0.9rem; line-height: 1.35; }
.action-check-item small {
  display: block;
  margin-top: 5px;
  color: #64748b;
  font-size: 0.78rem;
  font-weight: 650;
  line-height: 1.4;
}

/* Error */
.resep-error {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 14px;
  padding: 10px 14px;
  border-radius: 8px;
  background: #fef2f2;
  color: #dc2626;
  font-size: 0.84rem;
  font-weight: 650;
}

/* Table */
.planning-table th {
  padding: 13px 12px;
  border-bottom: 1px solid #c9ded6;
  background: #e7f5ef;
  color: #174236;
  font-size: 0.76rem;
  font-weight: 700;
  text-transform: uppercase;
  white-space: nowrap;
}

.planning-table td {
  padding: 11px 12px;
  color: #1e293b;
  font-size: 0.84rem;
  vertical-align: middle;
}

.planning-table tbody tr:hover { background: #f6fbf8; }
.table-muted { color: #64748b; }

.code-pill {
  display: inline-flex;
  align-items: center;
  height: 24px;
  padding: 0 8px;
  border-radius: 999px;
  background: #e0f2fe;
  color: #075985;
  font-size: 0.74rem;
  font-weight: 750;
}

.service-pill {
  display: inline-flex;
  align-items: center;
  height: 24px;
  padding: 0 8px;
  border-radius: 999px;
  background: #e7f5ef;
  color: #0f6b4c;
  font-size: 0.74rem;
  font-weight: 750;
}

.satuan-badge {
  display: inline-flex;
  align-items: center;
  height: 22px;
  padding: 0 8px;
  border-radius: 999px;
  background: #f1f5f9;
  color: #475569;
  font-size: 0.74rem;
  font-weight: 650;
}

/* Tipe badge resep */
.tipe-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  height: 26px;
  padding: 0 10px;
  border-radius: 6px;
  font-size: 0.76rem;
  font-weight: 750;
  white-space: nowrap;
}

.tipe-tunggal { background: #eff6ff; color: #1d4ed8; }
.tipe-puyer   { background: #fdf4ff; color: #7e22ce; }

/* Komposisi puyer di tabel */
.komposisi-list { display: flex; flex-direction: column; gap: 4px; }
.komposisi-item { display: flex; align-items: center; gap: 6px; font-size: 0.83rem; }

/* Empty state */
.empty-state { height: 108px; color: #64748b; text-align: center; }
.empty-state i { display: block; margin-bottom: 6px; font-size: 1.5rem; }

/* Btn */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  border-radius: 8px;
  font-weight: 650;
}

.btn-icon { width: 36px; height: 36px; padding: 0; }

/* Success overlay */
.success-overlay {
  position: fixed;
  inset: 0;
  z-index: 2000;
  display: grid;
  place-items: center;
  padding: 24px;
  background: rgba(15,23,42,0.42);
}

.success-dialog {
  width: min(95%, 380px);
  padding: 28px 24px;
  border-radius: 8px;
  background: #fff;
  text-align: center;
  box-shadow: 0 24px 60px rgba(15,23,42,0.22);
}

.success-icon {
  display: grid;
  place-items: center;
  width: 70px;
  height: 70px;
  margin: 0 auto 14px;
  border-radius: 50%;
  background: #dcfce7;
  color: #16a34a;
  font-size: 2.2rem;
}

.success-dialog h5 { margin: 0 0 6px; color: #166534; font-size: 1.05rem; font-weight: 750; }
.success-dialog p  { margin: 0 0 18px; color: #64748b; font-size: 0.9rem; }

.fade-in { animation: fadeIn 0.2s ease-in-out; }
.spinner { animation: spin 1s linear infinite; }

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(6px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .planning-toolbar { align-items: stretch; flex-direction: column; }
  .segmented-control, .segment-button, .panel-footer .btn { width: 100%; }
  .form-grid, .dose-grid, .action-check-grid { grid-template-columns: 1fr; }
  .dose-grid { grid-template-columns: 1fr 1fr; }
  .span-2 { grid-column: auto; }
  .resep-type-toggle { grid-template-columns: 1fr 1fr; }
}

</style>