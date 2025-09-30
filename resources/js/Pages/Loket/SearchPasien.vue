<template>
  <AppLayouts>
    <div class="container py-4">
      <!-- Search dan Tambah -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="input-group w-50">
          <input
            type="text"
            class="form-control"
            placeholder="Search"
            v-model="searchQuery"
          />
          <span class="input-group-text bg-white">
            <i class="bi bi-search"></i>
          </span>
        </div>
        <Link :href="route('loket.pasien')" class="btn btn-info text-white fw-semibold">
          Tambah Data Anak
        </Link>
      </div>

      <div class="card mb-3 shadow-sm rounded-3">
        <div class="card-body d-flex align-items-center">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-bordered text-center">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>NO_MR<br />NO. KK</th>
                    <th>NAMA<br />NIK</th>
                    <th>ALAMAT<br />KECAMATAN-DESA</th>
                    <th>NO. BPJS<br />Provider</th>
                    <th>ACTION</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in filteredPasien" :key="item.ID">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.NO_MR }}<br />{{ item.NO_KK }}</td>
                    <td>{{ item.NAMA_LGKP }}<br />{{ item.NIK }}</td>
                    <td>{{ item.ALAMAT }}<br />{{ item.NO_KEC }} {{ item.NO_KEL }}</td>
                    <td>{{ item.noKartu }}<br />{{ item.noKunjungan }}</td>
                    <td>
                      <div class="d-flex justify-content-center gap-2 mt-1">
                        <Link :href="route('loket.index')" class="btn btn-primary fw-semibold text-white">
                          <i class="bi bi-eye"></i>
                          Loket
                        </Link>
                        <Link class="btn btn-success fw-semibold text-white">
                          <i class="bi bi-filetype-html"></i>
                          Edit
                        </Link>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="filteredPasien.length === 0">
                    <td colspan="6" class="text-muted">Tidak ada data ditemukan</td>
                  </tr>
                </tbody>
              </table>
            </div>
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

const props = defineProps({
  pasien: Array,
});

// 🔎 State untuk query pencarian
const searchQuery = ref('');

// 🔎 Filter data pasien
const filteredPasien = computed(() => {
  if (!searchQuery.value) return props.pasien;

  const query = searchQuery.value.toLowerCase();

  return props.pasien.filter((item) =>
    (item.NO_MR || '').toLowerCase().includes(query) ||
    (item.NO_KK || '').toLowerCase().includes(query) ||
    (item.NAMA_LGKP || '').toLowerCase().includes(query) ||
    (item.NIK || '').toLowerCase().includes(query) ||
    (item.ALAMAT || '').toLowerCase().includes(query) ||
    (item.NO_KEC || '').toLowerCase().includes(query) ||
    (item.NO_KEL || '').toLowerCase().includes(query) ||
    (item.noKartu || '').toLowerCase().includes(query) ||
    (item.noKunjungan || '').toLowerCase().includes(query)
  );
});
</script>
