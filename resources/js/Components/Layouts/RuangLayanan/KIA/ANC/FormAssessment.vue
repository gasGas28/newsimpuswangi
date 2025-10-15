<template>
  <div>
    <h5 class="fw-bold mb-3">Pemeriksaan Pasien / Objektif</h5>

    <!-- Tombol navigasi antar form -->
    <div class="d-flex gap-3 flex-wrap">
      <a href="#" class="action-card medical-action" @click.prevent="toggleForm('diagnosa')">
        <div class="action-icon"><i class="bi bi-person-check"></i></div>
        <div class="action-label">Diagnosa</div>
      </a>

      <a href="#" class="action-card medical-action" @click.prevent="toggleForm('skrining')">
        <div class="action-icon"><i class="bi bi-activity"></i></div>
        <div class="action-label">Skrining</div>
      </a>
    </div>
    <!-- Tempat munculnya form yang aktif -->
    <div class="mt-4">
      <component :diagnosa="props.diagnosa" :is="activeComponent" v-if="activeComponent" />
    </div>
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';
  import Diagnosa from './Assesment/Diagnosa.vue';
  import Skrining from './Assesment/Skrining.vue';

  const activeForm = ref(null);

  const props = defineProps({
    diagnosa: Array,
  });

  // Fungsi toggle form
  const toggleForm = (form) => {
    activeForm.value = activeForm.value === form ? null : form;
  };

  // Menentukan komponen aktif berdasarkan state
  const activeComponent = computed(() => {
    switch (activeForm.value) {
      case 'diagnosa':
        return Diagnosa;
      case 'skrining':
        return Skrining;
      default:
        return Diagnosa;
    }
  });
</script>

<style scoped>
  .action-card {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 8px;
    background: #f9fafb;
    color: #333;
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
