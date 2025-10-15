<template>
  <!-- Modal -->
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
              <tr
                v-for="item in paginatedDiagnosa"
                :key="item.id"
              >
                <td>{{ item.kdDiag }}</td>
                <td>{{ item.nmDiag }}</td>
                <td>
                  <button class="btn btn-info btn-sm" @click="pilihDiagnosa(item)">
                    Pilih
                  </button>
                </td>
              </tr>
              <tr v-if="paginatedDiagnosa.length === 0">
                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination -->
          <nav class="d-flex justify-content-between align-items-center">
            <p class="mb-0 text-muted small">
              Menampilkan {{ startItem }} - {{ endItem }} dari {{ filteredDiagnosa.length }} data
            </p>
            <ul class="pagination pagination-sm mb-0">
              <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <button class="page-link" @click="prevPage">&laquo;</button>
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
                <button class="page-link" @click="nextPage">&raquo;</button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Props dari parent
const props = defineProps({
  diagnosa: {
    type: Array,
    default: () => [],
  },
})

// Modal control
const showModal = ref(true)
const keyword = ref('')

// Pagination
const currentPage = ref(1)
const perPage = 10

// Filter diagnosa berdasarkan keyword
const filteredDiagnosa = computed(() => {
  if (!keyword.value) return props.diagnosa
  const lower = keyword.value.toLowerCase()
  return props.diagnosa.filter(
    (d) =>
      d.kdDiag.toLowerCase().includes(lower) ||
      d.nmDiag.toLowerCase().includes(lower)
  )
})

// Hitung total halaman
const totalPages = computed(() => Math.ceil(filteredDiagnosa.value.length / perPage))

// Ambil data sesuai halaman aktif
const paginatedDiagnosa = computed(() => {
  const start = (currentPage.value - 1) * perPage
  const end = start + perPage
  return filteredDiagnosa.value.slice(start, end)
})

// Navigasi halaman
const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) currentPage.value = page
}
const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--
}
const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++
}

// Info item yang ditampilkan
const startItem = computed(() => (filteredDiagnosa.value.length === 0 ? 0 : (currentPage.value - 1) * perPage + 1))
const endItem = computed(() => Math.min(currentPage.value * perPage, filteredDiagnosa.value.length))

// Fungsi pilih diagnosa
const pilihDiagnosa = (item) => {
  console.log('Diagnosa dipilih:', item)
  showModal.value = false
}
</script>

<style scoped>
.modal {
  display: block;
}
.page-item.disabled .page-link {
  pointer-events: none;
  opacity: 0.5;
}
.page-item.active .page-link {
  background-color: #0d6efd;
  border-color: #0d6efd;
  color: #fff;
}
.page-link {
  cursor: pointer;
}
</style>
