<template>
  <!-- ==================== TINDAKAN ==================== -->
  <div class="fade-in">
    <section class="planning-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-plus-circle"></i> Rencana Edukasi Skrining PTM</h4>
          <p>Pilih intervensi dan edukasi yang akan dilakukan pada pasien.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="form-grid action-form-grid">
          <div class="form-field span-2">
            <label class="form-label">Intervensi / Edukasi yang Diberikan</label>
            <div class="action-check-grid">
              <label
                v-for="item in props.MasterEdukasi"
                :key="item.kode_snomed"
                class="action-check-item"
                :class="{ checked: selectedTindakan.includes(item.kode_snomed) }"
              >
                <input v-model="selectedTindakan" type="checkbox" :value="item.kode_snomed" />
                <span>
                  <strong>{{ item.nama_edukasi }}</strong>
                  <small>{{ item.display }}</small>
                </span>
              </label>
            </div>
          </div>

          <div class="form-field span-2">
            <label class="form-label" for="keterangan_tindakan">Keterangan</label>
            <textarea
              id="keterangan_tindakan"
              v-model="form.keterangan"
              class="form-control"
              rows="3"
              placeholder="Resep, anjuran, instruksi khusus, atau catatan tindak lanjut"
            ></textarea>
          </div>
        </div>
      </div>

      <div class="panel-footer">
        <button
          type="button"
          class="btn btn-outline-danger"
          @click="hapusForm"
          :disabled="form.processing"
        >
          <i class="bi bi-x-lg"></i>
          <span>Bersihkan</span>
        </button>
        <button
          type="button"
          class="btn btn-success"
          @click.prevent.stop="simpanData"
          :disabled="form.processing || selectedTindakan.length === 0"
        >
          <i
            class="bi"
            :class="form.processing ? 'bi-arrow-repeat spinner' : 'bi-check-circle'"
          ></i>
          <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Tindakan Terpilih' }}</span>
        </button>
      </div>
    </section>

    <section class="planning-panel">
      <div class="panel-header compact">
        <div>
          <h4><i class="bi bi-list-check"></i> Daftar Edukasi</h4>
          <p>{{ props.DataEdukasi.length }} Edukasi tercatat untuk pasien ini.</p>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table planning-table mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama Tindakan</th>
              <th>Display</th>
              <!-- <th class="text-center">Aksi</th> -->
            </tr>
          </thead>
          <tbody>
            <tr v-if="props.DataEdukasi.length === 0">
              <td colspan="7" class="empty-state">
                <i class="bi bi-inbox"></i>
                <span>Data edukasi belum tersedia.</span>
              </td>
            </tr>
            <tr v-for="(item, i) in props.DataEdukasi" :key="item.id || i">
              <td>{{ i + 1 }}</td>
              <td>
                <span class="code-pill">{{ item.kode_snomed || '-' }}</span>
              </td>
              <td class="fw-semibold">{{ item.nama_edukasi || '-' }}</td>
              <td>
                <span class="service-pill">{{ item.display || '-' }}</span>
              </td>
              <!-- <td class="text-center">
                <button
                  class="btn btn-outline-danger"
                  @click="hapusTindakan(item.idTindakan)"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </td> -->
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <ModalHapus
      :show="showDeleteModal"
      title="Hapus Tindakan?"
      message="Data tindakan yang dihapus tidak dapat dikembalikan."
      @close="showDeleteModal = false"
      @confirm="konfirmasiHapus"
    />

    <div v-if="showSuccessModal" class="success-overlay">
      <div class="success-dialog">
        <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
        <h5>Tindakan Berhasil Disimpan</h5>
        <p>Data tindakan telah berhasil disimpan ke sistem.</p>
        <button type="button" class="btn btn-success" @click="showSuccessModal = false">OK</button>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';
  import { router, useForm } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import ModalHapus from '../../../../Components/Layouts/Modal/ModalHapus.vue';

  const props = defineProps({
    DataPasien: Object,
    MasterEdukasi: Array,
    DataEdukasi: Array,
  });

  const selectedTindakan = ref([]);
  const showSuccessModal = ref(false);
  const showDeleteModal = ref(false);
  const selectedDeleteId = ref(null);

  const form = useForm({
    idpelayanan: props.DataPasien.idpelayanan,
    idskrining: props.DataPasien.idSkrining,
    kode_snomed: '',
    nama_edukasi: '',
    display: '',
    keterangan: '',
    procedureId: '0',
  });

  console.log('idSkrining', props.DataPasien.idSkrining);

  const hapusForm = () => {
    selectedTindakan.value = [];
    form.keterangan = '';
  };

  const simpanData = () => {
    const terpilih = props.MasterEdukasi.filter((item) =>
      selectedTindakan.value.includes(item.kode_snomed)
    );
    if (!terpilih.length) return;
    simpanTindakanBerikutnya(terpilih);
  };
  const simpanTindakanBerikutnya = (items, index = 0) => {
    const item = items[index];
    form.kode_snomed = item.kode_snomed;

    form.post(route('ptm.edukasi-simpan'), {
      preserveScroll: true,
      forceFormData: true,
      showGlobalLoader: false,
      only: ['DataEdukasi'],
      onSuccess: () => {
        if (index + 1 < items.length) {
          simpanTindakanBerikutnya(items, index + 1);
          return;
        }
        showSuccessModal.value = true;
        hapusForm();
      },
      onError: (error) => console.error(error),
    });
  };

  const hapusTindakan = (id) => {
    selectedDeleteId.value = id;
    showDeleteModal.value = true;
  };

  const konfirmasiHapus = () => {
    router.delete(route('ptm.edukasi-hapus', { id: selectedDeleteId.value }), {
      preserveScroll: true,
      onSuccess: () => {
        showDeleteModal.value = false;
        selectedDeleteId.value = null;
      },
    });
  };
</script>
