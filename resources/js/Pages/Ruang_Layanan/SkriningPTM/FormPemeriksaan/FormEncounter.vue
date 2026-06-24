<template>
  <div class="resume-form">
    <SendDataKunjungan
      :DataPasien="props.DataPasien"
      :TenagaMedis="props.TenagaMedis"
      :DataSkrining="props.DataSkrining"
    />
    <SendFaktorRisiko
      :DataPasien="props.DataPasien"
      :TenagaMedis="props.TenagaMedis"
      :DataSkrining="props.DataSkrining"
    />
  </div>
</template>

<script setup>
  import { ref, watchEffect, computed } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import SendDataKunjungan from '../KirimSatuSehat/KirimDataKunjungan.vue';
  import SendFaktorRisiko from '../KirimSatuSehat/KirimFaktorRisiko.vue';
  const props = defineProps({
    DataPasien: Object,
    TenagaMedis: Array,
    DataSkrining: Object,
  });
</script>

<style scoped src="./FormPemeriksaan.css"></style>
