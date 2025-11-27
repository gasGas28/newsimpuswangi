  <template>
    <div class="card m-4  shadow-sm border-0  rounded-4 rounded-bottom-0">
      <!-- Header -->
      <div
        class="card-header p-4 text-black d-flex justify-content-between align-items-center rounded-4 rounded-bottom-0"
        style="background: linear-gradient(135deg, #3b82f6, #10b981);">
        <h1 class="m-0 fs-4 text-white">{{ title }}</h1>
        <Link :href="backRoute" class="btn bg-white bg-opacity-25 border border-1 btn-sm text-white">
        <i class="fas fa-arrow-left me-1 text-white"></i> Kembali
        </Link>
      </div>

      <!-- Filter Form -->
      <div class="card mt-4 mx-4 shadow-sm">
        <div class="card-header r bg-transparent border-0 py-3"
          style="background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
          <h5 class="m-0 fw-semibold text-slate-700 d-flex align-items-center">
            <span class="rounded-5 bg-primary p-2 me-3">
              <i class="fas fa-sliders text-white"></i>
            </span>
            Filter Data
          </h5>
        </div>

        <div class="card-body ">
          <form @submit.prevent="filterData" class="row gx-3 gy-2 align-items-end">
            <!-- Tanggal Kunjungan -->
            <div class="col-md-5">
              <label class="form-label fw-semibold">Tanggal Kunjungan</label>
              <input type="date" class="form-control" v-model="tanggal_kunjungan">
            </div>

            <!-- Kategori Unit -->
            <div class="col-md-5">
              <label class="form-label fw-semibold">Kategori Unit</label>
              <select class="form-select" v-model="selectedUnit">
                <option value="">Pilih Unit</option>
                <option v-for="(unit, index) in unitList" :key="unit.id" :value="unit.id">{{ unit.data }}</option>
              </select>
            </div>

            <!-- Tombol Tampilkan -->
            <div class="col-md-2 d-flex align-items-end ">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-search me-1"></i> Tampilkan Data
              </button>
            </div>
          </form>
        </div>
      </div>
      <!-- Data Table -->
      <div class="card mt-4 m-4 shadow-sm">
        <div class="card-header my-2 border-0 bg-white d-flex justify-content-between align-items-center ">
          <div>
            <label class="me-2">Show</label>
            <select class="form-select d-inline w-auto" style="width: 80px">
              <option>10</option>
              <option>25</option>
            </select>
            <span class="ms-2">entries</span>
          </div>
          <form>
            <div class="input-group">
              <input type="search" class="form-control" placeholder="Search..." />
              <i class="bi bi-search input-group-text bg-white"></i>

            </div>
          </form>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center">
              <thead class="table align-middle">
                <tr>
                  <th class="text-center">NO</th>
                  <th>TANGGAL NO. URUT BPJS</th>
                  <th class="text-center">NO. MR</th>
                  <th>NAMA NIK</th>
                  <th>ALAMAT KECAMATAN-DESA</th>
                  <th>NO. BPJS NO. KUNJUNGAN</th>
                  <th>POLI</th>
                  <th class="text-center">AKSI</th>
                </tr>
              </thead>
              <tbody v-if="loading">
                <tr>
                  <td colspan="9" class="text-center">Memuat data...</td>
                </tr>
              </tbody>
              <tbody class="align-middle" v-else>
                <tr v-for="(item, index) in ListDataPasien" :key="index" class="text-center">
                  <td class="text-center">{{ index + 1 }}</td>
                  <td>{{ item.tglKunjungan }}</td>
                  <td class="text-center">{{ item.NO_MR }}</td>
                  <td>{{ item.NAMA_LGKP }} <br></br>{{ item.NIK }}</td>
                  <td class="" style="max-width: 200px">
                    {{ item.alamat }}
                    {{ 'RT ' + item.no_rt }}
                    {{ 'RW ' + item.no_rw }}
                    {{ item.nama_kel }}
                    {{ item.nama_kec }}
                    {{ item.nama_kab }}
                    {{ item.nama_prop }}</td>
                  <td></td>
                  <td class="align-middle text-center">
                    <div class="rounded-3 p-3"
                      style="background: linear-gradient(135deg, #f9fafb, #eef2ff); border: 1px solid #e5e7eb;">
                      <div class="fw-bold text-primary mb-2" style="font-size: 0.95rem;">
                        <i class="bi bi-building me-1"></i>{{ item.nmPoli }}
                      </div>
                      <span class="badge border-0 px-4 py-2 text-white shadow-sm bg-success">
                        {{ item.kunjSakitPel === 'true' ? 'SAKIT' : 'SEHAT' }}
                      </span>
                    </div>
                  </td>
                  <td class="text-center">
                    <Link v-if="title === 'RANAP PERAWATAN'" :href="route('ruang-layanan.rawat-inap.perawatan.detail', item.idpelayanan)" class="btn">
                      <span class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Detail Keperawatan
                      </span>
                    </Link>
                    <button v-else-if="title === 'RANAP PENERIMAAN'" class="btn btn-sm btn-primary" @click="pilihKamar(item.idpelayanan)">
                      Pilih Kamar
                    </button>
                    <button v-else-if="title === 'RANAP KELUAR'" class="btn btn-sm btn-danger" @click="pasienKeluar(item.idpelayanan)">
                      Pasien Keluar
                    </button>
                    <Link v-else :href="route(backRoute, [item.idLoket, item.kdPoli, item.idpelayanan])" class="btn">
                    <span class="btn btn-sm btn-success" v-if="item.sudahDilayani == 1">
                      <i class="fas fa-check-circle "></i> Selesai Dilayani
                    </span>
                    <span class="btn btn-sm btn-danger" v-else-if="item.sudahDilayani == 0">
                      <i class="fas fa-times-circle "></i> Belum Dilayani
                    </span>
                    <span class="btn btn-sm btn-warning" v-else>
                      <i class="fas fa-spinner fa-spin "></i> Proses Dilayani
                    </span>
                    </Link>
                  </td>
                </tr>
                <tr v-if="DataPasien.length === 0">
                  <td colspan="7" class="text-center py-4 text-muted">Tidak ada data ditemukan</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal Pilih Kamar -->
      <div v-if="showPilihKamarModal" class="modal-overlay">
        <div class="modal-container">
          <div class="modal-header">
            <h5 class="m-0">Pilih Kamar</h5>
            <button class="btn-close" @click="closePilihKamar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Tanggal Masuk</label>
              <input type="datetime-local" class="form-control" v-model="form.ranapMsk" readonly />
            </div>
            <div class="mb-3">
              <label class="form-label">Nama Kamar</label>
              <select class="form-select" v-model="form.kamarId" @change="ambilBed">
                <option value="">Pilih Kamar</option>
                <option v-for="kmr in daftarKamar" :key="kmr.id" :value="kmr.id">{{ kmr.nama }}</option>
              </select>
              <small class="text-muted" v-if="daftarKamar.length === 0">Belum ada data kamar.</small>
            </div>
            <div class="mb-3">
              <label class="form-label">Nama Bed</label>
              <select class="form-select" v-model="form.bedId">
                <option value="">Pilih Bed</option>
                <option v-for="bed in daftarBed" :key="bed.id" :value="bed.id" :disabled="bed.disabled">{{ bed.nama }}</option>
              </select>
              <small class="text-muted" v-if="form.kamarId && daftarBed.length === 0">Belum ada bed untuk kamar ini.</small>
            </div>
            <div class="mb-3">
              <label class="form-label">Opsi</label>
              <select class="form-select" v-model="form.pilihan">
                <option value="1">Masuk</option>
                <option value="2">Pindah Kamar</option>
                <option value="3">Salah Kamar</option>
                <option value="4">Pasien Keluar</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="closePilihKamar">Tutup</button>
            <button class="btn btn-primary" @click="simpanKamar">Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import axios from 'axios';
