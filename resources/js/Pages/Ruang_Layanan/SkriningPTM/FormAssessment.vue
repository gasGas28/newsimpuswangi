<template>
  <div class="assessment-form">

    <!-- ── Panel: Input Diagnosa ── -->
    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-pulse"></i> Diagnosa Medis</h4>
          <p>Pencarian dan pencatatan diagnosa ICD-10 pasien.</p>
        </div>
      </div>

      <div class="panel-body">

        <!-- Kode + Nama Diagnosa -->
        <div class="form-field span-full">
          <label class="form-label" for="kode-diagnosa">Diagnosa</label>
          <div class="diagnosa-input-group">
            <input
              id="kode-diagnosa"
              type="text"
              class="form-control diagnosa-kode"
              placeholder="Kode"
              disabled
              v-model="form.kode_diagnosa"
            />
            <input
              type="text"
              class="form-control diagnosa-nama"
              placeholder="Nama Diagnosa"
              disabled
              v-model="form.nama_diagnosa"
            />
            <button type="button" class="btn-diagnosa-cari" @click="showModal = true">
              <i class="bi bi-search"></i> Cari
            </button>
            <button type="button" class="btn-diagnosa-hapus" @click="hapusForm">
              <i class="bi bi-x-lg"></i> Hapus
            </button>
          </div>
        </div>

        <!-- Alergi Makanan & Obat -->
        <div class="alergi-grid">
          <div class="form-field">
            <label class="form-label" for="alergi-makanan">Alergi Makanan</label>
            <select id="alergi-makanan" class="form-select">
              <option value="">- Pilih -</option>
              <option
                v-for="alrgm in AlergiMakanan"
                :key="alrgm.kodeBpjs"
                :value="alrgm.kodeBpjs"
              >
                {{ alrgm.namaAlergiBpjs }}
              </option>
            </select>
          </div>
          <div class="form-field">
            <label class="form-label" for="alergi-obat">Alergi Obat</label>
            <select id="alergi-obat" class="form-select">
              <option value="">- Pilih -</option>
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

        <!-- Keterangan Alergi -->
        <div class="form-field span-full">
          <label class="form-label" for="ket-alergi">Keterangan Alergi</label>
          <textarea id="ket-alergi" class="form-control" rows="2"></textarea>
        </div>

        <!-- Kunjungan Kasus -->
        <div class="form-field span-full">
          <label class="form-label" for="kunjungan-kasus">Kunjungan Kasus</label>
          <select id="kunjungan-kasus" class="form-select" v-model="form.kunjungan_khusus">
            <option value="">- Pilih -</option>
            <option value="1">Kasus Baru</option>
            <option value="2">Kasus Lama</option>
            <option value="3">Kunjungan Kasus Lama</option>
            <option value="4">Kunjungan Kasus Baru</option>
          </select>
        </div>

        <!-- Keterangan -->
        <div class="form-field span-full">
          <label class="form-label" for="keterangan">Keterangan</label>
          <textarea
            id="keterangan"
            class="form-control"
            rows="2"
            v-model="form.keterangan"
          ></textarea>
        </div>

        <!-- Aksi -->
        <div class="form-actions">
          <button type="button" class="save-button" @click="saveForm">
            <i class="bi bi-save"></i>
            <span>Simpan Diagnosa Medis</span>
          </button>
        </div>

      </div>
    </section>

    <!-- ── Panel: Daftar Diagnosa ── -->
    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-list-check"></i> Daftar Diagnosa</h4>
          <p>Riwayat diagnosa yang telah dicatat untuk kunjungan ini.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="section-title">
          <h5>Diagnosa Aktif</h5>
          <span>{{ daftarDiagnosa.length }} entri</span>
        </div>

        <table class="diagnosa-table table table-sm">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th>Nama Diagnosa Medis</th>
              <th>Keterangan</th>
              <th>Kasus</th>
              <th>Poli</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!daftarDiagnosa.length">
              <td colspan="6" class="diagnosa-empty">
                Belum ada diagnosa yang dicatat.
              </td>
            </tr>
            <tr v-for="(diag, index) in daftarDiagnosa" :key="diag.idDiagnosa">
              <td>{{ index + 1 }}</td>
              <td>{{ diag.nmDiagnosa }}</td>
              <td>{{ diag.keterangan }}</td>
              <td>{{ diag.diagnosaKasus }}</td>
              <td>{{ nmPoli }}</td>
              <td>
                <button
                  type="button"
                  class="btn-hapus-diagnosa"
                  @click="hapusData(diag.idDiagnosa)"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </div>

  <DiagnosaModal
    :show="showModal"
    :Diagnosa="Diagnosa"
    @close="showModal = false"
    @select="pilihDiagnosa"
  />
