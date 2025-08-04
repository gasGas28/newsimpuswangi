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
  <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-white text-dark fw-bold fs-5 border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
      <span>Layanan - Penyakit Tidak Menular</span>
      <button class="btn btn-sm btn-outline-secondary" @click="kembali">← Kembali</button>
    </div>

    <div class="card-body py-4 px-4" style="background-color: #f8fbfd;">
      <div class="row g-4">
        <div
          v-for="item in entries"
          :key="item.nama"
          class="col-12 col-sm-6 col-lg-4"
        >
          <div
            class="card h-100 border-0 shadow-sm rounded-4 cursor-pointer"
            style="background-color: #B0E0E6; transition: transform 0.2s ease;"
            @click="goToLayanan(item.nama)"
            @mouseover="$event.currentTarget.style.transform = 'scale(1.01)'"
            @mouseleave="$event.currentTarget.style.transform = 'scale(1)'"
          >
            <div class="card-body d-flex justify-content-between align-items-center px-3 py-3">
              <div>
                <div class="fw-semibold fs-6 mb-1 text-dark">{{ item.nama }}</div>
                <small class="text-muted">Hari ini {{ item.jumlah }} pasien</small>
              </div>
              <img
                :src="getIcon(item.nama)"
                alt="icon"
                class="opacity-50"
                style="width: 32px; height: 32px;"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
