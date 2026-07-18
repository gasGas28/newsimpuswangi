<template>
  <div class="assessment-form">
    <!-- ── Panel: Input Diagnosa ── -->
    <div class="objektif-toolbar">
      <div>
        <p class="objektif-kicker">Assessment</p>
        <h3>Assessment Skrining Penyakit Tidak Menular</h3>
      </div>
    </div>
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
              <td colspan="6" class="diagnosa-empty">Belum ada diagnosa yang dicatat.</td>
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

      const dataBaru = response.data.data;

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

<style scoped src="@/css/FormPemeriksaan.css"></style>
