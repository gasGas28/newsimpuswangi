<template>
  <div class="kirim-hipertensi-wrapper">
    <!-- ── Panel: Ringkasan Data ── -->
    <section class="resume-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-check"></i> Data Diagnosis</h4>
          <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
        </div>
      </div>

      <div class="panel-body">
        <div v-for="diagnosa in props.DataDiagnosa" :key="diagnosa.idDiagnosa">
          <div class="summary-grid">
            <div class="summary-item">
              <div class="summary-label">Kode Diagnosa</div>
              <div class="summary-value">{{ diagnosa.kdDiagnosa }}</div>
            </div>
            <div class="summary-item">
              <div class="summary-label">Nama Diagnosa</div>
              <div class="summary-value">{{ diagnosa.nmDiagnosa }}</div>
            </div>
            <div class="summary-item">
              <div class="summary-label">Keterangan</div>
              <div class="summary-value">{{ diagnosa.keterangan }}</div>
            </div>
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
        <button type="button" class="save-button" :disabled="isSending" @click="kirimDiagnosis">
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
    DataDiagnosa: Object,
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

  // ─── Log ─────────────────────────────────────────────────────
  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `diagnosis_logs_${props.DataPasien?.idpelayanan ?? 'default'}`
  );

  // ─── Kirim ───────────────────────────────────────────────────
  const kirimDiagnosis = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data Diagnosis (Condition) berhasil dikirim.',

      steps: [
        {
          logTitle: 'Pengiriman Data Diagnosis (Condition)',
          routeFn: () => route('satusehat.diagnosis', props.DataPasien?.idpelayanan),
          idField: 'results',
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
