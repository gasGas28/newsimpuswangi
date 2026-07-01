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
      <div
        class="save-status"
        :class="{ success: lastStatus === 'success', danger: lastStatus === 'error' }"
      >
        {{ statusMessage }}
      </div>
      <button type="button" class="save-button" :disabled="isSending" @click="kirimEKG">
        <i class="bi" :class="isSending ? 'bi-arrow-repeat spin' : 'bi-send'"></i>
        <span>{{ isSending ? 'Mengirim...' : 'Kirim ke SATUSEHAT' }}</span>
      </button>
    </div>
  </section>
  <SubmitLogPanel
    :logs="logs"
    description="Riwayat percobaan pengiriman data ke platform SATUSEHAT."
    @clear="clearLogs"
  />
</template>

<script setup>
  import { ref, watchEffect, computed, watch } from 'vue';
  import { useForm, router, usePage } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import ModalAlert from '../../../../Components/Layouts/Modal/ModalAlert.vue';
  import SubmitLogPanel from '@/Components/Layouts/RuangLayanan/SkriningPTM/SubmitLogPanel.vue';
  import { useSubmitLog } from '@/composables/useSubmitLog.js';

  const props = defineProps({
    DataPasien: Object,
    TenagaMedis: Array,
  });

  const patient = computed(() => props.DataPasien || {});
  const page = usePage();
  const flash = computed(() => page.props.flash);

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

  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `EKG_logs_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  // ─── Kirim ───────────────────────────────────────────────────
  const kirimEKG = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage:
        'Data Deteksi Dini Penyakit Jantung (Observation + Condition) berhasil dikirim.',

      steps: [
        {
          logTitle: 'Pengiriman Data Deteksi Dini Penyakit Jantung (Observation)',
          routeFn: () => route('satusehat.send-ekg', props.DataPasien?.idSkrining),
          idField: 'observation_id',
        },
        {
          logTitle: 'Pengiriman Diagnosis Penyakit Jantung (Condition)',
          routeFn: () => route('satusehat.send-ekg', props.DataPasien?.idSkrining),
          idField: 'condition_id',
        },
      ],
    });
  };

  // Hanya update status bar jika submit() belum menanganinya
  watch(flash, (val) => {
    if (!val) return;
    if (val.success && lastStatus.value !== 'success') {
      lastStatus.value = 'success';
      statusMessage.value = val.message ?? 'Berhasil dikirim.';
    } else if (val.error && lastStatus.value !== 'error') {
      lastStatus.value = 'error';
      statusMessage.value = val.message ?? 'Pengiriman gagal.';
    }
  });
</script>

<style scoped src="@/css/FormPemeriksaan.css"></style>
