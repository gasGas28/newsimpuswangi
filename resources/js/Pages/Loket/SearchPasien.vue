<template>
  <AppLayouts>
    <div class="container py-4">
      <!-- Search dan Tambah -->
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <!-- Search -->
        <div class="input-group shadow-sm rounded-pill overflow-hidden w-50">
          <input
            type="text"
            class="form-control border-0"
            placeholder="Cari pasien..."
            v-model="searchQuery"
          />
          <span class="input-group-text bg-white border-0">
            <i class="bi bi-search"></i>
          </span>
        </div>

        <!-- Button Tambah -->
        <Link
          :href="route('loket.pasien')"
          class="btn btn-add fw-semibold text-white shadow-sm rounded-pill px-4"
        >
          <i class="bi bi-person-plus"></i> Tambah Pasien
        </Link>
      </div>

      <!-- Card -->
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
          <div
            class="d-flex justify-content-between align-items-center mb-0"
          >
            <!-- Total Pasien -->
            <div class="d-flex align-items-center gap-2 ps-2">
              <i class="bi bi-people text-primary fs-3"></i>
              <h5 class="mb-0 fw-semibold text-primary d-flex align-items-center gap-2 fs-4">
                Data Pasien
                <span class="badge bg-primary-subtle text-primary fw-semibold rounded-pill fs-6">
                  {{ props.pasien.length }} pasien
                </span>
              </h5>
            </div>

            <!-- Rows -->
            <div class="d-flex align-items-center bg-light px-3 py-1 rounded-pill shadow-sm gap-2">
              <span class="text-muted small">Rows</span>
              <select
                v-model="itemsPerPage"
                class="form-select form-select-sm border-0 bg-transparent fw-semibold shadow-none w-auto"
              >
                <option :value="5">5</option>
                <option :value="10">10</option>
                <option :value="20">20</option>
                <option :value="50">50</option>
              </select>
            </div>
          </div>
          <hr>
          <div class="table-responsive">
            <table class="table table-hover table-striped align-middle text-center mb-0">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>NO_MR<br />NO. KK</th>
                  <th>Nama<br />NIK</th>
                  <th>Alamat<br />Kecamatan - Desa</th>
                  <th>BPJS<br />Provider</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in paginatedPasien" :key="item.ID">
                  <td>{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                  <td>
                    {{ item.NO_MR }}<br /><small class="text-muted">{{ item.NO_KK }}</small>
                  </td>
                  <td class="fw-semibold">
                    {{ item.NAMA_LGKP }}<br />
                    <small class="text-muted">{{ item.NIK }}</small>
                  </td>
                  <td>
                    {{ item.ALAMAT }}<br /><small class="fw-semibold">{{ item.nama_kecamatan }} - {{ item.nama_kelurahan }}</small>
                  </td>
                  <td>
                    {{ item.noKartu }}<br /><small class="text-muted">{{ item.kdProvider }}</small>
                  </td>
                  <td>
                    <div class="d-flex justify-content-center gap-2">
                      <Link
                        :href="route('loket.index')"
                        class="btn btn-sm btn-outline-primary rounded-pill"
                        title="Ke Loket"
                      >
                        <i class="bi bi-eye"></i>
                      </Link>
                      <Link
                        :href="route('loket.edit', item.ID)"
                        class="btn btn-sm btn-outline-success rounded-pill"
                        title="Edit Data"
                      >
                        <i class="bi bi-pencil-square"></i>
                      </Link>
                    </div>
                  </td>
                </tr>
                <tr v-if="paginatedPasien.length === 0">
                  <td colspan="6" class="text-muted py-3">
                    <i class="bi bi-search"></i> Tidak ada data ditemukan
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-between align-items-center p-3">
            <small class="text-muted">
              Menampilkan {{ (currentPage - 1) * itemsPerPage + 1 }} -
              {{ Math.min(currentPage * itemsPerPage, filteredPasien.length) }}
              dari {{ filteredPasien.length }} data
            </small>

            <nav>
              <ul class="pagination pagination-sm mb-0">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                  <button class="page-link" @click="prevPage">«</button>
                </li>
                <li
                  v-for="page in totalPages"
                  :key="page"
                  class="page-item"
                  :class="{ active: page === currentPage }"
                >
                  <button class="page-link" @click="goToPage(page)">
                    {{ page }}
                  </button>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                  <button class="page-link" @click="nextPage">»</button>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
  import AppLayouts from '@/Components/Layouts/AppLayouts.vue';
  import { Link } from '@inertiajs/vue3';
  import { ref, computed } from 'vue';
  import { route } from 'ziggy-js';

  const props = defineProps({
    pasien: Object,
  });

  // 🔎 State
  const searchQuery = ref('');
  const currentPage = ref(1);
  const itemsPerPage = ref(5);

  // 🔎 Filter data pasien
  const filteredPasien = computed(() => {
    if (!searchQuery.value) return props.pasien;
    const query = searchQuery.value.toLowerCase();

    return props.pasien.filter(
      (item) =>
        (item.NO_MR || '').toLowerCase().includes(query) ||
        (item.NO_KK || '').toLowerCase().includes(query) ||
        (item.NAMA_LGKP || '').toLowerCase().includes(query) ||
        (item.NIK || '').toLowerCase().includes(query) ||
        (item.ALAMAT || '').toLowerCase().includes(query) ||
        (item.nama_kecamatan || '').toLowerCase().includes(query) ||
        (item.nama_kelurahan || '').toLowerCase().includes(query) ||
        (item.noKartu || '').toLowerCase().includes(query) ||
        (item.kdProvider || '').toLowerCase().includes(query)
    );
  });

  // 🔎 Pagination logic
  const paginatedPasien = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    return filteredPasien.value.slice(start, start + itemsPerPage.value);
  });

  const totalPages = computed(() => Math.ceil(filteredPasien.value.length / itemsPerPage.value));

  // 🔎 Methods
  const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
      currentPage.value = page;
    }
  };
  const prevPage = () => goToPage(currentPage.value - 1);
  const nextPage = () => goToPage(currentPage.value + 1);
</script>

<style scoped>
  .card-header {
    background: linear-gradient(135deg, #3b82f6, #10b981);
  }
  .btn-add {
    background: linear-gradient(135deg, #3b82f6, #10b981);
  }
  .bg-header {
    background: linear-gradient(135deg, #3b82f6, #10b981);
  }
</style>
