<script setup>
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
  import { ref } from 'vue';
  import { router } from '@inertiajs/vue3';

  defineOptions({ layout: AppLayout });

  function kembali() {
    router.get('/mal-sehat');
  }

  const entries = ref([
    { nama: 'Konseling Sanitasi', jumlah: 0 },
    { nama: 'Pengukuran Kebugaran (Calon Jamaah Haji)', jumlah: 0 },
    { nama: 'Pengukuran Kebugaran Anak SD', jumlah: 0 },
  ]);

  function getIcon(nama) {
    if (nama.includes('Sanitasi')) {
      return 'https://img.icons8.com/ios-filled/50/000000/safety-collection-place.png';
    } else if (nama.includes('Kebugaran Anak')) {
      return 'https://img.icons8.com/ios-filled/50/000000/children.png';
    } else if (nama.includes('Kebugaran')) {
      return 'https://img.icons8.com/ios-filled/50/000000/heart-health.png';
    }
    return 'https://img.icons8.com/ios-filled/50/000000/info.png';
  }

  function goToLayanan(nama) {
    if (nama.includes('Sanitasi')) {
      router.get('/mal-sehat/kesling/konseling-sanitasi');
    } else if (nama.includes('Calon Jamaah Haji')) {
      router.get('/mal-sehat/kesling/pengukuran-kebugaran-haji');
    } else if (nama.includes('Anak SD')) {
      router.get('/mal-sehat/kesling/pengukuran-kebugaran-anak');
    }
  }
</script>

<template>
  <div class="card shadow-sm border-0">
    <div
      class="card-header bg-white text-dark fw-bold fs-5 border-bottom d-flex justify-content-between align-items-center"
    >
      <span>Kesling - {{ kategoriUnit }}</span>
      <button class="btn btn-sm btn-outline-secondary" @click="kembali">← Kembali</button>
    </div>

    <div class="card-body bg-light-subtle p-3">
      <div class="row g-3">
        <div v-for="item in entries" :key="item.nama" class="col-12 col-sm-6 col-lg-4">
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
                style="width: 30px; height: 30px"
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
