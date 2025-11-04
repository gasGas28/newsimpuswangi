<template>
  <div>
    <!-- Tombol Aksi -->
    <div class="d-flex gap-3 flex-wrap mb-4">
      <a href="#" class="action-card" @click.prevent="toggleForm('kn-0')">
        <div class="action-icon"><i class="bi bi-person-check"></i></div>
        <div class="action-label">0 - 6 Jam</div>
      </a>

      <a href="#" class="action-card" @click.prevent="toggleForm('kn-1')">
        <div class="action-icon"><i class="bi bi-activity"></i></div>
        <div class="action-label">6 - 48 Jam (KN1)</div>
      </a>
      <a href="#" class="action-card" @click.prevent="toggleForm('kn-2')">
        <div class="action-icon"><i class="bi bi-person-check"></i></div>
        <div class="action-label">3 - 7 Hari (KN2)</div>
      </a>

      <a href="#" class="action-card" @click.prevent="toggleForm('kn-3')">
        <div class="action-icon"><i class="bi bi-activity"></i></div>
        <div class="action-label">8 - 28 Hari (KN3)</div>
      </a>
    </div>

    <!-- Form dinamis -->
    <div v-if="activeForm === 'kn-0'">
      <Form_KN />
    </div>

    <div v-if="activeForm === 'kn-1'">
      <Form_KN1 />
    </div>
    <div v-if="activeForm === 'kn-2'">
      <Form_KN2 />
    </div>
    <div v-if="activeForm === 'kn-3'">
      <Form_KN3 />
    </div>
  </div>
</template>

<script setup>
  import { ref } from 'vue';

  // Import komponen form dari file lain
  import Form_KN from './Form/Form_KN.vue';
  import Form_KN1 from './Form/Form_KN1.vue';
  import Form_KN2 from './Form/Form_KN2.vue';
  import Form_KN3 from './Form/Form_KN3.vue';

  const activeForm = ref('kn-0');

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
