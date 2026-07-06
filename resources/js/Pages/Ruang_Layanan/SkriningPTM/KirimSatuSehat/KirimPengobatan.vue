<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Pengobatan</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div v-for="obat in props.ResepObat" :key="obat.id_resep">
        <div class="summary-grid">
          <div class="summary-item">
            <div class="summary-label">Nama Obat</div>
            <div class="summary-value">{{ obat.NAMA }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Jenis Obat</div>
            <div class="summary-value">{{ obat.nama_puyer }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Jumlah Obat</div>
            <div class="summary-value">{{ obat.jumlah_puyer }} {{ obat.SATUAN }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Dosis Pakai</div>
            <div class="summary-value">{{ obat.dosis_pakai_puyer }}x sehari setiap {{ obat.tiapJam }} sam sekali {{ obat.kondisi }}</div>
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
      <button type="button" class="save-button" :disabled="isSending" @click="kirimObesitas">
        <i class="bi" :class="isSending ? 'bi-arrow-repeat spin' : 'bi-send'"></i>
        <span>{{ isSending ? 'Mengirim...' : 'Kirim ke SATUSEHAT' }}</span>
      </button>
    </div>
  </section>
  <SubmitLogPanel
    :logs="logs"
    description="Riwayat percobaan pengiriman data deteksi dini obesitas ke platform SATUSEHAT."
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
    ResepObat: Array,
  });

  const page = usePage();
  const flash = computed(() => page.props.flash);

  const patient = computed(() => props.DataPasien || {});

  const bb = computed(() => valueOrDash(patient.value.berat_badan));
  const tb = computed(() => valueOrDash(patient.value.tinggi_badan));
  const imt = computed(() => valueOrDash(patient.value.imt));
  const imt_status = computed(() => valueOrDash(patient.value.interpretasi_ptm));
  const lp = computed(() => valueOrDash(patient.value.lingkar_pinggang));
  const lp_status = computed(() => valueOrDash(patient.value.interpretasi_lp));

  function valueOrDash(value) {
    return value === undefined || value === null || value === '' ? '-' : value;
  }

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false);
  const validationMessages = ref([]);

  const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `obesitas_logs_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  // ─── Kirim ───────────────────────────────────────────────────
  const kirimObesitas = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data deteksi dini obesitas (Observation + Condition) berhasil dikirim.',

      steps: [
        {
          logTitle: 'Pengiriman Deteksi Dini Obesitas (Observation)',
          routeFn: () => route('satusehat.obesitas', props.DataPasien?.idSkrining),
          idField: 'observation_id',
        },
        {
          logTitle: 'Pengiriman Diagnosis Obesitas (Condition)',
          routeFn: () => route('satusehat.obesitas', props.DataPasien?.idSkrining),
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
