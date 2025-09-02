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

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Kategori Unit</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm" v-model="form.kategoriUnit">
                      <option selected disabled>- Pilih Kategori Unit -</option>
                      <option value="PUSKESMAS">PUSKESMAS</option>
                      <option value="KLINIK">KLINIK</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Unit</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm" v-model="form.unit">
                      <option selected disabled>- Pilih Unit -</option>
                      <option value="PUSKESMAS WONGSOREJO">PUSKESMAS WONGSOREJO</option>
                      <option value="PUSKESMAS PESANGGARAN">PUSKESMAS PESANGGARAN</option>
                    </select>
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
                      v-model="selectedPasien.ALAMAT"
                      disabled
                    ></textarea>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Wilayah *</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm bg-warning" v-model="form.wilayah">
                      <option value="">- Pilih -</option>
                      <option value="Dalam Wilayah">Dalam Wilayah</option>
                      <option value="Luar Wilayah">Luar Wilayah</option>
                    </select>
                    <small class="text-muted"
                      >*) Pemilihan wilayah, sesuaikan dengan alamat pasien !!</small
                    >
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Jenis Pengunjung *</label>
                  </div>
                  <div class="col-8">
                    <select
                      class="form-select form-select-sm bg-warning"
                      v-model="form.jenisPengunjung"
                    >
                      <option value="Pengunjung Lama">Pengunjung Lama</option>
                      <option value="Pengunjung Baru">Pengunjung Baru</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Kategori *</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm bg-warning" v-model="form.kategori">
                      <option value="NON BPJS">NON BPJS</option>
                      <option value="BPJS">BPJS</option>
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
                      v-model="form.jenisKunjungan"
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
                    <label class="form-label form-label-sm fw-bold">Poli Tujuan</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm" v-model="form.kdPoli">
                      <option value="">- Pilih Poli -</option>
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
                    <select class="form-select form-select-sm" v-model="form.kodeTKP">
                      <option value="RJTP (RAWAT JALAN)">RJTP (RAWAT JALAN)</option>
                      <option value="RJTL (RAWAT JALAN LANJUTAN)">
                        RJTL (RAWAT JALAN LANJUTAN)
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tombol Bawah -->
          <div class="d-flex justify-content-end mt-4 gap-2">
            <button class="btn btn-success">RIWAYAT KUNJUNGAN</button>
            <button class="btn btn-primary" @click="registerPasien" :disabled="!selectedPasien.ID">
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
                  <th>NO ANTRIAN<br />POLI</th>
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
                    {{ formatDate(item.tglKunjungan) }}<br />
                    {{ item.noUrut }}<br />
                    <small v-if="item.noPcare">({{ item.noPcare }})</small>
                  </td>
                  <td>{{ item.noAntrian }}<br />{{ item.poli?.nmPoli }}</td>
                  <td>
                    {{ item.pasien?.NO_MR }}<br />
                    {{ item.pasien?.NAMA_LGKP }}<br />
                    {{ item.pasien?.NIK }}
                  </td>
                  <td>
                    {{ item.pasien?.ALAMAT }}<br />
                    {{ item.pasien?.kecamatan?.NAMA_KEC }} - {{ item.pasien?.kelurahan?.NAMA_KEL }}
                  </td>
                  <td>
                    {{ item.pasien?.noKartu || '-' }}<br />
                    {{ item.statusKartu || '-' }}
                  </td>
                  <td>{{ item.poli?.nmPoli }}</td>
                  <td>
                    {{ item.unit }}<br />
                    {{ item.subUnit || '-' }}
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
                  </td>
                </tr>
                <tr v-else>
                  <td colspan="9" class="text-center">No matching records found</td>
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
  import { ref, defineProps, watch, onMounted } from 'vue';

  const props = defineProps({
    loket: {
      type: Object,
      default: () => ({ data: [], links: [] }),
    },
    poliList: {
      type: Array,
      default: () => [],
    },
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
  });

  // Form pendaftaran
  const form = useForm({
    pasienId: '',
    tglKunjungan: new Date().toISOString().split('T')[0],
    kategoriUnit: 'PUSKESMAS',
    unit: 'PUSKESMAS WONGSOREJO',
    PHONE: '',
    wilayah: 'Dalam Wilayah',
    jenisPengunjung: 'Pengunjung Lama',
    kategori: 'NON BPJS',
    jenisKunjungan: 'Kunjungan Sakit',
    kdPoli: '',
    kodeTKP: 'RJTP (RAWAT JALAN)',
    // Field-field required untuk database
    noUrut: '',
    kunjSakit: true,
    kunjBaru: 'false',
    kelUmur: 1,
    umur: 30,
    jknPbi: 'NON BPJS',
    statusPasien: 'umum',
    createdBy: 'test_user',
    kdProvider: '',
    statusKartu: '',
    noKartu: '',
    kdKegiatan: 1,
    puskId: 1,
    unitId: 1,
    kategoriUnitId: 1,
  });

  // Data untuk menyimpan daftar poli
  const filteredPoliList = ref([]);

  // Pencarian tabel
  const searchQuery = ref('');
  const perPage = ref(10);

  // Fungsi untuk memperbarui opsi poli berdasarkan jenis kunjungan
  const updatePoliOptions = async () => {
    try {
      const params = new URLSearchParams({
        jenisKunjungan: form.jenisKunjungan,
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
      };

      // Isi juga field pencarian dengan data yang ditemukan
      searchParams.value.no_mr = data.NO_MR || '';
      searchParams.value.nik = data.NIK || '';
      searchParams.value.no_bpjs = data.noKartu || '';

      form.pasienId = data.ID;
      form.PHONE = data.PHONE || '';

      // Set nilai untuk field required
      form.kunjSakit = form.jenisKunjungan === 'Kunjungan Sakit';
      form.kunjBaru = form.jenisPengunjung === 'Pengunjung Baru';
      form.jknPbi = form.kategori;
      form.kdProvider = form.kategori === 'BPJS' ? 'BPJS' : '';
      form.statusKartu = form.kategori === 'BPJS' ? 'Aktif' : '';
      form.noKartu = form.kategori === 'BPJS' ? data.noKartu || '' : '';
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
      };

      form.pasienId = '';
      form.PHONE = '';
    }
  };

  // Fungsi pendaftaran
  const registerPasien = () => {
    if (!selectedPasien.value.ID) {
      alert('Silakan cari pasien terlebih dahulu');
      return;
    }

    // Debug: tampilkan data yang akan dikirim
    console.log('Data yang dikirim:', form.data());

    form.post(route('loket.register'), {
      onSuccess: () => {
        alert('Pasien berhasil didaftarkan!');

        // Reset form setelah berhasil
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
        };

        searchParams.value = {
          no_mr: '',
          nik: '',
          no_bpjs: '',
        };

        form.reset();
        form.tglKunjungan = new Date().toISOString().split('T')[0];
        form.jenisKunjungan = 'Kunjungan Sakit';
        form.kunjSakit = true;
        form.kategoriUnit = 'PUSKESMAS';
        form.unit = 'PUSKESMAS WONGSOREJO';
        form.wilayah = 'Dalam Wilayah';
        form.jenisPengunjung = 'Pengunjung Lama';
        form.kategori = 'NON BPJS';
        form.kodeTKP = 'RJTP (RAWAT JALAN)';
      },
      onError: (errors) => {
        console.error('Errors:', errors);
        alert('Terjadi kesalahan: ' + (errors.message || Object.values(errors).join(', ')));
      },
    });
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

  // Watch perubahan pada jenis kunjungan
  watch(
    () => form.jenisKunjungan,
    (newValue) => {
      form.kunjSakit = newValue === 'Kunjungan Sakit';
      updatePoliOptions();
      form.kdPoli = '';
    }
  );

  // Watch perubahan pada jenis pengunjung
  watch(
    () => form.jenisPengunjung,
    (newValue) => {
      form.kunjBaru = newValue === 'Pengunjung Baru';
    }
  );

  // Watch perubahan pada kategori
  watch(
    () => form.kategori,
    (newValue) => {
      form.jknPbi = newValue;
      form.kdProvider = newValue === 'BPJS' ? 'BPJS' : '';
      form.statusKartu = newValue === 'BPJS' ? 'Aktif' : '';

      if (newValue === 'BPJS' && selectedPasien.value.noKartu) {
        form.noKartu = selectedPasien.value.noKartu;
      } else {
        form.noKartu = '';
      }
    }
  );

  // Inisialisasi data poli saat komponen dimuat
  onMounted(() => {
    filteredPoliList.value = props.poliList;
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
</style>
