<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Data Edukasi</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div v-for="edukasi in props.DataEdukasi" :key="edukasi.id">
        <div class="summary-grid">
          <div class="summary-item">
            <div class="summary-label">Kode Snomed</div>
            <div class="summary-value">{{ edukasi.kode_snomed }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Nama Edukasi</div>
            <div class="summary-value">{{ edukasi.nama_edukasi }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Display</div>
            <div class="summary-value">{{ edukasi.display }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Keterangan</div>
            <div class="summary-value">{{ edukasi.keterangan }}</div>
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
      <button type="button" class="save-button" :disabled="isSending" @click="kirimEdukasi">
        <i class="bi" :class="isSending ? 'bi-arrow-repeat spin' : 'bi-send'"></i>
        <span>{{ isSending ? 'Mengirim...' : 'Kirim ke SATUSEHAT' }}</span>
      </button>
    </div>
  </section>
  <SubmitLogPanel
    :logs="logs"
    description="Riwayat percobaan pengiriman data deteksi edukasi ke platform SATUSEHAT."
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
    DataEdukasi: Array,
  });

  const page = usePage();
  const flash = computed(() => page.props.flash);

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false);
  const validationMessages = ref([]);

   const { logs, isSending, lastStatus, statusMessage, clearLogs, submit } = useSubmitLog(
    `edukasi_${props.DataPasien?.idSkrining ?? 'default'}`
  );

  console.log('skriningid: ', props.DataPasien?.idSkrining);

  // ─── Kirim ───────────────────────────────────────────────────
  const kirimEdukasi = () => {
    submit({
      routerPost: router.post.bind(router),
      getFlash: () => page.props.flash,
      successMessage: 'Data Edukasi (Condition) berhasil dikirim.',

      steps: [
        {
          logTitle: 'Pengiriman Data Edukasi (Condition)',
          routeFn: () => route('satusehat.edukasi', props.DataPasien?.idSkrining),
          idField: 'procedureId',
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
