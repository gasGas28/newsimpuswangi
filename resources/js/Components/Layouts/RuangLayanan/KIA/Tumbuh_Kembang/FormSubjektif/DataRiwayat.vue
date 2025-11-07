<template>
  <div>
    <div class="p-1">
      <!-- Tombol navigasi antar form -->
      <div class="d-flex gap-3 flex-wrap">
        <a href="#" class="action-card medical-action" @click.prevent="toggleForm('riwayatGizi')">
          <div class="action-icon"><i class="bi bi-person-check"></i></div>
          <div class="action-label">Riwayat Gizi >></div>
        </a>
        <a
          href="#"
          class="action-card medical-action"
          @click.prevent="toggleForm('riwayatMakanan')"
        >
          <div class="action-icon"><i class="bi bi-person-check"></i></div>
          <div class="action-label">Riwayat Makanan >></div>
        </a>
      </div>

      <hr />

      <!-- Tempat munculnya form yang aktif -->
      <div class="mt-4">
        <component :is="activeComponent" v-if="activeComponent" :diagnosa="props.diagnosa" />
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';
  import RiwayatGizi from './RiwayatGizi.vue';
  import RiwayatMakanan from './RiwayatMakanan.vue';

  const props = defineProps({
    diagnosa: Array,
  });

  const activeForm = ref('riwayatGizi');

  // Fungsi toggle form
  const toggleForm = (form) => {
    activeForm.value = activeForm.value === form ? null : form;
  };

  // Menentukan komponen aktif berdasarkan state
  const activeComponent = computed(() => {
    switch (activeForm.value) {
      case 'riwayatGizi':
        return RiwayatGizi;
      case 'riwayatMakanan':
        return RiwayatMakanan;
      default:
        return RiwayatGizi;
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
  .form-card {
    width: 100%;
    margin: 0;
  }
</style>
