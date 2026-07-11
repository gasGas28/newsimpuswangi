<template>
  <div class="resume-form">
    <!-- ── Tab Navigation ── -->
    <div class="send-tabs-wrapper">
      <div class="send-tabs-grid">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          type="button"
          class="send-tab"
          :class="{ 'send-tab--active': selectedForm === tab.value }"
          @click="selectedForm = tab.value"
        >
          <i class="bi" :class="tab.icon"></i>
          <span>{{ tab.label }}</span>
        </button>
      </div>
    </div>

    <!-- ── Panel Konten ── -->
    <div class="send-tab-content">
      <div v-if="!selectedForm" class="send-tab-empty">
        <i class="bi bi-arrow-up-circle send-tab-empty-icon"></i>
        <p>Pilih jenis data yang ingin dikirim ke SATUSEHAT.</p>
      </div>

      <SendTindakan v-if="selectedForm === 'Tindakan'" :DataPasien="props.DataPasien" :DataTindakan="props.DataTindakan" :DataEdukasi="props.DataEdukasi" />
      <SendEdukasi v-if="selectedForm === 'Edukasi'" :DataPasien="props.DataPasien" :DataEdukasi="props.DataEdukasi" />
      <SendPengobatan v-if="selectedForm === 'Pengobatan'" :DataPasien="props.DataPasien" :ResepObat="props.ResepObat" />
    </div>
  </div>
</template>

<script setup>
  import { ref } from 'vue';
  import SendTindakan from '../KirimSatuSehat/KirimTindakan.vue';
  import SendEdukasi from '../KirimSatuSehat/KirimEdukasi.vue';
  import SendPengobatan from '../KirimSatuSehat/KirimPengobatan.vue';

  const props = defineProps({
    DataPasien: Object,
    DataTindakan: Array,
    DataEdukasi: Array,
    ResepObat: Array,
  });

  const selectedForm = ref('Tindakan');

  const tabs = [
    { value: 'Tindakan', label: 'Tindakan', icon: 'bi-speedometer2' },
    { value: 'Edukasi', label: 'Edukasi', icon: 'bi-heart-pulse' },
    { value: 'Pengobatan', label: 'Pengobatan', icon: 'bi-droplet-half' },
  ];
</script>

<style scoped src="@/css/FormPemeriksaan.css"></style>

<style scoped>
  .send-tabs-wrapper {
    margin-bottom: 20px;
  }

  .send-tabs-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    border-bottom: 2px solid #e2e8f0;
    row-gap: 0;
  }

  /* Baris pertama tiap tab punya garis bawah tipis pemisah antar baris */
  .send-tab:nth-child(-n + 6) {
    border-bottom: 2px solid #e2e8f0;
    margin-bottom: -2px;
  }

  /* ── Tiap tab: sama persis dengan versi sebelumnya ── */
  .send-tab {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    border: none;
    border-bottom: 3px solid transparent;
    background: transparent;
    color: #080808;
    font-size: 0.84rem;
    font-weight: 600;
    white-space: nowrap;
    cursor: pointer;
    border-radius: 6px 6px 0 0;
    transition:
      color 0.15s ease,
      border-color 0.15s ease,
      background 0.15s ease;
    margin-bottom: -2px;
  }

  .send-tab i {
    font-size: 0.95rem;
  }

  .send-tab:hover {
    color: #fdfdfd;
    background: #05c283;
  }

  /* ── Tab aktif ── */
  .send-tab--active {
    color: #f4f7f5;
    border-bottom-color: #05c283;
    background: #05c283;
  }

  .send-tab--active i {
    color: #fdfffe;
  }

  /* ── Empty state ── */
  .send-tab-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 60px 20px;
    color: #94a3b8;
    text-align: center;
  }

  .send-tab-empty-icon {
    font-size: 2.8rem;
    color: #cbd5e1;
  }

  .send-tab-empty p {
    margin: 0;
    font-size: 0.95rem;
    color: #64748b;
  }

  /* ── Responsive: layar kecil kembali ke 3 kolom ── */
  @media (max-width: 640px) {
    .send-tabs-grid {
      grid-template-columns: repeat(3, 1fr);
    }

    .send-tab:nth-child(-n + 6) {
      border-bottom: 2px solid #e2e8f0;
    }

    .send-tab:nth-child(-n + 3) {
      border-bottom: 2px solid #e2e8f0;
    }
  }
</style>
