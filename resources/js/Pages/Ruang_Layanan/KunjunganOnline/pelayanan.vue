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
        <!-- sesuai reusable: dataPasien = Array, dataAnamnesa = Array -->
        <PelayananPasien
          :dataPasien="DataPasienArr"
          :dataAnamnesa="DataAnamnesaArr"
          @ubah-melayani="isMelayani = $event"
        >
          <div class="shadow-sm rounded-5">
            <NavigasiFormPemeriksaan
              :currentTab="currentTab"
              @change-currentTab="currentTab = $event"
            />

            <div class="m-4 pb-4 row gx-5">
              <!-- SUBJECTIVE -->
              <FormPelayananSubjective
                v-if="currentTab === 'subjective'"
                :idLoket="DataPasienArr[0]?.idLoket || ''"
                :dataAnamnesa="DataAnamnesa"
                :masterAlergi="MasterAlergiArr"
                :idPasien="DataPasienArr[0]?.pasienId || ''"
                routeName="ruang-layanan-kunjungan-online.setAnamnesa"
                @dataAnamnesa-update="reloadAnamnesa"
              />

              <!-- OBJECTIVE -->
              <FormPelayananObjective
                v-else-if="currentTab === 'objective'"
                :currentSub="true"
                halaman="umum"
                :dataKesadaran="DataKesadaranArr"
                :dataAnamnesa="DataAnamnesaArr"
                routeName="ruang-layanan-kunjungan-online.setAnamnesaObjective"
                @dataAnamnesa-update="reloadAnamnesa"
              />

              <!-- ASSESMENT -->
              <FormPelayananAssesment
                v-else-if="currentTab === 'assesment'"
                :diagnosaKasus="DiagnosaKasusArr"
                :dataPasien="DataPasienObj"
              />

              <!-- PLANNING -->
              <FormPelayananPlanning v-else-if="currentTab === 'planning'" />

              <!-- STATUS PASIEN -->
              <FormPelayananStatusPasien v-else-if="currentTab === 'status_pasien'" />
            </div>
          </div>
        </PelayananPasien>
      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue'
import PelayananPasien from '../../../Components/Layouts/RuangLayanan/KunjOnline/PelayananPasien/PelayananPasien.vue'
import FormPelayananSubjective from '../../../Components/Layouts/RuangLayanan/KunjOnline/PelayananPasien/FormPelayananSubjective.vue'
import FormPelayananObjective from '../../../Components/Layouts/RuangLayanan/KunjOnline/PelayananPasien/FormPelayananObjective.vue'
import FormPelayananAssesment from '../../../Components/Layouts/RuangLayanan/KunjOnline/PelayananPasien/FormPelayananAssesment.vue'
import FormPelayananPlanning from '../../../Components/Layouts/RuangLayanan/KunjOnline/PelayananPasien/FormPelayananPlanning.vue'
import FormPelayananStatusPasien from '../../../Components/Layouts/RuangLayanan/KunjOnline/PelayananPasien/FormPelayananStatusPasien.vue'
import NavigasiFormPemeriksaan from '../../../Components/Layouts/RuangLayanan/KunjOnline/PelayananPasien/NavigasiFormPemeriksaan.vue'

import { computed } from 'vue'
import { Link, usePage, router, useRemember } from '@inertiajs/vue3' // <- pakai useRemember
import { route } from 'ziggy-js'

// ===== State yang dipersist (tetap ada setelah F5) =====
const isMelayani = useRemember(false, 'kunjonline.isMelayani')
const currentTab = useRemember('subjective', 'kunjonline.currentTab')

// Ambil props dari Inertia
const { props } = usePage();
console.log('datakjabsas',props.DataAnamnesa)
const DataAnamnesa = computed(() => props.DataAnamnesa);

// --- DataPasien: sediakan Object & Array (reusable minta Array)
const RawPasien = computed(() => {
  if (Array.isArray(props.DataPasien)) return props.DataPasien[0] ?? null
  return props.DataPasien ?? null
})

