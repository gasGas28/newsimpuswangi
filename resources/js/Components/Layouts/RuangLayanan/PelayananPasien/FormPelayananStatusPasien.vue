<template>
  <div class="container my-2">
    <div class="text-decoration-underline fw-bold t mb-4">
      <p class="bg-warning d-inline-block px-2 rounded">Rujukan</p>
    </div>
    <!-- FORM -->
    <form @submit.prevent="submit_rujukan">
      <div class="row mb-3">
        <label class="col-sm-2 col-form-label fw-bold">Status Pulang</label>
        <div class="col-sm-4">
          <select class="form-select" v-model="form.status_pulang">
            <option v-for="item in statusPulang" :key="item.kdStatusPulang" :value="item.kdStatusPulang">
              {{ item.nmStatusPulang }}
            </option>
          </select>
        </div>
      </div>

      <div class="row mb-3" v-if="statusPulangLabel === 'Rujuk Internal'">
        <label class="col-sm-2 col-form-label fw-bold">Poli Rujuk Internal</label>
        <div class="col-sm-4">
          <select class="form-select" v-model="form.poli_rujuk_internal">
            <option value="" selected>-- Pilih --</option>
            <option v-for="value in props.poliRujukInternal" :value="value.kdPoli">{{ value.nmPoli }}</option>
          </select>
        </div>
      </div>

      <div class="row mb-3"
        v-if="statusPulangLabel === 'Rujuk Lanjut (Rujuk Vertikal Pcare)' || statusPulangLabel === 'Rujuk Lanjut Rumah Sakit (Bukan BPJS)'">
        <label class="col-sm-2 col-form-label fw-bold">PPK Rujukan</label>
        <div class="col-sm-4">
          <input type="text" class="form-control">
        </div>
      </div>
      <div class="row mb-3" v-if="statusPulangLabel === 'Rujuk Lanjut Rumah Sakit (Bukan BPJS)'">
        <label class="col-sm-2 col-form-label fw-bold">Nama Poli</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" disabled>
        </div>
      </div>
      <div class="row mb-3" v-if="statusPulangLabel === 'Rujuk Lanjut Rumah Sakit (Bukan BPJS)'">
        <label class="col-sm-2 col-form-label fw-bold">Nama Dokter</label>
        <div class="col-sm-4">
          <select class="form-select">
            <option selected>- pilih -</option>
            <option>dr. Agus</option>
            <option>dr. Sari</option>
          </select>
        </div>
      </div>

      <div class="row mb-3" v-if="statusPulangLabel === 'Rujuk Lanjut (Rujuk Vertikal Pcare)'">
        <label class="col-sm-2 col-form-label fw-bold">Spesialis/Subspesialis</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" disabled>
        </div>
      </div>

      <div class="row mb-3"
        v-if="statusPulangLabel === 'Rujuk Lanjut (Rujuk Vertikal Pcare)' || statusPulangLabel === 'Rujuk Lanjut Rumah Sakit (Bukan BPJS)'">
        <label class="col-sm-2 col-form-label fw-bold">Catatan</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" disabled>
        </div>
      </div>

      <div class="row mb-3"
        v-if="statusPulangLabel === 'Rujuk Lanjut (Rujuk Vertikal Pcare)' || statusPulangLabel === 'Rujuk Lanjut Rumah Sakit (Bukan BPJS)'">
        <label class="col-sm-2 col-form-label fw-bold">Tgl Rencana Berkunjung</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" disabled>
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-2 col-form-label fw-bold">Tenaga Medis</label>
        <div class="col-sm-4">
          <select class="form-select" v-model="form.tenaga_medis">
            <option selected>- pilih -</option>
            <option v-for="item in TenagaMedisAskep">{{ item.nmDokter }}</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </div>
    </form>

    <!-- TABLE -->
    <div class="table-responsive mt-4">
      <table class="table table-bordered table-sm align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>No</th>
            <th>Asal Poli</th>
            <th>Keterangan</th>
            <th>Poli Tujuan</th>
            <th>Tenaga Medis</th>
            <th>Created By</th>
            <th>Mulai melayani</th>
            <th>Selesai melayani</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody v-if="loading">
          <tr>
            <td colspan="9" class="text-center">Memuat data...</td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr v-for="(item, index) in Pelayanan" :key="item.idPelayanan">
            <td>{{ index + 1 }}</td>
            <td><span class="badge bg-primary bg-opacity-75"> {{ item.simpus_poli.nmPoli }}</span></td>
            <td>{{ item.status_pulang?.nmStatusPulang ?? '' }}</td>
            <td>{{ item.tujuanPoli ?? '' }}</td>
            <td>{{ item.tenagaMedis ?? '' }}</td>
            <td>{{ item.createdBy ?? '' }}</td>
            <td>{{ item.tglPelayanan ?? '' }}</td>
            <td>{{ item.kdStatusPulang ?? '' }}</td>
            <td>
              <template v-if="item.kdStatusPulang === '4' || item.kdStatusPulang === '6'">
                <button class="btn btn-warning btn-xs" @click="batalRujukLanjut(item.loketId, item.idPelayanan)">
                  Batal Rujuk Lanjut
                </button>
              </template>
              <template v-else>
                <template v-if="(item.pelIdSebelum == null || item.pelIdSebelum == '0') && item.kdStatusPulang != '3'">
                  <button class="btn btn-outline btn-sm" disabled>
                    Poli Awal
                  </button>
                </template>

                <template v-else-if="item.kdStatusPulang == '3'">
                  <button class="btn btn-sm btn-warning" @click="batal_berobat_jalan">
                    Batal Berobat Jalan
                  </button>
                </template>
                <template v-else>
                  <template v-if="item.idpelayanan === cekAkhirPelayanan.idpelayanan">
                    <button @click="hapusRujuk(route('ruang-layanan.hapusRujuk', item.idpelayanan))" type="button"
                      class="btn btn-danger btn-sm">
                      Batal Rujuk
                    </button>
                  </template>
                  <template v-else>
                    <button class="btn btn-outline btn-sm" disabled>
                      Batalkan
                    </button>
                  </template>
                </template>

              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { route } from 'ziggy-js';