import { ref, onMounted, computed, watch } from 'vue'
import { route } from 'ziggy-js';

const props = defineProps({
  title: String,
  backRoute: String,
  unitList: Array,
  rows: Array,
  kluster: String,
  kdPoli: String
});
console.log('data pasien/unit list', props.unitList);

const emit = defineEmits(['filter', 'update-dataPelayanan'])

const tanggal_kunjungan = ref('')
const selectedUnit = ref('')
const DataPasien = ref([])
const loading = ref(false);
onMounted(() => {
  const today = new Date().toISOString().split('T')[0]
  tanggal_kunjungan.value = today
  DataPasien.value = props.rows
  loading.value = false
});

function filterData() {
  loading.value = true
  axios.get(route('ruang-layanan.index', [props.kdPoli, props.kluster]), {
    params: {
      tglKunjungan: tanggal_kunjungan.value,
      unit: selectedUnit.value
    }
  })
    .then(res => {
      console.log('Data hasil filter:', res.data.DataPasien)
      DataPasien.value = res.data.DataPasien
      loading.value = false
      console.log('panjang datapasien', res.data.tgl)
      console.log('panjang datapasien', res.data.unit)
    })
    .catch(err => {
      console.error(err)
    })
}
const ListDataPasien = computed(() => {
  return DataPasien.value
})
// console.log(DataPasien.value.length, 'panjang')

