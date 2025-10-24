<template>
  <h5 class="fw-semibold text-danger">Pemantauan dan Pendampingan</h5>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <!-- FORM UTAMA -->
      <form @submit.prevent="saveForm">
        <div class="row g-4">
          <!-- Kolom Kiri -->
          <div class="col-md-6">
            <div class="form-section">
              <div class="mb-3">
                <label class="form-label d-block"
                  >1. Terlalu muda usia melahirkan dibawah 20 tahun</label
                >
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="usiamuda" value="iya" />
                  <label class="form-check-label" for="yes">Iya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="usiamuda" value="tidak" />
                  <label class="form-check-label" for="tidak">Tidak</label>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label d-block"
                  >2. Terlalu rapat jarak kelahiran (< 2 tahun)
                </label>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="rapat-jarak" value="iya" />
                  <label class="form-check-label" for="yes">Iya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="rapat-jarak" value="tidak" />
                  <label class="form-check-label" for="tidak">Tidak</label>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label d-block"
                  >3. Terlalu tua (kehamilan di atas 35 tahun)
                </label>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="terlalu-tua" value="iya" />
                  <label class="form-check-label" for="yes">Iya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="terlalu-tua" value="tidak" />
                  <label class="form-check-label" for="tidak">Tidak</label>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label d-block">4. Terlalu sering melahirkan (anak > 3) </label>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="terlalu-sering" value="iya" />
                  <label class="form-check-label" for="yes">Iya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="radio"
                    name="terlalu-sering"
                    value="tidak"
                  />
                  <label class="form-check-label" for="tidak">Tidak</label>
                </div>
              </div>

              <!-- Bagian Complikasi -->
              <h5 class="fw-bold mb-2">A. Komplikasi Penyulit Kehamilan</h5>

              <label class="form-label fw-semibold d-block">Komplikasi</label>
              <div class="row mb-3">
                <div class="col-3">
                  <input type="text" class="form-control" disabled v-model="form.kode_complikasi" />
                </div>
                <div class="col-9">
                  <div class="input-group">
                    <input
                      type="text"
                      class="form-control bg-light"
                      disabled
                      v-model="form.nama_complikasi"
                    />
                    <button type="button" class="btn btn-info" @click="showModal = true">
                      Cari
                    </button>
                    <button type="button" class="btn btn-danger" @click="hapusForm">Del</button>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi Komplikasi</label>
                <textarea
                  class="form-control"
                  rows="4"
                  placeholder="Tuliskan Deskripsi Komplikasi"
                ></textarea>
              </div>

              <label class="form-label fw-semibold d-block"
                >B. Riwayat Penyakit Menular Pribadi</label
              >
              <div class="row mb-3">
                <div class="col-3">
                  <input type="text" class="form-control" disabled v-model="form.kode_riwayat" />
                </div>
                <div class="col-9">
                  <div class="input-group">
                    <input
                      type="text"
                      class="form-control bg-light"
                      disabled
                      v-model="form.nama_riwayat"
                    />
                    <button type="button" class="btn btn-info" @click="showModal1 = true">
                      Cari
                    </button>
                    <button type="button" class="btn btn-danger" @click="hapusRiwayat">Del</button>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Sumber</label>
                <select class="form-select">
                  <option value="NonClinic">Non Clinic</option>
                  <option value="Clinic">Clinic</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi Komplikasi</label>
                <textarea
                  class="form-control"
                  rows="4"
                  placeholder="Tuliskan Deskripsi Komplikasi"
                ></textarea>
              </div>
              <label class="form-label fw-semibold d-block">C. Riwayat Penyakit Keluarga</label>
              <div class="row mb-3">
                <div class="col-3">
                  <input
                    type="text"
                    class="form-control"
                    disabled
                    v-model="form.kode_riwayat_kel"
                  />
                </div>
                <div class="col-9">
                  <div class="input-group">
                    <input
                      type="text"
                      class="form-control bg-light"
                      disabled
                      v-model="form.nama_riwayat_kel"
                    />
                    <button type="button" class="btn btn-info" @click="showModal2 = true">
                      Cari
                    </button>
                    <button type="button" class="btn btn-danger" @click="hapusRiwayatKel">
                      Del
                    </button>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Sumber</label>
                <select class="form-select">
                  <option value="NonClinic">Non Clinic</option>
                  <option value="Clinic">Clinic</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi Komplikasi</label>
                <textarea
                  class="form-control"
                  rows="4"
                  placeholder="Tuliskan Deskripsi Komplikasi"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Kolom Kanan -->
          <div class="col-md-6">
            <div class="form-section">
              <div class="mb-3">
                <label class="form-label fw-semibold">Status Merokok</label>
                <select class="form-select">
                  <option value="Aktif">Aktif</option>
                  <option value="Pasif">Pasif</option>
                  <option value="Tidak Merokok">Tidak Merokok</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea
                  class="form-control"
                  rows="4"
                  placeholder="Tuliskan Deskripsi Status Merokok"
                ></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Status Alkohol</label>
                <select class="form-select">
                  <option value="Aktif">Aktif</option>
                  <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea
                  class="form-control"
                  rows="4"
                  placeholder="Tuliskan Deskripsi"
                ></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Apakah Disabilitas</label>
                <select class="form-select">
                  <option value="Aktif">Aktif</option>
                  <option value="Tidak">Tidak</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea
                  class="form-control"
                  rows="4"
                  placeholder="Tuliskan Deskripsi"
                ></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Apakah Mengikuti Kelas Hamil</label>
                <select class="form-select">
                  <option value="Ya">Ya</option>
                  <option value="Tidak">Tidak</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea
                  class="form-control"
                  rows="4"
                  placeholder="Tuliskan Deskripsi"
                ></textarea>
              </div>
              <div class="mb-2">
                <button type="submit" class="btn btn-success px-4 shadow-sm mt-4">
                  <i class="bi bi-save me-1"></i> Simpan Data
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- MODAL PILIH DIAGNOSA -->
    <div v-if="showModal" class="modal fade show d-block" style="background: rgba(0, 0, 0, 0.5)">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header d-flex justify-content-between align-items-center">
            <h5>Pilih Diagnosa</h5>
            <button class="btn-close" @click="showModal = false"></button>
          </div>

          <div class="modal-body">
            <!-- Input Pencarian -->
            <input
              type="text"
              v-model="keyword"
              class="form-control mb-3"
              placeholder="Cari diagnosa..."
            />

            <!-- Tabel Diagnosa -->
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

            <!-- Pagination Info -->
            <div class="d-flex justify-content-between align-items-center mt-2">
              <p class="text-muted small mb-0">
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
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Pilih Riwayat Pribadi -->
    <div v-if="showModal1" class="modal fade show d-block" style="background: rgba(0, 0, 0, 0.5)">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header d-flex justify-content-between align-items-center">
            <h5>Pilih Riwayat Pribadi</h5>
            <button class="btn-close" @click="showModal1 = false"></button>
          </div>

          <div class="modal-body">
            <!-- Input Pencarian -->
            <input
              type="text"
              v-model="keyword"
              class="form-control mb-3"
              placeholder="Cari diagnosa..."
            />

            <!-- Tabel Diagnosa -->
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>KODE</th>
                  <th>NAMA</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in paginatedRiwayat" :key="item.id">
                  <td>{{ item.code }}</td>
                  <td>{{ item.value_set }}</td>
                  <td>
                    <button class="btn btn-info btn-sm" @click="pilihRiwayat(item)">Pilih</button>
                  </td>
                </tr>
                <tr v-if="paginatedRiwayat.length === 0">
                  <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                </tr>
              </tbody>
            </table>

            <!-- Pagination Info -->
            <div class="d-flex justify-content-between align-items-center mt-2">
              <p class="text-muted small mb-0">
                Menampilkan {{ StartItem }} - {{ EndItem }} dari {{ filteredRiwayat.length }} data
              </p>
              <ul class="pagination pagination-sm mb-0">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                  <button class="page-link" @click="PrevPage">&laquo;</button>
                </li>
                <li
                  v-for="(page, index) in VisiblePages"
                  :key="index"
                  class="page-item"
                  :class="{ active: page === currentPage, disabled: page === '...' }"
                >
                  <button v-if="page !== '...'" class="page-link" @click="goPage(page)">
                    {{ page }}
                  </button>
                  <span v-else class="page-link">...</span>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === totalPage }">
                  <button class="page-link" @click="NextPage">&raquo;</button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

        <div v-if="showModal2" class="modal fade show d-block" style="background: rgba(0, 0, 0, 0.5)">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header d-flex justify-content-between align-items-center">
            <h5>Pilih Riwayat Keluarga</h5>
            <button class="btn-close" @click="showModal2 = false"></button>
          </div>

          <div class="modal-body">
            <!-- Input Pencarian -->
            <input
              type="text"
              v-model="keyword"
              class="form-control mb-3"
              placeholder="Cari diagnosa..."
            />

            <!-- Tabel Diagnosa -->
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>KODE</th>
                  <th>NAMA</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in paginatedRiwayat" :key="item.id">
                  <td>{{ item.code }}</td>
                  <td>{{ item.value_set }}</td>
                  <td>
                    <button class="btn btn-info btn-sm" @click="pilihRiwayatKel(item)">
                      Pilih
                    </button>
                  </td>
                </tr>
                <tr v-if="paginatedRiwayat.length === 0">
                  <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                </tr>
              </tbody>
            </table>

            <!-- Pagination Info -->
            <div class="d-flex justify-content-between align-items-center mt-2">
              <p class="text-muted small mb-0">
                Menampilkan {{ StartItem }} - {{ EndItem }} dari {{ filteredRiwayat.length }} data
              </p>
              <ul class="pagination pagination-sm mb-0">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                  <button class="page-link" @click="PrevPage">&laquo;</button>
                </li>
                <li
                  v-for="(page, index) in VisiblePages"
                  :key="index"
                  class="page-item"
                  :class="{ active: page === currentPage, disabled: page === '...' }"
                >
                  <button v-if="page !== '...'" class="page-link" @click="goPage(page)">
                    {{ page }}
                  </button>
                  <span v-else class="page-link">...</span>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === totalPage }">
                  <button class="page-link" @click="NextPage">&raquo;</button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';

  // State utama
  const showModal = ref(false);
  const showModal1 = ref(false);
  const showModal2 = ref(false);
  const keyword = ref('');
  const form = ref({
    kode_complikasi: '',
    kode_riwayat: '',
    kode_riwayat_kel: '',
    nama_complikasi: '',
    nama_riwayat_kel: '',
  });

  // Props dari Laravel
  const props = defineProps({
    diagnosa: Array,
    riwayat: Array,
  });

  // Pagination setup
  const currentPage = ref(1);
  const perPage = 10;

  // Filter pencarian
  const filteredDiagnosa = computed(() => {
    if (!keyword.value) return props.diagnosa;
    const lower = keyword.value.toLowerCase();
    return props.diagnosa.filter(
      (d) => d.kdDiag.toLowerCase().includes(lower) || d.nmDiag.toLowerCase().includes(lower)
    );
  });

  // Hitung total halaman
  const totalPages = computed(() => Math.ceil(filteredDiagnosa.value.length / perPage));

  // Data diagnosa per halaman
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

  // Pagination dinamis (visible pages)
  const visiblePages = computed(() => {
    const total = totalPages.value;
    const current = currentPage.value;
    const maxVisible = 5;
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

  // Info item awal-akhir
  const startItem = computed(() =>
    filteredDiagnosa.value.length === 0 ? 0 : (currentPage.value - 1) * perPage + 1
  );
  const endItem = computed(() =>
    Math.min(currentPage.value * perPage, filteredDiagnosa.value.length)
  );

  // Pilih diagnosa dari modal
  const pilihDiagnosa = (item) => {
    form.value.kode_complikasi = item.kdDiag;
    form.value.nama_complikasi = item.nmDiag;
    showModal.value = false;
  };

  // Reset form
  const hapusForm = () => {
    form.value.kode_complikasi = '';
    form.value.nama_complikasi = '';
  };

  // Filter pencarian
  const filteredRiwayat = computed(() => {
    if (!keyword.value) return props.riwayat;
    const lower = keyword.value.toLowerCase();
    return props.riwayat.filter(
      (d) => d.code.toLowerCase().includes(lower) || d.value_set.toLowerCase().includes(lower)
    );
  });

  // Hitung total halaman
  const totalPage = computed(() => Math.ceil(filteredRiwayat.value.length / perPage));

  // Data diagnosa per halaman
  const paginatedRiwayat = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    const end = start + perPage;
    return filteredRiwayat.value.slice(start, end);
  });

  // Navigasi halaman
  const goPage = (page) => {
    if (page >= 1 && page <= totalPage.value) currentPage.value = page;
  };
  const PrevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
  };
  const NextPage = () => {
    if (currentPage.value < totalPage.value) currentPage.value++;
  };

  // Pagination dinamis (visible pages)
  const VisiblePages = computed(() => {
    const total = totalPage.value;
    const current = currentPage.value;
    const maxVisible = 5;
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

  // Info item awal-akhir
  const StartItem = computed(() =>
    filteredRiwayat.value.length === 0 ? 0 : (currentPage.value - 1) * perPage + 1
  );
  const EndItem = computed(() =>
    Math.min(currentPage.value * perPage, filteredRiwayat.value.length)
  );

  // Pilih diagnosa dari modal
  const pilihRiwayat = (item) => {
    form.value.kode_riwayat = item.code;
    form.value.nama_riwayat = item.value_set;
    showModal1.value = false;
  };

  // Reset form
  const hapusRiwayat = () => {
    form.value.kode_riwayat = '';
    form.value.nama_riwayat = '';
  };

  const pilihRiwayatKel = (item) => {
    form.value.kode_riwayat_kel = item.code;
    form.value.nama_riwayat_kel = item.value_set;
    showModal2.value = false;
  };

  // Reset form
  const hapusRiwayatKel = () => {
    form.value.kode_riwayat_kel = '';
    form.value.nama_riwayat_kel = '';
  };

  // Simpan form
  const saveForm = () => {
    alert('Data disimpan!');
  };
</script>

<style scoped>
  .form-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 16px;
  }
  .table th,
  .table td {
    font-size: 0.9rem;
  }
  .pagination .page-link {
    cursor: pointer;
  }
</style>
