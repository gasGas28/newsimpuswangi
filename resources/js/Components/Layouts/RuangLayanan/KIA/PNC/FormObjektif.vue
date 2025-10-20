<template>
  <div>
    <h5 class="fw-bold mb-3">Pemeriksaan Fisik Pasien / Subjektif</h5>

    <!-- Tombol navigasi antar form -->
    <div class="d-flex gap-3 flex-wrap">
      <a href="#" class="action-card medical-action" @click.prevent="toggleForm('dataRiwayat')">
        <div class="action-icon"><i class="bi bi-person-check"></i></div>
        <div class="action-label">Data Riwayat Persalinan yang Lalu >></div>
      </a> 
    </div>
    
    <!-- Tempat munculnya form yang aktif -->
    <div class="mt-4">
      <component :is="activeComponent" v-if="activeComponent" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import DataRiwayat from './Objektif/DataRiwayat.vue';


const activeForm = ref(null)

// Fungsi toggle form
const toggleForm = (form) => {
  activeForm.value = activeForm.value === form ? null : form
}

// Menentukan komponen aktif berdasarkan state
const activeComponent = computed(() => {
  switch (activeForm.value) {
    case 'dataRiwayat':
      return DataRiwayat
    default:
      return DataRiwayat
  }
})
</script>

<style scoped>
.action-card {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 14px;
  border-radius: 8px;
  background: #fbfbfc;
  color: #141414;
  text-decoration: none;
  transition: background 0.2s, color 0.2s;
}

.action-card:hover {
  background: #e9f2ff;
  color: #0d6efd;
}



.action-icon {
  font-size: 1.25rem;
  color: inherit;
}

.action-label {
  font-weight: 500;
}

</style>
