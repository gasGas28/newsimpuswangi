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
        <button class="btn btn-danger btn-sm fw-semibold">
          Akhiri ANC
        </button>
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

// import form
import FormObstetri from './FormObstetri.vue'
import FormSubjektif from './FormSubjektif.vue'
import FormObjektif from './FormObjektif.vue'
// (bisa tambahkan form lain nanti)

const tabs = [
  { name: 'obstetri', label: 'Obstetri' },
  { name: 'subjektif', label: 'Subjektif' },
  { name: 'objektif', label: 'Objektif' },
  { name: 'assesment', label: 'Assesment' },
]

const selectedTab = ref('obstetri')

const currentForm = computed(() => {
  switch (selectedTab.value) {
    case 'obstetri': return FormObstetri
    case 'subjektif': return FormSubjektif
    case 'objektif': return FormObjektif
    case 'assesment': return FormObstetri
    default: return null
  }
})
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
