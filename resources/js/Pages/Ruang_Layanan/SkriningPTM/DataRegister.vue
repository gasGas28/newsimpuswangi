<template>
  <div class="page-container">
    <!-- Header -->
    <div class="page-header">
      <div class="header-left">
        <div class="header-icon">
          <i class="bi bi-heart-pulse"></i>
        </div>
        <div>
          <h3 class="page-title">Laporan & Register Cohort Pasien PTM</h3>
          <p class="page-subtitle">Data Laporan dan Register Cohort Hipertensi dan Diabetes Melitus</p>
        </div>
      </div>
      <div class="header-right">
        <button type="button" class="btn-back" @click="goBack">
          <i class="bi bi-arrow-left"></i>
          <span>Kembali</span>
        </button>
      </div>
    </div>

    <!-- Data Pasien Card -->
    <div v-if="riwayat.length > 0" class="patient-card">
      <div class="patient-card-top">
        <div class="patient-avatar">
          <i class="bi bi-person-fill"></i>
        </div>
        <div class="patient-summary">
          <h4 class="patient-name">{{ riwayat[0]?.NAMA_LGKP?.toUpperCase() || '-' }}</h4>
          <div class="patient-meta">
            <span><i class="bi bi-card-text"></i> NIK: {{ riwayat[0]?.NIK || '-' }}</span>
            <span><i class="bi bi-file-medical"></i> No MR: {{ riwayat[0]?.NO_MR || '-' }}</span>
            <span><i class="bi bi-calendar3"></i> {{ formatDate(riwayat[0]?.TGL_LHR) }}</span>
          </div>
        </div>
        <div class="patient-stats">
          <div class="stat-chip">
            <span class="stat-value">{{ riwayat.length }}</span>
            <span class="stat-label">Total Data</span>
          </div>
          <div class="stat-chip stat-chip-warning">
            <span class="stat-value">{{ jumlahTidakNormal }}</span>
            <span class="stat-label">Tidak Normal</span>
          </div>
        </div>
      </div>

      <div class="patient-row">
        <div class="patient-col-full">
          <div class="patient-field">
            <label><i class="bi bi-geo-alt"></i> Alamat</label>
            <p>{{ riwayat[0]?.ALAMAT || '-' }}</p>
          </div>
        </div>
      </div>

      <div class="patient-divider"></div>

      <!-- ===== FILTER STATUS ===== -->
      <div class="filter-row">
        <label class="filter-label">Filter Data</label>
        <div class="segmented-control" role="group">
          <button
            type="button"
            class="segment-btn"
            :class="{ active: filterStatus === 'semua' }"
            :disabled="isFiltering"
            @click="setFilter('semua')"
          >
            Semua Data
          </button>
          <button
            type="button"
            class="segment-btn"
            :class="{ active: filterStatus === 'tidak_normal' }"
            :disabled="isFiltering"
            @click="setFilter('tidak_normal')"
          >
            HT &amp; DM Saja
          </button>
          <i v-if="isFiltering" class="bi bi-arrow-repeat spin filter-spinner"></i>
        </div>
      </div>
      <!-- ===== END FILTER STATUS ===== -->

      <div class="patient-card-footer">
        <button
          @click="downloadExcel"
          class="btn-download btn-excel"
          :disabled="!riwayat.length || isDownloadingExcel"
        >
          <i :class="isDownloadingExcel ? 'bi bi-arrow-repeat spin' : 'bi bi-file-earmark-excel'"></i>
          <span>{{ isDownloadingExcel ? 'Mengunduh...' : 'Download Register Cohort' }}</span>
        </button>

        <button
          @click="downloadLaporanPTM"
          class="btn-download btn-laporan"
          :disabled="!riwayat.length || isDownloadingLaporan"
        >
          <i
            :class="isDownloadingLaporan ? 'bi bi-arrow-repeat spin' : 'bi bi-file-earmark-spreadsheet'"
          ></i>
          <span>{{ isDownloadingLaporan ? 'Mengunduh...' : 'Download Laporan' }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';
  import { router } from '@inertiajs/vue3';
  import { watch } from 'vue';
  import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';

  defineOptions({ layout: AppLayouts });

  const props = defineProps({
    DataRiwayat: Array,
    FilterStatus: { type: String, default: 'semua' }, // dikirim dari controller
  });
  watch(
    () => props.FilterStatus,
    (newVal) => {
      console.log('FilterStatus berubah jadi:', newVal);
    }
  );

  watch(
    () => props.DataRiwayat,
    (newVal) => {
      console.log('DataRiwayat berubah, jumlah:', newVal?.length);
    }
  );

  // computed, BUKAN const biasa — supaya ikut ter-update saat Inertia
  // mengirim props baru lewat router.get({ preserveState: true }),
  // karena preserveState mempertahankan instance komponen (tidak remount).
  const riwayat = computed(() => props.DataRiwayat || []);
  const isDownloading = ref(false);

  // ===== FILTER STATUS =====
  const filterStatus = ref(props.FilterStatus || 'semua');
  const isFiltering = ref(false);

  const isAbnormal = (kategori) => {
    if (!kategori) return false;
    return kategori.toLowerCase() !== 'normal';
  };

  // ringkasan jumlah data yang punya minimal satu kategori tidak normal
  const jumlahTidakNormal = computed(() => {
    return riwayat.value.filter((data) => {
      return (
        isAbnormal(data.kategori_tekanan_darah) ||
        isAbnormal(data.kategori_gula_darah_puasa) ||
        isAbnormal(data.kategori_gula_darah_sewaktu) ||
        isAbnormal(data.kategori_gula_darah_2_jam_pp) ||
        isAbnormal(data.kategori_hba1c)
      );
    }).length;
  });

  const applyFilter = () => {
    isFiltering.value = true;
    const nik = getQueryParam('NIK');

    router.get(
      route('ruang-layanan.register-ptm'),
      { NIK: nik, status: filterStatus.value },
      {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
          isFiltering.value = false;
        },
      }
    );
  };

  const setFilter = (value) => {
    if (filterStatus.value === value || isFiltering.value) return;
    filterStatus.value = value;
    applyFilter();
  };
  // ===== END FILTER STATUS =====

  const formatDate = (date) => {
    if (!date) return '-';
    try {
      return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
      });
    } catch {
      return date;
    }
  };

  const getQueryParam = (param) => {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  };

  const goBack = () => {
    window.history.back();
  };

  const isDownloadingExcel = ref(false);
  const isDownloadingLaporan = ref(false);

  const downloadLaporanPTM = async () => {
    if (riwayat.value.length === 0 || isDownloadingLaporan.value) return;
    isDownloadingLaporan.value = true;

    try {
      const response = await fetch(route('ptm.export-laporanPTM'), {
        method: 'GET',
        headers: {
          Accept: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        },
      });

      if (!response.ok) throw new Error('Gagal mengunduh Laporan');

      const blob = await response.blob();
      const contentDisposition = response.headers.get('Content-Disposition');
      let filename = 'Laporan_Klaster_3_PTM.xlsx';
      if (contentDisposition) {
        const match = contentDisposition.match(/filename="?([^"]+)"?/);
        if (match) filename = match[1];
      }

      const url = window.URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = filename;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      window.URL.revokeObjectURL(url);
    } catch (error) {
      console.error('Download Laporan error:', error);
      alert('Gagal mengunduh Laporan. Silakan coba lagi.');
    } finally {
      isDownloadingLaporan.value = false;
    }
  };

  const downloadExcel = async () => {
    if (riwayat.value.length === 0 || isDownloadingExcel.value) return;
    isDownloadingExcel.value = true;

    try {
      const tahun = new Date().getFullYear();
      // ikutkan filter status yang sedang aktif supaya export konsisten dengan tampilan
      const response = await fetch(
        route('ptm.export-register', { tahun, status: filterStatus.value }),
        {
          method: 'GET',
          headers: {
            Accept: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
          },
        }
      );

      if (!response.ok) throw new Error('Gagal mengunduh Excel');

      const blob = await response.blob();
      const contentDisposition = response.headers.get('Content-Disposition');
      let filename = `kohort-ptm-${tahun}.xlsx`;
      if (contentDisposition) {
        const match = contentDisposition.match(/filename="?([^"]+)"?/);
        if (match) filename = match[1];
      }

      const url = window.URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = filename;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      window.URL.revokeObjectURL(url);
    } catch (error) {
      console.error('Download Excel error:', error);
      alert('Gagal mengunduh Excel. Silakan coba lagi.');
    } finally {
      isDownloadingExcel.value = false;
    }
  };
