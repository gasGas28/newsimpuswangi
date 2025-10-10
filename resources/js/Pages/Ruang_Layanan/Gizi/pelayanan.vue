<template>
    <AppLayouts>
    <div class="card m-4">
    <div class="card-header bg-info d-flex justify-content-between p-3">
        <h1 class="fs-5">BP Gizi</h1>
        <Link class="btn btn-primary">Kembali</Link>
      </div>
    <div class="card-body">
    <DataPasien  @ubah-melayani="isMelayani = $event" :dataPasien="DataPasien"
          :dataAnamnesa="DataAnamnesa">
      <div class="card">
      <div class="card-header p-4 d-flex gap-4 align-items-center bg-info-subtle" >
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'subjective' }" @click.prevent="currentTab = 'subjective'">Subjective</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'objective' }" @click.prevent="currentTab = 'objective'">Objective</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'assesment' }" @click.prevent="currentTab = 'assesment'">Assessment</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'planning' }" @click.prevent="currentTab = 'planning'">Planning</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'status_pasien' }" @click.prevent="currentTab = 'status_pasien'">Status Pasien</a>
          <Link href="#" class="btn btn-success">Kirim RME v.1 ke SATU SEHAT</Link>
    </div>
    <div class="m-4 row gx-5">
    <FormPelayananSubjective v-if="currentTab === 'subjective'">
    </FormPelayananSubjective>
    <FormPelayananObjective v-if="currentTab === 'objective'"  :currrentSub=true halaman="gigi">
    </FormPelayananObjective>
    <FormPelayananAssesment v-if="currentTab === 'assesment'">
    </FormPelayananAssesment>
    <FormPelayananPlanning v-if="currentTab === 'planning'">
    </FormPelayananPlanning>
    <FormPelayananStatusPasien v-if="currentTab === 'status_pasien'">
    </FormPelayananStatusPasien>
    </div>
    </div>

    </DataPasien>
    </div>

   </div>
    </AppLayouts>
</template>

<script setup>
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';
import DataPasien from '../../../Components/Layouts/RuangLayanan/PelayananPasien/PelayananPasien.vue';
import FormPelayananSubjective from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananSubjective.vue';
import FormPelayananObjective from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananObjective.vue';
import FormPelayananAssesment from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananAssesment.vue';
import FormPelayananPlanning from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananPlanning.vue';
import FormPelayananStatusPasien from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananStatusPasien.vue';
import { ref } from 'vue';

const isMelayani = ref(false)

function handleMelayani(val) {
  isMelayani.value = val
}
const currentTab = ref('subjective')
</script>