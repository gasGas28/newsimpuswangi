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
      <div
        class="save-status"
        :class="{ success: lastStatus === 'success', danger: lastStatus === 'error' }"
      >
        {{ statusMessage }}
      </div>
      <button type="button" class="save-button" :disabled="isSending" @click="sendSatuSehat">
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
  import SubmitLogPanel from '@/Components/Layouts/RuangLayanan/SkriningPTM/SubmitLogPanel.vue';
  import { useSubmitLog } from '@/composables/useSubmitLog.js';

  const props = defineProps({
    DataPasien: Object,
    TenagaMedis: Array,
  });

  const patient = computed(() => props.DataPasien || {});
  const page = usePage();
  const flash = computed(() => page.props.flash);

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

  // ─── Log ─────────────────────────────────────────────────────
  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `gangguan-pendengaran_logs_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  // ─── Kirim ───────────────────────────────────────────────────
  const sendSatuSehat = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data Observation Gangguan Pendengaran berhasil dikirim ke SATUSEHAT.',

      steps: [
        {
          logTitle: 'Pengiriman Data Gangguan Pendengaran (Observation)',
          routeFn: () => route('satusehat.gangguan-pendengaran', props.DataPasien?.idSkrining),
          idField: 'observationId',
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
