<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Gangguan Penglihatan</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Visus Mata Kanan</div>
          <div class="summary-value">{{ visus_kn }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Visus Mata Kiri</div>
          <div class="summary-value">{{ visus_kr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Pinhole Mata Kiri</div>
          <div class="summary-value">{{ pinhole_kr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Pinhole Mata Kanan</div>
          <div class="summary-value">{{ pinhole_kn }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Anterior Mata Kiri</div>
          <div class="summary-value">{{ anterior_kr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Anterior Mata Kanan</div>
          <div class="summary-value">{{ anterior_kn }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Shadow Mata Kiri</div>
          <div class="summary-value">{{ shadow_kr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Shadow Mata Kanan</div>
          <div class="summary-value">{{ shadow_kn }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">refleks Mata Kiri</div>
          <div class="summary-value">{{ refleks_kr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">refleks Mata Kanan</div>
          <div class="summary-value">{{ refleks_kn }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">glaukoma Mata Kiri</div>
          <div class="summary-value">{{ glaukoma_kr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">glaukoma Mata Kanan</div>
          <div class="summary-value">{{ glaukoma_kn }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">retinopati Mata Kiri</div>
          <div class="summary-value">{{ retinopati_kr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">retinopati Mata Kanan</div>
          <div class="summary-value">{{ retinopati_kn }}</div>
        </div>
      </div>
    </div>
    <div class="form-actions">
      <div class="save-status"></div>
      <button type="button" class="save-button" @click="sendSatusehat">
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
  import ModalAlert from '../../../../Components/Layouts/Modal/ModalAlert.vue';

  const props = defineProps({
    DataPasien: Object,
  });

  const patient = computed(() => props.DataPasien || {});

  const visus_kr =  computed(() => valueOrDash(patient.value.visus_os));
  const visus_kn =  computed(() => valueOrDash(patient.value.visus_od));
  const pinhole_kr =  computed(() => valueOrDash(patient.value.pinhole_os));
  const pinhole_kn =  computed(() => valueOrDash(patient.value.pinhole_od));
  const anterior_kr =  computed(() => valueOrDash(patient.value.anterior_os));
  const anterior_kn =  computed(() => valueOrDash(patient.value.anterior_od));
  const shadow_kr =  computed(() => valueOrDash(patient.value.shadow_os));
  const shadow_kn =  computed(() => valueOrDash(patient.value.shadow_od));
  const refleks_kr =  computed(() => valueOrDash(patient.value.refleks_os));
  const refleks_kn =  computed(() => valueOrDash(patient.value.refleks_od));
  const glaukoma_kr =  computed(() => valueOrDash(patient.value.glaukoma_os));
  const glaukoma_kn =  computed(() => valueOrDash(patient.value.glaukoma_od));
  const retinopati_kr =  computed(() => valueOrDash(patient.value.retinopati_os));
  const retinopati_kn =  computed(() => valueOrDash(patient.value.retinopati_od));
  

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

  const sendSatusehat = () => {
    console.log('props.DataSkrining:', props.DataSkrining);
    console.log('idSkrining yang dikirim:', props.DataPasien?.idSkrining);
    router.post(
      route('satusehat.gangguan-penglihatan', props.DataPasien?.idSkrining),
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
