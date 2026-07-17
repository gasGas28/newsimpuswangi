<template>
  <div class="kirim-hipertensi-wrapper">

    <!-- ── Panel: Ringkasan Data ── -->
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
            <div class="summary-value">{{ sistolik }} mmHg</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Diastolik</div>
            <div class="summary-value">{{ diastolik }} mmHg</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Kategori Tekanan Darah</div>
            <div class="summary-value">{{ hipertensi }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Frekuensi Nadi</div>
            <div class="summary-value">{{ nadi }} x/menit</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Frekuensi Napas</div>
            <div class="summary-value">{{ napas }} x/menit</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Suhu Tubuh</div>
            <div class="summary-value">{{ suhu }} °C</div>
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
        <button
          type="button"
          class="save-button"
          :disabled="isSending"
          @click="kirimHipertensi"
        >
          <i class="bi" :class="isSending ? 'bi-arrow-repeat spin' : 'bi-send'"></i>
          <span>{{ isSending ? 'Mengirim...' : 'Kirim ke SATUSEHAT' }}</span>
        </button>
      </div>
    </section>

    <!-- ── Panel: Log Pengiriman ── -->
    <SubmitLogPanel
      :logs="logs"
      description="Riwayat percobaan pengiriman data hipertensi ke platform SATUSEHAT."
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
    TenagaMedis: Array,
  });

  const page = usePage();
  const flash = computed(() => page.props.flash);
  const patient = computed(() => props.DataPasien || {});

  const sistolik   = computed(() => valueOrDash(patient.value.sistolik));
  const diastolik  = computed(() => valueOrDash(patient.value.tekanan_diastolik));
  const hipertensi = computed(() => valueOrDash(patient.value.kategori_tekanan_darah));
  const nadi       = computed(() => valueOrDash(patient.value.nadi));
  const napas      = computed(() => valueOrDash(patient.value.pernapasan));
  const suhu       = computed(() => valueOrDash(patient.value.suhu));

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  // ─── Log ─────────────────────────────────────────────────────
  const {
    logs, isSending, lastStatus, statusMessage,
    clearLogs, submit,
  } = useSubmitLog(`hipertensi_logs_${props.DataPasien?.idSkrining ?? 'default'}`);

  // ─── Kirim ───────────────────────────────────────────────────
  const kirimHipertensi = () => {
    submit({
      routerPost:     router.post.bind(router),
      getFlash:       () => page.props.flash,
      successMessage: 'Data hipertensi (Observation + Condition) berhasil dikirim.',

      steps: [
        {
          logTitle: 'Pengiriman Tekanan Darah (Observation)',
          routeFn:  () => route('satusehat.hipertensi', props.DataPasien?.idSkrining),
          idField:  'observation_id',
        },
        {
          logTitle: 'Pengiriman Diagnosis Hipertensi (Condition)',
          routeFn:  () => route('satusehat.hipertensi', props.DataPasien?.idSkrining),
          idField:  'condition_id',
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

<style scoped>

</style>
