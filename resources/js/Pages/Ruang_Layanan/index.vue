<template>
  <AppLayouts>
    <div class="container my-4">
      <div class="p-4 rounded mb-4 text-white" style="background: linear-gradient(135deg, #3b82f6, #10b981);">
        <h1 class="h3 mb-2">
          JUMLAH PASIEN RUANG LAYANAN PUSKESMAS WONGSOREJO HARI INI
        </h1>
        <div class="bg-white bg-opacity-25 d-inline-block px-3 py-2 rounded-pill mt-2">
          <i class="fas fa-calendar-alt me-2"></i>
          {{ currentDate }}
        </div>
      </div>
      <div class="row g-4">
        <div class="col-6 col-md-4 col-lg-3" v-for="layanan in dataLayanan" :key="layanan.nama">
          <Link :href="route(layanan.link)" class="text-decoration-none">
          <div class="card shadow ">
            <div class="card-body ">
              <div class="mb-3 rounded-4 d-flex justify-content-center"
                :style="{ width: '60px', height: '60px', backgroundColor: (layanan.bg) }">
                <i :class="layanan.icon" class="text-white fs-2"></i>
              </div>
              <h5 class="card-title mb-2">{{ layanan.nama }}</h5>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Total Pasien</small>
                <strong class="fs-4">{{ layanan.jumlah }}</strong>
              </div>
            </div>
          </div>
          </Link>
        </div>
      </div>
    </div>
  </AppLayouts>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayouts from '../../Components/Layouts/AppLayouts.vue';

const currentDate = new Date().toLocaleDateString('id-ID', {
  weekday: 'long',
  day: 'numeric',
  month: 'long',
  year: 'numeric'
})

const dataLayanan = [
  { nama: 'Umum', jumlah: 1000, link: 'ruang-layanan.umum', icon: 'bi bi-person-fill', bg: '#3b82f6' },
  { nama: 'Gigi', jumlah: 0, link: 'ruang-layanan.gigi', icon: 'bi bi-clipboard-check', bg: '#10b981' },
  { nama: 'UGD', jumlah: 0, link: 'ruang-layanan.ugd', icon: 'bi bi-hospital', bg: '#facc15' },
  { nama: 'KB', jumlah: 0, link: 'ruang-layanan.kb', icon: 'bi bi-people-fill', bg: '#3b82f6' },
  { nama: 'Sanitasi', jumlah: 0, link: 'ruang-layanan.sanitasi', icon: 'bi bi-droplet-half', bg: '#14b8a6' },
  { nama: 'Rawat Inap', jumlah: 0, link: 'ruang-layanan.rawat-inap', icon: 'bi bi-heart-pulse', bg: '#facc15' },
  { nama: 'Kunjungan Online', jumlah: 10, link: 'ruang-layanan.kunjungan-online', icon: 'bi bi-chat-dots', bg: '#3b82f6' },
  { nama: 'Gizi', jumlah: 0, link: 'ruang-layanan.gizi', icon: 'bi bi-egg-fried', bg: '#10b981' },
  { nama: 'Laborat', jumlah: 0, link: 'ruang-layanan.laborat', icon: 'bi bi-eyedropper', bg: '#facc15' },
  { nama: 'KIA', jumlah: 0, link: 'ruang-layanan.laborat', icon: 'bi bi-gender-female', bg: '#14b8a6' },
]

</script>

<style>

</style>