watch(ListDataPasien, (newVal) => {
  console.log('DataPasien berubah:', newVal)
})

// State & helpers untuk modal Pilih Kamar
const showPilihKamarModal = ref(false)
const selectedPelayananId = ref(null)
const form = ref({
  ranapMsk: getNowForInput(),
  kamarId: '',
  bedId: '',
  pilihan: '1'
})
const daftarKamar = ref([])
const daftarBed = ref([])

function getNowForInput() {
  const d = new Date()
  const pad = (n) => String(n).padStart(2, '0')
  const yyyy = d.getFullYear()
  const mm = pad(d.getMonth() + 1)
  const dd = pad(d.getDate())
  const hh = pad(d.getHours())
  const ii = pad(d.getMinutes())
  return `${yyyy}-${mm}-${dd}T${hh}:${ii}`
}

async function loadKamar() {
  try {
    const { data } = await axios.get(route('ruang-layanan.ranap.kamar-list'))
    daftarKamar.value = data.map(k => ({ id: k.id, nama: k.nama_kamar }))
  } catch (e) {
    console.error('Gagal load kamar:', e)
    daftarKamar.value = []
  }
}

async function ambilBed() {
  form.value.bedId = ''
  daftarBed.value = []
  if (!form.value.kamarId) return
  try {
    const tglCek = form.value.ranapMsk.replace('T',' ') + ':00'
    const { data } = await axios.get(route('ruang-layanan.ranap.bed-on', form.value.kamarId), { params: { tglCek }})
    daftarBed.value = data.map(b => ({ id: b.id, nama: b.nama, disabled: !!b.penuh }))
  } catch (e) {
    console.error('Gagal load bed:', e)
    daftarBed.value = []
  }
}

function closePilihKamar() {
  showPilihKamarModal.value = false
}

function pilihKamar(idpelayanan) {
  selectedPelayananId.value = idpelayanan
  showPilihKamarModal.value = true
  loadKamar()
  // Tetap emit agar parent yang sudah mendengar event tidak rusak
  emit('update-dataPelayanan', { type: 'pilih-kamar', idpelayanan })
}

function pasienKeluar(idpelayanan) {
  // Emit ke parent agar bisa ditangani (misal proses pasien keluar)
  emit('update-dataPelayanan', { type: 'pasien-keluar', idpelayanan })
}

async function simpanKamar() {
  try {
    const payload = {
      idPelayanan: selectedPelayananId.value,
      ranapMsk: form.value.ranapMsk.replace('T',' ') + ':00',
      kamarId: form.value.kamarId,
      bedId: form.value.bedId,
      pilihan: form.value.pilihan
    }
    const { data } = await axios.post(route('ruang-layanan.ranap.simpan-kamar'), payload)
    if (data.status === 'success') {
      showPilihKamarModal.value = false
    } else {
      alert(data.message || 'Gagal menyimpan kamar')
    }
  } catch (e) {
    console.error('Gagal simpan kamar:', e)
    alert('Gagal menyimpan kamar')
  }
}
</script>
<style>
/***** Modal sederhana *****/
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
}
.modal-container {
  width: 100%;
  max-width: 560px;
  background: #fff;
  border-radius: 0.5rem;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.modal-header, .modal-footer {
  padding: 0.75rem 1rem;
  background: #f8fafc;
  border-bottom: 1px solid #e5e7eb;
}
.modal-header { display: flex; align-items: center; justify-content: space-between; }
.modal-body { padding: 1rem; }
.btn-close {
  border: none;
  background: transparent;
  font-size: 1.25rem;
}

/* Menyesuaikan style disabled option seperti referensi */
select option:disabled {
  color: #000;
  background: #e0e0eb;
}
</style>
