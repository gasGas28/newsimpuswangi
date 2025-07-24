<template>
  <AppLayout title="Layanan Detail">
    <div class="container py-5">
      <div class="row mb-3">
        <div class="col">
          <h1 class="fw-bold">Layanan MAL Sehat</h1>
          <p class="text-muted">Pilih layanan yang tersedia untuk melihat detail pasien hari ini.</p>
        </div>
      </div>

      <div class="row g-2">
        <div
          v-for="item in layanan"
          :key="item.nama"
          class="col-md-4"
        >
          <div
            class="card h-100 shadow-sm cursor-pointer d-flex justify-content-between align-items-center flex-row p-3"
            @click="goToLayanan(item.nama)"
          >
            <div>
              <h5 class="fw-bold mb-1">{{ item.nama }}</h5>
              <p class="text-muted mb-0">Hari ini {{ item.jumlah }} pasien</p>
            </div>
            <img
              :src="item.icon"
              alt="icon"
              width="40"
              height="40"
              class="icon-neutral"
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { router } from '@inertiajs/vue3'

const layanan = [
  { nama: "Konseling Sanitasi", jumlah: 0, icon: "https://cdn-icons-png.flaticon.com/512/3659/3659891.png" },
  { nama: "Kesga", jumlah: 0, icon: "https://cdn-icons-png.flaticon.com/512/943/943918.png" },
  { nama: "PTM", jumlah: 0, icon: "https://cdn-icons-png.flaticon.com/512/2621/2621048.png" },
  { nama: "P3M", jumlah: 0, icon: "https://cdn-icons-png.flaticon.com/512/4341/4341139.png" },
  { nama: "Yankes primer", jumlah: 1, icon: "https://cdn-icons-png.flaticon.com/512/3063/3063827.png" },
  { nama: "Farmasi", jumlah: 0, icon: "https://cdn-icons-png.flaticon.com/512/1040/1040230.png" },
  { nama: "Biakes", jumlah: 0, icon: "https://cdn-icons-png.flaticon.com/512/3659/3659692.png" },
  { nama: "Promkes & PM", jumlah: 1, icon: "https://cdn-icons-png.flaticon.com/512/1810/1810742.png" },
  { nama: "Home Visit", jumlah: 0, icon: "https://cdn-icons-png.flaticon.com/512/1946/1946433.png" },
  { nama: "Sehat", jumlah: 0, icon: "https://cdn-icons-png.flaticon.com/512/1057/1057094.png" },
  { nama: "Rapid Test", jumlah: 0, icon: "https://cdn-icons-png.flaticon.com/512/2913/2913461.png" },
]

function goToLayanan(nama) {
  const slug = encodeURIComponent(nama.toLowerCase().replace(/ /g, '-'))
  router.get(`/layanan/${slug}`)
}
</script>

<style scoped>
.card {
  border-radius: 0;
  transition: box-shadow 0.2s ease-in-out;
}
.card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.icon-neutral {
  filter: grayscale(100%);
}
</style>
