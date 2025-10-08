<template>
    <div class="container py-4">
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
          <div class="d-flex align-items-center gap-2 ps-2">
            <!-- Total Pasien -->
            <div class="d-flex align-items-center gap-2 ps-2">
              <i class="bi bi-funnel-fill text-primary fs-3"></i>
              <h2 class="mb-0 fw-bold text-primary d-flex align-items-center gap-2 fs-4">
                Filter Laporan
                <span class="badge bg-primary-subtle text-primary fw-semibold rounded-pill fs-6">
                  Total Pasien
                </span>
              </h2>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center mt-4 mb-4 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
              <input
                type="date"
                v-model="startDate"
                class="form-control shadow-sm rounded-pill px-3"
              />
              <span class="fw-semibold">s/d</span>
              <input
                type="date"
                v-model="endDate"
                class="form-control shadow-sm rounded-pill px-3"
              />
            </div>

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
          </div>
          <hr>
          <div class="d-flex align-items-center bg-light px-3 py-1 rounded-pill shadow-sm gap-2">
            <div class="table-responsive">
              <table class="table table-hover table-striped align-middle text-center mb-0">
                <thead class="table-light">
                  <tr>
                    <th>No.</th>
                    <th>Tanggal Kunjungan</th>
                    <th>NIK</th>
                    <th>NO RM</th>
                    <th>Nama</th>
                    <th>Kecamatan</th>
                    <th>Desa</th>
                    <th>Anamnesa</th>
                    <th>Diagnosa</th>
                    <th>Tindakan</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(filter, index) in filteredLaporan" :key="filter.idLoket">
                    <td>{{ index + 1 }}</td>
                    <td>{{ filter.tglKunjungan }}</td>
                    <td>{{ filter.pasien?.NIK }}</td>
                    <td>{{ filter.pasien?.NO_MR }}</td>
                    <td>{{ filter.pasien?.NAMA_LGKP }}</td>
                    <td>{{ filter.pasien?.NO_KEC }}</td>
                    <td>{{ filter.pasien?.NO_KEL }}</td>
                    <td>
                      <div class="table-text fw-semibold text-start" v-if="filter.anamnesa">
                        <p>
                          Sistole: {{ filter.anamnesa.sistole }}, Diastole:
                          {{ filter.anamnesa.diastole }}
                        </p>
                        <p>Suhu: {{ filter.anamnesa.suhu }} °C</p>
                        <p>BB: {{ filter.anamnesa.beratBadan }} kg</p>
                        <p>TB: {{ filter.anamnesa.tinggiBadan }} cm</p>
                        <p>Lingkar Perut: {{ filter.anamnesa.lingkarPerut }} cm</p>
                        <p>IMT: {{ filter.anamnesa.imtKet }}</p>
                        <p>
                          RespRate: {{ filter.anamnesa.respRate }} HeartRate:
                          {{ filter.anamnesa.heartRate }}
                        </p>
                        <p>Catatan: {{ filter.anamnesa.catatan }}</p>
                      </div>
                      <em v-else>Belum ada anamnesa</em>
                    </td>
                    <td>
                      <div class="fw-semibold text-start table-text" v-if="filter.kunjungan">
                        <p>{{ filter.kunjungan.nmdiagnosa1 }}</p>
                        <p>{{ filter.kunjungan.nmdiagnosa2 }}</p>
                        <p>{{ filter.kunjungan.nmdiagnosa3 }}</p>
                      </div>
                      <em v-else>Belum Ada Diagnosa</em>
                    </td>
                    <td>
                      <div class="fw-semibold text-start table-text" v-if="filter.kunjungan">
                        <p>{{ filter.kunjungan.nmtindakan1 }}</p>
                        <p>{{ filter.kunjungan.nmtindakan2 }}</p>
                        <p>{{ filter.kunjungan.nmtindakan3 }}</p>
                        <p>{{ filter.kunjungan.nmtindakan4 }}</p>
                        <p>{{ filter.kunjungan.nmtindakan5 }}</p>
                      </div>
                      <em v-else> Belum ada tindakan </em>
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
  import { ref, computed } from 'vue';
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
  import { Link } from '@inertiajs/vue3';
  defineOptions({ layout: AppLayout })


  const props = defineProps({
    rekamMedis: Array,
  });

  const searchQuery = ref('');
  const startDate = ref('');
  const endDate = ref('');

  const filteredLaporan = computed(() => {
    let results = props.rekamMedis;

    // filter rentang tanggal dulu
    if (startDate.value && endDate.value) {
      results = results.filter((item) => {
        const tgl = (item.tglKunjungan || '').slice(0, 10);
        return tgl >= startDate.value && tgl <= endDate.value;
      });
    } else if (startDate.value) {
      results = results.filter((item) => (item.tglKunjungan || '').slice(0, 10) >= startDate.value);
    } else if (endDate.value) {
      results = results.filter((item) => (item.tglKunjungan || '').slice(0, 10) <= endDate.value);
    }

    // lalu filter berdasarkan query teks
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase();
      results = results.filter(
        (item) =>
          (item.pasien?.NO_MR || '').toLowerCase().includes(query) ||
          (item.pasien?.NO_KK || '').toLowerCase().includes(query) ||
          (item.pasien?.NAMA_LGKP || '').toLowerCase().includes(query) ||
          (item.pasien?.NIK || '').toLowerCase().includes(query) ||
          (item.pasien?.ALAMAT || '').toLowerCase().includes(query) ||
          (item.nama_kecamatan || '').toLowerCase().includes(query) ||
          (item.nama_kelurahan || '').toLowerCase().includes(query) ||
          (item.noKartu || '').toLowerCase().includes(query) ||
          (item.kdProvider || '').toLowerCase().includes(query)
      );
    }

    return results;
  });
</script>
