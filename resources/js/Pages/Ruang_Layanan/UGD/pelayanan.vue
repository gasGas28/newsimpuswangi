<template>
  <AppLayouts>
    <div class="card m-4  rounded-4 rounded-bottom-0">
      <div class="card-header bg-info d-flex justify-content-between p-3  rounded-4 rounded-bottom-0"
        style="background: linear-gradient(135deg, #3b82f6, #10b981);">
        <h1 class="fs-5 text-white">BP UGD</h1>
        <Link :href="backRoute" class="btn bg-white bg-opacity-25 border border-1 btn-sm text-white">
        <i class="fas fa-arrow-left me-1 text-white"></i> Kembali
        </Link>
      </div>
      <div class="card-body">
        <PelayananPasien :isMelayani="isMelayani" @ubah-melayani="handleMelayani">
          <div class="shadow-sm rounded-5">
            <NavigasiFormPemeriksaan :currentTab="currentTab" @change-currentTab="currentTab = $event">
            </NavigasiFormPemeriksaan>
            <div class="m-4 pb-4 row gx-5">
              <FormPelayananSubjective v-if="currentTab === 'subjective'">
              </FormPelayananSubjective>
              <FormPelayananObjective v-if="currentTab === 'objective'" :currrentSub=true halaman="umum">
              </FormPelayananObjective>
              <FormPelayananAssesment v-if="currentTab === 'assesment'">
              </FormPelayananAssesment>
              <FormPelayananPlanning v-if="currentTab === 'planning'">
              </FormPelayananPlanning>
              <FormPelayananStatusPasien v-if="currentTab === 'status_pasien'">
              </FormPelayananStatusPasien>
            </div>
          </div>
        </PelayananPasien>
      </div>
    </div>
  </AppLayouts>
</template>
<script setup>
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';
import PelayananPasien from '../../../Components/Layouts/RuangLayanan/PelayananPasien/PelayananPasien.vue';
import FormPelayananSubjective from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananSubjective.vue';
import FormPelayananObjective from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananObjective.vue';
import FormPelayananAssesment from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananAssesment.vue';
import FormPelayananPlanning from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananPlanning.vue';
import FormPelayananStatusPasien from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananStatusPasien.vue';
import NavigasiFormPemeriksaan from '../../../Components/Layouts/RuangLayanan/PelayananPasien/NavigasiFormPemeriksaan.vue';
import { ref } from 'vue';

const isMelayani = ref(false);
const currentTab = ref('subjective');

function handleMelayani(val) {
  isMelayani.value = val
}
</script>

<style>
.tab-item {
  border: none;
  background: transparent;
  font-weight: 500;
  border-radius: 0;
  position: relative;
  transition: all 0.3s ease;
  color: #6c757d;
}

.tab-item:hover {
  color: #495057 !important;
}

.tab-item.active {
  color: #0d6efd !important;
  font-weight: 600;
}

.tab-indicator {
  position: absolute;
  bottom: -16px;
  left: 0;
  height: 3px;
  background-color: #0d6efd;
  transition: all 0.3s ease;
  z-index: 1;
  border-radius: 3px 3px 0 0;
}
</style>