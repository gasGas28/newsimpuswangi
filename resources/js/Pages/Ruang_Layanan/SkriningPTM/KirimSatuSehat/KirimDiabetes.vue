<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Diabetes</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Gula Darah Sewaktu (GDS)</div>
          <div class="summary-value">{{ gds }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Interpretasi GDS</div>
          <div class="summary-value">{{ kategori_gds }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Gula Darah Puasa (GDP)</div>
          <div class="summary-value">{{ gdp }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Interpretasi GDP</div>
          <div class="summary-value">{{ kategori_gdp }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Gula Darah 2 Jam Pasca Puasa (GD2PP)</div>
          <div class="summary-value">{{ gd2pp }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Interpretasi GD2PP</div>
          <div class="summary-value">{{ kategori_gd2pp }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">hbA1C</div>
          <div class="summary-value">{{ hba1c }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kategori hbA1c</div>
          <div class="summary-value">{{ kategori_hba1c }}</div>
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
      <button type="button" class="save-button" :disabled="isSending" @click="kirimDiabetes">
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

  const page = usePage();
  const flash = computed(() => page.props.flash);

  const patient = computed(() => props.DataPasien || {});

  const gdp = computed(() => valueOrDash(patient.value.gula_darah_puasa));
  const gds = computed(() => valueOrDash(patient.value.gula_darah_sewaktu));
  const gd2pp = computed(() => valueOrDash(patient.value.gula_darah_2_jam_pp));
  const hba1c = computed(() => valueOrDash(patient.value.hba1c));
  const kategori_gdp = computed(() => valueOrDash(patient.value.kategori_gula_darah_puasa));
  const kategori_gds = computed(() => valueOrDash(patient.value.kategori_gula_darah_sewaktu));
  const kategori_gd2pp = computed(() => valueOrDash(patient.value.kategori_gula_darah_2_jam_pp));
  const kategori_hba1c = computed(() => valueOrDash(patient.value.kategori_hba1c));

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false);
  const validationMessages = ref([]);

  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `diabetes_logs_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  // ─── Kirim ───────────────────────────────────────────────────
  const kirimDiabetes = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data Deteksi Dini Diabetes (Observation + Condition) berhasil dikirim.',

      steps: [
        {
          logTitle: 'Pengiriman Diagnosis Diabetes (Condition)',
          routeFn: () => route('satusehat.diabetes', props.DataPasien?.idSkrining),
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
