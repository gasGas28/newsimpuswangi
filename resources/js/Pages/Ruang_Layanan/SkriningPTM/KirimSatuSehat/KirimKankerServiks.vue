<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Pemeriksaan Serviks</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Inspekulo</div>
          <div class="summary-value">{{ inspekulo }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">IVA</div>
          <div class="summary-value">{{ iva }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Sadanis</div>
          <div class="summary-value">{{ sadanis }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">HPV / DNA</div>
          <div class="summary-value">{{ hpv }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">USG Payudara</div>
          <div class="summary-value">{{ usg }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Krioterapi</div>
          <div class="summary-value">{{ krioterapi }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Thermal</div>
          <div class="summary-value">{{ thermal }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">TCA</div>
          <div class="summary-value">{{ tca }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Rujukan</div>
          <div class="summary-value">{{ rujuk }}</div>
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
  import { useSubmitLog } from '@/composables/useSubmitLog.js';
  import SubmitLogPanel from '@/Components/Layouts/RuangLayanan/SkriningPTM/SubmitLogPanel.vue';

  const props = defineProps({
    DataPasien: Object,
  });

  const patient = computed(() => props.DataPasien || {});
  const page = usePage();
  const flash = computed(() => page.props.flash);

  const inspekulo = computed(() => valueOrDash(patient.value.inspekulo));
  const iva = computed(() => valueOrDash(patient.value.iva));
  const hpv = computed(() => valueOrDash(patient.value.hpv_dna));
  const sadanis = computed(() => valueOrDash(patient.value.sadanis));
  const usg = computed(() => valueOrDash(patient.value.usg));
  const krioterapi = computed(() => valueOrDash(patient.value.krioterapi));
  const thermal = computed(() => valueOrDash(patient.value.thermal));
  const tca = computed(() => valueOrDash(patient.value.tca));
  const rujuk = computed(() => valueOrDash(patient.value.rujuk_serviks));

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const validationMessages = ref([]);

  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `kanker-serviks_logs_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  const sendSatuSehat = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data Observation Kanker Serviks berhasil dikirim ke SATUSEHAT.',

      steps: [
        {
          logTitle: 'Pengiriman Data Kanker Serviks (Observation)',
          routeFn: () => route('satusehat.kanker-serviks', props.DataPasien?.idSkrining),
          idField: 'id',
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
