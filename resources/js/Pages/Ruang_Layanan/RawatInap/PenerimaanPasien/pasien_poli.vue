<template>
    <AppLayouts>
    <DataPasien title="RANAP PENERIMAAN" :rows>

    </DataPasien>
    </AppLayouts>

</template>

<script setup>
import { ref } from 'vue';
import AppLayouts from '../../../../Components/Layouts/AppLayouts.vue';
import DataPasien from '../../../../Components/Layouts/RuangLayanan/DataPasien.vue';

const rows = ref([
  {
    tanggal: '2025-07-30',
    nomor_mr: 'MR001',
    alamat: 'Kec. A - Desa B',
    nomor_bpjs: 'BPJS12345',
    poli: 'Umum',
  }
])
</script>