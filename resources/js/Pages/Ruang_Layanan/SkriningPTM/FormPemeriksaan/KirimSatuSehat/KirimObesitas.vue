<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Obesitas</h4>
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
      <div class="save-status"></div>
      <button type="button" class="save-button" @click="kirimObesitas">
        <i class="bi bi-save"></i>
        <span>Kirim Satu Sehat</span>
      </button>
    </div>
  </section>
</template>

<script setup>
  import { ref, watchEffect, computed, watch } from 'vue';
  import { useForm, router, usePage } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import ModalAlert from '../../../../../Components/Layouts/Modal/ModalAlert.vue';

  const props = defineProps({
    DataPasien: Object,
    TenagaMedis: Array,
    DataSkrining: Object,
  });

  const page = usePage();
  const flash = computed(() => page.props.flash);

  const data = computed(() => props.DataSkrining || {});
  const patient = computed(() => props.DataPasien || {});

  const NIK = computed(() => valueOrDash(patient.value.NIK));
  const fasyankes = computed(() => valueOrDash(patient.value.nama_unit));
  const patientName = computed(() => valueOrDash(patient.value.NAMA_LGKP));
  const petugas = computed(() => valueOrDash(patient.value.nmDokter));
  const kunjungan = computed(() => valueOrDash(patient.value.jenis_kunjungan));

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

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false);
  const validationMessages = ref([]);

  const kirimObesitas = () => {
    console.log('props.DataSkrining:', props.DataSkrining);
    console.log('idSkrining yang dikirim:', props.DataPasien?.idSkrining);
    router.post(
      route('satusehat.obesitas', props.DataPasien?.idSkrining),
      {},
      {
        preserveScroll: true,
        onSuccess: () => {
          console.log('Encounter berhasil dikirim');
        },
        onError: (errors) => {
          console.error(errors);
        },
      }
    );
  };
</script>

<style scoped src="@/css/FormPemeriksaan.css"></style>
