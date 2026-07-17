<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Asam Urat</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Asam Urat</div>
          <div class="summary-value">{{ asam_urat }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kategori Asam Urat</div>
          <div class="summary-value">{{ kategori }}</div>
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
        <button type="button" class="save-button" :disabled="isSending" @click="kirimAsamUrat">
          <i class="bi" :class="isSending ? 'bi-arrow-repeat spin' : 'bi-send'"></i>
          <span>{{ isSending ? 'Mengirim...' : 'Kirim ke SATUSEHAT' }}</span>
        </button>
      </div>
    </section>

    <!-- ── Panel: Log Pengiriman ── -->
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

  const page = usePage();
  const flash = computed(() => page.props.flash);

  const patient = computed(() => props.DataPasien || {});

  const asam_urat = computed(() => valueOrDash(patient.value.asam_urat));
  const kategori = computed(() => valueOrDash(patient.value.kategori_asam_urat));

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

  const {
    logs, isSending, lastStatus, statusMessage,
    clearLogs, submit,
  } = useSubmitLog(`asam-urat_logs_${props.DataPasien?.idSkrining ?? 'default'}`);

  // ─── Kirim ───────────────────────────────────────────────────
  const kirimAsamUrat = () => {
    submit({
      routerPost:     router.post.bind(router),
      getFlash:       () => page.props.flash,
      successMessage: 'Data Asam Urat (Observation + Condition) berhasil dikirim.',

      steps: [
        {
          logTitle: 'Pengiriman Data Asam Urat (Observation)',
          routeFn:  () => route('satusehat.asam-urat', props.DataPasien?.idSkrining),
          idField:  'observation_id',
        },
        {
          logTitle: 'Pengiriman Diagnosis Asam Urat (Condition)',
          routeFn:  () => route('satusehat.asam-urat', props.DataPasien?.idSkrining),
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
