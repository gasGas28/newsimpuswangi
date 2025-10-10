<template>
  <AppLayouts>
    <div class="card m-4  rounded-4 rounded-bottom-0">
      <div class="card-header bg-info d-flex justify-content-between p-3  rounded-4 rounded-bottom-0"
        style="background: linear-gradient(135deg, #3b82f6, #10b981);">
        <h1 class="fs-5 text-white">BP GIGI</h1>
        <Link :href="backRoute" class="btn bg-white bg-opacity-25 border border-1 btn-sm text-white">
        <i class="fas fa-arrow-left me-1 text-white"></i> Kembali
        </Link>
      </div>
      <div class="card-body">
        <PelayananPasien @ubah-melayani="isMelayani = $event" :dataPasien="DataPasien" :dataAnamnesa="DataAnamnesa">
          <div class="shadow-sm rounded-5">
            <NavigasiFormPemeriksaan :currentTab="currentTab" @change-currentTab="currentTab = $event">
            </NavigasiFormPemeriksaan>
            <div class="m-4 pb-4 row gx-5">
              <FormPelayananSubjective v-if="currentTab === 'subjective'" :idLoket="DataPasien.idLoket"
                :dataAnamnesa="DataAnamnesa" :masterAlergi="MasterAlergi" :routeName="routeNameFormSubjective"
                :idPasien="DataPasien.ID" :AlergiPasien="AlergiPasien" @dataAnamnesa-update="refreshDataAnamnesa">
              </FormPelayananSubjective>
              <FormPelayananObjective v-if="currentTab === 'objective'" :currrentSub=true halaman="gigi"
                :dataKesadaran="DataKesadaran" :dataAnamnesa="DataAnamnesa" :routeName="routeNameFormObjective"
                :statusPasien="statusPasien" :tenaga-medis-askep="TenagaMedisAskep">
                <template #status_pasien>
                  <div>
                    <label for="" class="fw-bold">Status</label>
                    <select class="form-control my-3" v-model="statusPasien">
                      <option value="" selected>-- Pilih --</option>
                      <option value="anak sekolah">ANAK SEKOLAH</option>
                      <option value="apras">APRAS</option>
                      <option value="bumil">BUMIL</option>
                      <option value="umum">UMUM</option>
                    </select>
                  </div>
                </template>
              </FormPelayananObjective>
              <FormPelayananAssesment v-if="currentTab === 'assesment'" :diagnosaKasus="DiagnosaKasus"
                :dataPasien="DataPasien" routeDiagnosaMedis="ruang-layanan-gigi.diagnosa-medis"
                :AlergiPasien="AlergiPasien" :simpusDataDiagnosaMedis="SimpusDataDiagnosaMedis"
                @dataAnamnesa-update="refreshDataAnamnesa" :diagnosa-keperawatan="DiagnosaKeperawatan">
              </FormPelayananAssesment>
              <FormPelayananPlanning v-if="currentTab === 'planning'" :dataTindakan="DataTindakan"
                :dataPasien="DataPasien" :simpusTindakan="SimpusTindakan" :keterangangigi="keterangangigi"
                routePlanningTindakan="ruang-layanan-gigi.set-PlanningTindakan" :simpusResepObat="SimpusResepObat"
                :MasterObat="MasterObat" route-resep-obat="ruang-layanan-gigi.set-PlanningPengobatan" route-detail-resep-obat="'ruang-layanan-gigi.set-PlanningPengobatanDetail'">
                <div class="mb-3 row">
                  <div class="col-sm-6">
                    <div class="border p-3">
                      <div v-for="(baris, index) in layoutKursi" :key="index"
                        class="border border-dark d-flex justify-content-center mb-2 flex-wrap">
                        <div v-for="(kursi, i) in baris" :key="i"
                          class="d-flex flex-column align-items-center my-1 fw-bold" style="width: 40px">
                          <input type="checkbox" class="form-check-input mb-1" :id="'kursi-' + kursi.nomor"
                            v-model="kursi.terpilih" />
                          <label :for="'kursi-' + kursi.nomor" style="font-size: 12px">{{
                            kursi.nomor
                          }}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="mb-3 row align-items-center">
                  <label class="col-sm-2 col-form-label fw-bold">Keterangan Gigi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control bg-light" :value="keterangangigi" disabled />
                  </div>
                </div>
              </FormPelayananPlanning>
              <FormPelayananStatusPasien v-if="currentTab === 'status_pasien'" :statusPulang="statusPulang">
              </FormPelayananStatusPasien>
            </div>
          </div>
        </PelayananPasien>
      </div>
    </div>
  </AppLayouts>
