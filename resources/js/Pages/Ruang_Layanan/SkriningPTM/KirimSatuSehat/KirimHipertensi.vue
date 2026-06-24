<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Hipertensi</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Sistolik</div>
          <div class="summary-value">{{ sistolik }} mm/Hg</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Tekanan Diastolik</div>
          <div class="summary-value">{{ diastolik }} mm\Hg</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kategori Tekanan Darah</div>
          <div class="summary-value">{{ hipertensi }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Frekuensi Nadi</div>
          <div class="summary-value">{{ nadi }} x / min</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Frekuensi Napas</div>
          <div class="summary-value">{{ napas }} x / min</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Suhu Tubuh</div>
          <div class="summary-value">{{ suhu }} C</div>
        </div>
      </div>
    </div>
    <div class="form-actions">
      <div class="save-status"></div>
      <button type="button" class="save-button" @click="kirimHipertensi">
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

  const sistolik = computed(() => valueOrDash(patient.value.sistolik));
  const diastolik = computed(() => valueOrDash(patient.value.tekanan_diastolik));
  const hipertensi = computed(() => valueOrDash(patient.value.kategori_tekanan_darah));
  const nadi = computed(() => valueOrDash(patient.value.nadi));
  const napas = computed(() => valueOrDash(patient.value.pernapasan));
  const suhu = computed(() => valueOrDash(patient.value.suhu));

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

  const kirimHipertensi = () => {
    console.log('props.DataSkrining:', props.DataSkrining);
    console.log('idSkrining yang dikirim:', props.DataPasien?.idSkrining);
    router.post(
      route('satusehat.hipertensi', props.DataPasien?.idSkrining),
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
