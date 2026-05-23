<template>
  <div>
    <!-- Tab Navigasi -->
    <div class="mb-4">
      <div class="d-flex gap-2 flex-wrap">
        <a
          href="#"
          class="action-card"
          :class="{ 'active-card': activeFormPlanning === 'tindakan' }"
          @click.prevent="toggleForm('tindakan')"
        >
          <div class="action-icon"><i class="bi bi-person-check"></i></div>
          <div class="action-label">Tindakan</div>
        </a>
        <a
          href="#"
          class="action-card"
          :class="{ 'active-card': activeFormPlanning === 'pengobatan' }"
          @click.prevent="toggleForm('pengobatan')"
        >
          <div class="action-icon"><i class="bi bi-capsule"></i></div>
          <div class="action-label">Pengobatan</div>
        </a>
      </div>
    </div>

    <!-- ==================== FORM TINDAKAN ==================== -->
    <div v-if="activeFormPlanning === 'tindakan'" class="fade-in">

      <!-- Form Input Tindakan -->
      <div class="card section-card mb-4">
        <div class="card-header section-header header-tindakan">
          <h5 class="m-0 text-white d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i> Tambah Tindakan
          </h5>
        </div>
        <div class="card-body p-4">
          <!-- Kode Tindakan -->
          <div class="row mb-3 align-items-center">
            <label class="col-sm-3 col-lg-2 col-form-label fw-semibold">Kode Tindakan</label>
            <div class="col-sm-9 col-lg-6">
              <div class="input-group">
                <input
                  type="text"
                  class="form-control"
                  v-model="form.kode_tindakan"
                  placeholder="Masukkan atau cari kode tindakan"
                />
                <button class="btn btn-outline-primary" @click="showModal = true" type="button">
                  <i class="bi bi-search me-1"></i> Cari
                </button>
                <button class="btn btn-outline-danger" @click="hapusForm" type="button" title="Bersihkan form">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Nama Tindakan -->
          <div class="row mb-3">
            <label class="col-sm-3 col-lg-2 col-form-label fw-semibold">Nama Tindakan</label>
            <div class="col-sm-9 col-lg-6">
              <textarea
                class="form-control"
                rows="2"
                v-model="form.nama_tindakan"
                placeholder="Nama tindakan akan otomatis terisi"
              ></textarea>
            </div>
          </div>

          <!-- Nama Tindakan (Ind) -->
          <div class="row mb-3">
            <label class="col-sm-3 col-lg-2 col-form-label fw-semibold">Nama Tindakan (Ind)</label>
            <div class="col-sm-9 col-lg-6">
              <textarea
                class="form-control"
                rows="2"
                v-model="form.nama_tindakan_ind"
                placeholder="Nama tindakan dalam bahasa Indonesia"
              ></textarea>
            </div>
          </div>

          <!-- Keterangan -->
          <div class="row mb-4">
            <label class="col-sm-3 col-lg-2 col-form-label fw-semibold">Keterangan</label>
            <div class="col-sm-9 col-lg-6">
              <textarea
                class="form-control"
                rows="2"
                v-model="form.keterangan"
                placeholder="Masukkan keterangan tindakan"
              ></textarea>
            </div>
          </div>

          <!-- Tombol Simpan -->
          <div class="row">
            <div class="col-sm-3 col-lg-2"></div>
            <div class="col-sm-9 col-lg-6">
              <button
                type="button"
                class="btn btn-success px-4 fw-semibold"
                @click.prevent.stop="simpanData"
              >
                <i class="bi bi-check-circle me-2"></i> Simpan Tindakan
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel Daftar Tindakan -->
      <div class="card section-card">
        <div class="card-header section-header header-daftar">
          <h5 class="m-0 text-white d-flex align-items-center gap-2">
            <i class="bi bi-list-ul"></i> Daftar Tindakan
          </h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="text-center ps-3" style="width:50px">No</th>
                  <th style="width:130px">Kode</th>
                  <th>Nama Tindakan</th>
                  <th style="width:110px">Poli</th>
                  <th>Keterangan</th>
                  <th style="width:130px">Created By</th>
                  <th class="text-center" style="width:90px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <!-- Empty State -->
                <tr v-if="dataTindakan.length === 0">
                  <td colspan="7" class="text-center py-5">
                    <i class="bi bi-inbox empty-icon d-block mb-2"></i>
                    <p class="text-muted fw-semibold mb-1">Data Tindakan Belum Tersedia</p>
                    <small class="text-muted">Silakan tambahkan tindakan melalui form di atas</small>
                  </td>
                </tr>
                <!-- Data Row -->
                <tr v-for="(item, i) in dataTindakan" :key="i">
                  <td class="text-center ps-3">{{ i + 1 }}</td>
                  <td>
                    <span class="badge bg-primary bg-opacity-75 px-2 py-1">{{ item.kdTindakan }}</span>
                  </td>
                  <td class="fw-semibold">{{ item.nmTindakan }}</td>
                  <td>
                    <span class="badge bg-info text-dark px-2 py-1">{{ item.nmPoli }}</span>
                  </td>
                  <td class="text-muted small">{{ item.keterangan }}</td>
                  <td class="small">{{ item.createdBy }}</td>
                  <td class="text-center">
                    <button
                      class="btn btn-outline-danger btn-sm"
                      @click="hapusTindakan(item.idTindakan)"
                      title="Hapus tindakan"
                    >
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal Pilih Tindakan -->
      <TindakanModal
        :show="showModal"
        :tindakan="tindakan"
        @close="showModal = false"
        @select="pilihTindakan"
      />

      <!-- Modal Konfirmasi Hapus -->
      <ModalHapus
        :show="showDeleteModal"
        title="Hapus Tindakan?"
        message="Data tindakan yang dihapus tidak dapat dikembalikan."
        @close="showDeleteModal = false"
        @confirm="konfirmasiHapus"
      />

      <!-- Modal Sukses -->
      <div
        v-if="showSuccessModal"
        class="modal fade show d-block"
        tabindex="-1"
        style="background: rgba(0,0,0,0.5)"
      >
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-body text-center py-5">
              <i class="bi bi-check-circle-fill text-success mb-3" style="font-size:3rem"></i>
              <h5 class="fw-semibold text-success mb-2">Tindakan Berhasil Disimpan</h5>
              <p class="text-muted mb-0">Data tindakan telah berhasil disimpan ke sistem.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
              <button type="button" class="btn btn-success px-5" @click="showSuccessModal = false">
                OK
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ==================== FORM PENGOBATAN ==================== -->
    <div v-if="activeFormPlanning === 'pengobatan'" class="fade-in">

      <!-- Form Input Resep -->
      <div class="card section-card mb-4">
        <div class="card-header section-header header-pengobatan">
          <h5 class="m-0 text-white d-flex align-items-center gap-2">
            <i class="bi bi-capsule-pill"></i> Pengobatan & Resep
          </h5>
        </div>
        <div class="card-body p-4">
          <!-- Tipe Obat -->
          <div class="row mb-3 align-items-center">
            <label class="fw-semibold col-sm-3 col-lg-2 col-form-label">Tipe Obat</label>
            <div class="col-sm-4 col-lg-3">
              <select class="form-select" v-model="jenisPuyer">
                <option value="Puyer">PUYER</option>
                <option value="Bukan Puyer">BUKAN PUYER</option>
              </select>
            </div>
          </div>

          <!-- Section khusus Puyer -->
          <div v-if="jenisPuyer === 'Puyer'" class="puyer-section rounded-3 p-3 mb-3">
            <!-- Jumlah Puyer -->
            <div class="row mb-3 align-items-center">
              <label class="fw-semibold col-sm-3 col-lg-2 col-form-label">Jumlah Puyer</label>
              <div class="col-sm-3 col-lg-2">
                <input
                  type="number"
                  class="form-control"
                  v-model="jumlahPuyer"
                  placeholder="0"
                  min="0"
                />
              </div>
              <small class="col text-muted">bungkus</small>
            </div>

            <!-- Dosis -->
            <div class="row mb-3 align-items-center">
              <label class="fw-semibold col-sm-3 col-lg-2 col-form-label">Dosis Pakai</label>
              <div class="col-sm-9 col-lg-7">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                  <input
                    type="number"
                    class="form-control dosis-input"
                    v-model="dosisPerHari"
                    placeholder="0"
                    min="0"
                  />
                  <span class="fw-semibold text-muted text-nowrap">x Sehari, setiap</span>
                  <input
                    type="number"
                    class="form-control dosis-input"
                    v-model="intervalJam"
                    placeholder="0"
                    min="0"
                  />
                  <span class="fw-semibold text-muted text-nowrap">Jam Sekali</span>
                </div>
              </div>
            </div>

            <!-- Waktu -->
            <div class="row mb-3">
              <label class="fw-semibold col-sm-3 col-lg-2 col-form-label">Waktu</label>
              <div class="col-sm-9 col-lg-7">
                <div class="d-flex gap-4 flex-wrap mt-1">
                  <div class="form-check" v-for="w in ['Pagi', 'Siang', 'Malam']" :key="w">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      :value="w.toLowerCase()"
                      v-model="waktu"
                      :id="'waktu-' + w"
                    />
                    <label class="form-check-label" :for="'waktu-' + w">{{ w }}</label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Kondisi -->
            <div class="row">
              <label class="fw-semibold col-sm-3 col-lg-2 col-form-label">Kondisi</label>
              <div class="col-sm-9 col-lg-7">
                <div class="d-flex gap-4 flex-wrap mt-1">
                  <div
                    class="form-check"
                    v-for="k in ['Sebelum Makan', 'Saat Makan', 'Setelah Makan']"
                    :key="k"
                  >
                    <input
                      class="form-check-input"
                      type="checkbox"
                      :value="k.toLowerCase()"
                      v-model="kondisi"
                      :id="'kondisi-' + k"
                    />
                    <label class="form-check-label" :for="'kondisi-' + k">{{ k }}</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tombol Simpan Resep -->
          <div class="row pt-3">
            <div class="col-sm-3 col-lg-2"></div>
            <div class="col-sm-9 col-lg-6">
              <button class="btn btn-success px-4 fw-semibold" @click="simpanResep">
                <i class="bi bi-check-circle me-2"></i> Simpan Resep
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel Daftar Resep — FIXED: hapus tbody duplikat -->
      <div class="card section-card">
        <div class="card-header section-header header-daftar-resep">
          <h5 class="m-0 text-white d-flex align-items-center gap-2">
            <i class="bi bi-list-ul"></i> Daftar Resep
          </h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3" style="width:110px">Poli</th>
                  <th>Jenis / Nama Obat</th>
                  <th style="width:110px">Jumlah</th>
                  <th style="width:150px">Dosis Pakai</th>
                  <th class="text-center" style="width:90px">Aksi</th>
                </tr>
              </thead>
              <!-- FIXED: satu tbody, gunakan v-if/v-else yang benar -->
              <tbody>
                <tr v-if="dataResep.length === 0">
                  <td colspan="5" class="text-center py-5">
                    <i class="bi bi-capsule empty-icon d-block mb-2"></i>
                    <p class="text-muted fw-semibold mb-1">Belum Ada Resep</p>
                    <small class="text-muted">Tambahkan resep melalui form di atas</small>
                  </td>
                </tr>
                <tr v-for="(item, index) in dataResep" :key="index">
                  <td class="ps-3">
                    <span class="badge bg-warning text-dark px-2 py-1">{{ item.poli }}</span>
                  </td>
                  <td class="fw-semibold">{{ item.jenis }}</td>
                  <td>{{ item.jumlah }}</td>
                  <td class="small text-muted">{{ item.dosis }}</td>
                  <td class="text-center">
                    <button
                      class="btn btn-outline-danger btn-sm"
                      @click="hapusResep(index)"
                      title="Hapus resep"
                    >
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import TindakanModal from './TindakanModal.vue';
import ModalHapus from '../../../Modal/ModalHapus.vue';

