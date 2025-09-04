<template>
  <AppLayouts>
    <div class="card m-4 rounded-4 rounded-bottom-0">
      <div
        class="card-header bg-info d-flex justify-content-between p-3 rounded-4 rounded-bottom-0"
        style="background: linear-gradient(135deg, #3b82f6, #10b981);"
      >
        <h1 class="fs-5 text-white">Kunjungan Online</h1>
        <Link :href="backRoute" class="btn bg-white bg-opacity-25 border border-1 btn-sm text-white">
          <i class="fas fa-arrow-left me-1 text-white"></i> Kembali
        </Link>
      </div>

      <div class="card-body">
        <!-- persis seperti halaman Umum: kirim OBJEK (bukan array) -->
        <PelayananPasien
          :dataPasien="DataPasien"
          :dataAnamnesa="DataAnamnesa"
          @ubah-melayani="isMelayani = $event"
        >
          <div class="shadow-sm rounded-5">
            <NavigasiFormPemeriksaan
              :currentTab="currentTab"
              @change-currentTab="currentTab = $event"
            />
            <div class="m-4 pb-4 row gx-5">
              <FormPelayananSubjective
                v-if="currentTab === 'subjective'"
                :idLoket="DataPasien?.idLoket"
                :dataAnamnesa="DataAnamnesa"
                :masterAlergi="MasterAlergi"
              />
              <FormPelayananObjective
                v-if="currentTab === 'objective'"
                :currrentSub="true"
                halaman="umum"
                :dataKesadaran="DataKesadaran"
                :dataAnamnesa="DataAnamnesa"
              />
              <FormPelayananAssesment
                v-if="currentTab === 'assesment'"
                :diagnosaKasus="DiagnosaKasus"
                :dataPasien="DataPasien"
              />
              <FormPelayananPlanning v-if="currentTab === 'planning'" />
              <FormPelayananStatusPasien v-if="currentTab === 'status_pasien'" />
            </div>
          </div>
        </PelayananPasien>
      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue'
import PelayananPasien from '../../../Components/Layouts/RuangLayanan/KunjOnline/PelayananPasien.vue'
import FormPelayananSubjective from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananSubjective.vue'
import FormPelayananObjective from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananObjective.vue'
import FormPelayananAssesment from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananAssesment.vue'
import FormPelayananPlanning from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananPlanning.vue'
import FormPelayananStatusPasien from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananStatusPasien.vue'
import NavigasiFormPemeriksaan from '../../../Components/Layouts/RuangLayanan/PelayananPasien/NavigasiFormPemeriksaan.vue'

import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const isMelayani = ref(false)
const currentTab = ref('subjective')

// ambil PASCAL CASE dari controller (samain dengan halaman Umum)
const { props } = usePage()
const DataPasien      = props.DataPasien?.[0] ?? null   // object tunggal
const DataAnamnesa    = props.DataAnamnesa ?? null
const DataKesadaran   = props.DataKesadaran ?? []
const DiagnosaKasus   = props.DiagnosaKasus ?? []
const MasterAlergi    = props.MasterAlergi ?? []
const DiagnosaMedis   = props.DiagnosaMedis ?? [] // kalau dibutuhkan form lain, sudah siap

// tombol kembali
const backRoute = route('ruang-layanan.kunjungan-online')
</script>

<style>
.tab-item{border:none;background:transparent;font-weight:500;border-radius:0;position:relative;transition:all .3s ease;color:#6c757d}
.tab-item:hover{color:#495057!important}
.tab-item.active{color:#0d6efd!important;font-weight:600}
.tab-indicator{position:absolute;bottom:-16px;left:0;height:3px;background-color:#0d6efd;transition:all .3s ease;z-index:1;border-radius:3px 3px 0 0}
</style>
