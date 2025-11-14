<template>
  <div>
    <div class="p-3">
      <!-- Tombol navigasi antar form -->
      <div class="d-flex gap-3 flex-wrap">
        <a
          href="#"
          class="action-card medical-action"
          @click.prevent="toggleForm('dataAntropometri')"
        >
          <div class="action-icon"><i class="bi bi-person-check"></i></div>
          <div class="action-label">Pengiriman Data Antropometri</div>
        </a>
        <a href="#" class="action-card medical-action" @click.prevent="toggleForm('dataStimulasi')">
          <div class="action-icon"><i class="bi bi-person-check"></i></div>
          <div class="action-label">
            Pengiriman Data SDIDTK (Stimulasi, Deteksi, dan Intervensi Dini Tumbuh Kembang)
          </div>
        </a>
        <a href="#" class="action-card medical-action" @click.prevent="toggleForm('dataStatus')">
          <div class="action-icon"><i class="bi bi-person-check"></i></div>
          <div class="action-label">
            Pengiriman Data Status Pertumbuhan dan Perkembangan, serta Data Lainnya
          </div>
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
import DataAntropometri from './FormObjektif/DataAntropometri.vue';
import DataStatus from './FormObjektif/DataStatus.vue';
import DataStimulasi from './FormObjektif/DataStimulasi.vue';


  const props = defineProps({
    diagnosa: Array,
  });

  const activeForm = ref(null);

  // Fungsi toggle form
  const toggleForm = (form) => {
    activeForm.value = activeForm.value === form ? null : form;
  };

  // Menentukan komponen aktif berdasarkan state
  const activeComponent = computed(() => {
    switch (activeForm.value) {
      case 'dataAntropometri':
        return DataAntropometri;
      case 'dataStatus':
        return DataStatus;
      case 'dataStimulasi':
        return DataStimulasi;
      default:
        return DataAntropometri;
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