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
  });

  const patient = computed(() => props.DataPasien || {});
  const page = usePage();
  const flash = computed(() => page.props.flash);

  const visus_kr = computed(() => valueOrDash(patient.value.visus_os));
  const visus_kn = computed(() => valueOrDash(patient.value.visus_od));
  const pinhole_kr = computed(() => valueOrDash(patient.value.pinhole_os));
  const pinhole_kn = computed(() => valueOrDash(patient.value.pinhole_od));
  const anterior_kr = computed(() => valueOrDash(patient.value.anterior_os));
  const anterior_kn = computed(() => valueOrDash(patient.value.anterior_od));
  const shadow_kr = computed(() => valueOrDash(patient.value.shadow_os));
  const shadow_kn = computed(() => valueOrDash(patient.value.shadow_od));
  const refleks_kr = computed(() => valueOrDash(patient.value.refleks_os));
  const refleks_kn = computed(() => valueOrDash(patient.value.refleks_od));
  const glaukoma_kr = computed(() => valueOrDash(patient.value.glaukoma_os));
  const glaukoma_kn = computed(() => valueOrDash(patient.value.glaukoma_od));
  const retinopati_kr = computed(() => valueOrDash(patient.value.retinopati_os));
  const retinopati_kn = computed(() => valueOrDash(patient.value.retinopati_od));

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false);
  const validationMessages = ref([]);

  // ─── Log ─────────────────────────────────────────────────────
  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `gangguan-penglihatan_logs_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  // ─── Kirim ───────────────────────────────────────────────────
  const sendSatuSehat = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data Observation Gangguan Penglihatan berhasil dikirim ke SATUSEHAT.',

      steps: [
        {
          logTitle: 'Pengiriman Data Gangguan Penglihatan (Observation)',
          routeFn: () => route('satusehat.gangguan-penglihatan', props.DataPasien?.idSkrining),
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
