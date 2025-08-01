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
</script>

<template>
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white text-dark fw-bold fs-5 border-bottom">
      MAL SEHAT
    </div>
    <div class="card-body bg-light-subtle p-3">
      <div class="row g-3">
        <div
          class="col-12 col-sm-6 col-lg-4"
          v-for="item in layanan"
          :key="item.nama"
        >
          <div
            class="card h-100 cursor-pointer border-0 shadow-sm hover-shadow"
            @click="router.get(item.link)"
            :class="[
              ['Home Visit', 'Sehat', 'Rapid Test'].includes(item.nama)
                ? 'bg-info-subtle'
                : 'bg-white',
              'rounded-0'
            ]"
          >
            <div class="card-body d-flex justify-content-between align-items-center p-2">
              <div class="text-dark">
                <h6 class="mb-1">{{ item.nama }}</h6>
                <small>Hari ini {{ item.jumlah }} pasien</small>
              </div>
              <img
                :src="item.icon"
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