</template>
<script setup>
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';
import PelayananPasien from '../../../Components/Layouts/RuangLayanan/PelayananPasien/PelayananPasien.vue';
import FormPelayananSubjective from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananSubjective.vue';
import FormPelayananObjective from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananObjective.vue';
import FormPelayananAssesment from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananAssesment.vue';
import FormPelayananPlanning from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananPlanning.vue';
import FormPelayananStatusPasien from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananStatusPasien.vue';
import NavigasiFormPemeriksaan from '../../../Components/Layouts/RuangLayanan/PelayananPasien/NavigasiFormPemeriksaan.vue';
import { ref, computed, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const isMelayani = ref(false);
const keterangangigi = ref('');
const currentTab = ref('subjective');
const routeNameFormSubjective = 'ruang-layanan-gigi.setAnamnesaSubjective';
const routeNameFormObjective = 'ruang-layanan-gigi.setAnamnesaObjective';

const page = usePage();
const DataAnamnesa = computed(() => page.props.DataAnamnesa);
const DataPasien = computed(() => page.props.DataPasien[0]);
const DataKesadaran = computed(() => page.props.DataKesadaran);
const DiagnosaKasus = computed(() => page.props.DiagnosaKasus);
const MasterAlergi = computed(() => page.props.MasterAlergi);
const DataTindakan = computed(() => page.props.DataTindakan);
const SimpusDataDiagnosaMedis = computed(() => page.props.SimpusDataDiagnosa);
const SimpusTindakan = computed(() => page.props.SimpusTindakan);
const AlergiPasien = computed(() => page.props.AlergiPasien);
const statusPulang = computed(() => page.props.StatusPulang);
const SimpusResepObat = computed(() => page.props.SimpusResepObat);
const MasterObat = computed(() => page.props.MasterObat);
const TenagaMedisAskep = computed(() => page.props.TenagaMedisAskep);
const DiagnosaKeperawatan = computed(() => page.props.DiagnosaKeperawatan);
console.log('Data RUjuk', page.props.DataRujuk);

console.log(' SimpusResepObat', SimpusResepObat.value);
const layoutKursi = ref([
  // Baris 1
  [
    { nomor: 18, terpilih: false },
    { nomor: 17, terpilih: false },
    { nomor: 16, terpilih: false },
    { nomor: 15, terpilih: false },
    { nomor: 14, terpilih: false },
    { nomor: 13, terpilih: false },
    { nomor: 12, terpilih: false },
    { nomor: 11, terpilih: false },
    { nomor: 21, terpilih: false },
    { nomor: 22, terpilih: false },
    { nomor: 23, terpilih: false },
    { nomor: 24, terpilih: false },
    { nomor: 25, terpilih: false },
    { nomor: 26, terpilih: false },
    { nomor: 27, terpilih: false },
    { nomor: 28, terpilih: false },
  ],
  // Baris 2
  [
    { nomor: 55, terpilih: false },
    { nomor: 54, terpilih: false },
    { nomor: 53, terpilih: false },
    { nomor: 52, terpilih: false },
    { nomor: 51, terpilih: false },
    { nomor: 61, terpilih: false },
    { nomor: 62, terpilih: false },
    { nomor: 63, terpilih: false },
    { nomor: 64, terpilih: false },
    { nomor: 65, terpilih: false },
  ],
  // Baris 3
  [
    { nomor: 85, terpilih: false },
    { nomor: 84, terpilih: false },
    { nomor: 83, terpilih: false },
    { nomor: 82, terpilih: false },
    { nomor: 81, terpilih: false },
    { nomor: 71, terpilih: false },
    { nomor: 72, terpilih: false },
    { nomor: 73, terpilih: false },
    { nomor: 74, terpilih: false },
    { nomor: 75, terpilih: false },
  ],
  // Baris 4
  [
    { nomor: 48, terpilih: false },
    { nomor: 47, terpilih: false },
    { nomor: 46, terpilih: false },
    { nomor: 45, terpilih: false },
    { nomor: 44, terpilih: false },
    { nomor: 43, terpilih: false },
    { nomor: 42, terpilih: false },
    { nomor: 41, terpilih: false },
    { nomor: 31, terpilih: false },
    { nomor: 32, terpilih: false },
    { nomor: 33, terpilih: false },
    { nomor: 34, terpilih: false },
    { nomor: 35, terpilih: false },
    { nomor: 36, terpilih: false },
    { nomor: 37, terpilih: false },
    { nomor: 38, terpilih: false },
  ],
]);

const gigiTerpilih = computed(() => {
  return layoutKursi.value
    .flat()
    .filter(item => item.terpilih)
    .map(item => item.nomor)
    .join(', ')
})
watch(gigiTerpilih, (baru) => {
  keterangangigi.value = baru,
    console.log('dipilih', baru)
});
const refreshDataAnamnesa = () => {
  router.reload({
    only: ['DataAnamnesa', 'SimpusDataDiagnosaMedis', 'SimpusTindakan', 'AlergiPasien'],
    preserveState: true,
    preserveScroll: true
  })
}

</script>

<style>
.tab-item {
  border: none;
  background: transparent;
  font-weight: 500;
  border-radius: 0;
  position: relative;
  transition: all 0.3s ease;
  color: #6c757d;
}

.tab-item:hover {
  color: #495057 !important;
}

.tab-item.active {
  color: #0d6efd !important;
  font-weight: 600;
}

.tab-indicator {
  position: absolute;
  bottom: -16px;
  left: 0;
  height: 3px;
  background-color: #0d6efd;
  transition: all 0.3s ease;
  z-index: 1;
  border-radius: 3px 3px 0 0;
}
</style>