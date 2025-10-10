<template>
   <AppLayouts>
      <SuratKeterangan create-route='ruang-layanan-umum.create-surat-keterangan' :id-poli="idPoli"
         :id-pelayanan="idPelayanan" :suket-list="suketList" @suket-list-update="refreshData">

      </SuratKeterangan>
   </AppLayouts>
</template>
<script setup>
import { router, usePage } from '@inertiajs/vue3';
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';
import { computed } from 'vue';
import SuratKeterangan from '../../../Components/Layouts/RuangLayanan/SuratKeterangan.vue';
const page = usePage();

const idPoli = page.props.idPoli;
const idPelayanan = page.props.idPelayanan
const suketList = computed(()=>page.props.suketList);

function refreshData() {
   router.reload({
      only: ['suketList'],
      preserveState: true,
      preserveScroll: true
   })
}
</script>