const activeFormPlanning = ref('tindakan');
const toggleForm = (form) => { activeFormPlanning.value = form; };

const props = defineProps({
  DataPasien: Object,
  tindakan: Array,
  DataTindakan: Array,
});

const dataTindakan = computed(() => props.DataTindakan || []);

// Form Tindakan
const form = useForm({
  idpelayanan: props.DataPasien.idpelayanan,
  loketId: props.DataPasien.idLoket,
  kdPoli: props.DataPasien.kdPoli,
  kode_tindakan: '',
  nama_tindakan: '',
  nama_tindakan_ind: '',
  keterangan: '',
});

// Modal state
const showModal = ref(false);
const showSuccessModal = ref(false);
const showDeleteModal = ref(false);
const selectedDeleteId = ref(null);

const hapusTindakan = (id) => {
  selectedDeleteId.value = id;
  showDeleteModal.value = true;
};

const konfirmasiHapus = () => {
  router.delete(route('ptm.tindakan-hapus', { id: selectedDeleteId.value }), {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      selectedDeleteId.value = null;
    },
    onError: (errors) => console.error(errors),
  });
};

const pilihTindakan = (item) => {
  form.kode_tindakan = item.kdTindakan;
  form.nama_tindakan = item.nmTindakan;
  form.nama_tindakan_ind = item.nmTindakanInd;
  showModal.value = false;
};

