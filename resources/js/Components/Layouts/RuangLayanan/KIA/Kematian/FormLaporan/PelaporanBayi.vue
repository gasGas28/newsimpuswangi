<template>
  <div>
    <!-- Tombol Aksi -->
    <div class="d-flex gap-3 flex-wrap mb-4">
      <a href="#" class="action-card" @click.prevent="toggleForm('bayi-lahir-hidup')">
        <div class="action-icon"><i class="bi bi-person-check"></i></div>
        <div class="action-label">Kematian Bayi Lahir Hidup</div>
      </a>

      <a href="#" class="action-card" @click.prevent="toggleForm('bayi-lahir-mati')">
        <div class="action-icon"><i class="bi bi-activity"></i></div>
        <div class="action-label">Kematian Bayi Lahir Mati</div>
      </a>
    </div>

    <!-- Form dinamis -->
    <div v-if="activeForm === 'bayi-lahir-hidup'">
      <FormBayiLahirHidup @close="activeForm = null" :diagnosa="props.diagnosa" />
    </div>

    <div v-if="activeForm === 'bayi-lahir-mati'">
      <FormBayiLahirMati @close="activeForm = null" />
    </div>
  </div>
</template>

<script setup>
  import { ref } from 'vue';

  // Import komponen form dari file lain
  import FormBayiLahirHidup from './FormBayiLahirHidup.vue';
  import FormBayiLahirMati from './FormBayiLairMati.vue';

  const activeForm = ref('bayi-lahir-hidup');

  const props = defineProps({
    diagnosa: Array,
  });

  const toggleForm = (formType) => {
    activeForm.value = activeForm.value === formType ? null : formType;
  };
</script>

<style scoped>
  .action-card {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    background: #f9fafb;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
    border: none;
    border-radius: 6px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
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
