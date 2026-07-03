<template>
    <!-- ==================== TINDAKAN ==================== -->
    <div class="fade-in">
      <section class="planning-panel">
        <div class="panel-header">
          <div>
            <h4><i class="bi bi-plus-circle"></i> Rencana Tindakan Skrining PTM</h4>
            <p>Pilih intervensi dan edukasi yang akan dilakukan pada pasien.</p>
          </div>
        </div>

        <div class="panel-body">
          <div class="form-grid action-form-grid">
            <div class="form-field span-2">
              <label class="form-label">Intervensi / Edukasi yang Diberikan</label>
              <div class="action-check-grid">
                <label
                  v-for="item in daftarTindakanPtm"
                  :key="item.kode"
                  class="action-check-item"
                  :class="{ checked: selectedTindakan.includes(item.kode) }"
                >
                  <input v-model="selectedTindakan" type="checkbox" :value="item.kode" />
                  <span>
                    <strong>{{ item.nama }}</strong>
                    <small>{{ item.keterangan }}</small>
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
          <button type="button" class="btn btn-outline-danger" @click="hapusForm" :disabled="form.processing">
            <i class="bi bi-x-lg"></i>
            <span>Bersihkan</span>
          </button>
          <button
            type="button"
            class="btn btn-success"
            @click.prevent.stop="simpanData"
            :disabled="form.processing || selectedTindakan.length === 0"
          >
            <i class="bi" :class="form.processing ? 'bi-arrow-repeat spinner' : 'bi-check-circle'"></i>
            <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Tindakan Terpilih' }}</span>
          </button>
        </div>
      </section>

      <section class="planning-panel">
        <div class="panel-header compact">
          <div>
            <h4><i class="bi bi-list-check"></i> Daftar Tindakan</h4>
            <p>{{ dataTindakan.length }} tindakan tercatat untuk pasien ini.</p>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table planning-table mb-0">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Tindakan</th>
                <th>Poli</th>
                <th>Keterangan</th>
                <th>Created By</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="dataTindakan.length === 0">
                <td colspan="7" class="empty-state">
                  <i class="bi bi-inbox"></i>
                  <span>Data tindakan belum tersedia.</span>
                </td>
              </tr>
              <tr v-for="(item, i) in dataTindakan" :key="item.idTindakan || i">
                <td>{{ i + 1 }}</td>
                <td><span class="code-pill">{{ item.kdTindakan || '-' }}</span></td>
                <td class="fw-semibold">{{ item.nmTindakan || '-' }}</td>
                <td><span class="service-pill">{{ item.nmPoli || '-' }}</span></td>
                <td class="table-muted">{{ item.keterangan || '-' }}</td>
                <td>{{ item.createdBy || '-' }}</td>
                <td class="text-center">
                  <button class="btn btn-outline-danger btn-sm btn-icon" @click="hapusTindakan(item.idTindakan)">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
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
  DataTindakan: Array,
});

const dataTindakan = computed(() => props.DataTindakan || []);
const selectedTindakan = ref([]);
const showSuccessModal = ref(false);
const showDeleteModal = ref(false);
const selectedDeleteId = ref(null);

const daftarTindakanPtm = [
  { kode: 'eduk_gizi', nama: 'Edukasi gizi seimbang', keterangan: 'Anjuran pola makan sehat, pembatasan gula, garam, dan lemak.' },
  { kode: 'eduk_aktfis', nama: 'Edukasi aktivitas fisik', keterangan: 'Anjuran aktivitas fisik rutin sesuai kondisi pasien.' },
  { kode: 'eduk_rokok', nama: 'Konseling berhenti merokok (UBM)', keterangan: 'Upaya berhenti merokok dan pencegahan paparan asap rokok.' },
  { kode: 'eduk_htn', nama: 'Edukasi tatalaksana hipertensi', keterangan: 'Pemantauan tekanan darah, gaya hidup, dan kepatuhan kontrol.' },
  { kode: 'eduk_dm', nama: 'Edukasi tatalaksana DM', keterangan: 'Pemantauan gula darah, diet, aktivitas fisik, dan kontrol berkala.' },
];

const form = useForm({
  idpelayanan: props.DataPasien.idpelayanan,
  loketId: props.DataPasien.idLoket,
  kdPoli: props.DataPasien.kdPoli,
  kode_tindakan: '',
  nama_tindakan: '',
  nama_tindakan_ind: '',
  keterangan: '',
});

const hapusForm = () => {
  selectedTindakan.value = [];
  form.keterangan = '';
};

const simpanData = () => {
  const terpilih = daftarTindakanPtm.filter((item) => selectedTindakan.value.includes(item.kode));
  if (!terpilih.length) return;
  simpanTindakanBerikutnya(terpilih);
};

const simpanTindakanBerikutnya = (items, index = 0) => {
  const item = items[index];
  form.kode_tindakan = item.kode;
  form.nama_tindakan = item.nama;
  form.nama_tindakan_ind = item.nama;

  form.post(route('ptm.tindakan-simpan'), {
    preserveScroll: true,
    forceFormData: true,
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
  router.delete(route('ptm.tindakan-hapus', { id: selectedDeleteId.value }), {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      selectedDeleteId.value = null;
    },
  });
};
</script>