const props = defineProps({
  statusPulang: Array,
  DataRujuk: Array,
  TenagaMedisAskep: Object,
  idPelayanan: String,
  idLoket: String,
  poliRujukInternal: Array
});
const emit = defineEmits(['dataRujuk-update'])
const DataRujuk = props.DataRujuk ?? '';
const TenagaMedisAskep = props.TenagaMedisAskep ?? '';
const Pelayanan = ref(null);
const loading = ref(true)
const cekAkhirPelayanan = ref(null);
console.log('data poli rujuk internal', props.idPelayanan);

onMounted(() => {
  fetchPelayanan(route('ruang-layanan.ambilPelayanan', { idLoket: props.idLoket, idPelayanan: props.idPelayanan }));
});
const form = useForm({
  status_pulang: '',
  tenaga_medis: '',
  poli_rujuk_internal: ''
});

const statusPulangLabel = computed(() => {
  const found = props.statusPulang.find(
    (item) => item.kdStatusPulang === form.status_pulang
  )
  return found ? found.nmStatusPulang : ''
})

function submit_rujukan() {
  form.post(route('ruang-layanan.simpanRujukan', {
    idLoket: props.idLoket,
    idPelayanan: props.idPelayanan
  }), {
    preserveScroll: true,
    onSuccess: () => {
      alert("Rujuk tersimpan");
      loading.value = true
      fetchPelayanan(route('ruang-layanan.ambilPelayanan', { idLoket: props.idLoket, idPelayanan: props.idPelayanan }))
    },
  });
}

function fetchPelayanan(url) {
  console.log(route('ruang-layanan.ambilPelayanan', { idLoket: props.idLoket, idPelayanan: props.idPelayanan }))
  if (!url) return;

  const relativeUrl = url.startsWith('http')
    ? new URL(url).pathname + new URL(url).search
    : url;

  axios.get(relativeUrl)
    .then(res => {
      Pelayanan.value = res.data.pelayanan;
      cekAkhirPelayanan.value = res.data.cekAkhirPelayanan
      console.log('Hasil fetch pelayanan :', 'hao');
    })
    .catch(err => console.error(err))
    .finally(() => {
      loading.value = false;
      console.log('selesai', loading.value)
    });
}

function hapusRujuk(url) {
  if (!url) return;

  const relativeUrl = url.startsWith('http')
    ? new URL(url).pathname + new URL(url).search
    : url;

  axios.delete(relativeUrl)
    .then(res => {
    })
    .catch(err => console.error(err))
    .finally(() => {
      loading.value = true
      fetchPelayanan(route('ruang-layanan.ambilPelayanan', { idLoket: props.idLoket, idPelayanan: props.idPelayanan }))
    });
}

function batal_berobat_jalan() {
  console.log('idLoket:', props.idLoket, 'idPelayanan:', props.idPelayanan);

  axios.get(
    route('ruang-layanan.batal-berobat-jalan', {
      idLoket: props.idLoket,
      idpelayanan: props.idPelayanan
    })
  )
    .then(res => {
      console.log('Response:', res.data);
      alert(res.data.message);
    })
    .catch(err => {
      console.error('Error detail:', err.response?.data || err.message);
      alert('Terjadi kesalahan');
    }).finally(() => {
      loading.value = true
      fetchPelayanan(route('ruang-layanan.ambilPelayanan', { idLoket: props.idLoket, idPelayanan: props.idPelayanan }))
    });;
}


</script>