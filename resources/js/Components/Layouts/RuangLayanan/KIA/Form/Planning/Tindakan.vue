<template>
  <h5 class="fw-semibold text-danger">Tindakan</h5>
  <div class="card border-0 shadow-sm rounded-4 p-3">
    <!-- Kode Tindakan -->
    <div class="row mb-2">
      <label class="col-sm-2 col-form-label">Kode Tindakan</label>
      <div class="col-sm-6 d-flex">
        <input
          type="text"
          class="form-control"
          disabled
          v-model="form.kode_tindakan"
          placeholder="Masukkan kode tindakan"
        />
        <button class="btn btn-info btn-sm ms-2 text-white" @click="showModal = true">Cari</button>
        <button class="btn btn-danger btn-sm ms-1" @click="hapusForm">Del</button>
      </div>
    </div>

    <!-- Nama Tindakan -->
    <div class="row mb-2">
      <label class="col-sm-2 col-form-label">Nama Tindakan</label>
      <div class="col-sm-6">
        <textarea disabled class="form-control" rows="1" v-model="form.nama_tindakan"></textarea>
      </div>
    </div>

    <!-- Nama Tindakan (Ind) -->
    <div class="row mb-2">
      <label class="col-sm-2 col-form-label">Nama Tindakan (Ind)</label>
      <div class="col-sm-6">
        <textarea
          disabled
          class="form-control"
          rows="1"
          v-model="form.nama_tindakan_ind"
        ></textarea>
      </div>
    </div>

    <!-- Keterangan -->
    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">Keterangan</label>
      <div class="col-sm-6">
        <textarea class="form-control" rows="1" v-model="form.keterangan"></textarea>
      </div>
    </div>

    <!-- Tombol Simpan -->
    <div class="row mb-4">
      <div class="col-sm-2"></div>
      <div class="col-sm-6">
        <button class="btn btn-primary btn-sm" @click="simpanData">Simpan</button>
      </div>
    </div>

    <hr />

    <!-- Tabel Data Tindakan -->
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Tindakan</th>
            <th>Peraturan</th>
            <th>Harga</th>
            <th>Bayar</th>
            <th>Poli</th>
            <th>Keterangan</th>
            <th>Ket Gigi</th>
            <th>Created By</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in dataTindakan" :key="i">
            <td>{{ i + 1 }}</td>
            <td>{{ item.kode }}</td>
            <td>{{ item.nama }}</td>
            <td>{{ item.peraturan }}</td>
            <td>{{ item.harga }}</td>
            <td>{{ item.bayar }}</td>
            <td>{{ item.poli }}</td>
            <td>{{ item.keterangan }}</td>
            <td>{{ item.ketGigi }}</td>
            <td>{{ item.createdBy }}</td>
            <td>
              <button class="btn btn-outline-secondary btn-sm">Edit</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Modal Pilih Tindakan -->
  <div
    v-if="showModal"
    class="modal fade show"
    tabindex="-1"
    style="display: block; background: rgba(0, 0, 0, 0.5)"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between align-items-center">
          <h5>Pilih Tindakan</h5>
          <button class="btn-close" @click="showModal = false"></button>
        </div>

        <div class="modal-body">
          <!-- Input Cari -->
          <input
            type="text"
            v-model="keyword"
            class="form-control mb-3"
            placeholder="Cari tindakan..."
          />

          <!-- <p class="text-muted">Kata kunci: {{ keyword }}</p> -->

          <!-- Tabel List Tindakan -->
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>KODE</th>
                <th>NAMA</th>
                <th>NAMA (IND)</th>
                <th>ACTION</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in paginatedTindakan" :key="item.idTindakan">
                <td>{{ item.kdTindakan }}</td>
                <td>{{ item.nmTindakan }}</td>
                <td>{{ item.nmTindakanInd }}</td>
                <td>
                  <button class="btn btn-info btn-sm" @click.stop="pilihTindakan(item)">
                    Pilih
                  </button>
                </td>
              </tr>
              <tr v-if="paginatedTindakan.length === 0">
                <td colspan="4" class="text-center text-muted">Tidak ada data</td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination -->
          <nav class="d-flex justify-content-between align-items-center mt-2">
            <p class="mb-0 text-muted small">
              Menampilkan {{ startItem }} - {{ endItem }} dari {{ filteredTindakan.length }} data
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
</template>

