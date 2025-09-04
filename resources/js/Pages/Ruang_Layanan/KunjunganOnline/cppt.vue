<template>
  <AppLayouts>
    <div class="card m-4 rounded-4 rounded-bottom-0">
      <div class="card-header bg-info d-flex justify-content-between p-3 rounded-4 rounded-bottom-0"
           style="background: linear-gradient(135deg, #3b82f6, #10b981);">
        <h1 class="fs-5 text-white">CATATAN PERKEMBANGAN PASIEN TERINTEGRASI (CPPT)</h1>
        <Link :href="backRoute" class="btn bg-white bg-opacity-25 border border-1 btn-sm text-white">
          <i class="fas fa-arrow-left me-1 text-white"></i> Kembali
        </Link>
      </div>

      <div class="card-body">
        <RLKunjOnlineCPPT :dataPasien="DataPasien" :items="Items" />
      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayouts from '@/Components/Layouts/AppLayouts.vue'
import RLKunjOnlineCPPT from '@/Components/Layouts/RuangLayanan/KunjOnline/CPPT.vue'

defineProps({
  DataPasien: { type: Object, required: true },
  Items: { type: Array, required: true },
  backRoute: { type: String, required: true },
})
</script>
