<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Pemeriksaan EKG</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Heart Rate</div>
          <div class="summary-value">{{ hr }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Irama</div>
          <div class="summary-value">{{ irama }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Axis</div>
          <div class="summary-value">{{ axis }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Segmen ST</div>
          <div class="summary-value">{{ segmen_st }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kompleks QRS</div>
          <div class="summary-value">{{ qrs }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kesimpulan EKG</div>
          <div class="summary-value">{{ hasil }}</div>
        </div>
      </div>
    </div>
    <div class="form-actions">
      <div class="save-status"></div>
      <button type="button" class="save-button" @click="kirimEKG">
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

  const hr = computed(() => valueOrDash(patient.value.hr));
  const irama = computed(() => valueOrDash(patient.value.irama));
  const axis = computed(() => valueOrDash(patient.value.axis));
  const segmen_st = computed(() => valueOrDash(patient.value.segmen_st));
  const qrs = computed(() => valueOrDash(patient.value.qrs));
  const hasil = computed(() => valueOrDash(patient.value.kesimpulan_ekg));

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false);
  const validationMessages = ref([]);

  const kirimEKG = () => {
    console.log('props.DataSkrining:', props.DataSkrining);
    console.log('idSkrining yang dikirim:', props.DataPasien?.idSkrining);
    router.post(
      route('satusehat.send-ekg', props.DataPasien?.idSkrining),
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
