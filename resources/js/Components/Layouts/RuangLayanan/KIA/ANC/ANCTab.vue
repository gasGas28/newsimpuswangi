<template>
  <div>
    <h5 class="mb-2">Pelayanan Antenatal Care / Ibu Hamil</h5>
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
        <button class="btn btn-danger btn-sm fw-semibold">Akhiri ANC</button>
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
  import FormObstetri from './FormObstetri.vue';
  import FormSubjektif from './FormSubjektif.vue';
  import FormObjektif from './FormObjektif.vue';
  import FormAssessment from './FormAssessment.vue';
  import FormImunisasi from './FormImunisasi.vue';
  import FormPlanning from './FormPlanning.vue';
  import FormStatusPasien from './FormStatusPasien.vue';
  // (bisa tambahkan form lain nanti)

  const tabs = [
    { name: 'obstetri', label: 'Obstetri' },
    { name: 'subjektif', label: 'Subjektif' },
    { name: 'objektif', label: 'Objektif' },
    { name: 'assessment', label: 'Assessment' },
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

  const selectedTab = ref('obstetri');

  const currentForm = computed(() => {
    switch (selectedTab.value) {
      case 'obstetri':
        return FormObstetri;
      case 'subjektif':
        return FormSubjektif;
      case 'objektif':
        return FormObjektif;
      case 'assessment':
        return FormAssessment;
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
