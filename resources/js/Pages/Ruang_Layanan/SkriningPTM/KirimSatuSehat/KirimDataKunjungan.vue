<template>
  <div class="kirim-kunjungan-wrapper">
    <!-- ── Panel: Ringkasan Data ── -->
    <section class="resume-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-check"></i> Data Kunjungan</h4>
          <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="summary-grid">
          <div class="summary-item">
            <div class="summary-label">Nama Pasien</div>
            <div class="summary-value">{{ patientName }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">NIK</div>
            <div class="summary-value">{{ NIK }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Tanggal Skrining</div>
            <div class="summary-value">{{ tglSkrining }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Fasyankes</div>
            <div class="summary-value">{{ fasyankes }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Pemeriksa</div>
            <div class="summary-value">{{ petugas }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Jenis Kunjungan</div>
            <div class="summary-value">{{ kunjungan }}</div>
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
        <button type="button" class="save-button" :disabled="isSending" @click="kirimEncounter">
          <i class="bi" :class="isSending ? 'bi-arrow-repeat spin' : 'bi-send'"></i>
          <span>{{ isSending ? 'Mengirim...' : 'Kirim ke SATUSEHAT' }}</span>
        </button>
      </div>
    </section>

    <!-- ── Panel: Log Pengiriman ── -->
    <SubmitLogPanel
      :logs="logs"
      description="Riwayat percobaan pengiriman data kunjungan ke platform SATUSEHAT."
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
    DataSkrining: Object,
  });

  const page = usePage();
  const flash = computed(() => page.props.flash);
  const patient = computed(() => props.DataPasien || {});

  const NIK = computed(() => valueOrDash(patient.value.NIK));
  const fasyankes = computed(() => valueOrDash(patient.value.nama_unit));
  const patientName = computed(() => valueOrDash(patient.value.NAMA_LGKP));
  const petugas = computed(() => valueOrDash(patient.value.nmDokter));
  const kunjungan = computed(() => valueOrDash(patient.value.jenis_kunjungan));

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

  // ─── Log ─────────────────────────────────────────────────────
  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `encounter_logs_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  // ─── Kirim ───────────────────────────────────────────────────
  const kirimEncounter = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data kunjungan berhasil dikirim ke SATUSEHAT.',

      steps: [
        {
          logTitle: 'Pengiriman Data Kunjungan (Encounter)',
          routeFn: () => route('satusehat.encounter', props.DataPasien?.idSkrining),
          idField: 'encounterId',
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
  .kirim-kunjungan-wrapper {
    display: grid;
    gap: 18px;
  }

  .spin {
    display: inline-block;
    animation: spin 0.8s linear infinite;
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  .save-status.danger {
    color: #dc2626;
  }
</style>