</template>

<script setup>
  import { ref, computed } from 'vue';
  import axios from 'axios';
  import { route } from 'ziggy-js';
  import { router, Link, useForm } from '@inertiajs/vue3';
  import DiagnosaModal from '../../../Components/Layouts/RuangLayanan/SkriningPTM/DiagnosaModal.vue';

  const props = defineProps({
    DataPasien: Object,
    Diagnosa: Array,
    DataDiagnosa: Array,
    AlergiMakanan: Array,
    AlergiObat: Array,
  });

  const data = computed(() => props.DataPasien || {});
  const kdPoli = computed(() => data.value.kdPoli);
  const loketId = computed(() => data.value.idLoket);
  const pelayananId = computed(() => data.value.idPelayanan);
  const nmPoli = computed(() => data.value.nmPoli);
  const daftarDiagnosa = computed(() => props.DataDiagnosa || {});

  const form = ref({
    kode_diagnosa: '',
    nama_diagnosa: '',
    kunjungan_khusus: '',
    keterangan: '',
    kdPoli: kdPoli,
    loketId: loketId,
    pelayananId: pelayananId,
  });

  const saveForm = async () => {
    try {
      const response = await axios.post(route('ruang-layanan-anc.dataDiagnosa'), form.value);

      // Ambil data dari server
      const dataBaru = response.data.data;

      // Tambahkan ke tabel tanpa reload
      daftarDiagnosa.value.push(dataBaru);

      // Reset form
      form.value = {
        kode_diagnosa: '',
        nama_diagnosa: '',
        kunjungan_khusus: '',
        keterangan: '',
        kdPoli: kdPoli,
        loketId: loketId,
        pelayananId: pelayananId,
      };

      alert('Data berhasil disimpan!');
    } catch (error) {
      console.error(error);
      alert('Gagal menyimpan data');
    }
  };

  const hapusData = async (id) => {
    if (!confirm('Yakin ingin menghapus data ini?')) return;

    try {
      await axios.delete(route('diagnosa.destroy', id));

      // Hapus dari reactive state
      const index = daftarDiagnosa.value.findIndex((item) => item.idDiagnosa === id);
      if (index !== -1) {
        daftarDiagnosa.value.splice(index, 1);
      }

      alert('Data berhasil dihapus!');
    } catch (error) {
      console.error(error);
      alert('Gagal menghapus data.');
    }
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
</script>

<style scoped>
.assessment-form {
  display: grid;
  gap: 18px;
}

.assessment-panel {
  overflow: hidden;
  border: 1px solid #d8e0ea;
  border-radius: 8px;
  background: #ffffff;
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
}

.panel-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 18px 20px;
  border-bottom: 1px solid #d8e0ea;
  background: #1f7a8c;
}

.panel-header h4 {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: #ffffff;
  font-size: 1rem;
  font-weight: 750;
}

.panel-header p {
  margin: 5px 0 0;
  color: #dff7fb;
  font-size: 0.86rem;
}

.panel-body {
  display: grid;
  gap: 16px;
  padding: 20px;
}

.form-field {
  min-width: 0;
  padding: 14px;
  border: 1px solid #e7edf3;
  border-radius: 8px;
  background: #fbfdff;
}

.span-full {
  grid-column: 1 / -1;
}

.form-label {
  margin-bottom: 6px;
  color: #27364a;
  font-size: 0.86rem;
  font-weight: 700;
}

.form-control,
.form-select {
  width: 100%;
  min-height: 42px;
  border: 1px solid #cbd7e3;
  border-radius: 8px;
  color: #0f172a;
  font-size: 0.9rem;
}

.form-control:disabled,
.form-select:disabled {
  background: #f4f7fa;
  color: #64748b;
  opacity: 1;
}

.form-control:focus,
.form-select:focus {
  border-color: #1f7a8c;
  box-shadow: 0 0 0 0.2rem rgba(31, 122, 140, 0.14);
}

textarea.form-control {
  min-height: 92px;
  resize: vertical;
}

.diagnosa-input-group {
  display: flex;
  align-items: stretch;
}

.diagnosa-input-group .diagnosa-kode {
  max-width: 120px;
  border-radius: 8px 0 0 8px;
  flex-shrink: 0;
}

