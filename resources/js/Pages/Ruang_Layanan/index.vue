<template>
  <AppLayouts>
    <div class="container my-4">
      <h5 class="mb-4">JUMLAH PASIEN RUANG LAYANAN PUSKESMAS HARI INI</h5>
      <div class="row g-3">
        <div class="col-6 col-md-3" v-for="layanan in dataLayanan" :key="layanan.nama">
          <div class="card shadow-sm rounded overflow-hidden">
            <div class="row g-0">
              <!-- Ikon kiri -->
              <div class="col-4 bg-success text-white d-flex align-items-center justify-content-center">
                <i class="fas fa-users fa-2x"></i>
              </div>
              <!-- Konten kanan -->
              <div class="col">
                <Link :href="route('ruang-layanan.umum')" class="text-decoration-none text-dark d-block p-3">
                  <div class="fw-semibold text-uppercase small">{{ layanan.nama }}</div>
                  <div class="fs-5 fw-bold">{{ layanan.jumlah }}</div>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayouts from '../../Components/Layouts/AppLayouts.vue';
const dataLayanan = [
  { nama: 'Umum', jumlah: 1, link: 'ruang-layanan.umum' },
  { nama: 'Gigi', jumlah: 0, link: '/simpus/gigi' },
  { nama: 'KIA', jumlah: 0, link: '/simpus/kia' },
  { nama: 'UGD', jumlah: 0, link: '/simpus/ugd' },
  { nama: 'KB', jumlah: 0, link: '/simpus/kb' },
  { nama: 'Sanitasi', jumlah: 0, link: '/simpus/sanitasi' },
  { nama: 'Rawat Inap', jumlah: 0, link: '/simpus/rawat-inap' },
  { nama: 'Kunjungan Onli', jumlah: 0, link: '/simpus/kunjungan' },
  { nama: 'Gizi', jumlah: 0, link: '/simpus/gizi' },
  { nama: 'Laborat', jumlah: 0, link: '/simpus/laborat' },
]
</script>
