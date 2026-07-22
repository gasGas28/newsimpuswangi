<template>
  <div class="kirim-hipertensi-wrapper">
    <!-- ── Panel: Ringkasan Data ── -->
    <section class="resume-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-check"></i> Data Thalasemia</h4>
          <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="summary-grid">
          <div class="summary-item">
            <div class="summary-label">Hemoglobin</div>
            <div class="summary-value">{{ hb }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">MCV</div>
            <div class="summary-value">{{ mcv }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">MCH</div>
            <div class="summary-value">{{ mch }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Eritrosit</div>
            <div class="summary-value">{{ eritrosit }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">RDW</div>
            <div class="summary-value">{{ rdw }}</div>
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
        <button type="button" class="save-button" :disabled="isSending" @click="kirimThalasemia">
          <i class="bi" :class="isSending ? 'bi-arrow-repeat spin' : 'bi-send'"></i>
          <span>{{ isSending ? 'Mengirim...' : 'Kirim ke SATUSEHAT' }}</span>
        </button>
      </div>
    </section>

    <!-- ── Panel: Log Pengiriman ── -->
    <SubmitLogPanel
      :logs="logs"
      description="Riwayat percobaan pengiriman data thalasemia ke platform SATUSEHAT."
      @clear="clearLogs"
    />
  </div>
</template>

<script setup>
  import { computed, watch } from 'vue';
  import { router, usePage } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import SubmitLogPanel from '@/Components/Layouts/RuangLayanan/SkriningPTM/SubmitLogPanel.vue';
  import { useSubmitLog } from '@/composables/useSubmitLog.js';

  const props = defineProps({
    DataPasien: Object,
  });

  const page = usePage();
  const flash = computed(() => page.props.flash);
  const patient = computed(() => props.DataPasien || {});

  const hb = computed(() => valueOrDash(patient.value.hemoglobin));
  const mcv = computed(() => valueOrDash(patient.value.mcv));
  const mch = computed(() => valueOrDash(patient.value.mch));
  const eritrosit = computed(() => valueOrDash(patient.value.eritrosit));
  const rdw = computed(() => valueOrDash(patient.value.rdw));

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  // ─── Log ─────────────────────────────────────────────────────
  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `thalasemia_logs_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  // ─── Kirim ───────────────────────────────────────────────────
  const kirimThalasemia = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data Thalasemia (Observation + Condition) berhasil dikirim.',

      steps: [
        {
          logTitle: 'Pengiriman Thalasemia (Observation)',
          routeFn: () => route('satusehat.thalasemia', props.DataPasien?.idSkrining),
          idField: 'thalasemia',
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
