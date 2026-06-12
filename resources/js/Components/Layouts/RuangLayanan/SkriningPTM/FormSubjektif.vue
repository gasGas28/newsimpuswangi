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
      <dataKunjungan
        :DataPasien="props.DataPasien"
        :formData="props.formData"
        :TenagaMedis="props.TenagaMedis"
      />
    </div>
    <div v-if="activeFormPlanning === 'faktorRisiko'" class="fade-in">
      <faktorRisiko
        :DataPasien="props.DataPasien"
        :formData="props.formData"
        :TenagaMedis="props.TenagaMedis"
      />
    </div>

   
  </div>
</template>

<script setup>
  import { computed, ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import dataKunjungan from './FormPemeriksaan/DataKunjungan.vue';
  import faktorRisiko from './FormPemeriksaan/Faktor Risiko.vue';

  const activeFormPlanning = ref('dataKunjungan');
  const isSaving = ref(false);
  const saveStatus = ref('idle');
  const saveErrors = ref({});
  const showDuplicateModal = ref(false);
  const duplicateMessage = ref('Silakan lanjutkan pengisian data pada kunjungan yang sudah ada.');

  const toggleForm = (form) => {
    activeFormPlanning.value = form;
  };

  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
    TenagaMedis: Array,
  });

  const pasien = computed(() => props.DataPasien || {});

  

  const isFaktorRisikoFilled = (subjektif) => {
    const hasSmokingAnswer = subjektif.merokok === 'ya' || subjektif.merokok === 'tidak';
    const hasSmokingStatus = Boolean(subjektif.status_merokok);

    return hasSmokingAnswer && hasSmokingStatus;
  };
</script>

<style scoped src="./FormPemeriksaan/FormPemeriksaan.css"></style>
