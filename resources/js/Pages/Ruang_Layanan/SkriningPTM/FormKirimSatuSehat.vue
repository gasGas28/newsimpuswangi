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
          <span>Encounter</span>
        </button>
        <button
          type="button"
          class="segment-button"
          :class="{ active: activeForm === 'condition' }"
          @click="toggleForm('condition')"
        >
          <i class="bi bi-exclamation-triangle"></i>
          <span>Condition</span>
        </button>
      </div>
    </div>

    <div v-if="activeForm === 'encounter'" class="fade-in">
      <encounter :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining"/>
    </div>
    <div v-if="activeForm === 'condition'" class="fade-in">
      <condition :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining"/>
    </div>
  </div>
</template>

<script setup>
  import { computed, ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import encounter from './FormPemeriksaan/FormEncounter.vue';
  import condition from './FormPemeriksaan/FormCondition.vue';

  const activeForm = ref('encounter');
  const toggleForm = (form) => {
    activeForm.value = form;
  };

  const props = defineProps({
    DataPasien: Object,
    TenagaMedis: Array,
    DataSkrining: Object,
  });

  const pasien = computed(() => props.DataPasien || {});
</script>

<style scoped src="./FormPemeriksaan/FormPemeriksaan.css"></style>
