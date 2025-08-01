<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

function kembali() {
  router.get('/mal-sehat')
}

const entries = ref([
  { nama: 'Permintaan Obat', jumlah: 0 },
])

function getIcon(nama) {
  if (nama.includes('Obat')) {
    return 'https://img.icons8.com/ios-filled/50/pill.png'
  }
  return 'https://img.icons8.com/ios-filled/50/info.png'
}

function goToLayanan(nama) {
  if (nama.includes('Obat')) {
    router.get('/mal-sehat/farmasi/permintaanobat')
  }
}
</script>

<template>
  <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-white text-dark fw-bold fs-5 border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
      <span>Layanan - Farmasi{{ kategoriUnit }}</span>
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

<style scoped>
.hover-shadow:hover {
  box-shadow: 0 0.4rem 0.9rem rgba(0, 0, 0, 0.04);
}
</style>
