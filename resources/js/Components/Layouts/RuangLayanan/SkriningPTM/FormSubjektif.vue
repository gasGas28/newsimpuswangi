<template>
  <div class="subjektif-form">
    <div class="subjektif-toolbar">
      <div>
        <p class="subjektif-kicker">Subjektif</p>
        <h3>Pendaftaran Kunjungan Peserta Skrining</h3>
      </div>
      <div class="segmented-control" role="tablist" aria-label="Planning">
        <button
          type="button"
          class="segment-button"
          :class="{ active: activeFormPlanning === 'dataKunjungan' }"
          @click="toggleForm('dataKunjungan')"
        >
          <i class="bi bi-person-check"></i>
          <span>Data Kunjungan</span>
        </button>
        <button
          type="button"
          class="segment-button"
          :class="{ active: activeFormPlanning === 'faktorRisiko' }"
          @click="toggleForm('faktorRisiko')"
        >
          <i class="bi bi-exclamation-triangle"></i>
          <span>Faktor Risiko</span>
        </button>
      </div>
    </div>

    <div v-if="activeFormPlanning === 'dataKunjungan'" class="fade-in">
      <dataKunjungan :DataPasien="props.DataPasien" :formData="props.formData" />
    </div>
    <div v-if="activeFormPlanning === 'faktorRisiko'" class="fade-in">
      <faktorRisiko :DataPasien="props.DataPasien" :formData="props.formData" />
    </div>
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';
  import dataKunjungan from './FormPemeriksaan/DataKunjungan.vue';
  import faktorRisiko from './FormPemeriksaan/Faktor Risiko.vue';

  const activeFormPlanning = ref('dataKunjungan');
  const toggleForm = (form) => {
    activeFormPlanning.value = form;
  };

  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });
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

  .fade-in {
    animation: fadeIn 0.3s ease;
  }
</style>
