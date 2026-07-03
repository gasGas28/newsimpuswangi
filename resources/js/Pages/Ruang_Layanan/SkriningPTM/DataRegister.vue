<template>
  <div class="page-container">
    <!-- Header -->
    <div class="page-header">
      <div class="header-left">
        <h3 class="page-title">Riwayat Pemeriksaan PTM Pasien</h3>
        <p class="page-subtitle">Data Riwayat Kesehatan (Medical Record)</p>
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
      <div class="patient-row">
        <div class="patient-col">
          <div class="patient-field">
            <label>Nama Pasien</label>
            <p>{{ riwayat[0]?.NAMA_LGKP?.toUpperCase() || '-' }}</p>
          </div>
        </div>
        <div class="patient-col">
          <div class="patient-field">
            <label>NIK</label>
            <p>{{ riwayat[0]?.NIK || '-' }}</p>
          </div>
        </div>
      </div>
      <div class="patient-row">
        <div class="patient-col">
          <div class="patient-field">
            <label>No MR</label>
            <p>{{ riwayat[0]?.NO_MR || '-' }}</p>
          </div>
        </div>
        <div class="patient-col">
          <div class="patient-field">
            <label>Tgl Lahir</label>
            <p>{{ formatDate(riwayat[0]?.TGL_LHR) }}</p>
          </div>
        </div>
      </div>
      <div class="patient-row">
        <div class="patient-col-full">
          <div class="patient-field">
            <label>Alamat</label>
            <p>{{ riwayat[0]?.ALAMAT || '-' }}</p>
          </div>
        </div>
      </div>
      <div class="patient-card-footer">
        <button
          @click="downloadPDF"
          class="btn-download btn-pdf"
          :disabled="!riwayat.length || isDownloading"
        >
          <i :class="isDownloading ? 'bi bi-arrow-repeat' : 'bi bi-file-pdf'"></i>
          <span>{{ isDownloading ? 'Mengunduh...' : 'Download PDF' }}</span>
        </button>
      </div>
    </div>

    <!-- Tabel Data -->
    <div class="table-container">
      <div class="table-header-info">
        <h4>Data Riwayat Pemeriksaan</h4>
        <span class="record-count">{{ riwayat.length }} data pemeriksaan</span>
      </div>

      <table class="data-table">
        <thead>
          <tr>
            <th class="col-no">No</th>
            <th class="col-date">Tgl Kunjung</th>
            <th class="col-bp">Tekanan Darah</th>
            <th class="col-anthro">Antropometri</th>
            <th class="col-nadi">Nadi</th>
            <th class="col-suhu">Suhu</th>
            <th class="col-rr">RR</th>
            <th class="col-keluhan">Keluhan Utama</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(data, index) in riwayat" :key="index" class="data-row">
            <td class="col-no">{{ index + 1 }}</td>
            <td class="col-date">{{ formatDate(data.tanggal_skrining) }}</td>
            <td class="col-bp">
              <span class="badge-bp"
                >{{ data.sistolik || '-' }}/{{ data.tekanan_diastolik || '-' }}</span
              >
            </td>
            <td class="col-anthro">
              <div class="anthro-item">TB: {{ data.tinggi_badan || '-' }}</div>
              <div class="anthro-item">BB: {{ data.berat_badan || '-' }}</div>
              <div class="anthro-item">IMT: {{ data.imt || '-' }}</div>
            </td>
            <td class="col-nadi">{{ data.nadi || '-' }}</td>
            <td class="col-suhu">{{ data.suhu || '-' }}°C</td>
            <td class="col-rr">{{ data.pernapasan || '-' }}</td>
            <td class="col-keluhan">{{ data.keluhan_utama || '-' }}</td>
          </tr>
          <tr v-if="!riwayat.length" class="empty-row">
            <td colspan="8">Tidak ada data riwayat kesehatan</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
  import { ref } from 'vue';

  const props = defineProps({
    DataRiwayat: Array,
  });

  const riwayat = props.DataRiwayat || [];
  const isDownloading = ref(false);

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

  const downloadPDF = async () => {
    if (riwayat.length === 0 || isDownloading.value) return;

    isDownloading.value = true;
    const NIK = getQueryParam('NIK');

    try {
      const response = await fetch(
        route('ruang-layanan.download-register') + '?NIK=' + NIK,
        {
          method: 'GET',
          headers: {
            Accept: 'application/pdf',
          },
        }
      );

      if (!response.ok) {
        throw new Error('Gagal mengunduh PDF');
      }

      const blob = await response.blob();

      const contentDisposition = response.headers.get('Content-Disposition');
      let filename = 'Riwayat_Pasien.pdf';
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
      console.error('Download PDF error:', error);
      alert('Gagal mengunduh PDF. Silakan coba lagi.');
    } finally {
      isDownloading.value = false;
    }
  };

  console.log('Data Riwayat:', riwayat);
</script>

