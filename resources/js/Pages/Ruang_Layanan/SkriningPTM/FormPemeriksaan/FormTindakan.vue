<template>
  <div class="assessment-form">
    <!-- ── Panel: Input Tindakan ── -->
    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-person-check"></i> Tindakan</h4>
          <p>Pencarian dan pencatatan tindakan medis pasien.</p>
        </div>
      </div>

      <div class="panel-body">
        <!-- Kode + Nama Tindakan -->
        <div class="form-field span-full">
          <label class="form-label" for="kode-tindakan">Tindakan</label>
          <div class="tindakan-input-group">
            <input
              id="kode-tindakan"
              type="text"
              class="form-control tindakan-kode"
              placeholder="Kode"
              disabled
              v-model="form.kode_tindakan"
            />
            <input
              type="text"
              class="form-control tindakan-nama"
              placeholder="Nama Tindakan"
              disabled
              v-model="form.nama_tindakan"
            />
            <button type="button" class="btn-tindakan-cari" @click="showModal = true">
              <i class="bi bi-search"></i> Cari
            </button>
            <button type="button" class="btn-tindakan-hapus" @click="hapusForm">
              <i class="bi bi-x-lg"></i> Hapus
            </button>
          </div>
        </div>

        <!-- Nama Tindakan (Ind) -->
        <div class="form-field span-full">
          <label class="form-label" for="nama-tindakan-ind">Nama Tindakan (Ind)</label>
          <textarea
            id="nama-tindakan-ind"
            class="form-control"
            rows="2"
            disabled
            v-model="form.nama_tindakan_ind"
          ></textarea>
        </div>

        <!-- Keterangan -->
        <div class="form-field span-full">
          <label class="form-label" for="keterangan-tindakan">Keterangan</label>
          <textarea
            id="keterangan-tindakan"
            class="form-control"
            rows="2"
            v-model="form.keterangan"
          ></textarea>
        </div>

        <!-- Aksi -->
        <div class="form-actions">
          <button type="button" class="save-button" @click="saveForm">
            <i class="bi bi-save"></i>
            <span>Simpan Tindakan</span>
          </button>
        </div>
      </div>
    </section>

    <!-- ── Panel: Daftar Tindakan ── -->
    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-list-check"></i> Daftar Tindakan</h4>
          <p>Riwayat tindakan yang telah dicatat untuk kunjungan ini.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="section-title">
          <h5>Tindakan Tercatat</h5>
          <span>{{ props.DataTindakan.length }} entri</span>
        </div>

        <table class="diagnosa-table table table-sm">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th>Kode</th>
              <th>Nama Tindakan</th>
              <th>Poli</th>
              <th>Keterangan</th>
              <th>Created By</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="props.DataTindakan.length === 0">
              <td colspan="11" class="diagnosa-empty">Belum ada tindakan yang dicatat.</td>
            </tr>
            <tr v-for="(item, i) in props.DataTindakan" :key="i">
              <td>{{ i + 1 }}</td>
              <td>{{ item.kdTindakan }}</td>
              <td>{{ item.nmTindakan }}</td>
              <td>{{ item.nmTindakanInd }}</td>
              <td>{{ item.kdPoli }}</td>
              <td>{{ item.keterangan }}</td>
              <td>
                <button type="button" class="btn-hapus-diagnosa" title="Edit">
                  <i class="bi bi-pencil"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>

  <TindakanModal
    :show="showModal"
    :tindakan="props.tindakan"
    @close="showModal = false"
    @select="pilihTindakan"
  />
</template>

<script setup>
  import { ref, computed } from 'vue';
  import { router } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import TindakanModal from '../../../../Components/Layouts/RuangLayanan/SkriningPTM/TindakanModal.vue';

  const props = defineProps({
    DataPasien: Object,
    DataTindakan: Array,
    tindakan: Array,
  });

  const data = computed(() => props.DataPasien || {});
  const kdPoli = computed(() => data.value.kdPoli);
  const loketId = computed(() => data.value.idLoket);
  const pelayananId = computed(() => data.value.idPelayanan);

  const form = ref({
    kode_tindakan: '',
    nama_tindakan: '',
    nama_tindakan_ind: '',
    keterangan: '',
    kdPoli: kdPoli,
    loketId: loketId,
    idpelayanan: pelayananId,
  });

  // Modal control
  const showModal = ref(false);

  // Pilih tindakan dari modal
  const pilihTindakan = (item) => {
    console.log('item dari modal:', item); // <- cek dulu field aslinya apa
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

  const saveForm = () => {
    router.post(route('ptm.tindakan-simpan'), form.value, {
      preserveScroll: true,
      onSuccess: () => {
        // Reset form
        form.value = {
          kode_tindakan: '',
          nama_tindakan: '',
          nama_tindakan_ind: '',
          keterangan: '',
          kdPoli: kdPoli,
          loketId: loketId,
          idpelayanan: pelayananId,
        };

        alert('Data berhasil disimpan!');
      },
      onError: (errors) => {
        console.error(errors);
        alert('Gagal menyimpan data');
      },
    });
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

  .tindakan-input-group {
    display: flex;
    align-items: stretch;
  }

  .tindakan-input-group .tindakan-kode {
    max-width: 120px;
    border-radius: 8px 0 0 8px;
    flex-shrink: 0;
  }

  .tindakan-input-group .tindakan-nama {
    flex: 1;
    min-width: 0;
    border-left: 0;
    border-radius: 0;
  }

  .btn-tindakan-cari,
  .btn-tindakan-hapus,
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
    transition:
      background-color 0.16s ease,
      border-color 0.16s ease,
      color 0.16s ease,
      box-shadow 0.16s ease;
  }

  .btn-tindakan-cari {
    min-height: 42px;
    padding: 9px 14px;
    border-radius: 0;
    background: #2563eb;
    color: #ffffff;
    font-size: 0.88rem;
  }

  .btn-tindakan-cari:hover {
    background: #1d4ed8;
  }

  .btn-tindakan-hapus {
    min-height: 42px;
    padding: 9px 14px;
    border: 1px solid #cbd7e3;
    border-left: 0;
    border-radius: 0 8px 8px 0;
    background: #ffffff;
    color: #475569;
    font-size: 0.88rem;
  }

  .btn-tindakan-hapus:hover {
    background: #fff1f2;
    border-color: #fecdd3;
    color: #be123c;
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

    .tindakan-input-group {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .tindakan-input-group .tindakan-kode,
    .tindakan-input-group .tindakan-nama,
    .btn-tindakan-cari,
    .btn-tindakan-hapus {
      max-width: 100%;
      border: 1px solid #cbd7e3;
      border-radius: 8px;
    }

    .tindakan-input-group .tindakan-kode,
    .tindakan-input-group .tindakan-nama {
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
