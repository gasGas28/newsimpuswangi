<template>
  <AppLayouts>
    <FormAnamnesa
      title="Kunjungan Online"
      :backRoute="backRoute"
      :unitList="unitList"
      :rows="rows"
    />
  </AppLayouts>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import AppLayouts from '@/Components/Layouts/AppLayouts.vue'
import FormAnamnesa from '@/Components/Layouts/RuangLayanan/DataPasien.vue'

const { props } = usePage()

// rows harus berisi field persis seperti yang dipakai DataPasien.vue
const rows = Array.isArray(props?.DataPasien) ? props.DataPasien : []

// mapping unit aman (relasi bisa null)
const unitList = (Array.isArray(props?.DataUnit) ? props.DataUnit : [])
  .map(u => `[ ${u?.data_master_unit?.kategori ?? '-'} ] ${u?.nama_unit ?? '-'}`)

// nama route DETAIL (dipakai DataPasien.vue untuk route(backRoute, idLoket))
const backRoute = 'ruang-layanan.kunjungan-online.pelayanan'
</script>
