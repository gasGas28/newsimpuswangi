<template>
  <AppLayouts>
    <SuratRujukanForm
      :dataPasien="props.DataPasien"
      :backRoute="props.backRoute"
      :saveRoute="props.saveRoute"
      :rumahSakitOptions="props.rumahSakitList || []"
      :poliOptions="props.poliList || []"
      :dokterOptions="props.dokterList || []"
    />
  </AppLayouts>
</template>

<script setup>
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue'
import SuratRujukanForm from '../../../Components/Layouts/RuangLayanan/KunjOnline/SuratRujukanForm.vue'
import { usePage } from '@inertiajs/vue3'
const { props } = usePage()
</script>
