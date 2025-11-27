<template>
  <div>
    <!-- Tombol navigasi antar form -->
    <div class="d-flex gap-3 flex-wrap">
      <a
        href="#"
        class="action-card medical-action"
        :class="{ 'active-card': activeFormAssesment === 'diagnosa' }"
        @click.prevent="toggleForm('diagnosa')"
      >
        <div class="action-icon"><i class="bi bi-person-check"></i></div>
        <div class="action-label">Diagnosa</div>
      </a>

      <a
        href="#"
        class="action-card medical-action"
        :class="{ 'active-card': activeFormAssesment === 'skrining' }"
        @click.prevent="toggleForm('skrining')"
      >
        <div class="action-icon"><i class="bi bi-activity"></i></div>
        <div class="action-label">Skrining</div>
      </a>
    </div>

    <!-- Form aktif -->
    <div class="mt-4">
      <component
        :DataPasien="props.DataPasien"
        :DataDiagnosa="props.DataDiagnosa"
        :diagnosa="props.diagnosa"
        :diagnosaKeperawatan="props.diagnosaKeperawatan"
        :AlergiMakanan="props.AlergiMakanan"
        :AlergiObat="props.AlergiObat"
        :is="activeComponent"
        v-if="activeComponent"
      />

      <!-- Pesan jika belum memilih form -->
    </div>
  </div>
</template>

<script setup>
  import { ref, computed, watch } from 'vue';
  import Diagnosa from './Form/Diagnosa.vue';
  import Skrining from './Form/Skrining.vue';

  const props = defineProps({
    DataPasien: Array,
    DataDiagnosa: Array,
    diagnosa: Array,
    diagnosaKeperawatan: Array,
    AlergiMakanan: Array,
    AlergiObat: Array,
  });

  // state aktif (diagnosa / skrining)
  const activeFormAssesment = ref(localStorage.getItem('activeFormAssesment') || 'diagnosa');

  // Simpan kembali jika user ganti tab
  watch(activeFormAssesment, (val) => {
    localStorage.setItem('activeFormAssesment', val);
  });
  const toggleForm = (form) => {
    activeFormAssesment.value = form;
  };
  // Menentukan komponen aktif berdasarkan state
  const activeComponent = computed(() => {
    switch (activeFormAssesment.value) {
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
    color: #10b981;
  }

  .active-card {
    background: #10b981;
    color: #fff;
  }

  .action-icon {
    font-size: 1.25rem;
    color: inherit;
  }

  .action-label {
    font-weight: 500;
  }
</style>
