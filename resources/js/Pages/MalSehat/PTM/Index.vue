<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

function kembali() {
  router.get('/mal-sehat')
}

const entries = ref([
  { nama: 'Konseling Upaya Berhenti Merokok', jumlah: 0 },
  { nama: 'Skrining Faktor Risiko PTM', jumlah: 0 },
])

function getIcon(nama) {
  if (nama.includes('Merokok')) {
    return 'https://img.icons8.com/ios-filled/50/no-smoking.png'
  } else if (nama.includes('Skrining')) {
    return 'https://img.icons8.com/ios-filled/50/heart-health.png'
  }
  return 'https://img.icons8.com/ios-filled/50/info.png'
}

function goToLayanan(nama) {
  if (nama.includes('Merokok')) {
    router.get('/mal-sehat/ptm/konselingberhentimerokok')
  } else if (nama.includes('Skrining')) {
    router.get('/mal-sehat/ptm/skriningfaktorrisiko')
  }
}
</script>

<template>
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white text-dark fw-bold fs-5 border-bottom d-flex justify-content-between align-items-center">
      <span>PTM - {{ kategoriUnit }}</span>
      <button class="btn btn-sm btn-outline-secondary" @click="kembali">← Kembali</button>
    </div>

    <div class="card-body bg-light-subtle p-3">
      <div class="row g-3 justify-content-start">
        <div
          v-for="item in entries"
          :key="item.nama"
          class="col-12 col-sm-6 col-lg-4"
        >
          <div
            class="card h-100 border-0 shadow-sm hover-shadow bg-white rounded-0 cursor-pointer"
            @click="goToLayanan(item.nama)"
          >
            <div class="card-body d-flex justify-content-between align-items-center p-2">
              <div class="text-dark">
                <h6 class="mb-1">{{ item.nama }}</h6>
                <small>Hari ini {{ item.jumlah }} pasien</small>
              </div>
              <img
                :src="getIcon(item.nama)"
                alt="icon"
                class="opacity-50"
                style="width: 30px; height: 30px;"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.hover-shadow:hover {
  box-shadow: 0 0.4rem 0.9rem rgba(0, 0, 0, 0.04);
}
</style>
