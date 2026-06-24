<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Gangguan Pendengaran</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Curiga Tuli Kongenital Kanan</div>
          <div class="summary-value">{{ tuli_kn }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Curiga Tuli Kongenital Kiri</div>
          <div class="summary-value">{{ tuli_kr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">OMSK / Congek Kanan</div>
          <div class="summary-value">{{ omsk_kn }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">OMSK / Congek Kiri</div>
          <div class="summary-value">{{ omsk_kr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Presbikusis Kanan</div>
          <div class="summary-value">{{ presbi_kn }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Presbikusis Kiri</div>
          <div class="summary-value">{{ presbi_kr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Serumen Kiri</div>
          <div class="summary-value">{{ serumen_kr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Serumen Kanan</div>
          <div class="summary-value">{{ serumen_kn }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Bisik Kanan</div>
          <div class="summary-value">{{ bisik_kn }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Bisik Kiri</div>
          <div class="summary-value">{{ bisik_kr }}</div>
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
    TenagaMedis: Array,
  });

  const patient = computed(() => props.DataPasien || {});

  const data_helper = (field) =>
    computed(() => (patient.value[field] === 'true' ? 'TRUE' : 'FALSE'));

  const tuli_kr = data_helper('tuli_kiri');
  const tuli_kn = data_helper('tuli_kanan');
  const omsk_kr = data_helper('omsk_kiri');
  const omsk_kn = data_helper('omsk_kanan');
  const presbi_kr = data_helper('presbi_kiri');
  const presbi_kn = data_helper('presbi_kanan');
  const serumen_kr = data_helper('serumen_kiri');
  const serumen_kn = data_helper('serumen_kanan');

  const bisik_kn = computed(() => patient.value.bisik_kanan);
  const bisik_kr = computed(() => patient.value.bisik_kiri);

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false);
  const validationMessages = ref([]);

  const sendSatusehat = () => {
    console.log('props.DataSkrining:', props.DataSkrining);
    console.log('idSkrining yang dikirim:', props.DataPasien?.idSkrining);
    router.post(
      route('satusehat.gangguan-pendengaran', props.DataPasien?.idSkrining),
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