// --- DataPasien: pastikan key yang dipakai header tersedia (UPPERCASE)
const DataPasienArr = computed(() => {
  const src = Array.isArray(props.DataPasien) ? props.DataPasien : []
  const r = src[0] || null

  if (!r) {
    return [{
      idLoket: '',
      NO_MR: '-', no_mr: '-', no_rm: '-',
      NAMA_LGKP: '-', nama_lgkp: '-', nama: '-',
      NIK: '-', nik: '-',
      ALAMAT: '', alamat: '',
      NO_RT: '', no_rt: '', rt: '',
      NO_RW: '', no_rw: '', rw: '',
      JENIS_KLMIN: null, jenis_klmin: null, jk: '-',
      umur: 0, umur_tahun: 0, umur_bulan: 0, umur_hari: 0,
      NAMA_KEL: '', nama_kel: '', kelurahan: '',
      NAMA_KEC: '', nama_kec: '', kecamatan: '',
      NAMA_KAB: '', nama_kab: '', kabupaten: '',
      NAMA_PROP: '', nama_prop: '', provinsi: '',
      nmPoli: 'Kunjungan Online',
      tglKunjungan: '', tgl_kunjungan: '',
      noKartu: null, no_bpjs: null, kdProvider: null, provider: null,
      pasienId: null,
    }]
  }

  return [{
    idLoket: r.idLoket,
    NO_MR: r.NO_MR, no_mr: r.no_mr ?? r.NO_MR, no_rm: r.no_rm ?? r.NO_MR,
    NAMA_LGKP: r.NAMA_LGKP, nama_lgkp: r.nama_lgkp ?? r.NAMA_LGKP, nama: r.nama ?? r.NAMA_LGKP,
    NIK: r.NIK, nik: r.nik ?? r.NIK,
    ALAMAT: r.ALAMAT, alamat: r.alamat ?? r.ALAMAT,
    NO_RT: r.NO_RT, no_rt: r.no_rt ?? r.NO_RT, rt: r.rt ?? r.NO_RT,
    NO_RW: r.NO_RW, no_rw: r.no_rw ?? r.NO_RW, rw: r.rw ?? r.NO_RW,
    JENIS_KLMIN: r.JENIS_KLMIN, jenis_klmin: r.jenis_klmin ?? r.JENIS_KLMIN, jk: r.jk ?? '-',
    umur: r.umur ?? 0, umur_tahun: r.umur_tahun ?? r.umur ?? 0,
    umur_bulan: r.umur_bulan ?? 0, umur_hari: r.umur_hari ?? 0,
    NAMA_KEL: r.NAMA_KEL, nama_kel: r.nama_kel ?? r.NAMA_KEL, kelurahan: r.kelurahan ?? r.NAMA_KEL,
    NAMA_KEC: r.NAMA_KEC, nama_kec: r.nama_kec ?? r.NAMA_KEC, kecamatan: r.kecamatan ?? r.NAMA_KEC,
    NAMA_KAB: r.NAMA_KAB, nama_kab: r.nama_kab ?? r.NAMA_KAB, kabupaten: r.kabupaten ?? r.NAMA_KAB,
    NAMA_PROP: r.NAMA_PROP, nama_prop: r.nama_prop ?? r.NAMA_PROP, provinsi: r.provinsi ?? r.NAMA_PROP,
    nmPoli: r.nmPoli ?? 'Kunjungan Online',
    tglKunjungan: r.tglKunjungan ?? '', tgl_kunjungan: r.tgl_kunjungan ?? r.tglKunjungan ?? '',
    noKartu: r.noKartu ?? null, no_bpjs: r.no_bpjs ?? r.noKartu ?? null,
    kdProvider: r.kdProvider ?? null, provider: r.provider ?? r.kdProvider ?? null,
    pasienId: r.pasienId ?? null, // <- dipakai Form Subjective
  }]
})

const DataPasienObj = computed(() => RawPasien.value ?? { idLoket: '', NO_MR: '-', NAMA_LGKP: '-' })

// --- DataAnamnesa: SELALU Array & punya struktur subjective.alergi
const DataAnamnesaArr = computed(() => {
  const src = props.DataAnamnesa
  if (Array.isArray(src) && src.length) return src
  return [{
    subjective: { alergi: [] },
    keluhan_utama: '',
    riwayat_penyakit: ''
  }]
})

// --- MasterAlergi
const MasterAlergiArr = computed(() => {
  const src = props.MasterAlergi
  if (Array.isArray(src) && src.length) return src
  return [{ kode: '', nama: '' }]
})

// --- Lain-lain
const DataKesadaranArr = computed(() => Array.isArray(props.DataKesadaran) ? props.DataKesadaran : [])
const DiagnosaKasusArr = computed(() => Array.isArray(props.DiagnosaKasus) ? props.DiagnosaKasus : [])
const DiagnosaMedisArr = computed(() => Array.isArray(props.DiagnosaMedis) ? props.DiagnosaMedis : [])

// ===== Refresh helper agar state & scroll tetap =====
function reloadAnamnesa() {
  router.reload({
    only: ['DataAnamnesa'],
    preserveState: true,
    preserveScroll: true,
  })
}


// Tombol kembali
const backRoute = route('ruang-layanan.kunjungan-online')
</script>

<style>
.tab-item{border:none;background:transparent;font-weight:500;border-radius:0;position:relative;transition:all .3s ease;color:#6c757d}
.tab-item:hover{color:#495057!important}
.tab-item.active{color:#0d6efd!important;font-weight:600}
.tab-indicator{position:absolute;bottom:-16px;left:0;height:3px;background-color:#0d6efd;transition:all .3s ease;z-index:1;border-radius:3px 3px 0 0}
</style>
