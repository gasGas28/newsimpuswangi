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
      <div
        class="save-status"
        :class="{ success: lastStatus === 'success', danger: lastStatus === 'error' }"
      >
        {{ statusMessage }}
      </div>
      <button type="button" class="save-button" :disabled="isSending" @click="kirimStroke">
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
  const kategori_kolesterol = computed(() =>
    valueOrDash(patient.value.interpretasi_kolesterol_total)
  );
  const kategori_hdl = computed(() => valueOrDash(patient.value.interpretasi_hdl));
  const kategori_ldl = computed(() => valueOrDash(patient.value.interpretasi_ldl));
  const kategori_trigliserida = computed(() =>
    valueOrDash(patient.value.interpretasi_trigliserida)
  );

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false);
  const validationMessages = ref([]);

  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `stroke_logs_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  // ─── Kirim ───────────────────────────────────────────────────
  const kirimStroke = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data deteksi dini stroke (Observation + Condition) berhasil dikirim.',

      steps: [
        {
          logTitle: 'Pengiriman Deteksi Dini stroke (Observation)',
          routeFn: () => route('satusehat.profil-lipid', props.DataPasien?.idSkrining),
          idField: 'observation_id',
        },
        {
          logTitle: 'Pengiriman Diagnosis Stroke (Condition)',
          routeFn: () => route('satusehat.profil-lipid', props.DataPasien?.idSkrining),
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
