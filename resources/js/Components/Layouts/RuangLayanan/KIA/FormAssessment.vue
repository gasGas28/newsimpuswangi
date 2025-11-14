<template>
  <div>
    <!-- Tombol navigasi antar form -->
    <div class="d-flex gap-3 flex-wrap">
      <a href="#" class="action-card medical-action" @click.prevent="toggleForm('diagnosa')">
        <div class="action-icon"><i class="bi bi-person-check"></i></div>
        <div class="action-label">Diagnosa</div>
      </a>

      <a href="#" class="action-card medical-action" @click.prevent="toggleForm('skrining')">
        <div class="action-icon"><i class="bi bi-activity"></i></div>
        <div class="action-label">Skrining</div>
      </a>
    </div>

    <!-- Form aktif -->
    <div class="mt-4">
      <!-- Form Diagnosa -->
      <div v-if="activeForm === 'diagnosa'" class="p-2">
        <div class="row g-4 mb-2">
          <div class="col-md-6">
            <div class="card shadow-sm border-0 p-2">
              <div class="card-body">
                <form>
                  <div class="row mb-3">
                    <label class="form-label fw-semibold">Diagnosa</label>
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
                        <button type="button" class="btn btn-info" @click="showModal = true">
                          Cari
                        </button>
                        <button type="button" class="btn btn-danger" @click="hapusForm">Del</button>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-6 d-flex flex-column">
                      <label class="form-label fw-semibold">Alergi Makanan</label>
                      <select class="form-select">
                        <option v-for="alrgm in AlergiMakanan" :key="alrgm.kodeBpjs">
                          {{ alrgm.namaAlergiBpjs }}
                        </option>
                      </select>
                    </div>
                    <div class="col-6 d-flex flex-column">
                      <label class="form-label fw-semibold">Alergi Obat</label>
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
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Keterangan Alergi</label>
                    <textarea class="form-control" rows="2"></textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Kunjungan Kasus</label>
                    <select class="form-control" v-model="form.kunjungan_khusus">
                      <option value="">- Pilih -</option>
                      <option value="1">Kasus Baru</option>
                      <option value="2">Kasus Lama</option>
                      <option value="3">Kunjungan Kasus Lama</option>
                      <option value="4">Kunjungan Kasus Baru</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <textarea class="form-control" rows="2" v-model="form.keterangan"></textarea>
                  </div>

                  <div class="mb-4">
                    <button type="button" @click="saveForm" class="btn btn-success">
                      <i class="far fa-save me-2"></i> SIMPAN DIAGNOSA MEDIS
                    </button>
                  </div>

                  <hr />

                  <div class="mb-1">
                    <table class="table table-sm">
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
                            <button
                              @click="hapusData(diag.idDiagnosa)"
                              class="btn btn-danger btn-sm"
                            >
                              Hapus
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3">
              <div class="card-body">
                <!-- Pemeriksaan Penunjang -->
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label class="form-label fw-semibold">Pemeriksaan Penunjang</label>
                    <button
                      type="button"
                      class="btn btn-info w-100"
                      @click="alert('Buka form laboratorium')"
                    >
                      <i class="fas fa-flask me-2"></i> Laboratorium
                    </button>
                  </div>
                </div>
                <div class="row mb-3">
                  <label class="form-label fw-semibold mb-2">Form Lanjutan</label>
                  <div class="d-flex gap-2">
                    <button
                      type="button"
                      class="btn btn-secondary bg-opacity-75 flex-fill"
                      @click="alert('Buka form diare')"
                    >
                      <i class="fas fa-flask"></i> Diare
                    </button>
                    <button
                      type="button"
                      class="btn btn-secondary bg-opacity-75 flex-fill"
                      @click="alert('Buka form katarak')"
                    >
                      <i class="fas fa-flask"></i> Katarak
                    </button>
                    <button
                      type="button"
                      class="btn btn-secondary bg-opacity-75 flex-fill"
                      @click="alert('Buka form PTM')"
                    >
                      <i class="fas fa-flask"></i> PTM
                    </button>
                    <button
                      type="button"
                      class="btn btn-secondary bg-opacity-75 flex-fill"
                      @click="alert('Buka form hipertensi')"
                    >
                      <i class="fas fa-flask"></i> Hipertensi
                    </button>
                  </div>
                </div>

                <div class="mb-1">
                  <label class="form-label fw-semibold">Diagnosa Keperawatan</label>
                  <select class="form-select">
                    <option value="">-- Pilih --</option>
                    <option v-for="item in diagnosaKeperawatan" :key="item.kdDiag">
                      {{ item.nmDiag }}
                    </option>
                  </select>
                </div>
                <div class="mb-4">
                  <button type="button" @click="formDiagnosaKep" class="btn btn-success mt-4">
                    <i class="far fa-save me-2"></i> Simpan Diagnosa Keperawatan
                  </button>
                </div>
                <hr />
                <div class="mb-3">
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Diagnosa Asuhan Keperawatan</th>
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
            </div>
          </div>
        </div>

        <DiagnosaModal
          :show="showModal"
          :diagnosa="diagnosa"
          @close="showModal = false"
          @select="pilihDiagnosa"
        />
      </div>

      <!-- Form Skrining -->

      <div v-else-if="activeForm === 'skrining'" class="p-3">
        <form @submit.prevent="saveSkrining">
          <div class="row g-4 mb-3">
            <div class="col-md-6">
              <div class="card shadow-sm p-2 border-0">
                <div class="card-body">
                  <div class="mb-3">
                    <label for="skrining" class="form-label fw-semibold">Skrining</label>
                    <select class="form-select">
                      <option value="">Skrining Tuberkolosis</option>
                      <option value="">Skrining Masalah Kesehatan</option>
                      <option value="">Skrining Kesehatan Gigi dan Mulut</option>
                      <option value="">Skrining Anemia</option>
                      <option value="">Skrining Pre-Eklamsia</option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <h5>Berdasarkan Anamnesia</h5>
                    <p>
                      Gejala Penyakit TB bergantung pada lokasi lesi sehingga dapat menunjukkan
                      manifestasi klinis sebagai berikut
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row g-4">
            <div class="col-md-6">
              <div class="card shadow-sm border-0 p-3">
                <div class="card-body">
                  <div class="mb-2">
                    <label for="batuk2minggu" class="form-label fw-semibold"
                      >1. Batuk &ge; 2 Minggu</label
                    >
                    <select class="form-select">
                      <option>Select...</option>
                      <option value="Iya">Iya</option>
                      <option value="Tidak">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <label for="batukBerdahak" class="form-label fw-semibold"
                      >2. Batuk Berdahak</label
                    >
                    <select class="form-select">
                      <option>Select...</option>
                      <option value="Iya">Iya</option>
                      <option value="Tidak">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <label for="batukBerdahakDarah" class="form-label fw-semibold"
                      >3. Batuk Berdahat Dapat Bercampur Darah</label
                    >
                    <select class="form-select">
                      <option>Select...</option>
                      <option value="Iya">Iya</option>
                      <option value="Tidak">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <label for="batukNyeri" class="form-label fw-semibold"
                      >4. Dapat Disertai Nyeri</label
                    >
                    <select class="form-select">
                      <option>Select...</option>
                      <option value="Iya">Iya</option>
                      <option value="Tidak">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <label for="sesakNafas" class="form-label fw-semibold">5. Sesak Nafas</label>
                    <select class="form-select">
                      <option>Select...</option>
                      <option value="Iya">Iya</option>
                      <option value="Tidak">Tidak</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card shadow-sm border-0 p-3">
                <div class="card-body">
                  <div class="mb-2">
                    <label for="melaise" class="form-label fw-semibold">1. Melaise</label>
                    <select class="form-select">
                      <option>Select...</option>
                      <option value="Iya">Iya</option>
                      <option value="Tidak">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <label for="penurunanBB" class="form-label fw-semibold"
                      >2. Penurunan Berat Badan</label
                    >
                    <select class="form-select">
                      <option>Select...</option>
                      <option value="Iya">Iya</option>
                      <option value="Tidak">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <label for="nafsuMakan" class="form-label fw-semibold"
                      >3. Menurunnya Nafsu Makan</label
                    >
                    <select class="form-select">
                      <option>Select...</option>
                      <option value="Iya">Iya</option>
                      <option value="Tidak">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <label for="menggigil" class="form-label fw-semibold">4. Menggigil</label>
                    <select class="form-select">
                      <option>Select...</option>
                      <option value="Iya">Iya</option>
                      <option value="Tidak">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <label for="demam" class="form-label fw-semibold">5. Demam</label>
                    <select class="form-select">
                      <option>Select...</option>
                      <option value="Iya">Iya</option>
                      <option value="Tidak">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <label for="berkeringat" class="form-label fw-semibold"
                      >6. Berkeringat di Malam Hari</label
                    >
                    <select class="form-select">
                      <option>Select...</option>
                      <option value="Iya">Iya</option>
                      <option value="Tidak">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <button type="submit" class="btn btn-success w-100 px-4 shadow-sm mt-2">
                      <i class="bi bi-save me-1"></i> Simpan Data
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Pesan jika belum memilih form -->
      <div v-else class="text-muted text-center mt-4">
        <em>Pilih form terlebih dahulu</em>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { router } from '@inertiajs/vue3';
  import { ref, computed } from 'vue';
  import { route } from 'ziggy-js';
  import DiagnosaModal from './DiagnosaModal.vue';
  // state aktif (diagnosa / skrining)
  const activeForm = ref('diagnosa');
  const toggleForm = (form) => {
    activeForm.value = activeForm.value === form ? null : form;
  };

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

  const saveSkrining = () => {
    alert('Data disimpan!');
  };

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
        onSuccess: () => {
          alert('Data berhasil dihapus!');
          // Hapus dari tampilan langsung (biar terasa cepat)
          DataDiagnosa.value = DataDiagnosa.value.filter((item) => item.id !== id);
        },
        onError: (errors) => {
          console.error(errors);
          alert('Gagal menghapus data.');
        },
      });
    }
  };
</script>

<style scoped>
  .action-card {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 8px;
    background: #f9fafb;
    color: #333;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
  }

  .action-card:hover {
    background: #e9f2ff;
    color: #0d6efd;
  }

  .action-icon {
    font-size: 1.25rem;
    color: inherit;
  }

  .action-label {
    font-weight: 500;
  }
</style>
