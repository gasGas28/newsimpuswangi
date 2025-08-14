<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const layanan = [
  {
    nama: "Kesling",
    jumlah: 0,
    icon: "https://img.icons8.com/ios-filled/50/000000/toilet.png",
    link: "/mal-sehat/kesling",
  },
  {
    nama: "Kesga",
    jumlah: 0,
    icon: "https://img.icons8.com/ios-filled/50/000000/pregnant.png",
    link: "/mal-sehat/kesga",
  },
  {
    nama: "PTM",
    jumlah: 0,
    icon: "https://img.icons8.com/ios-filled/50/000000/heart-with-pulse--v1.png",
    link: "/mal-sehat/ptm",
  },
  {
    nama: "P3M",
    jumlah: 0,
    icon: "https://img.icons8.com/ios-filled/50/000000/stethoscope.png",
    link: "/mal-sehat/p3m",
  },
  {
    nama: "Yankes primer",
    jumlah: 1,
    icon: "https://img.icons8.com/ios-filled/50/000000/hospital-room.png",
    link: "/mal-sehat/yankes-primer",
  },
  {
    nama: "Farmasi",
    jumlah: 0,
    icon: "https://img.icons8.com/ios-filled/50/000000/pill.png",
    link: "/mal-sehat/farmasi",
  },
  {
    nama: "Biakes",
    jumlah: 0,
    icon: "https://img.icons8.com/ios-filled/50/000000/virus.png",
    link: "/mal-sehat/biakes",
  },
  {
    nama: "Promkes & PM",
    jumlah: 1,
    icon: "https://img.icons8.com/ios-filled/50/000000/megaphone.png",
    link: "/mal-sehat/promkes",
  },
  {
    nama: "Home Visit",
    jumlah: 0,
    icon: "https://img.icons8.com/ios-filled/50/000000/home.png",
    link: "/mal-sehat/home-visit",
  },
  {
    nama: "Sehat",
    jumlah: 0,
    icon: "https://img.icons8.com/ios-filled/50/000000/health-book.png",
    link: "/mal-sehat/sehat",
  },
  {
    nama: "Rapid Test",
    jumlah: 0,
    icon: "https://img.icons8.com/ios-filled/50/000000/test-tube.png",
    link: "/mal-sehat/rapid-test",
  },
]

function getCardColor(nama) {
  if (['Home Visit', 'Sehat', 'Rapid Test'].includes(nama)) {
    return '#f0f8ff' // biru sangat soft
  }
  return '#B0E0E6' // biru soft
}
</script>

<template>
  <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-white text-dark fw-bold fs-5 border-bottom py-3 px-4">
      MAL SEHAT
    </div>
    <div class="card-body py-4 px-4" style="background-color: #f8fbfd;">
      <div class="row g-4">
        <div
          class="col-12 col-sm-6 col-lg-4"
          v-for="item in layanan"
          :key="item.nama"
        >
          <div
            class="card h-100 border-0 shadow-sm rounded-4 cursor-pointer"
            :style="{ backgroundColor: getCardColor(item.nama), transition: 'transform 0.2s ease' }"
            @click="router.get(item.link)"
            @mouseover="$event.currentTarget.style.transform = 'scale(1.01)'"
            @mouseleave="$event.currentTarget.style.transform = 'scale(1)'"
          >
            <div class="card-body d-flex justify-content-between align-items-center px-3 py-3">
              <div>
                <div class="fw-semibold fs-6 mb-1 text-dark">{{ item.nama }}</div>
                <small class="text-muted">Hari ini {{ item.jumlah }} pasien</small>
              </div>
              <img
                :src="item.icon"
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
