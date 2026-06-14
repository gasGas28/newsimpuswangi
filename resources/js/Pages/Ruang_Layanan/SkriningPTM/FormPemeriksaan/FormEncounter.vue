<template>
  <div class="resume-form">
    <section class="resume-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-check"></i> Data Kunjungan</h4>
          <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="summary-grid">
          <div class="summary-item">
            <div class="summary-label">Nama Pasien</div>
            <div class="summary-value">{{ patientName }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">NIK</div>
            <div class="summary-value">{{ NIK }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Tanggal Skrining</div>
            <div class="summary-value">{{ tglSkrining }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Fasyankes</div>
            <div class="summary-value">{{ fasyankes }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Pemeriksa</div>
            <div class="summary-value">{{ petugas }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Jenis Kunjungan</div>
            <div class="summary-value">{{ kunjungan }}</div>
          </div>
        </div>
      </div>
      <div class="form-actions">
        <div class="save-status" :class="{ success: saveStatus === 'ready' }">
          {{ saveMessage }}
        </div>
        <button
          type="button"
          class="save-button"
          :disabled="dataKunjungan.processing"
          @click="kirimDataKunjungan"
        >
          <i class="bi" :class="dataKunjungan.processing ? 'bi-arrow-repeat' : 'bi-save'"></i>
          <span>{{ dataKunjungan.processing ? 'Menyimpan...' : 'Kirim Satu Sehat' }}</span>
        </button>
      </div>
    </section>

   </div>
  <!-- ✅ Modal di dalam root element -->
  <ModalAlert
    :show="showSuccessModal"
    type="success"
    title="Data Berhasil Disimpan"
    message="Data faktor risiko berhasil disimpan."
    button-text="Tutup"
    @close="showSuccessModal = false"
  />

  <ModalAlert
    :show="showValidationModal"
    type="warning"
    title="Data Belum Lengkap"
    message="Mohon lengkapi data berikut:"
    :items="validationMessages"
    @close="showValidationModal = false"
  />
</template>

<script setup>
  import { ref, watchEffect, computed } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import ModalAlert from '../../../../Components/Layouts/Modal/ModalAlert.vue';

  const props = defineProps({
    DataPasien: Object,
    TenagaMedis: Array,
    DataSkrining: Object,
  });

  const data = computed(() => props.DataSkrining || {});
  const patient = computed(() => props.DataPasien || {});

  const NIK = computed(() => valueOrDash(patient.value.NIK));
  const fasyankes = computed(() => valueOrDash(patient.value.nama_unit));
  const patientName = computed(() => valueOrDash(patient.value.NAMA_LGKP));
  const hipertensi = computed(() => valueOrDash(data.value.kategori_tekanan_darah));
  const sistolik = computed(() => valueOrDash(data.value.sistolik));
  const diastolik = computed(() => valueOrDash(data.value.tekanan_diastolik));
  const gds = computed(() => valueOrDash(data.value.gula_darah_sewaktu));
  const petugas = computed(() => valueOrDash(data.value.id_petugas));
  const kunjungan = computed(() => valueOrDash(data.value.jenis_kunjungan));

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  function toDateInput(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return Number.isNaN(date.getTime()) ? '' : date.toISOString().split('T')[0];
  }

  const tglSkrining =
    toDateInput(props.DataPasien?.tglKunjungan) || new Date().toISOString().split('T')[0];
  // console.log(hipertensi);
  // console.log(data);


   const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false); 
  const validationMessages = ref([]);

  // --- Form ---
  const dataKunjungan = useForm({
    idSkrining: props.DataPasien?.idSkrining,
    idpelayanan: props.DataPasien?.idpelayanan,

    nama_pasien: patientName || '',
    fasyankes: fasyankes || '',
    nik: NIK || '',
    kunjungan: kunjungan || ''
  });

  const saveStatus = ref('idle');
  const saveError = ref('');

  const saveMessage = computed(() => {
    if (saveStatus.value === 'ready') return 'Data Berhasil Dikirim.';
    if (saveError.value) return saveError.value;
    return 'Simpan setelah data kunjungan selesai diisi.';
  });

   function extractMessage(errors) {
    return (
      Object.values(errors || {})
        .flat()
        .find(Boolean) || 'Terjadi kesalahan saat menyimpan data.'
    );
  }

  function isDuplicateError(message) {
    const lower = message.toLowerCase();
    return ['sudah', 'tersimpan', 'duplikat', 'duplicate', 'already', 'exists'].some((kw) =>
      lower.includes(kw)
    );
  }

  function closeDuplicateModal() {
    showDuplicateModal.value = false;
  }

  function saveFaktorRisiko() {
  saveStatus.value = 'idle'
  saveError.value  = ''
  showSuccessModal.value    = false
  showValidationModal.value = false
  validationMessages.value  = []

  dataKunjungan.post(route('pelayanan.simpan-risiko-ptm'), {
    preserveScroll: true,

    onSuccess: () => {
      saveStatus.value = 'ready'
      saveError.value  = ''
      validationMessages.value = []
      dataKunjungan.clearErrors()
      dataKunjungan.defaults(dataKunjungan.data())
      showSuccessModal.value = true
    },

    onError: (errors) => {
      saveStatus.value = 'error'
      const message = extractMessage(errors)
      validationMessages.value = Object.values(errors).flat()
      saveError.value = message

      if (isDuplicateError(message)) {
        showDuplicateModal.value = true
      } else {
        showValidationModal.value = true
      }
    },
  })
}

  const compositionPreview = `{
  "resourceType": "Composition",
  "status": "preliminary",
  "type": {
    "coding": [
      {
        "system": "http://loinc.org",
        "code": "74213-0",
        "display": "Skrining PTM"
      }
    ]
  },
  "title": "Ringkasan Skrining PTM",
  "date": "2026-06-10",
  "subject": {
    "display": "Siti Rahayu",
    "identifier": "3509012345670001"
  },
  "section": [
    {
      "title": "Ringkasan Skrining PTM",
      "text": {
        "status": "generated",
        "div": "10/12 data utama lengkap"
      }
    },
    {
      "title": "Ringkasan Temuan",
      "text": {
        "status": "generated",
        "div": "Hipertensi Grade 1; Gula Darah Tinggi; Dislipidemia; Tidak Merokok"
      }
    }
  ]
}`;
</script>

<style scoped src="./FormPemeriksaan.css"></style>
