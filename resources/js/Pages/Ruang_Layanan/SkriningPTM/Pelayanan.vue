<template>
  <div class="p-2 rounded-4 mt-2">
    <!-- Komponen Data Pasien -->
    <DataPasienCard title="Pemeriksaan Skrining Penyakit Tidak Menukar" :DataPasien="props.DataPasien" :backRoute="backRoute" />
    <!-- Komponen Quick Actions -->
    <QuickActions
      :DataPasien="props.DataPasien"
      :sudahMulai="sudahMulai"
      @mulai-pemeriksaan="mulaiPemeriksaan"
    />
  </div>
  <!-- Modal Success -->
  <div
    v-if="showSuccessModal"
    class="custom-modal d-flex justify-content-center align-items-center"
  >
    <div class="modal-content-custom">
      <div class="success-icon">
        <i class="bi bi-check-circle-fill"></i>
      </div>
      <h5 class="fw-bold mt-3">Berhasil</h5>
      <p class="text-muted mb-0">Pemeriksaan dimulai</p>
    </div>
  </div>
  <!-- Form Pemeriksaan -->
  <div v-if="showFormPemeriksaan" class="bg-white rounded-4 shadow-sm p-4 mt-3">
    <FormPemeriksaan :DataPasien="props.DataPasien" />
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';
import FormPemeriksaan from '../../../Components/Layouts/RuangLayanan/SkriningPTM/TabPemeriksaan.vue';
import DataPasienCard from '../../../Components/Layouts/RuangLayanan/SkriningPTM/DataPasien.vue';
import QuickActions from '../../../Components/Layouts/RuangLayanan/SkriningPTM/QuickActions.vue';

defineOptions({ layout: AppLayouts });

const props = defineProps({
  DataPasien: Array,
  pelayanan: Object,
});

const backRoute = 'ruang-layanan.ptm';
const showSuccessModal = ref(false);
const showFormPemeriksaan = ref(false);
const sudahMulai = ref(false);

onMounted(() => {
  if (props.DataPasien[0].sudahDilayani == 2) {
    sudahMulai.value = true;
    showFormPemeriksaan.value = true;
  }
});

const mulaiPemeriksaan = () => {
  sudahMulai.value = true;

  router.post(
    route('pelayanan.update-status'),
    {
      idpelayanan: props.DataPasien[0].idpelayanan,
      status: 2,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        showSuccessModal.value = true;
        setTimeout(() => {
          showSuccessModal.value = false;
          showFormPemeriksaan.value = true;
        }, 1500);
      },
    }
  );
};
</script>

<style scoped>
.custom-modal {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  z-index: 9999;
  backdrop-filter: blur(3px);
}

.modal-content-custom {
  background: white;
  padding: 30px;
  border-radius: 20px;
  text-align: center;
  width: 320px;
  animation: popup 0.3s ease;
}

.success-icon {
  font-size: 70px;
  color: #22c55e;
}

@keyframes popup {
  from {
    transform: scale(0.8);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}
</style>