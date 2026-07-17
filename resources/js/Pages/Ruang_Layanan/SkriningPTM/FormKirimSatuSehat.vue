<template>
  <div class="subjektif-form">
    <div class="subjektif-toolbar">
      <div>
        <p class="subjektif-kicker">Kirim Satu Sehat</p>
        <h3>Pengiriman Data Ke Satu Sehat</h3>
      </div>
      <div class="segmented-control" role="tablist" aria-label="Planning">
        <button
          type="button"
          class="segment-button"
          :class="{ active: activeForm === 'encounter' }"
          @click="toggleForm('encounter')"
        >
          <i class="bi bi-person-check"></i>
          <span>Subjective</span>
        </button>
        <button
          type="button"
          class="segment-button"
          :class="{ active: activeForm === 'condition' }"
          @click="toggleForm('condition')"
        >
          <i class="bi bi-person-check"></i>
          <span>Objective</span>
        </button>
        <button
          type="button"
          class="segment-button"
          :class="{ active: activeForm === 'assessment' }"
          @click="toggleForm('assessment')"
        >
          <i class="bi bi-person-check"></i>
          <span>Assessment</span>
        </button>
        <button
          type="button"
          class="segment-button"
          :class="{ active: activeForm === 'planning' }"
          @click="toggleForm('planning')"
        >
          <i class="bi bi-person-check"></i>
          <span>Planning</span>
        </button>
        <button
          type="button"
          class="segment-button"
          :class="{ active: activeForm === 'status' }"
          @click="toggleForm('status')"
        >
          <i class="bi bi-person-check"></i>
          <span>StatusPasien</span>
        </button>
      </div>
    </div>

    <div v-if="activeForm === 'encounter'" class="fade-in">
      <encounter :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining"/>
    </div>
    <div v-if="activeForm === 'condition'" class="fade-in">
      <condition :DataPasien="props.DataPasien" :DataSkrining="props.DataSkrining"/>
    </div>
    <div v-if="activeForm === 'assessment'" class="fade-in">
      <assessment :DataPasien="props.DataPasien" :DataDiagnosa="props.DataDiagnosa"/>
    </div>
    <div v-if="activeForm === 'planning'" class="fade-in">
      <planning :DataPasien="props.DataPasien" :DataTindakan='props.DataTindakan' :DataEdukasi='props.DataEdukasi' :ResepObat="props.ResepObat"
/>
    </div>
    <div v-if="activeForm === 'status'" class="fade-in">
      <status :DataPasien="props.DataPasien"/>
    </div>
  </div>
</template>

<script setup>
  import { computed, ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import encounter from './FormPemeriksaan/FormEncounter.vue';
  import condition from './FormPemeriksaan/FormCondition.vue';
  import assessment from './KirimSatuSehat/KirimDiagnosis.vue';
  import planning from './FormPemeriksaan/FormKirimPlanning.vue';
  import status from './KirimSatuSehat/KirimStatusPasien.vue';

  const activeForm = ref('encounter');
  const toggleForm = (form) => {
    activeForm.value = form;
  };

  const props = defineProps({
    DataPasien: Object,
    DataDiagnosa: Array,
    DataTindakan: Array,
    DataEdukasi: Array,
    ResepObat: Array,
  });

  const pasien = computed(() => props.DataPasien || {});
</script>

<style scoped src="@/css/FormPemeriksaan.css"></style>
