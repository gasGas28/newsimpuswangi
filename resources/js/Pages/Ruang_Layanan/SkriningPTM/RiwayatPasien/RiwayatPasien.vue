<template>
  <div class="page-container">
    <!-- Header dengan Buttons -->
    <div class="page-header">
      <div class="header-left">
        <h3 class="page-title">Riwayat Pemeriksaan PTM Pasien</h3>
        <p class="page-subtitle">Data Riwayat Kesehatan (Medical Record)</p>
      </div>
      <div class="header-right">
        <button
          @click="downloadPDF"
          class="btn-download btn-pdf"
          :disabled="!riwayat.length || isDownloading"
        >
          <i class="bi bi-file-pdf"></i>
          <span>PDF</span>
        </button>
        <button
          @click="downloadLembarPTM"
          class="btn-download btn-primary-custom"
          :disabled="!riwayat.length || isDownloading"
        >
          <i class="bi bi-file-earmark"></i>
          <span>Lembar PTM</span>
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

  const downloadPDF = async () => {
    if (riwayat.length === 0 || isDownloading.value) return;

    isDownloading.value = true;
    const idSkrining = getQueryParam('idSkrining');

    try {
      const response = await fetch(
        route('ruang-layanan.riwayat-ptm-pdf') + '?idSkrining=' + idSkrining,
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

  const downloadLembarPTM = async () => {
    if (riwayat.length === 0 || isDownloading.value) return;

    isDownloading.value = true;
    const idSkrining = getQueryParam('idSkrining');

    try {
      const response = await fetch(
        route('ruang-layanan.lembar-ptm') + '?idSkrining=' + idSkrining,
        {
          method: 'GET',
          headers: {
            Accept: 'application/pdf',
          },
        }
      );

      if (!response.ok) {
        throw new Error('Gagal mengunduh Lembar PTM');
      }

      const blob = await response.blob();

      const contentDisposition = response.headers.get('Content-Disposition');
      let filename = 'Lembar_PTM.pdf';
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
      console.error('Download Lembar PTM error:', error);
      alert('Gagal mengunduh Lembar PTM. Silakan coba lagi.');
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
    background: white;
    padding: 24px;
    font-family:
      -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  }

  /* Header */
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e0e0e0;
  }

  .header-left {
    flex: 1;
  }

  .page-title {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 4px;
  }

  .page-subtitle {
    font-size: 13px;
    color: #999;
    margin: 0;
  }

  .header-right {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: flex-end;
  }

  /* Buttons */
  .btn-download {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
  }

  .btn-pdf {
    background-color: #dc3545;
    color: white;
  }

  .btn-pdf:hover:not(:disabled) {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
  }

  .btn-primary-custom {
    background-color: #0d6efd;
    color: white;
  }

  .btn-primary-custom:hover:not(:disabled) {
    background-color: #0b5ed7;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
  }

  .btn-download:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .btn-download i {
    font-size: 16px;
  }

  /* Patient Card */
  .patient-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .patient-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 16px;
  }

  .patient-row:last-child {
    margin-bottom: 0;
  }

  .patient-col {
    display: flex;
    flex-direction: column;
  }

  .patient-col-full {
    grid-column: 1 / -1;
  }

  .patient-field {
    display: flex;
    flex-direction: column;
  }

  .patient-field label {
    font-size: 12px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
  }

  .patient-field p {
    font-size: 15px;
    color: #212529;
    word-break: break-word;
  }

  /* Table Container */
  .table-container {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
  }

  .table-header-info {
    background: #f8f9fa;
    padding: 16px 20px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .table-header-info h4 {
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
  }

  .record-count {
    font-size: 12px;
    color: #6c757d;
  }

  /* Table */
  .data-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: auto;
  }

  .data-table thead {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
  }

  .data-table thead tr {
    border: none;
  }

  .data-table th {
    padding: 14px 12px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
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
    background-color: #f0f7ff;
  }

  .data-table tbody tr.empty-row:hover {
    background-color: #fafbfc;
  }

  .data-table td {
    padding: 12px;
    font-size: 14px;
    color: #212529;
    border: none;
    vertical-align: middle;
  }

  .data-table tbody tr.empty-row td {
    text-align: center;
    padding: 32px 12px;
    color: #6c757d;
    font-style: italic;
  }

  /* Column Widths */
  .col-no {
    width: 5%;
    text-align: center;
    font-weight: 600;
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

  .col-nadi {
    width: 10%;
    text-align: center;
  }

  .col-suhu {
    width: 10%;
    text-align: center;
  }

  .col-rr {
    width: 10%;
    text-align: center;
  }

  .col-keluhan {
    width: 17%;
  }

  /* Badge */
  .badge-bp {
    display: inline-block;
    background-color: #0dcaf0;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
  }

  /* Anthropometry */
  .anthro-item {
    font-size: 13px;
    color: #666;
    line-height: 1.5;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .page-container {
      padding: 16px;
    }

    .page-header {
      flex-direction: column;
      gap: 12px;
      margin-bottom: 20px;
      padding-bottom: 16px;
    }

    .header-right {
      width: 100%;
      justify-content: flex-start;
    }

    .btn-download span {
      display: none;
    }

    .patient-row {
      grid-template-columns: 1fr;
      gap: 12px;
      margin-bottom: 12px;
    }

    .data-table th,
    .data-table td {
      padding: 8px;
      font-size: 12px;
    }

    .col-anthro {
      width: 16%;
    }

    .col-keluhan {
      width: 15%;
    }
  }

  @media (max-width: 480px) {
    .data-table th {
      font-size: 11px;
    }

    .data-table td {
      font-size: 11px;
      padding: 6px;
    }

    .col-no,
    .col-bp,
    .col-nadi,
    .col-suhu,
    .col-rr {
      width: auto;
    }

    .badge-bp {
      font-size: 11px;
      padding: 2px 6px;
    }
  }

  /* Print */
  @media print {
    .page-header {
      display: none;
    }

    .page-container {
      padding: 0;
    }

    .patient-card {
      box-shadow: none;
      page-break-inside: avoid;
    }

    .table-container {
      box-shadow: none;
    }

    .data-table thead {
      background: white;
      color: black;
    }

    .data-table th,
    .data-table td {
      border: 1px solid #000;
    }

    .data-table tbody tr:nth-child(odd) {
      background-color: white;
    }

    .data-table tbody tr:hover {
      background-color: white;
    }
  }
</style>
