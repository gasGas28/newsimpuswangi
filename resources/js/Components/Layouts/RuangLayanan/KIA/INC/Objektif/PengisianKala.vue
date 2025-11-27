<template>
  <div>
    <!-- Tombol Aksi -->
    <div class="d-flex gap-3 flex-wrap mb-4">
      <a href="#" class="action-card" @click.prevent="toggleForm('kala1')">
        <div class="action-icon"><i class="bi bi-person-check"></i></div>
        <div class="action-label">Kala 1</div>
      </a>

      <a href="#" class="action-card" @click.prevent="toggleForm('kala2')">
        <div class="action-icon"><i class="bi bi-activity"></i></div>
        <div class="action-label">Kala 2</div>
      </a>
      <a href="#" class="action-card" @click.prevent="toggleForm('kala3')">
        <div class="action-icon"><i class="bi bi-person-check"></i></div>
        <div class="action-label">Kala 3</div>
      </a>

      <a href="#" class="action-card" @click.prevent="toggleForm('kala4')">
        <div class="action-icon"><i class="bi bi-activity"></i></div>
        <div class="action-label">Kala 4</div>
      </a>
    </div>

    <!-- Form dinamis -->
    <div v-if="activeForm === 'kala1'">
      <Kala1 />
    </div>

    <div v-if="activeForm === 'kala2'">
      <Kala2 />
    </div>
    <div v-if="activeForm === 'kala3'">
      <Kala3 />
    </div>
    <div v-if="activeForm === 'kala4'">
      <Kala4  />
    </div>
  </div>
</template>

<script setup>
  import { ref } from 'vue';

  // Import komponen form dari file lain
  import Kala1 from './Form/Kala1.vue';
  import Kala2 from './Form/Kala2.vue';
  import Kala3 from './Form/Kala3.vue';
  import Kala4 from './Form/Kala4.vue';


  const activeForm = ref('kala1');

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
