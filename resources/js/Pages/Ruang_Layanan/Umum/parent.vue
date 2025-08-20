<template>
    <child @halo="handleHalo">
    </child>
</template>

<script setup>
import child from './child.vue';
function handleHalo(pesan) {
  console.log("Pesan dari anak:", pesan)
}
</script>