<template>
  <div>
    <h5 class="mb-2">Pelayanan Intranatal Care / Pelayanan Ibu Bersalin</h5>
  </div>
  <div class="card border-0 shadow-sm rounded-3">
    <!-- Tabs -->
    <div class="d-flex text-white align-items-center border-bottom bg-bottom p-2 rounded-top-3">
      <button
        v-for="tab in tabs"
        :key="tab.name"
        class="btn-tab"
        :class="{ active: selectedTab === tab.name }"
        @click="selectedTab = tab.name"
      >
        {{ tab.label }}
      </button>

      <div class="ms-auto">
        <button class="btn btn-success btn-sm fw-semibold">Kirim Data Ke Satu Sehat</button>
      </div>
    </div>

    <!-- Dynamic Form -->
    <div class="card-body bg-white">
      <component
        :DataPasien="props.DataPasien"
        :DataDiagnosa="props.DataDiagnosa"
        :KunjunganAnc="props.KunjunganAnc"
        :diagnosa="props.diagnosa"
        :AlergiMakanan="props.AlergiMakanan"
        :AlergiObat="props.AlergiObat"
        :diagnosaKeperawatan="props.diagnosaKeperawatan"
        :riwayat="props.riwayat"
        :tindakan="props.tindakan"
        :is="currentForm"
      />
    </div>
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';

  // import form
  import FormObjektif from './FormObjektif.vue';
  import FormPlanning from '../Form/FormPlanning.vue';
  import FormImunisasi from '../Form/FormImunisasi.vue';
  import FormStatusPasien from '../Form/FormStatusPasien.vue';

  const tabs = [
    { name: 'objektif', label: 'Objektif' },
    { name: 'imunisasi', label: 'Imunisasi' },
    { name: 'planning', label: 'Planning' },
    { name: 'status_pasien', label: 'Status Pasien' },
  ];

  const props = defineProps({
    DataPasien: Array,
    diagnosa: Array,
    tindakan: Array,
    riwayat: Array,
    diagnosaKeperawatan: Array,
    AlergiMakanan: Array,
    AlergiObat: Array,
    KunjunganAnc: Array,
    DataDiagnosa: Array,
  });

  const selectedTab = ref('objektif');

  const currentForm = computed(() => {
    switch (selectedTab.value) {
      case 'objektif':
        return FormObjektif;
      case 'imunisasi':
        return FormImunisasi;
      case 'planning':
        return FormPlanning;
      case 'status_pasien':
        return FormStatusPasien;
      default:
        return null;
    }
  });
</script>

<style scoped>
  .btn-tab {
    background: transparent;
    margin: 2px;
    border: none;
    padding: 8px 14px;
    font-weight: 600;
    color: #e9f2ff;
    border-radius: 6px;
    transition: 0.2s;
  }

  /* .btn-tab:hover {
  background: #e9f2ff;
  color: #10b981;
} */

  .btn-tab.active {
    background: #10b981;
    color: #fff;
  }

  .card {
    border-radius: 10px;
  }

  .btn-outline-danger {
    border-radius: 6px;
  }
  .bg-bottom {
    background: linear-gradient(135deg, #3b82f6, #10b981);
  }
</style>
