<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Kolorektal</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Jawaban Kuesioner 1</div>
          <div class="summary-value">{{ answer1 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Jawaban Kuesioner 2</div>
          <div class="summary-value">{{ answer1 }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Hasil Kuesioner</div>
          <div class="summary-value">{{ hasil_kuesioner }}</div>
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
  const hasil_kuesioner = computed(() => patient.value.result);

  // const answer1 = computed(() => valueOrDash(patient.value.kuesioner1));

  const answer1 = computed(() => {
    if (patient.value.question1 === 'false') {
      return 'Tidak memiliki riwayat keluarga kanker kolorektal generasi pertama';
    } else if (patient.value.question1 === 'true') {
      return 'Memiliki riwayat keluarga kanker kolorektal generasi pertama';
    } else {
      return 'Data belum tersedia';
    }
  });

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `kolorektal_logs_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const validationMessages = ref([]);
  
  const sendSatuSehat = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data Observation Kanker Kolorektal berhasil dikirim ke SATUSEHAT.',

      steps: [
        {
          logTitle: 'Pengiriman Data Kanker Kolorektal (Observation)',
          routeFn: () => route('satusehat.send-kolorektal', props.DataPasien?.idSkrining),
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