</script>

<style scoped>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .page-container {
    min-height: 100vh;
    background: #f1f5f9;
    padding: 24px;
    font-family:
      -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  }

  /* ===== HEADER ===== */
  .page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 22px;
    padding: 20px 24px;
    border-radius: 14px;
    background: linear-gradient(120deg, #1e3a8a 0%, #2563eb 55%, #0ea5e9 100%);
    box-shadow: 0 12px 28px rgba(37, 99, 235, 0.28);
  }

  .header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1;
    min-width: 0;
  }

  .header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    flex-shrink: 0;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.16);
    color: #ffffff;
    font-size: 22px;
  }

  .page-title {
    margin-bottom: 3px;
    color: #ffffff;
    font-size: 21px;
    font-weight: 750;
  }

  .page-subtitle {
    color: rgba(255, 255, 255, 0.78);
    font-size: 13px;
  }

  .header-right {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    flex-wrap: wrap;
  }

  .btn-back {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 9px 18px;
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.12);
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.18s ease, transform 0.12s ease;
    white-space: nowrap;
  }

  .btn-back:hover {
    background: rgba(255, 255, 255, 0.24);
    transform: translateY(-1px);
  }

  .btn-back i,
  .btn-download i {
    font-size: 16px;
  }

  /* ===== PATIENT CARD ===== */
  .patient-card {
    margin-bottom: 24px;
    padding: 22px 24px;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    background: #ffffff;
    box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06);
  }

  .patient-card-top {
    display: flex;
    align-items: center;
    gap: 18px;
    margin-bottom: 18px;
  }

  .patient-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    flex-shrink: 0;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #0ea5e9);
    color: #ffffff;
    font-size: 26px;
    box-shadow: 0 6px 14px rgba(37, 99, 235, 0.3);
  }

  .patient-summary {
    flex: 1;
    min-width: 0;
  }

  .patient-name {
    margin-bottom: 6px;
    color: #0f172a;
    font-size: 18px;
    font-weight: 750;
    letter-spacing: 0.2px;
  }

  .patient-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px 18px;
    color: #64748b;
    font-size: 13px;
  }

  .patient-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .patient-stats {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
  }

  .stat-chip {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 84px;
    padding: 10px 14px;
    border-radius: 12px;
    background: #eff6ff;
  }

  .stat-chip .stat-value {
    color: #1d4ed8;
    font-size: 20px;
    font-weight: 800;
    line-height: 1.1;
  }

  .stat-chip .stat-label {
    margin-top: 2px;
    color: #64748b;
    font-size: 11px;
    font-weight: 650;
    text-transform: uppercase;
  }

  .stat-chip-warning {
    background: #fef2f2;
  }

  .stat-chip-warning .stat-value {
    color: #dc2626;
  }

  .patient-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 4px;
  }

  .patient-col-full {
    grid-column: 1 / -1;
  }

  .patient-field {
    display: flex;
    flex-direction: column;
    min-width: 0;
  }

  .patient-field label {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 6px;
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }

  .patient-field p {
    color: #1e293b;
    font-size: 15px;
    word-break: break-word;
  }

  .patient-divider {
    height: 1px;
    margin: 18px 0;
    background: #eef2f7;
  }

  /* ===== FILTER ===== */
  .filter-row {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
  }

  .filter-label {
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }

  .segmented-control {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px;
    border-radius: 999px;
    background: #f1f5f9;
  }

  .segment-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 999px;
    background: transparent;
    color: #475569;
    font-size: 13px;
    font-weight: 650;
    cursor: pointer;
    transition: background-color 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
  }

  .segment-btn:disabled {
    cursor: not-allowed;
    opacity: 0.6;
  }

  .segment-btn.active {
    background: #2563eb;
    color: #ffffff;
    box-shadow: 0 6px 14px rgba(37, 99, 235, 0.28);
  }

  .filter-spinner {
    color: #2563eb;
    font-size: 16px;
  }

  /* ===== DOWNLOAD BUTTONS ===== */
  .patient-card-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
    padding-top: 18px;
    border-top: 1px solid #e2e8f0;
    flex-wrap: wrap;
  }

  .btn-download {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 10px 18px;
    border: 0;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.2;
    cursor: pointer;
    transition:
      background-color 0.18s ease,
      box-shadow 0.18s ease,
      transform 0.12s ease;
    white-space: nowrap;
  }

  .btn-download:hover:not(:disabled) {
    transform: translateY(-1px);
  }

  .btn-excel {
    background-color: #16a34a;
    color: #ffffff;
    box-shadow: 0 8px 18px rgba(22, 163, 74, 0.22);
  }

  .btn-excel:hover:not(:disabled) {
    background-color: #15803d;
    box-shadow: 0 10px 22px rgba(22, 163, 74, 0.28);
  }

  .btn-laporan {
    background-color: #2563eb;
    color: #ffffff;
    box-shadow: 0 8px 18px rgba(37, 99, 235, 0.22);
  }

  .btn-laporan:hover:not(:disabled) {
    background-color: #1d4ed8;
    box-shadow: 0 10px 22px rgba(37, 99, 235, 0.28);
  }

  .btn-download:disabled {
    opacity: 0.56;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
  }

  .spin {
    animation: spin 0.8s linear infinite;
  }

  /* ===== TABLE ===== */
  .table-container {
    overflow: hidden;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    background: #ffffff;
    box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06);
  }

  .table-header-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 16px 22px;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
  }

  .table-header-info h4 {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #1f2d3d;
    font-size: 15px;
    font-weight: 700;
  }

  .record-count {
    padding: 4px 12px;
    border-radius: 999px;
    background: #eef2f7;
    color: #475569;
    font-size: 12px;
    font-weight: 650;
  }

  .table-scroll {
    overflow-x: auto;
  }

  .data-table {
    width: 100%;
    min-width: 920px;
    border-collapse: collapse;
    table-layout: auto;
  }

  .data-table thead {
    background: #0f1f3d;
    color: #ffffff;
  }

  .data-table th {
    padding: 14px 12px;
    border: none;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.6px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
    position: sticky;
    top: 0;
  }

  .data-table tbody tr {
    border-bottom: 1px solid #eef2f7;
    transition: background-color 0.16s ease;
  }

  .data-table tbody tr:nth-child(odd) {
    background-color: #fafbfd;
  }

  .data-table tbody tr:hover {
    background-color: #eef7ff;
  }

  .data-table td {
    padding: 12px;
    border: none;
    color: #1e293b;
    font-size: 13.5px;
    vertical-align: middle;
  }

  .empty-row td {
    padding: 0 !important;
  }

  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 48px 12px;
    color: #94a3b8;
  }

  .empty-state i {
    font-size: 30px;
  }

  .text-muted {
    color: #94a3b8;
  }

  .col-no {
    width: 4%;
    font-weight: 700;
    text-align: center;
  }

  .col-date {
    width: 12%;
  }

  .date-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 8px;
    background: #f1f5f9;
    color: #334155;
    font-size: 12.5px;
    font-weight: 600;
    white-space: nowrap;
  }

  .col-bp {
    width: 12%;
    text-align: center;
  }

  .col-gula {
    width: 16%;
  }

  .col-anthro {
    width: 14%;
    font-size: 13px;
  }

  .col-nadi,
  .col-suhu,
  .col-rr {
    width: 8%;
    text-align: center;
  }

  .col-keluhan {
    width: 17%;
  }

  .badge-bp {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 999px;
    background-color: #0ea5e9;
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
  }

  .badge-abnormal {
    background-color: #dc2626 !important;
  }

  .kategori-label {
    margin-top: 3px;
    color: #64748b;
    font-size: 11px;
    text-align: center;
  }

  .gula-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #475569;
    font-size: 12.5px;
    line-height: 1.7;
  }

  .dot-status {
    display: inline-block;
    width: 7px;
    height: 7px;
    flex-shrink: 0;
    border-radius: 50%;
  }

  .dot-normal {
    background-color: #16a34a;
  }

  .dot-abnormal {
    background-color: #dc2626;
  }

  .anthro-item {
    color: #475569;
    font-size: 12.5px;
    line-height: 1.6;
  }

  .anthro-imt {
    font-weight: 700;
    color: #1e293b;
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  @media (max-width: 768px) {
    .page-container {
      padding: 16px;
    }

    .page-header {
      align-items: flex-start;
      flex-direction: column;
      gap: 14px;
      padding: 18px;
    }

    .header-right {
      width: 100%;
      justify-content: flex-start;
    }

    .patient-card-top {
      flex-wrap: wrap;
    }

    .patient-stats {
      width: 100%;
      justify-content: flex-start;
    }

    .patient-row {
      grid-template-columns: 1fr;
      gap: 12px;
    }

    .patient-card-footer {
      align-items: stretch;
      flex-direction: column;
    }

    .patient-card-footer .btn-download {
      width: 100%;
    }
  }

  @media (max-width: 480px) {
    .page-title {
      font-size: 18px;
    }

    .table-header-info {
      align-items: flex-start;
      flex-direction: column;
    }

    .badge-bp {
      padding: 2px 8px;
      font-size: 11px;
    }
  }

  @media print {
    .page-header,
    .patient-card-footer {
      display: none;
    }

    .page-container {
      padding: 0;
      background: #ffffff;
    }

    .patient-card,
    .table-container {
      box-shadow: none;
    }

    .patient-card {
      page-break-inside: avoid;
    }

    .data-table thead {
      background: #ffffff;
      color: #000000;
    }

    .data-table th,
    .data-table td {
      border: 1px solid #000000;
    }

    .data-table tbody tr:nth-child(odd),
    .data-table tbody tr:hover {
      background-color: #ffffff;
    }
  }
</style>