<template>
  <div class="container-fluid p-3">
    <div class="row">
      <!-- ========================= Diagnosa Medis ========================= -->
      <form class="col-6" @submit.prevent="simpanDiagnosaMedis">
        <h5 class="fw-semibold mb-3">Diagnosa</h5>

        <!-- Kode & Nama Diagnosa -->
        <div class="row mb-3">
          <div class="col-3">
            <input
              type="text"
              class="form-control"
              placeholder="Kode"
              disabled
              v-model="form.kode_diagnosa"
            />
          </div>
          <div class="col-9">
            <div class="input-group">
              <input
                type="text"
                class="form-control bg-light"
                placeholder="Nama Diagnosa"
                disabled
                v-model="form.nama_diagnosa"
              />
              <button type="button" class="btn btn-info" @click="showModal = true">Cari</button>
              <button type="button" class="btn btn-danger" @click="hapusForm">Del</button>
            </div>
          </div>
        </div>

        <!-- Alergi -->
        <div class="row mb-3">
          <div class="col-3 d-flex flex-column">
            <label class="fw-bold">Alergi Makanan</label>
            <input
              type="text"
              class="form-control bg-warning bg-opacity-75"
              v-model="form.alergi_makanan"
            />
          </div>
          <div class="col-3 d-flex flex-column">
            <label class="fw-bold">Alergi Obat</label>
            <input
              type="text"
              class="form-control bg-warning bg-opacity-75"
              v-model="form.alergi_obat"
            />
          </div>
          <div class="col-6 d-flex flex-column">
            <label class="fw-bold">Keterangan Alergi</label>
            <input
              type="text"
              class="form-control bg-warning bg-opacity-75"
              v-model="form.keterangan_alergi"
            />
          </div>
        </div>

        <!-- Kunjungan -->
        <div class="row mb-3">
          <div class="col-3 d-flex flex-column">
            <label class="fw-bold">Kunjungan Khusus</label>
            <select class="form-control" v-model="form.kunjungan_khusus">
              <option value="">- Pilih -</option>
              <option value="Kasus Baru">Kasus Baru</option>
              <option value="Kasus Lama">Kasus Lama</option>
            </select>
          </div>
          <div class="col-9 d-flex flex-column">
            <label class="fw-bold">Keterangan</label>
            <input type="text" class="form-control" v-model="form.keterangan_kunjungan" />
          </div>
        </div>

        <button type="submit" class="btn btn-success">
          <i class="far fa-save me-2"></i> SIMPAN DIAGNOSA MEDIS
        </button>
      </form>

      <!-- ========================= Diagnosa Keperawatan ========================= -->
      <div class="col-6">
        <div class="row mb-4">
          <div class="col-5">
            <div class="fw-bold t mb-4">
              <p class="d-inline-block px-2">Pemeriksaan Penunjang</p>
            </div>
            <button class="btn btn-info" @click="alert('Buka form laboratorium')">
              <i class="fas fa-flask me-2"></i> Laboratorium
            </button>
          </div>

          <div class="col-7">
            <div class="fw-bold t mb-4">
              <p class="d-inline-block px-2">Form Lanjutan</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
              <button
                v-for="item in ['Diare', 'Katarak', 'PTM', 'Hipertensi']"
                :key="item"
                class="btn btn-secondary"
                @click="alert(`Form lanjutan: ${item}`)"
              >
                <i class="far fa-file-alt me-2"></i>{{ item }}
              </button>
            </div>
          </div>
        </div>

        <form @submit.prevent="simpanDiagnosaKeperawatan" class="row">
          <div class="fw-bold t mb-4">
            <p class="d-inline-block px-2">Diagnosa Keperawatan</p>
          </div>
          <select class="form-control" v-model="formDiagnosaKeperawatan.kode_diagnosa">
            <option value="">-- Pilih --</option>
            <option v-for="item in daftarDiagnosaKeperawatan" :key="item.id" :value="item.id">
              {{ item.nama }}
            </option>
          </select>

          <button type="submit" class="btn btn-success mt-4">
            <i class="far fa-save me-2"></i> Simpan Diagnosa Keperawatan
          </button>
        </form>
      </div>
    </div>

    <!-- ========================= Tabel Diagnosa ========================= -->
    <div class="row mt-4">
      <div class="col-6">
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Diagnosa Medis</th>
              <th>Keterangan</th>
              <th>Kasus</th>
              <th>Poli</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in daftarDiagnosaMedis" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ item.nama_diagnosa }}</td>
              <td>{{ item.keterangan_kunjungan }}</td>
              <td>{{ item.kunjungan_khusus }}</td>
              <td>Umum</td>
              <td>
                <button class="btn btn-danger btn-sm" @click="hapusDiagnosa(index)">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="col-6">
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Diagnosa Asuhan Keperawatan</th>
              <th>Keterangan</th>
              <th>Kasus</th>
              <th>Poli</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in daftarDiagnosaKeperawatanSimpan" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ item.nama_diagnosa }}</td>
              <td>-</td>
              <td>-</td>
              <td>Umum</td>
              <td>
                <button class="btn btn-danger btn-sm" @click="hapusDiagnosaKeperawatan(index)">
                  Hapus
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ========================= Modal Pilih Diagnosa ========================= -->
    <div v-if="showModal" class="modal fade show d-block" style="background: rgba(0, 0, 0, 0.5)">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header d-flex justify-content-between align-items-center">
            <h5>Pilih Diagnosa</h5>
            <button class="btn-close" @click="showModal = false"></button>
          </div>

          <div class="modal-body">
            <!-- Search -->
            <input
              type="text"
              v-model="keyword"
              class="form-control mb-3"
              placeholder="Cari diagnosa..."
              @keyup.enter="doSearch"
            />

            <!-- Tabel -->
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>KODE</th>
                  <th>NAMA</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in diagnosa.data" :key="item.id">
                  <td>{{ item.kdDiag }}</td>
                  <td>{{ item.nmDiag }}</td>
                  <td>
                    <button class="btn btn-info btn-sm" @click="pilihDiagnosa(item)">Pilih</button>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- PAGINATION -->
            <nav class="d-flex justify-content-between align-items-center mt-3">
              <ul class="pagination pagination-sm mb-0">
                <li class="page-item" :class="{ disabled: !diagnosa.prev_page_url }">
                  <Link class="page-link" :href="diagnosa.prev_page_url">«</Link>
                </li>

                <li
                  v-for="page in diagnosa.links"
                  :key="page.label"
                  class="page-item"
                  :class="{ active: page.active }"
                >
                  <Link v-if="page.url" class="page-link" :href="page.url" v-html="page.label" />
                  <span v-else class="page-link" v-html="page.label" />
                </li>

                <li class="page-item" :class="{ disabled: !diagnosa.next_page_url }">
                  <Link class="page-link" :href="diagnosa.next_page_url">»</Link>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';

  // Form utama
  const form = ref({
    kode_diagnosa: '',
    nama_diagnosa: '',
    alergi_makanan: 'Tidak Ada Alergi',
    alergi_obat: 'Tidak Ada Alergi',
    keterangan_alergi: '',
    kunjungan_khusus: '',
    keterangan_kunjungan: '',
  });

  const keyword = ref(props.filters?.search || '');

  function doSearch() {
    router.get(
      route('pelayanan.index', {
        id: props.DataPasien[0]?.idLoket,
        idPoli: props.idPoli,
        idPelayanan: props.idPelayanan,
      }),
      { search: keyword.value },
      { preserveState: true, replace: true }
    );
  }

  const props = defineProps({
    diagnosa: Object,
    filters: Object,
    idPelayanan: String,
    idPoli: String,
    DataPasien: Object,
  });

  // Modal control
  const showModal = ref(false);
  // Pagination
  const currentPage = ref(1);
  const perPage = 10;

  // Filter diagnosa berdasarkan keyword
  const filteredDiagnosa = computed(() => {
    if (!keyword.value) return props.diagnosa;
    const lower = keyword.value.toLowerCase();
    return props.diagnosa.filter(
      (d) => d.kdDiag.toLowerCase().includes(lower) || d.nmDiag.toLowerCase().includes(lower)
    );
  });

  // Hitung total halaman
  const totalPages = computed(() => Math.ceil(filteredDiagnosa.value.length / perPage));

  // Ambil data sesuai halaman aktif
  const paginatedDiagnosa = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    const end = start + perPage;
    return filteredDiagnosa.value.slice(start, end);
  });

  // Navigasi halaman
  const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) currentPage.value = page;
  };
  const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
  };
  const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
  };

  // Info item yang ditampilkan
  const startItem = computed(() =>
    filteredDiagnosa.value.length === 0 ? 0 : (currentPage.value - 1) * perPage + 1
  );
  const endItem = computed(() =>
    Math.min(currentPage.value * perPage, filteredDiagnosa.value.length)
  );

  function searchDiagnosa() {
    fetchPage(route('api.diagnosa-medis', { search: keyword.value }));
  }

  // Simpanan data tabel
  const daftarDiagnosaMedis = ref([]);
  const daftarDiagnosaKeperawatanSimpan = ref([]);

  // Diagnosa keperawatan dummy
  const daftarDiagnosaKeperawatan = ref([
    { id: 'KP01', nama: 'Asuhan Luka' },
    { id: 'KP02', nama: 'Perawatan Lansia' },
  ]);

  const formDiagnosaKeperawatan = ref({
    kode_diagnosa: '',
    nama_diagnosa: '',
  });

  // Fungsi
  const pilihDiagnosa = (item) => {
    form.value.kode_diagnosa = item.kdDiag;
    form.value.nama_diagnosa = item.nmDiag;
    showModal.value = false;
  };

  const hapusForm = () => {
    form.value.kode_diagnosa = '';
    form.value.nama_diagnosa = '';
  };

  const simpanDiagnosaMedis = () => {
    daftarDiagnosaMedis.value.push({ ...form.value });
    alert('Diagnosa Medis Disimpan');
  };

  const hapusDiagnosa = (index) => {
    daftarDiagnosaMedis.value.splice(index, 1);
  };

  const simpanDiagnosaKeperawatan = () => {
    const selected = daftarDiagnosaKeperawatan.value.find(
      (d) => d.id === formDiagnosaKeperawatan.value.kode_diagnosa
    );
    if (selected) {
      daftarDiagnosaKeperawatanSimpan.value.push({
        ...formDiagnosaKeperawatan.value,
        nama_diagnosa: selected.nama,
      });
      alert('Diagnosa Keperawatan Disimpan');
    }
  };

  const hapusDiagnosaKeperawatan = (index) => {
    daftarDiagnosaKeperawatanSimpan.value.splice(index, 1);
  };
</script>

<style scoped>
  .modal {
    display: block;
  }
  .bg-warning {
    color: #000;
  }
</style>