const hapusForm = () => {
  form.kode_tindakan = '';
  form.nama_tindakan = '';
  form.nama_tindakan_ind = '';
  form.keterangan = '';
};

const simpanData = () => {
  form.post(route('ptm.tindakan-simpan'), {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      showSuccessModal.value = true;
      form.reset();
      hapusForm();
    },
    onError: (errors) => console.log(errors),
  });
};

// Pengobatan
const jenisPuyer = ref('Bukan Puyer');
const jumlahPuyer = ref('');
const dosisPerHari = ref('');
const intervalJam = ref('');
const waktu = ref([]);
const kondisi = ref([]);
const dataResep = ref([]);

watch(jenisPuyer, (val) => {
  if (val === 'Bukan Puyer') {
    jumlahPuyer.value = '';
    dosisPerHari.value = '';
    intervalJam.value = '';
    waktu.value = [];
    kondisi.value = [];
  }
});

const simpanResep = () => {
  dataResep.value.push({
    poli: props.DataPasien.kdPoli || 'KIA',
    jenis: jenisPuyer.value,
    jumlah: jenisPuyer.value === 'Puyer' ? `${jumlahPuyer.value} bungkus` : '-',
    dosis: jenisPuyer.value === 'Puyer'
      ? `${dosisPerHari.value}x / ${intervalJam.value} jam`
      : '-',
  });
};