<style scoped>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .page-container {
    min-height: 100vh;
    background: #f5f7fb;
    padding: 24px;
    font-family:
      -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  }

  .page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 22px;
    padding: 18px 20px;
    border: 1px solid #dde5ee;
    border-radius: 8px;
    background: #ffffff;
    box-shadow: 0 8px 22px rgba(15, 23, 42, 0.05);
  }

  .header-left {
    flex: 1;
    min-width: 0;
  }

  .page-title {
    margin-bottom: 4px;
    color: #1f2d3d;
    font-size: 22px;
    font-weight: 750;
  }

  .page-subtitle {
    color: #64748b;
    font-size: 13px;
  }

  .header-right {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    flex-wrap: wrap;
  }

  .btn-back,
  .btn-download {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 9px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.2;
    cursor: pointer;
    transition: background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease,
      box-shadow 0.18s ease;
    white-space: nowrap;
  }

  .btn-back {
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
  }

  .btn-back:hover {
    border-color: #94a3b8;
    background: #f8fafc;
    color: #0f172a;
  }

  .btn-download {
    border: 0;
  }

  .btn-pdf {
    background-color: #dc2626;
    color: #ffffff;
    box-shadow: 0 8px 18px rgba(220, 38, 38, 0.18);
  }

  .btn-pdf:hover:not(:disabled) {
    background-color: #b91c1c;
    box-shadow: 0 10px 22px rgba(220, 38, 38, 0.24);
  }

  .btn-download:disabled {
    opacity: 0.56;
    cursor: not-allowed;
    box-shadow: none;
  }

  .btn-back i,
  .btn-download i {
    font-size: 16px;
  }

  .btn-download .bi-arrow-repeat {
    animation: spin 0.8s linear infinite;
  }

  .patient-card {
    margin-bottom: 24px;
    padding: 20px;
    border: 1px solid #dde5ee;
    border-radius: 8px;
    background: #ffffff;
    box-shadow: 0 8px 22px rgba(15, 23, 42, 0.05);
  }

  .patient-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 16px;
  }

  .patient-row:last-of-type {
    margin-bottom: 0;
  }

  .patient-col,
  .patient-field {
    display: flex;
    flex-direction: column;
    min-width: 0;
  }

  .patient-col-full {
    grid-column: 1 / -1;
  }

  .patient-field label {
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

  .patient-card-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
  }

  .table-container {
    overflow: hidden;
    border: 1px solid #dde5ee;
    border-radius: 8px;
    background: #ffffff;
    box-shadow: 0 8px 22px rgba(15, 23, 42, 0.05);
  }

  .table-header-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 16px 20px;
    border-bottom: 1px solid #dde5ee;
    background: #f8fafc;
  }

  .table-header-info h4 {
    color: #1f2d3d;
    font-size: 16px;
    font-weight: 700;
  }

  .record-count {
    color: #64748b;
    font-size: 12px;
    font-weight: 650;
    text-align: right;
  }

  .data-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: auto;
  }

  .data-table thead {
    background: #1f2d3d;
    color: #ffffff;
  }

  .data-table th {
    padding: 14px 12px;
    border: none;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-align: left;
    text-transform: uppercase;
    white-space: normal;
  }

  .data-table tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: background-color 0.2s ease;
  }

  .data-table tbody tr:nth-child(odd) {
    background-color: #fafbfc;
  }

  .data-table tbody tr:hover {
    background-color: #eef7ff;
  }

  .data-table td {
    padding: 12px;
    border: none;
    color: #1e293b;
    font-size: 14px;
    vertical-align: middle;
  }

  .data-table tbody tr.empty-row td {
    padding: 32px 12px;
    color: #64748b;
    font-style: italic;
    text-align: center;
  }

  .col-no {
    width: 5%;
    font-weight: 700;
    text-align: center;
  }

  .col-date {
    width: 12%;
    white-space: nowrap;
  }

  .col-bp {
    width: 12%;
    text-align: center;
  }

  .col-anthro {
    width: 14%;
    font-size: 13px;
  }

  .col-nadi,
  .col-suhu,
  .col-rr {
    width: 10%;
    text-align: center;
  }

  .col-keluhan {
    width: 17%;
  }

  .badge-bp {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    background-color: #0ea5e9;
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
  }

  .anthro-item {
    color: #475569;
    font-size: 13px;
    line-height: 1.5;
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
      gap: 12px;
      margin-bottom: 20px;
      padding: 16px;
    }

    .header-right {
      width: 100%;
      justify-content: flex-start;
    }

    .patient-row {
      grid-template-columns: 1fr;
      gap: 12px;
      margin-bottom: 12px;
    }

    .patient-card-footer {
      align-items: stretch;
      flex-direction: column;
    }

    .patient-card-footer .btn-download {
      width: 100%;
    }

    .table-container {
      overflow-x: auto;
    }

    .data-table {
      min-width: 760px;
    }

    .data-table th,
    .data-table td {
      padding: 8px;
      font-size: 12px;
    }
  }

  @media (max-width: 480px) {
    .page-title {
      font-size: 19px;
    }

    .table-header-info {
      align-items: flex-start;
      flex-direction: column;
    }

    .record-count {
      text-align: left;
    }

    .badge-bp {
      padding: 2px 6px;
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





