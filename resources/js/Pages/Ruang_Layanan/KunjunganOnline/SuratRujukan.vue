<template>
  <AppLayouts>
    <SuratRujukanComp
      :items="props.listRujukan ?? []"
      :backRoute="props.backRoute"
      :createRoute="props.createRoute"
      :dataPasien="props.DataPasien"
    />
  </AppLayouts>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'

// dari Pages/Ruang_Layanan/KunjunganOnline ke Components = ../../../
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue'
import SuratRujukanComp from '../../../Components/Layouts/RuangLayanan/KunjOnline/SuratRujukan.vue'

const { props } = usePage()
</script>
