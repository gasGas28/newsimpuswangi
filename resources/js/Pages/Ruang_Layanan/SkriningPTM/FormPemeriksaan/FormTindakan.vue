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
          <div class="diagnosa-input-group">
            <input
              id="kode-tindakan"
              type="text"
              class="form-control diagnosa-kode"
              placeholder="Kode"
              disabled
              v-model="form.kode_tindakan"
            />
            <input
              type="text"
              class="form-control diagnosa-nama"
              placeholder="Nama Tindakan"
              disabled
              v-model="form.nama_tindakan"
            />
            <button type="button" class="btn-diagnosa-cari" @click="showModal = true">
              <i class="bi bi-search"></i> Cari
            </button>
            <button type="button" class="btn-diagnosa-hapus" @click="hapusForm">
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
              <!-- <th class="col-aksi">Aksi</th> -->
            </tr>
          </thead>
          <tbody>
            <tr v-if="props.DataTindakan.length === 0">
              <td colspan="5" class="diagnosa-empty">Belum ada tindakan yang dicatat.</td>
            </tr>
            <tr v-for="(item, i) in props.DataTindakan" :key="i">
              <td>{{ i + 1 }}</td>
              <td>{{ item.kdTindakan }}</td>
              <td>{{ item.nmTindakan }}</td>
              <td>{{ item.nmTindakanInd }}</td>
              <td>{{ item.keterangan }}</td>
              <!-- <td>
                <button type="button" class="btn-hapus-diagnosa" title="Edit">
                  <i class="bi bi-pencil"></i>
                </button>
              </td> -->
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
      showGlobalLoader: false,
      only: ['DataTindakan'],
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

<style scoped src="@/css/FormPemeriksaan.css"></style>
