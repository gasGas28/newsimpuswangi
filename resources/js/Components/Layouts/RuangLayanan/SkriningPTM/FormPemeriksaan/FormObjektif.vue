<template>
  <FormAntropometri :DataPasien="DataPasien" />
</template>

<script setup>
  import FormAntropometri from './FormAntropometri.vue';

  defineProps({
    DataPasien: Array,
  });
</script>
