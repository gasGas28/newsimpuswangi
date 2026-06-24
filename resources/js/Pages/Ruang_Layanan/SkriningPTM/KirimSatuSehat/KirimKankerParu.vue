<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Kuesioner Kanker Paru</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Kuesioner 1</div>
          <div class="summary-value">{{ answer1 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 2</div>
          <div class="summary-value">{{ answer2 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 3</div>
          <div class="summary-value">{{ answer3 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 4</div>
          <div class="summary-value">{{ answer3 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 5</div>
          <div class="summary-value">{{ answer5 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 6</div>
          <div class="summary-value">{{ answer6 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 7</div>
          <div class="summary-value">{{ answer7 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Hasil Kuesioner</div>
          <div class="summary-value">{{ hasil }}</div>
        </div>
      </div>
    </div>
    <div class="form-actions">
      <div class="save-status"></div>
      <button type="button" class="save-button" @click="kirimAsamUrat">
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

  const answer1 = computed(() => valueOrDash(patient.value.kuesioner1));  
  const answer2 = computed(() => valueOrDash(patient.value.kuesioner2));  
  const answer3 = computed(() => valueOrDash(patient.value.kuesioner3));  
  const answer4 = computed(() => valueOrDash(patient.value.kuesioner4));  
  const answer5 = computed(() => valueOrDash(patient.value.kuesioner5));  
  const answer6 = computed(() => valueOrDash(patient.value.kuesioner6));  
  const answer7 = computed(() => valueOrDash(patient.value.kuesioner7));  
  const hasil = computed(() => valueOrDash(patient.value.hasil_kuesioner));  

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

  const kirimAsamUrat = () => {
    console.log('props.DataSkrining:', props.DataSkrining);
    console.log('idSkrining yang dikirim:', props.DataPasien?.idSkrining);
    router.post(
      route('satusehat.send-paru', props.DataPasien?.idSkrining),
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