const hapusResep = (index) => {
  dataResep.value.splice(index, 1);
};
</script>

<style scoped>
/* ====== Tab Navigasi ====== */
.action-card {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 18px;
  border-radius: 10px;
  background: #f3f4f6;
  color: #374151;
  text-decoration: none;
  transition: all 0.25s ease;
  border: 2px solid transparent;
  font-size: 0.9rem;
}

.action-card:hover {
  background: #dbeafe;
  color: #1d4ed8;
  border-color: #93c5fd;
  transform: translateY(-1px);
}

.active-card {
  background: linear-gradient(135deg, #10b981, #059669);
  color: #fff !important;
  border-color: #047857;
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
}

.action-icon { font-size: 1.2rem; }
.action-label { font-weight: 600; }

/* ====== Card ====== */
.section-card {
  border: none;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
}

.section-header {
  padding: 14px 20px;
  border: none;
}

.header-tindakan  { background: linear-gradient(135deg, #10b981, #059669); }
.header-daftar    { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.header-pengobatan  { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.header-daftar-resep { background: linear-gradient(135deg, #6366f1, #4f46e5); }

/* ====== Tabel ====== */
.table thead th {
  font-size: 0.82rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  color: #6b7280;
  border-bottom: 1px solid #e5e7eb;
  padding: 12px 10px;
}

.table tbody tr {
  border-bottom: 1px solid #f3f4f6;
  transition: background 0.15s;
}

.table tbody tr:hover { background: #f9fafb; }

/* ====== Empty State ====== */
.empty-icon {
  font-size: 2.5rem;
  color: #d1d5db;
}

/* ====== Puyer Section ====== */
.puyer-section {
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
}

.dosis-input {
  width: 75px !important;
}

/* ====== Fade Animation ====== */
.fade-in {
  animation: fadeIn 0.25s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>