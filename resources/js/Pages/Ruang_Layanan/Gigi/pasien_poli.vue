<template>
    <AppLayouts>
      <FormAnamnesa title="Gigi" :backRoute :unitList :rows>

      </FormAnamnesa>
    </AppLayouts>
</template>
<script setup>
import { ref } from 'vue'
import { route } from 'ziggy-js';
import FormAnamnesa from '../../../Components/Layouts/RuangLayanan/DataPasien.vue';
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';

const unitList = [
  '[ PUSKESMAS ] TEGALLDIMO', 
  '[ PUSKESMAS ] KALITENGAH',
  '[ PUSKESMAS ] KARANGBINANGUN'
]

const rows = ref([
  {
    tanggal: '2025-07-30',
    nomor_mr: 'MR001',
    alamat: 'Kec. A - Desa B',
    nomor_bpjs: 'BPJS12345',
    poli: 'Umum',
    linkTo: route('ruang-layanan-gigi.pelayanan')
  }
])
</script>