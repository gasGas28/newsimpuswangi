<template>
  <AppLayout title="Loket">
    <div class="container py-4">
      <!-- Tombol Aksi -->
      <div class="d-flex justify-content-end mb-3 gap-2">
        <Link :href="route('loket.search')" class="btn btn-success text-white">
          <i class="bi bi-search me-1"></i> CARI PASIEN
        </Link>
        <Link :href="route('loket.pasien')" class="btn btn-primary text-white">
          <i class="bi bi-plus-lg me-1"></i> TAMBAH PASIEN
        </Link>
      </div>

      <!-- Form Loket -->
      <div class="card border-success mb-4">
        <div class="card-header bg-info text-white fw-bold">Form Loket</div>
        <div class="card-body">
          <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">NO. MR</label>
                  </div>
                  <div class="col-8">
                    <div class="input-group">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="searchParams.no_mr"
                        @keyup.enter="searchPasien('no_mr')"
                      />
                      <button class="btn btn-info text-white btn-sm" @click="searchPasien('no_mr')">
                        CEK ..!
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">NIK</label>
                  </div>
                  <div class="col-8">
                    <div class="input-group">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="searchParams.nik"
                        @keyup.enter="searchPasien('nik')"
                      />
                      <button class="btn btn-info text-white btn-sm" @click="searchPasien('nik')">
                        CEK ..!
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">NO. BPJS</label>
                  </div>
                  <div class="col-8">
                    <div class="input-group">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="searchParams.no_bpjs"
                        @keyup.enter="searchPasien('no_bpjs')"
                      />
                      <button
                        class="btn btn-info text-white btn-sm"
                        @click="searchPasien('no_bpjs')"
                      >
                        CEK ..!
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">NAMA</label>
                  </div>
                  <div class="col-8">
                    <div class="input-group">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="selectedPasien.NAMA_LGKP"
                        disabled
                      />
                      <button class="btn btn-danger text-white btn-sm" @click="openSearchModal">
                        Cari.....
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Tanggal Kunjungan</label>
                  </div>
                  <div class="col-8">
                    <input
                      type="date"
                      class="form-control form-control-sm"
                      v-model="form.tglKunjungan"
                    />
                  </div>
                </div>
              </div>

              <!-- KATEGORI UNIT -->
              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Kategori Unit</label>
                  </div>
                  <div class="col-8">
                    <select
                      class="form-select form-select-sm"
                      v-model="form.kategoriUnitId"
                      @change="loadUnitList"
                      :disabled="loading.kategoriUnit"
                    >
                      <option value="">- Pilih Kategori Unit -</option>
                      <option v-for="(name, id) in kategoriUnits" :key="id" :value="id">
                        {{ name }}
                      </option>
                    </select>
                    <div v-if="loading.kategoriUnit" class="text-muted small mt-1">
                      <i class="bi bi-arrow-repeat spinner"></i> Loading...
                    </div>
                  </div>
                </div>
              </div>

              <!-- UNIT -->
              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Unit</label>
                  </div>
                  <div class="col-8">
                    <select
                      class="form-select form-select-sm"
                      v-model="form.unitId"
                      :disabled="!form.kategoriUnitId || loading.unitList"
                    >
                      <option value="">- Pilih Unit -</option>
                      <option v-for="(name, id) in unitList" :key="id" :value="id">
                        {{ name }}
                      </option>
                    </select>
                    <div v-if="loading.unitList" class="text-muted small mt-1">
                      <i class="bi bi-arrow-repeat spinner"></i> Loading unit...
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Status Kartu</label>
                  </div>
                  <div class="col-8">
                    <div class="input-group">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="selectedPasien.statusKartu"
                        disabled
                      />
                      <button class="btn btn-info text-white btn-sm" @click="checkCardStatus">
                        CEK ..!
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Provider Kartu</label>
                  </div>
                  <div class="col-8">
                    <input
                      type="text"
                      class="form-control form-control-sm"
                      v-model="selectedPasien.kdProvider"
                      disabled
                    />
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Jenis Peserta</label>
                  </div>
                  <div class="col-8">
                    <input
                      type="text"
                      class="form-control form-control-sm"
                      v-model="selectedPasien.jenisPeserta"
                      disabled
                    />
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">No HP</label>
                  </div>
                  <div class="col-8">
                    <input type="text" class="form-control form-control-sm" v-model="form.PHONE" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Alamat *</label>
                  </div>
                  <div class="col-8">
                    <textarea
                      class="form-control form-control-sm"
                      rows="2"
                      :value="formatKecamatanKelurahan"
                      disabled
                      style="height: 60px"
                    ></textarea>
                  </div>
                </div>
              </div>

              <!-- WILAYAH -->
              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Wilayah *</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm bg-warning" v-model="form.wilayah">
                      <option value="">- Pilih Wilayah -</option>
                      <option
                        v-for="(name, id) in wilayahList"
                        :key="id"
                        :value="id"
                        v-if="id !== '0'"
                      >
                        {{ name }}
                      </option>
                    </select>

                    <!-- Info wilayah otomatis -->
                    <small
                      v-if="autoDetectedWilayah && form.wilayah === autoDetectedWilayah"
                      class="text-info d-block mt-1"
                    >
                      <i class="bi bi-check-circle"></i> Terdeteksi otomatis
                    </small>
                    <small v-else-if="form.wilayah" class="text-success d-block mt-1">
                      <i class="bi bi-pencil"></i> Dipilih manual
                    </small>
                  </div>
                </div>
              </div>

              <!-- JENIS PENGUNJUNG DENGAN FITUR OTOMATIS -->
              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Jenis Pengunjung *</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm bg-warning" v-model="jenisPengunjung">
                      <option value="Pengunjung Baru">Pengunjung Baru</option>
                      <option value="Pengunjung Lama">Pengunjung Lama</option>
                    </select>

                    <!-- Info status otomatis - HANYA TAMPIL JIKA PENGUNJUNG LAMA -->
                    <small
                      v-if="jenisPengunjungAuto === 'Pengunjung Lama'"
                      class="text-warning d-block mt-1"
                    >
                      <small>💡 Pasien sudah pernah daftar di {{ currentMonthYear }}</small>
                    </small>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Kategori *</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm bg-warning" v-model="kategori">
                      <option value="NON_BPJS">NON BPJS</option>
                      <option value="JKN_PBI">JKN PBI</option>
                      <option value="JKN_NON_PBI">JKN NON PBI</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Jenis Kunjungan</label>
                  </div>
                  <div class="col-8">
                    <select
                      class="form-select form-select-sm"
                      v-model="jenisKunjungan"
                      @change="updatePoliOptions"
                    >
                      <option value="Kunjungan Sakit">Kunjungan Sakit</option>
                      <option value="Kunjungan Sehat">Kunjungan Sehat</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Kegiatan</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm" v-model="form.kdPoli">
                      <option value="">- Pilih Kegiatan -</option>
                      <option v-for="poli in filteredPoliList" :value="poli.kdPoli">
                        {{ poli.nmPoli }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Sub Kegiatan</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm" disabled></select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Kode TKP</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm" v-model="kodeTKP">
                      <option value="RJTP (RAWAT JALAN)">RJTP (RAWAT JALAN)</option>
                      <option value="RJTL (RAWAT JALAN LANJUTAN)">
                        RJTL (RAWAT JALAN LANJUTAN)
                      </option>
                      <option value="Promotif">Promotif</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tombol Bawah -->
          <div class="d-flex justify-content-end mt-4 gap-2">
            <button
              class="btn btn-success"
              @click="showRiwayatKunjungan"
              :disabled="!selectedPasien.ID"
            >
              RIWAYAT KUNJUNGAN
            </button>
            <button
              class="btn btn-primary"
              @click="registerPasien"
              :disabled="!selectedPasien.ID || !formValid"
            >
              SIMPAN
            </button>
          </div>
        </div>
      </div>

      <!-- Daftar Loket -->
      <div class="card border-success">
        <div
          class="card-header bg-info text-white d-flex justify-content-between align-items-center"
        >
          <span class="fw-bold">Daftar Loket</span>
          <button class="btn btn-success btn-sm">Cek Pendaftaran Pcare</button>
        </div>

        <!-- Show entries & Search -->
        <div
          class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom flex-wrap"
          style="background-color: #f0fdf0"
        >
          <div class="d-flex align-items-center">
            <label class="me-2 mb-0">Show</label>
            <select class="form-select form-select-sm" style="width: 80px" v-model="perPage">
              <option>10</option>
              <option>25</option>
              <option>50</option>
              <option>100</option>
            </select>
            <label class="ms-2 mb-0">entries</label>
          </div>

          <div class="d-flex align-items-center mt-2 mt-sm-0">
            <label class="me-2 mb-0">Search:</label>
            <input
              type="text"
              class="form-control form-control-sm"
              placeholder="Cari..."
              style="width: 200px"
              v-model="searchQuery"
            />
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead class="text-center" style="background-color: #90ee90">
                <tr>
                  <th>TANGGAL<br />NO. URUT<br />(pcare)</th>
                  <th>NO. ANTRIAN<br />POLI</th>
                  <th>NO. MR<br />NAMA (UMUR)<br />NIK</th>
                  <th>ALAMAT<br />KECAMATAN-DESA</th>
                  <th>NO. BPJS<br />STATUS</th>
                  <th>POLI</th>
                  <th>UNIT<br />SUB UNIT</th>
                  <th>ACTION<br />PCARE<br />KATEGORI</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="loket.data.length > 0" v-for="item in loket.data" :key="item.idLoket">
                  <td>
                    {{ formatDate(item.tglKunjungan) }}
                    <small v-if="item.noPcare">({{ item.noPcare }})</small>
                  </td>
                  <td>
                    {{ item.noAntrian || `A${item.noUrut?.padStart(3, '0')}` }}
                  </td>
                  <td>
                    <!-- PERBAIKI: item.NO_MR (bukan item.pasien?.NO_MR) -->
                    {{ item.NO_MR }}<br />
                    {{ item.NAMA_LGKP }} ({{ item.umur_tahun || '-' }} th)<br />
                    {{ item.NIK }}
                  </td>
                  <td>
                    <!-- PERBAIKI: formatAlamatUntukTabel(item) (bukan item.pasien) -->
                    {{ formatAlamatUntukTabel(item).rtRw }}<br />
                    {{ item.nama_kecamatan || '-' }} - {{ item.nama_kelurahan || '-' }}
                  </td>
                  <td>
                    <!-- PERBAIKI: item.noKartu (bukan item.pasien?.noKartu) -->
                    {{ item.noKartu || '-' }}<br />
                    {{ item.statusKartu || '-' }}
                  </td>
                  <td>{{ item.nmPoli }}</td>
                  <td>
                    {{ item.kategori_unit || '-' }}<br />
                    {{ item.nama_unit || '-' }}
                  </td>
                  <td>
                    {{ item.action || '-' }}<br />
                    {{ item.noPcare || '-' }}<br />
                    {{ item.kategori || '-' }}
                  </td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-info">
                      <i class="bi bi-eye"></i>
                    </button>
                    <button
                      class="btn btn-sm btn-success"
                      title="Cetak Kartu Pasien"
                      @click="cetakKartuPasien(item.pasien_id)"
                      :disabled="!item.pasien_id"
                    >
                      <i class="bi bi-printer"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
              <li
                v-for="link in loket.links"
                :key="link.label"
                class="page-item"
                :class="{ active: link.active, disabled: !link.url }"
              >
                <Link class="page-link" :href="link.url || '#'" v-html="link.label" />
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
  import { Link, useForm, router } from '@inertiajs/vue3';
  import { ref, defineProps, watch, onMounted, computed } from 'vue';

  const props = defineProps({
    loket: {
      type: Object,
      default: () => ({ data: [], links: [] }),
    },
    poliList: {
      type: Array,
      default: () => [],
    },
    kategoriUnits: {
      type: Object,
      default: () => ({}),
    },
    wilayah: {
      type: Object,
      default: () => ({}),
    },
  });

  // Data untuk master data
  const kategoriUnits = ref({});
  const unitList = ref({});
  const wilayahList = ref({});
  const masterKecamatan = ref({});
  const masterKelurahan = ref({});

  // Data tambahan untuk fitur otomatis
  const autoDetectedStatus = ref('');
  const autoDetectedWilayah = ref('');

  // Loading states
  const loading = ref({
    kategoriUnit: false,
    unitList: false,
    wilayah: false,
  });

  // Data pencarian
  const searchParams = ref({
    no_mr: '',
    nik: '',
    no_bpjs: '',
  });

  // Data pasien yang dipilih
  const selectedPasien = ref({
    ID: '',
    NO_MR: '',
    NIK: '',
    NAMA_LGKP: '',
    ALAMAT: '',
    noKartu: '',
    statusKartu: '',
    kdProvider: '',
    jenisPeserta: '',
    PHONE: '',
    TGL_LHR: '',
    kecamatan: null,
    kelurahan: null,
    NO_KEC: '',
    NO_KEL: '',
    NO_RT: '',
    NO_RW: '',
  });

  // Form pendaftaran
  const form = useForm({
    pasienId: '',
    tglKunjungan: new Date().toISOString().split('T')[0],
    kategoriUnitId: '',
    unitId: '',
    PHONE: '',
    wilayah: '',
    kunjBaru: 'true',
    jknPbi: 'NON_BPJS',
    kunjSakit: 'true',
    kdPoli: '',
    kdTkp: '10',
    noUrut: '',
    kelUmur: 1,
    umur: 30,
    statusPasien: 'umum',
    createdBy: 'test_user',
    kdProvider: '',
    statusKartu: '',
    noKartu: '',
    kdKegiatan: 1,
    puskId: 1,
    TGL_LHR: '',
  });

  // Computed properties untuk mapping UI ke database
  const jenisPengunjungAuto = computed(() => {
    if (!selectedPasien.value.ID || !form.tglKunjungan) {
      return 'Pengunjung Baru';
    }

    return autoDetectedStatus.value || 'Pengunjung Baru';
  });

  const currentMonthYear = computed(() => {
    if (!form.tglKunjungan) return '';
    const date = new Date(form.tglKunjungan);
    return date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
  });

  // Computed untuk jenisPengunjung
  const jenisPengunjung = computed({
    get: () => {
      return form.kunjBaru === 'true' ? 'Pengunjung Baru' : 'Pengunjung Lama';
    },
    set: (value) => {
      form.kunjBaru = value === 'Pengunjung Baru' ? 'true' : 'false';
    },
  });

  const jenisKunjungan = computed({
    get: () => (form.kunjSakit === 'true' ? 'Kunjungan Sakit' : 'Kunjungan Sehat'),
    set: (value) => {
      form.kunjSakit = value === 'Kunjungan Sakit' ? 'true' : 'false';
    },
  });

  const kategori = computed({
    get: () => form.jknPbi,
    set: (value) => {
      form.jknPbi = value;
    },
  });

  // COMPUTED UNTUK KODE TKP DENGAN DEFAULT
  const kodeTKP = computed({
    get: () => {
      const mappings = {
        10: 'RJTP (RAWAT JALAN)',
        20: 'RJTL (RAWAT JALAN LANJUTAN)',
        50: 'Promotif',
      };
      return mappings[form.kdTkp] || 'RJTP (RAWAT JALAN)';
    },
    set: (value) => {
      const mappings = {
        'RJTP (RAWAT JALAN)': '10',
        'RJTL (RAWAT JALAN LANJUTAN)': '20',
        'Promotif': '50',
      };
      form.kdTkp = mappings[value] || '10';
    },
  });

  // Data untuk menyimpan daftar poli
  const filteredPoliList = ref([]);

  // Pencarian tabel
  const searchQuery = ref('');
  const perPage = ref(10);

  // Computed property untuk validasi form
  const formValid = computed(() => {
    return selectedPasien.value.ID && form.kategoriUnitId && form.unitId && form.wilayah;
  });

  // ========== METHOD OTOMATIS ==========

  // Method untuk cek status otomatis dari backend
  const checkAutoStatus = async () => {
    if (!selectedPasien.value.ID || !form.tglKunjungan) {
      return;
    }

    try {
      const params = new URLSearchParams({
        pasienId: selectedPasien.value.ID,
        tglKunjungan: form.tglKunjungan,
      });

      const response = await fetch(
        route('loket.api.check-jenis-pengunjung') + '?' + params.toString()
      );

      if (response.ok) {
        const data = await response.json();
        autoDetectedStatus.value = data.status;

        // Set nilai otomatis berdasarkan deteksi sistem
        form.kunjBaru = data.status === 'Pengunjung Baru' ? 'true' : 'false';
      }
    } catch (error) {
      console.error('Error cek status pengunjung:', error);
      autoDetectedStatus.value = 'Pengunjung Baru';
      form.kunjBaru = 'true';
    }
  };

  // checkAutoWilayah dengan error handling yang proper
  const checkAutoWilayah = async () => {
    if (!selectedPasien.value.ID) {
      return;
    }

    console.log('Memeriksa wilayah otomatis untuk pasien:', selectedPasien.value.ID);

    try {
      const params = new URLSearchParams({
        pasienId: selectedPasien.value.ID,
      });

      const response = await fetch(route('loket.api.wilayah-otomatis') + '?' + params.toString());

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
      }

      const data = await response.json();

      // VALIDASI RESPONSE
      if (data.error) {
        throw new Error(data.message || `Error: ${data.error}`);
      }

      if (!data.wilayah) {
        throw new Error('Server tidak mengembalikan data wilayah');
      }

      // SET DATA JIKA SEMUA VALID
      autoDetectedWilayah.value = data.wilayah;

      if (!form.wilayah) {
        form.wilayah = data.wilayah;
        console.log('Wilayah otomatis berhasil:', data.wilayah);
      } else {
        console.log('Wilayah terdeteksi:', data.wilayah, '(tetap menggunakan pilihan manual)');
      }
    } catch (error) {
      console.error('Error deteksi wilayah otomatis:', error.message);

      // TAMPILKAN PESAN ERROR YANG JELAS
      const errorMessage =
        `Gagal mendeteksi wilayah otomatis ${error.message} Silakan pilih wilayah secara manual dari dropdown.`.trim();

      alert(errorMessage);
    }
  };

  // Fungsi untuk memuat daftar unit berdasarkan kategori
  const loadUnitList = async () => {
    if (!form.kategoriUnitId) {
      unitList.value = {};
      form.unitId = '';
      return;
    }

    loading.value.unitList = true;
    try {
      const response = await fetch(route('loket.api.unit-list', form.kategoriUnitId));
      if (response.ok) {
        const data = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        const options = doc.querySelectorAll('option');

        unitList.value = {};
        options.forEach((option) => {
          if (option.value) {
            unitList.value[option.value] = option.textContent;
          }
        });
      } else {
        console.error('Gagal memuat daftar unit');
      }
    } catch (error) {
      console.error('Error memuat unit:', error);
    } finally {
      loading.value.unitList = false;
    }
  };

  // Fungsi untuk memuat data master saat komponen dimuat
  const loadMasterData = async () => {
    kategoriUnits.value = props.kategoriUnits || {};
    wilayahList.value = props.wilayah || {};

    if (Object.keys(kategoriUnits.value).length === 0) {
      loading.value.kategoriUnit = true;
      try {
        const response = await fetch(route('loket.api.kategori-unit'));
        if (response.ok) {
          kategoriUnits.value = await response.json();
        }
      } catch (error) {
        console.error('Error memuat kategori unit:', error);
      } finally {
        loading.value.kategoriUnit = false;
      }
    }

    if (Object.keys(wilayahList.value).length === 0) {
      loading.value.wilayah = true;
      try {
        const response = await fetch(route('loket.api.wilayah'));
        if (response.ok) {
          wilayahList.value = await response.json();
        }
      } catch (error) {
        console.error('Error memuat wilayah:', error);
      } finally {
        loading.value.wilayah = false;
      }
    }
  };

  // Fungsi untuk memperbarui opsi poli berdasarkan jenis kunjungan
  const updatePoliOptions = async () => {
    try {
      const params = new URLSearchParams({
        jenisKunjungan: jenisKunjungan.value,
      });

      const response = await fetch(route('loket.api.poli-by-jenis') + '?' + params.toString(), {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
      });

      if (response.ok) {
        filteredPoliList.value = await response.json();
      } else {
        console.error('Gagal mengambil data poli');
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  // Fungsi untuk load master data wilayah
  const loadMasterWilayah = async () => {
    try {
      // Load kecamatan untuk Banyuwangi (NO_PROP=35, NO_KAB=10)
      const responseKecamatan = await fetch(
        route('loket.api.kecamatan') + '?propinsi=35&kabupaten=10'
      );
      if (responseKecamatan.ok) {
        const dataKecamatan = await responseKecamatan.json();
        // Convert array to object for easy lookup
        masterKecamatan.value = {};
        dataKecamatan.forEach((kec) => {
          masterKecamatan.value[kec.NO_KEC] = kec.NAMA_KEC;
        });
      }

      // Load kelurahan akan dilakukan on-demand saat ada data pasien
    } catch (error) {
      console.error('Error loading master wilayah:', error);
    }
  };

  // Mapping kecamatan & kelurahan kode ke nama
  const formatKecamatanKelurahan = computed(() => {
    const pasien = selectedPasien.value;

    if (pasien.nama_kecamatan && pasien.nama_kelurahan) {
      return `${pasien.nama_kecamatan} - ${pasien.nama_kelurahan}`;
    }

    return `${pasien.NO_KEC} ${pasien.NO_KEL}`;
  });

  const formatAlamatUntukTabel = (item) => {
    if (!item) return { rtRw: '', wilayah: '' };

    // PERBAIKI: Akses langsung dari item (bukan item.pasien)
    const rtRw = item.NO_RT || item.NO_RW ? `RT ${item.NO_RT || '?'}/RW ${item.NO_RW || '?'}` : '';

    const wilayah =
      item.nama_kecamatan && item.nama_kelurahan
        ? `${item.nama_kecamatan} - ${item.nama_kelurahan}`
        : `Kec.${item.NO_KEC || '?'} Kel.${item.NO_KEL || '?'}`;

    return { rtRw, wilayah };
  };

  const cetakKartuPasien = (pasienId) => {
    if (pasienId) {
      // Navigasi ke halaman cetak kartu di window yang sama
      router.visit(route('loket.cetak_kartu', pasienId));
    } else {
      alert('Data pasien tidak tersedia untuk dicetak');
    }
  };

  // const debugUnitDetailContent = () => {
  //   if (props.loket.data.length > 0) {
  //     const item = props.loket.data[0];
  //     console.log('=== DEBUG UNIT_DETAIL CONTENT ===');
  //     console.log('unit_detail object:', item.unit_detail);
  //     console.log('Keys in unit_detail:', Object.keys(item.unit_detail || {}));
  //     console.log('master_unit:', item.unit_detail?.master_unit);
  //     console.log('Keys in master_unit:', Object.keys(item.unit_detail?.master_unit || {}));
  //     console.log('nama_unit:', item.unit_detail?.nama_unit);
  //     console.log('id_kategori:', item.unit_detail?.id_kategori);
  //   }
  // };

  // Fungsi pencarian pasien
  const searchPasien = async (type) => {
    try {
      if (!searchParams.value[type]) {
        alert('Harap isi field yang ingin dicari');
        return;
      }

      const params = new URLSearchParams();
      params.append(type, searchParams.value[type]);

      const url = route('api.pasien.search') + '?' + params.toString();
      const response = await fetch(url);

      if (!response.ok) {
        if (response.status === 404) {
          throw new Error('Pasien tidak ditemukan');
        }
        throw new Error('Terjadi kesalahan saat mencari pasien');
      }

      const data = await response.json();

      // Isi data pasien yang ditemukan ke field yang sesuai
      selectedPasien.value = {
        ...selectedPasien.value,
        ...data,
        ID: data.ID || '',
        NO_MR: data.NO_MR || '',
        NIK: data.NIK || '',
        NAMA_LGKP: data.NAMA_LGKP || '',
        ALAMAT: data.ALAMAT || '',
        noKartu: data.noKartu || '',
        PHONE: data.PHONE || '',
        TGL_LHR: data.TGL_LHR || '',
        kecamatan: data.kecamatan || null,
        kelurahan: data.kelurahan || null,
        NO_KEC: data.NO_KEC || '',
        NO_KEL: data.NO_KEL || '',
      };

      if (data.NO_KEC && data.NO_KEL) {
        await loadKelurahanByKecamatan(data.NO_KEC);
      }

      // Mengisi field pencarian dengan data yang ditemukan
      searchParams.value.no_mr = data.NO_MR || '';
      searchParams.value.nik = data.NIK || '';
      searchParams.value.no_bpjs = data.noKartu || '';

      form.pasienId = data.ID;
      form.PHONE = data.PHONE || '';
      form.TGL_LHR = data.TGL_LHR || '';

      // Set nilai untuk field required
      form.kunjSakit = jenisKunjungan.value === 'Kunjungan Sakit';
      form.kunjBaru = jenisPengunjung.value === 'Pengunjung Baru';
      form.jknPbi = kategori.value;
      form.kdProvider = kategori.value === 'BPJS' ? 'BPJS' : '';
      form.statusKartu = kategori.value === 'BPJS' ? 'Aktif' : '';
      form.noKartu = kategori.value === 'BPJS' ? data.noKartu || '' : '';

      // Cek status otomatis setelah data pasien tersedia
      await checkAutoStatus();
      await checkAutoWilayah();
    } catch (error) {
      alert(error.message);
      console.error('Error:', error);

      // Reset data pasien jika tidak ditemukan
      selectedPasien.value = {
        ID: '',
        NO_MR: '',
        NIK: '',
        NAMA_LGKP: '',
        ALAMAT: '',
        noKartu: '',
        statusKartu: '',
        kdProvider: '',
        jenisPeserta: '',
        PHONE: '',
        TGL_LHR: '',
        kecamatan: null,
        kelurahan: null,
        NO_KEC: '',
        NO_KEL: '',
        NO_RT: '',
        NO_RW: '',
      };

      form.pasienId = '';
      form.PHONE = '';
      form.TGL_LHR = '';
    }
  };

  // Fungsi untuk load kelurahan berdasarkan kecamatan
  const loadKelurahanByKecamatan = async (kodeKecamatan) => {
    try {
      const response = await fetch(
        route('loket.api.kelurahan') +
          '?kecamatan=' +
          kodeKecamatan +
          '&propinsi=' +
          selectedPasien.value.NO_PROP +
          '&kabupaten=' +
          selectedPasien.value.NO_KAB
      );

      if (response.ok) {
        const dataKelurahan = await response.json();
        masterKelurahan.value[kodeKecamatan] = {};
        dataKelurahan.forEach((kel) => {
          masterKelurahan.value[kodeKecamatan][kel.NO_KEL] = kel.NAMA_KEL;
        });
      }
    } catch (error) {
      console.error('Error loading kelurahan:', error);
    }
  };

  // Fungsi pendaftaran
  const registerPasien = () => {
    if (!selectedPasien.value.ID) {
      alert('Silakan cari pasien terlebih dahulu');
      return;
    }

    if (!formValid.value) {
      alert('Harap lengkapi semua field yang wajib diisi (Kategori Unit, Unit, dan Wilayah)');
      return;
    }

    form.post(route('loket.simpan'), {
      preserveScroll: true,
      onSuccess: (page) => {
        // Handle success message dari Inertia
        if (page.props.success) {
          alert(page.props.success);
        }

        // Reset form setelah berhasil
        resetForm();
      },
      onError: (errors) => {
        console.error('Errors:', errors);
        if (errors.message) {
          alert('Terjadi kesalahan: ' + errors.message);
        } else {
          alert('Terjadi kesalahan. Silakan coba lagi.');
        }
      },
      onFinish: () => {
        // Loading state cleanup jika needed
      },
    });
  };

  // Fungsi reset form yang komprehensif
  const resetForm = () => {
    // Reset selected pasien
    selectedPasien.value = {
      ID: '',
      NO_MR: '',
      NIK: '',
      NAMA_LGKP: '',
      ALAMAT: '',
      noKartu: '',
      statusKartu: '',
      kdProvider: '',
      jenisPeserta: '',
      PHONE: '',
      TGL_LHR: '',
      kecamatan: null,
      kelurahan: null,
      NO_KEC: '',
      NO_KEL: '',
      NO_RT: '',
      NO_RW: '',
    };

    // Reset search params
    searchParams.value = {
      no_mr: '',
      nik: '',
      no_bpjs: '',
    };

    // Reset form fields
    form.reset();

    // Set default values
    form.tglKunjungan = new Date().toISOString().split('T')[0];
    form.kunjSakit = 'true';
    form.kunjBaru = 'true';
    form.jknPbi = 'NON_BPJS';
    form.kategoriUnitId = '';
    form.unitId = '';
    form.wilayah = '';
    form.kdTkp = '10';
    form.kdPoli = '';
    form.PHONE = '';
    form.TGL_LHR = '';
    form.kelUmur = 1;
    form.umur = 30;

    // Reset computed values
    autoDetectedStatus.value = '';
    autoDetectedWilayah.value = '';
    jenisPengunjung.value = 'Pengunjung Baru';
    jenisKunjungan.value = 'Kunjungan Sakit';
    kategori.value = 'NON_BPJS';
    kodeTKP.value = 'RJTP (RAWAT JALAN)';

    // Reset unit list
    unitList.value = {};
  };

  // Fungsi untuk menampilkan riwayat kunjungan
  const showRiwayatKunjungan = () => {
    if (!selectedPasien.value.ID) {
      alert('Silakan pilih pasien terlebih dahulu');
      return;
    }
    alert('Fitur riwayat kunjungan akan segera tersedia');
  };

  // Format tanggal
  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID');
  };

  // Fungsi cek status kartu
  const checkCardStatus = () => {
    if (!selectedPasien.value.noKartu) {
      alert('Pasien tidak memiliki nomor BPJS');
      return;
    }

    // Simulasi cek status kartu
    selectedPasien.value.statusKartu = 'Aktif';
    selectedPasien.value.kdProvider = 'BPJS Kesehatan';
    selectedPasien.value.jenisPeserta = 'PBI';
  };

  // Fungsi buka modal pencarian
  const openSearchModal = () => {
    router.visit(route('loket.search'));
  };

  // ========== WATCH FUNCTIONS ==========

  // Watch perubahan pada computed properties
  watch(jenisPengunjung, (newValue) => {
    form.kunjBaru = newValue === 'Pengunjung Baru' ? 'true' : 'false';
  });

  watch(jenisKunjungan, (newValue) => {
    form.kunjSakit = newValue === 'Kunjungan Sakit' ? 'true' : 'false';
    updatePoliOptions();
    form.kdPoli = '';
  });

  watch(kategori, (newValue) => {
    form.jknPbi = newValue;
    form.kdProvider = newValue === 'BPJS' ? 'BPJS' : '';
    form.statusKartu = newValue === 'BPJS' ? 'Aktif' : '';

    if (newValue === 'BPJS' && selectedPasien.value.noKartu) {
      form.noKartu = selectedPasien.value.noKartu;
    } else {
      form.noKartu = '';
    }
  });

  // Watch untuk perubahan pasien
  watch(
    () => selectedPasien.value.ID,
    (newId, oldId) => {
      if (newId && newId !== oldId) {
        checkAutoStatus();
        checkAutoWilayah();
      }
    }
  );

  // Watch untuk tanggal kunjungan (hanya pengunjung)
  watch(
    () => form.tglKunjungan,
    (newDate, oldDate) => {
      if (newDate && newDate !== oldDate && selectedPasien.value.ID) {
        checkAutoStatus();
      }
    }
  );

  // Inisialisasi data saat komponen dimuat
  onMounted(() => {
    filteredPoliList.value = props.poliList;
    loadMasterData();
    loadMasterWilayah();
    // debugUnitDetailContent();
    console.log('=== DEBUG LOKET DATA STRUCTURE ===');
    if (props.loket.data.length > 0) {
      console.log('Sample item:', props.loket.data[0]);
      console.log('Available keys:', Object.keys(props.loket.data[0]));
    }

    // Set initial values untuk computed properties
    jenisPengunjung.value = 'Pengunjung Baru';
    jenisKunjungan.value = 'Kunjungan Sakit';
    kategori.value = 'NON_BPJS';
    kodeTKP.value = 'RJTP (RAWAT JALAN)';
  });
</script>

<style scoped>
  .form-label {
    font-weight: 500;
  }
  .table th,
  .table td {
    vertical-align: middle;
  }
  .spinner {
    animation: spin 1s linear infinite;
  }
  @keyframes spin {
    from {
      transform: rotate(0deg);
    }
    to {
      transform: rotate(360deg);
    }
  }
</style>
