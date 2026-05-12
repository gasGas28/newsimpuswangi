<template>
  <div class="row">
    <div class="col-md-6">
      <div class="p-3">
        <h5 class="mb-4 fw-semibold text-success">Riwayat Penyakit Pribadi</h5>
        <RiwayatPenyakit :DataPasien="DataPasien" />
      </div>
    </div>
    <div class="col-md-6">
      <div class="p-3">
        <h5 class="mb-4 fw-semibold text-success">Riwayat Penyakit Keluarga</h5>
        <RiwayatPenyakit :DataPasien="DataPasien" />
      </div>
    </div>
  </div>
</template>

<script setup>
  import RiwayatPenyakit from './RiwayatPenyakit.vue';
  const props = defineProps({
    DataPasien: Array,
  });
</script>
