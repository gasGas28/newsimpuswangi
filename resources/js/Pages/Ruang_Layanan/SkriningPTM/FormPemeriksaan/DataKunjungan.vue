<template>
  <div class="subjektif-form">
    <section class="subjektif-panel visit-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-pulse"></i> Data Kunjungan</h4>
          <p>Encounter FHIR - periode, fasyankes, dan alasan kunjungan.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="visit-grid">
          <div class="form-field">
            <label class="form-label" for="tanggal_skrining">Tanggal Skrining</label>
            <input
              type="date"
              id="tanggal_skrining"
              class="form-control"
              v-model="form.tanggal_skrining"
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="jenis_kunjungan">Jenis Kunjungan</label>
            <input
              type="text"
              id="jenis_kunjungan"
              class="form-control"
              v-model="form.jenis_kunjungan"
              placeholder="Masukkan jenis kunjungan (misal: Skrining, Konsultasi, Kontrol, Rujukan)"
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="dokter">Dokter</label>
            <div class="field-with-icon">
              <i class="bi bi-person-badge"></i>
              <input
                type="text"
                id="dokter"
                class="form-control"
                v-model="form.id_petugas"
                placeholder="Masukkan nama dokter"
              />
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="fasyankes">Fasyankes</label>
            <div class="field-with-icon">
              <i class="bi bi-hospital"></i>
              <input
                type="text"
                id="fasyankes"
                class="form-control"
                v-model="form.fasyankes"
                placeholder="Masukkan nama fasyankes"
              />
            </div>
          </div>

          <div class="form-field span-full">
            <label class="form-label" for="keluhan_utama">Keluhan Utama</label>
            <textarea
              id="keluhan_utama"
              class="form-control"
              rows="4"
              v-model="form.keluhan_utama"
              placeholder="Tuliskan keluhan utama, riwayat singkat, atau alasan skrining"
            ></textarea>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <div class="save-status" :class="{ success: saveStatus === 'ready' }">
          {{ saveMessage }}
        </div>
        <button type="button" class="save-button" :disabled="isSaving" @click="saveSubjektif">
          <i class="bi" :class="isSaving ? 'bi-arrow-repeat' : 'bi-save'"></i>
          <span>{{ isSaving ? 'Menyimpan...' : 'Simpan Subjektif' }}</span>
        </button>
      </div>
    </section>
    <ModalAlert
      :show="showSuccessModal"
      type="success"
      title="Kunjungan Berhasil Disimpan"
      message="Silakan lanjutkan pengisian Faktor Risiko."
      button-text="Tutup"
      secondary-button-text="Lanjut Faktor Risiko"
      @close="showSuccessModal = false"
      @secondary-action="lanjutFaktorRisiko"
    />

    <ModalAlert
      :show="showValidationModal"
      type="warning"
      title="Data Belum Lengkap"
      message="Mohon lengkapi data berikut:"
      :items="validationMessages"
      @close="showValidationModal = false"
    />
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import ModalAlert from '../../../../Components/Layouts/Modal/ModalAlert.vue';

  const props = defineProps({
    DataPasien: Object,
  });

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const validationMessages = ref([]);
  const emit = defineEmits(['change-tab']);
  function lanjutFaktorRisiko() {
    showSuccessModal.value = false;
    emit('change-tab', 'faktor-risiko');
  }

  function toDateInput(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return Number.isNaN(date.getTime()) ? '' : date.toISOString().split('T')[0];
  }

  const pasien = computed(() => props.DataPasien || {});

  const defaultTanggal =
    toDateInput(props.DataPasien?.tglKunjungan) || new Date().toISOString().split('T')[0];

  const defaultJenisKunjungan = (() => {
    if (pasien.value.kunjBaru === 'true') return 'Kunjungan Baru';
    if (pasien.value.kunjBaru === 'false') return 'Kunjungan Lama';
    return '';
  })();

  const defaultFasyankes = props.DataPasien?.nama_unit || '-';

  // --- Form ---

  const form = useForm({
    idSkrining: pasien.value?.idSkrining || '',
    idPelayanan: pasien.value?.idpelayanan || '',
    idLoket: pasien.value?.idLoket || '',
    nik_pasien: pasien.value?.NIK || '',
    tanggal_skrining: defaultTanggal,
    jenis_kunjungan: defaultJenisKunjungan,
    id_petugas: '',
    fasyankes: defaultFasyankes,
    keluhan_utama: '',
  });

  console.log('Form initialized with:', form);

  // --- UI state ---

  const isSaving = ref(false);
  const saveStatus = ref('idle');
  const saveError = ref('');

  // --- Computed ---

  const saveMessage = computed(() => {
    if (saveStatus.value === 'ready') return 'Data subjektif berhasil disimpan.';
    if (saveError.value) return saveError.value;
    return 'Simpan setelah data kunjungan selesai diisi.';
  });

  // --- Actions ---

  console.log('Component loaded');

  function saveSubjektif() {
    console.log('Data yang akan dikirim:', form.data());

    isSaving.value = true;
    saveStatus.value = 'idle';
    saveError.value = '';

    showSuccessModal.value = false;
    showValidationModal.value = false;

    validationMessages.value = [];

    form.post(route('pelayanan.tambah-kunjungan-ptm'), {
        preserveScroll: true,

        onBefore: () => {
            console.log('Mulai request');
        },

        onSuccess: () => {
            saveStatus.value = 'ready';

            saveError.value = '';
            validationMessages.value = [];

            form.clearErrors();
            form.defaults(form.data());

            showSuccessModal.value = true;
        },

        onError: (errors) => {
            saveStatus.value = 'error';

            validationMessages.value = Object.values(errors).flat();

            saveError.value = extractMessage(errors);

            showValidationModal.value = true;
        },

        onFinish: () => {
            console.log('Request selesai');
            isSaving.value = false;
        },
    });
}

  function closeDuplicateModal() {
    showDuplicateModal.value = false;
  }

  // --- Error helpers ---

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
</script>

<style scoped src="./FormPemeriksaan.css"></style>
