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
          <div class="summary-label">Berat Badan</div>
          <div class="summary-value">{{ bb }} KG</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Tinggi Badan</div>
          <div class="summary-value">{{ tb }} CM</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Indeks Massa Tubuh (IMT)</div>
          <div class="summary-value">{{ imt }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Status IMT</div>
          <div class="summary-value">{{ imt_status }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Lingkar Pinggang</div>
          <div class="summary-value">{{ lp }} CM</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Status Lingkar Pinggang</div>
          <div class="summary-value">{{ lp_status }}</div>
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
  import ModalAlert from '../../../../Components/Layouts/Modal/ModalAlert.vue';

  const props = defineProps({
    DataPasien: Object,
    TenagaMedis: Array,
  });

  const page = usePage();
  const flash = computed(() => page.props.flash);

  const patient = computed(() => props.DataPasien || {});

  const bb = computed(() => valueOrDash(patient.value.berat_badan));
  const tb = computed(() => valueOrDash(patient.value.tinggi_badan));
  const imt = computed(() => valueOrDash(patient.value.imt));
  const imt_status = computed(() => valueOrDash(patient.value.interpretasi_ptm));
  const lp = computed(() => valueOrDash(patient.value.lingkar_pinggang));
  const lp_status = computed(() => valueOrDash(patient.value.interpretasi_lp));

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
