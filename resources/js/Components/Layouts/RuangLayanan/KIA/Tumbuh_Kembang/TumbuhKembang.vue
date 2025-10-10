<template>
  <div class="card shadow-sm border-0 rounded-4">
    <!-- Tabs -->
    <div class="d-flex align-items-center bg-success p-2 rounded-top-4">
      <button
        v-for="tab in tabs"
        :key="tab.name"
        class="btn text-white fw-semibold me-1"
        :class="{'btn-primary': selectedTab === tab.name, 'btn-success': selectedTab !== tab.name}"
        @click="selectedTab = tab.name"
      >
        {{ tab.label }}
      </button>
      <div class="ms-auto">
        <button class="btn btn-danger btn-sm fw-semibold">Akhiri Tumbuh Kembang</button>
      </div>
    </div>

    <!-- Dynamic Form -->
    <div class="card-body bg-white">
      <component :is="currentForm" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// import semua form
import FormObstetri from './FormObstetri.vue'
// import FormSubjektif from './forms/FormSubjektif.vue'
import FormObjektif from './FormObjektif.vue'
// import FormAssessment from './forms/FormAssessment.vue'
// import FormImunisasi from './forms/FormImunisasi.vue'
// import FormPlanning from './forms/FormPlanning.vue'
// import FormStatusPasien from './forms/FormStatusPasien.vue'

const tabs = [
  { name: 'obstetri', label: 'Obstetri' },
  // { name: 'subjektif', label: 'Subjektif' },
  { name: 'objektif', label: 'Objektif' },
  // { name: 'assessment', label: 'Assessment' },
  // { name: 'imunisasi', label: 'Imunisasi' },
  // { name: 'planning', label: 'Planning' },
  // { name: 'status', label: 'Status Pasien' },
]

const selectedTab = ref('obstetri')

const currentForm = computed(() => {
  switch (selectedTab.value) {
    case 'obstetri': return FormObstetri
    case 'subjektif': return FormSubjektif
    case 'objektif': return FormObjektif
    case 'assessment': return FormAssessment
    case 'imunisasi': return FormImunisasi
    case 'planning': return FormPlanning
    case 'status': return FormStatusPasien
    default: return null
  }
})
</script>

<style scoped>
.btn {
  border-radius: 0 !important;
}
.btn-primary {
  background-color: #0d6efd !important;
}
</style>