<script setup>
  import { ref, computed, toRaw } from 'vue';

  // Form utama
  const form = ref({
    kode_tindakan: '',
    nama_tindakan: '',
    nama_tindakan_ind: '',
    keterangan: '',
  });

  const props = defineProps({
    // tindakan: {
    //   type: Array,
    //   default: () => [
    //     { id: 1, kdTindakan: 'TDK001', nmTindakan: 'Pemeriksaan Umum', nmTindakanInd: 'General Checkup' },
    //     { id: 2, kdTindakan: 'TDK002', nmTindakan: 'Tes Darah', nmTindakanInd: 'Blood Test' },
    //     { id: 3, kdTindakan: 'TDK003', nmTindakan: 'Rontgen', nmTindakanInd: 'X-Ray' },
    //   ],
    // },
    tindakan: Array,
  });

  console.log('Item pertama:', props.tindakan[0]);

  // Modal control
  const showModal = ref(false);
  const keyword = ref('');

  // Pagination setup
  const currentPage = ref(1);
  const perPage = 10;

  const filteredTindakan = computed(() => {
    if (!keyword.value) return props.tindakan;

    const lower = keyword.value.toLowerCase();

    return props.tindakan
      .map((item) => toRaw(item)) // ambil nilai asli dari Proxy
      .filter(
        (d) =>
          (d.kdTindakan ?? '').toLowerCase().includes(lower) ||
          (d.nmTindakan ?? '').toLowerCase().includes(lower) ||
          (d.nmTindakanInd ?? '').toLowerCase().includes(lower)
      );
  });

  // Hitung total halaman
  const totalPages = computed(() => Math.ceil(filteredTindakan.value.length / perPage));

  // Pagination ringkas
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

  // Data halaman aktif
  const paginatedTindakan = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    const end = start + perPage;
    return filteredTindakan.value.slice(start, end);
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

  // Info jumlah item
  const startItem = computed(() =>
    filteredTindakan.value.length === 0 ? 0 : (currentPage.value - 1) * perPage + 1
  );
  const endItem = computed(() =>
    Math.min(currentPage.value * perPage, filteredTindakan.value.length)
  );

  // Pilih tindakan dari modal
  const pilihTindakan = (item) => {
    form.value.kode_tindakan = item.kdTindakan;
    form.value.nama_tindakan = item.nmTindakan;
    form.value.nama_tindakan_ind = item.nmTindakanInd;
    showModal.value = false;
  };

  // Hapus form
  const hapusForm = () => {
    form.value.kode_tindakan = '';
    form.value.nama_tindakan = '';
    form.value.nama_tindakan_ind = '';
    form.value.keterangan = '';
  };

  // Data contoh tabel utama
  const dataTindakan = ref([
    {
      kode: 'TDK001',
      nama: 'Pemeriksaan Umum',
      peraturan: 'Standar',
      harga: '50.000',
      bayar: 'Tunai',
      poli: 'Umum',
      keterangan: '',
      ketGigi: '-',
      createdBy: 'user1',
    },
  ]);

  console.log(props.tindakan[0]);

  // Simpan data
  const simpanData = () => {
    alert('Data tindakan berhasil disimpan!');
  };
</script>

<style scoped>
  .card {
    background: #fff;
  }
  .table th,
  .table td {
    font-size: 0.88rem;
  }
  button {
    white-space: nowrap;
  }
  .modal-dialog {
    margin-top: 10vh;
  }
</style>
