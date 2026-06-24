<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Pemeriksaan Serviks</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Inspekulo</div>
          <div class="summary-value">{{ inspekulo }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">IVA</div>
          <div class="summary-value">{{ iva }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Sadanis</div>
          <div class="summary-value">{{ sadanis }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">HPV / DNA</div>
          <div class="summary-value">{{ hpv }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">USG Payudara</div>
          <div class="summary-value">{{ usg }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Krioterapi</div>
          <div class="summary-value">{{ krioterapi }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Thermal</div>
          <div class="summary-value">{{ thermal }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">TCA</div>
          <div class="summary-value">{{ tca }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Rujukan</div>
          <div class="summary-value">{{ rujuk }}</div>
        </div>
      </div>
    </div>
    <div class="form-actions">
      <div class="save-status"></div>
      <button type="button" class="save-button" @click="kirimKankerServiks">
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

  //  'inspekulo',
  //       'iva',
  //       'hpv_dna',
  //       'sadanis',
  //       'usg',
  //       'krioterapi',
  //       'thermal',
  //       'tca',
  //       'rujuk_serviks',

  const patient = computed(() => props.DataPasien || {});

  const inspekulo = computed(() => valueOrDash(patient.value.inspekulo));
  const iva = computed(() => valueOrDash(patient.value.iva));
  const hpv = computed(() => valueOrDash(patient.value.hpv_dna));
  const sadanis = computed(() => valueOrDash(patient.value.sadanis));
  const usg = computed(() => valueOrDash(patient.value.usg));
  const krioterapi = computed(() => valueOrDash(patient.value.krioterapi));
  const thermal = computed(() => valueOrDash(patient.value.thermal));
  const tca = computed(() => valueOrDash(patient.value.tca));
  const rujuk = computed(() => valueOrDash(patient.value.rujuk_serviks));



  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false);
  const validationMessages = ref([]);

  const kirimKankerServiks = () => {
    console.log('props.DataSkrining:', props.DataSkrining);
    console.log('idSkrining yang dikirim:', props.DataPasien?.idSkrining);
    router.post(
      route('satusehat.kanker-serviks', props.DataPasien?.idSkrining),
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
