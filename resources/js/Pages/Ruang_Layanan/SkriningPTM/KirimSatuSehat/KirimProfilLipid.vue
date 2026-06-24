<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Profil Lipid</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Kolesterol Total</div>
          <div class="summary-value">{{ kolesterol }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kategori Kolesterol Total</div>
          <div class="summary-value">{{ kategori_kolesterol }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">High Density</div>
          <div class="summary-value">{{ hdl }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kategroi High Density</div>
          <div class="summary-value">{{ kategori_hdl }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Low Density</div>
          <div class="summary-value">{{ ldl }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kategori Low Density</div>
          <div class="summary-value">{{ kategori_ldl }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Trigliserida</div>
          <div class="summary-value">{{ trigliserida }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kategori Trigliserida</div>
          <div class="summary-value">{{ kategori_trigliserida }}</div>
        </div>
      </div>
    </div>
    <div class="form-actions">
      <div class="save-status"></div>
      <button type="button" class="save-button" @click="kirimProfilLipid">
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
    DataSkrining: Object,
  });

  const page = usePage();
  const flash = computed(() => page.props.flash);

  const data = computed(() => props.DataSkrining || {});
  const patient = computed(() => props.DataPasien || {});

  const kolesterol = computed(() => valueOrDash(patient.value.kolesterol_total));
  const hdl = computed(() => valueOrDash(patient.value.hdl));
  const ldl = computed(() => valueOrDash(patient.value.ldl));
  const trigliserida = computed(() => valueOrDash(patient.value.trigliserida));
  const kategori_kolesterol = computed(() => valueOrDash(patient.value.interpretasi_kolesterol_total));
  const kategori_hdl = computed(() => valueOrDash(patient.value.interpretasi_hdl));
  const kategori_ldl = computed(() => valueOrDash(patient.value.interpretasi_ldl));
  const kategori_trigliserida = computed(() => valueOrDash(patient.value.interpretasi_trigliserida));

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }
 

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false);
  const validationMessages = ref([]);

  const kirimProfilLipid = () => {
    console.log('props.DataSkrining:', props.DataSkrining);
    console.log('idSkrining yang dikirim:', props.DataPasien?.idSkrining);
    router.post(
      route('satusehat.profil-lipid', props.DataPasien?.idSkrining),
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