.diagnosa-input-group .diagnosa-nama {
  flex: 1;
  min-width: 0;
  border-left: 0;
  border-radius: 0;
}

.btn-diagnosa-cari,
.btn-diagnosa-hapus,
.save-button,
.btn-hapus-diagnosa {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  border: 0;
  font-weight: 750;
  line-height: 1.2;
  white-space: nowrap;
  transition: background-color 0.16s ease, border-color 0.16s ease, color 0.16s ease,
    box-shadow 0.16s ease;
}

.btn-diagnosa-cari {
  min-height: 42px;
  padding: 9px 14px;
  border-radius: 0;
  background: #2563eb;
  color: #ffffff;
  font-size: 0.88rem;
}

.btn-diagnosa-cari:hover {
  background: #1d4ed8;
}

.btn-diagnosa-hapus {
  min-height: 42px;
  padding: 9px 14px;
  border: 1px solid #cbd7e3;
  border-left: 0;
  border-radius: 0 8px 8px 0;
  background: #ffffff;
  color: #475569;
  font-size: 0.88rem;
}

.btn-diagnosa-hapus:hover {
  background: #fff1f2;
  border-color: #fecdd3;
  color: #be123c;
}

.alergi-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 16px;
  align-items: start;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding-top: 16px;
  border-top: 1px solid #e7edf3;
}

.save-button {
  min-height: 42px;
  padding: 10px 17px;
  border-radius: 8px;
  background: #0f766e;
  color: #ffffff;
  font-size: 0.9rem;
  box-shadow: 0 8px 18px rgba(15, 118, 110, 0.18);
}

.save-button:hover {
  background: #115e59;
}

.section-title {
  display: flex;
  align-items: end;
  justify-content: space-between;
  gap: 12px;
  padding-bottom: 10px;
  border-bottom: 1px solid #e7edf3;
}

.section-title h5 {
  margin: 0;
  color: #183245;
  font-size: 0.95rem;
  font-weight: 750;
}

.section-title span {
  color: #64748b;
  font-size: 0.78rem;
  font-weight: 700;
  text-align: right;
}

.diagnosa-table {
  margin: 0;
  overflow: hidden;
  border: 1px solid #e7edf3;
  border-radius: 8px;
  font-size: 0.88rem;
}

.diagnosa-table thead th {
  border-bottom: 1px solid #d8e0ea;
  background: #f5f9fc;
  color: #334155;
  font-size: 0.78rem;
  font-weight: 800;
  text-transform: uppercase;
  vertical-align: middle;
}

.diagnosa-table tbody td {
  color: #27364a;
  vertical-align: middle;
}

.diagnosa-table tbody tr:hover td {
  background: #f8fbfd;
}

.diagnosa-table .col-no {
  width: 44px;
}

.diagnosa-table .col-aksi {
  width: 80px;
  text-align: center;
}

.diagnosa-table td:last-child {
  text-align: center;
}

.diagnosa-empty {
  padding: 22px 0;
  color: #94a3b8;
  font-size: 0.86rem;
  font-weight: 650;
  text-align: center;
}

.btn-hapus-diagnosa {
  min-width: 34px;
  min-height: 32px;
  padding: 5px 10px;
  border: 1px solid #fecaca;
  border-radius: 6px;
  background: #fff5f5;
  color: #dc2626;
  font-size: 0.82rem;
}

.btn-hapus-diagnosa:hover {
  border-color: #f87171;
  background: #fee2e2;
}

@media (max-width: 768px) {
  .panel-header {
    align-items: flex-start;
    padding: 16px;
  }

  .panel-body {
    padding: 16px;
  }

  .alergi-grid {
    grid-template-columns: 1fr;
  }

  .diagnosa-input-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
  }

  .diagnosa-input-group .diagnosa-kode,
  .diagnosa-input-group .diagnosa-nama,
  .btn-diagnosa-cari,
  .btn-diagnosa-hapus {
    max-width: 100%;
    border: 1px solid #cbd7e3;
    border-radius: 8px;
  }

  .diagnosa-input-group .diagnosa-kode,
  .diagnosa-input-group .diagnosa-nama {
    grid-column: 1 / -1;
  }

  .form-actions {
    align-items: stretch;
    flex-direction: column;
  }

  .save-button {
    width: 100%;
  }
}

@media (max-width: 576px) {
  .section-title {
    align-items: flex-start;
    flex-direction: column;
  }

  .section-title span {
    text-align: left;
  }

  .diagnosa-table {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }
}
</style>

