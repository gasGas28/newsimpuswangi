<template>
  <div class="container-fluid p-3">
    <div class="row">
      <!-- ========================= Diagnosa Medis ========================= -->
      <form class="col-6">
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
            <select class="form-select">
              <option v-for="alrgm in AlergiMakanan" :key="alrgm.kodeBpjs">
                {{ alrgm.namaAlergiBpjs }}
              </option>
            </select>
          </div>
          <div class="col-3 d-flex flex-column">
            <label class="fw-bold">Alergi Obat</label>
            <select class="form-select">
              <option
                v-for="alrgo in AlergiObat"
                :key="alrgo.kodeBpjs"
                :value="alrgo.namaAlergiBpjs"
              >
                {{ alrgo.namaAlergiBpjs }}
              </option>
            </select>
          </div>
          <div class="col-6 d-flex flex-column">
            <label class="fw-bold">Keterangan Alergi</label>
            <input type="text" class="form-control bg-warning bg-opacity-75" />
          </div>
        </div>

        <!-- Kunjungan -->
        <div class="row mb-3">
          <div class="col-3 d-flex flex-column">
            <label class="fw-bold">Kunjungan Kasus</label>
            <select class="form-control" v-model="form.kunjungan_khusus">
              <option value="">- Pilih -</option>
              <option value="1">Kasus Baru</option>
              <option value="2">Kasus Lama</option>
              <option value="3">Kunjungan Kasus Lama</option>
              <option value="4">Kunjungan Kasus Baru</option>
            </select>
          </div>
          <div class="col-9 d-flex flex-column">
            <label class="fw-bold">Keterangan</label>
            <input type="text" class="form-control" v-model="form.keterangan" />
          </div>
        </div>

        <button @click="saveForm" class="btn btn-success">
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

        <form class="row">
          <div class="fw-bold t mb-4">
            <p class="d-inline-block px-2">Diagnosa Keperawatan</p>
          </div>
          <select class="form-control">
            <option value="">-- Pilih --</option>
            <option v-for="item in diagnosaKeperawatan" :key="item.kdDiag">
              {{ item.nmDiag }}
            </option>
          </select>

          <button type="button" @click="formDiagnosaKep" class="btn btn-success mt-4">
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
            <tr v-for="(diag, index) in DataDiagnosa" :key="diag.idDiagnosa">
              <td>{{ index + 1 }}</td>
              <td>{{ diag.nmDiagnosa }}</td>
              <td>{{ diag.keterangan }}</td>
              <td>{{ diag.diagnosaKasus }}</td>
              <td>KIA</td>
              <td>
                <button @click="hapusData(diag.idDiagnosa)" class="btn btn-danger btn-sm">
                  Hapus
                </button>
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
            <tr>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>Umum</td>
              <td>
                <button class="btn btn-danger btn-sm">Hapus</button>
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
                <tr v-for="item in paginatedDiagnosa" :key="item.id">
                  <td>{{ item.kdDiag }}</td>
                  <td>{{ item.nmDiag }}</td>
                  <td>
                    <button class="btn btn-info btn-sm" @click="pilihDiagnosa(item)">Pilih</button>
                  </td>
                </tr>
                <tr v-if="paginatedDiagnosa.length === 0">
                  <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                </tr>
              </tbody>
            </table>

            <nav class="d-flex justify-content-between align-items-center mt-2">
              <p class="mb-0 text-muted small">
                Menampilkan {{ startItem }} - {{ endItem }} dari {{ filteredDiagnosa.length }} data
              </p>

              <ul class="pagination pagination-sm mb-0">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                  <button class="page-link" @click="prevPage">&laquo;</button>
                </li>

                <li
                  v-for="(page, index) in visiblePages"
                  :key="index"
                  class="page-item"
                  :class="{ active: page === currentPage, disabled: page === '...' }"
                >
                  <button v-if="page !== '...'" class="page-link" @click="goToPage(page)">
                    {{ page }}
                  </button>
                  <span v-else class="page-link">...</span>
                </li>

                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                  <button class="page-link" @click="nextPage">&raquo;</button>
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
  import { route } from 'ziggy-js';
  import { router } from '@inertiajs/vue3';

  const props = defineProps({
    DataPasien: Array,
    DataDiagnosa: Array,
    diagnosa: Array,
    diagnosaKeperawatan: Array,
    AlergiMakanan: Array,
    AlergiObat: Array,
  });

  // Form utama
  const form = ref({
    kode_diagnosa: '',
    nama_diagnosa: '',
    kunjungan_khusus: '',
    keterangan: '',
    kdPoli: props.DataPasien[0].kdPoli,
    loketId: props.DataPasien[0].idLoket,
    pelayananId: props.DataPasien[0].idPelayanan,
  });

  const saveForm = () => {
    router.post(route('ruang-layanan-anc.dataDiagnosa'), form.value, {
      onSuccess: () => {
        alert('Data Diagnosa berhasil disimpan!');
        form.value = {
          kode_diagnosa: '',
          nama_diagnosa: '',
          kunjungan_khusus: '',
          keterangan: '',
        };
      },
      onError: (errors) => {
        console.error(errors);
        alert('Gagal menyimpan data.');
      },
    });
  };
  const formDiagnosaKep = () => {
    router.post(route('ruang-layanan-anc.diagnosaKep'), form.value, {
      onSuccess: () => {
        alert('Data Diagnosa berhasil disimpan!');
        form.value = {
          diagnosaKep: '',
        };
      },
      onError: (errors) => {
        console.error(errors);
        alert('Gagal menyimpan data.');
      },
    });
  };
  // Modal control
  const showModal = ref(false);
  const keyword = ref('');

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

  // Pagination ringkas: hanya tampilkan beberapa halaman di sekitar halaman aktif
  const visiblePages = computed(() => {
    const total = totalPages.value;
    const current = currentPage.value;
    const maxVisible = 5; // jumlah maksimum nomor halaman yang ditampilkan
    const pages = [];

    if (total <= maxVisible) {
      for (let i = 1; i <= total; i++) pages.push(i);
    } else {
      if (current <= 3) {
        pages.push(1, 2, 3, 4, '...', total);
      } else if (current >= total - 2) {
        pages.push(1, '...', total - 3, total - 2, total - 1, total);
      } else {
        pages.push(1, '...', current - 1, current, current + 1, '...', total);
      }
    }
    return pages;
  });

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
  const hapusData = (id) => {
    if (confirm('Yakin ingin menghapus data ini?')) {
      router.delete(route('diagnosa.destroy', id), {
        onSuccess: () => alert('Data berhasil dihapus!'),
        onError: (error) => {
          console.error(error);
          alert('Gagal menghapus data!');
        },
      });
    }
  };
</script>

<style scoped>
  .modal {
    display: block;
  }
  .bg-warning {
    color: #000;
  }

  .pagination .page-link {
    border-radius: 50%;
    margin: 0 2px;
    min-width: 32px;
    text-align: center;
  }

  .pagination .page-item.active .page-link {
    background-color: #198754;
    border-color: #198754;
    color: #fff;
  }

  .pagination .page-item.disabled .page-link {
    color: #aaa;
  }
</style>
