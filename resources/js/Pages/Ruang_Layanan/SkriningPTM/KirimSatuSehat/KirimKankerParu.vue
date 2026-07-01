<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Kuesioner Kanker Paru</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Kuesioner 1</div>
          <div class="summary-value">{{ answer1 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 2</div>
          <div class="summary-value">{{ answer2 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 3</div>
          <div class="summary-value">{{ answer3 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 4</div>
          <div class="summary-value">{{ answer3 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 5</div>
          <div class="summary-value">{{ answer5 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 6</div>
          <div class="summary-value">{{ answer6 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kuesioner 7</div>
          <div class="summary-value">{{ answer7 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Hasil Kuesioner</div>
          <div class="summary-value">{{ hasil }}</div>
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

  const answer1 = computed(() => valueOrDash(patient.value.kuesioner1));
  const answer2 = computed(() => valueOrDash(patient.value.kuesioner2));
  const answer3 = computed(() => valueOrDash(patient.value.kuesioner3));
  const answer4 = computed(() => valueOrDash(patient.value.kuesioner4));
  const answer5 = computed(() => valueOrDash(patient.value.kuesioner5));
  const answer6 = computed(() => valueOrDash(patient.value.kuesioner6));
  const answer7 = computed(() => valueOrDash(patient.value.kuesioner7));
  const hasil = computed(() => valueOrDash(patient.value.hasil_kuesioner));

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const validationMessages = ref([]);

  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `kanker-paru_logs_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  const sendSatuSehat = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data Observation Kanker Paru berhasil dikirim ke SATUSEHAT.',

      steps: [
        {
          logTitle: 'Pengiriman Data Kanker Paru (Observation)',
          routeFn: () => route('satusehat.send-paru', props.DataPasien?.idSkrining),
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
