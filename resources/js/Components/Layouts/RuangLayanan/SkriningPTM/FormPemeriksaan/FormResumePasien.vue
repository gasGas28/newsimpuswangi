<template>
  <div class="resume-form">
    <section class="resume-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-check"></i> Ringkasan Skrining PTM</h4>
          <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
        </div>
        <span class="status-pill complete">10/12 lengkap</span>
      </div>

      <div class="panel-body">
        <div class="summary-grid">
          <div class="summary-item">
            <div class="summary-label">Nama Pasien</div>
            <div class="summary-value">Siti Rahayu</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">NIK</div>
            <div class="summary-value">3509012345670001</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Tanggal Skrining</div>
            <div class="summary-value">2026-06-10</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Fasyankes</div>
            <div class="summary-value">Puskesmas Sumbersari</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Pemeriksa</div>
            <div class="summary-value">dr. Andi Kurniawan</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Status IMT</div>
            <div class="summary-value">Obesitas I</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Tekanan Darah</div>
            <div class="summary-value">140/90 mmHg</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Gula Darah Sewaktu</div>
            <div class="summary-value">198 mg/dL</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Kolesterol Total</div>
            <div class="summary-value">215 mg/dL</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Kategori Risiko PTM</div>
            <div class="summary-value">Risiko Tinggi</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Status Kasus</div>
            <div class="summary-value">Kasus Baru</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Rencana Rujuk</div>
            <div class="summary-value">-</div>
          </div>
        </div>
      </div>
    </section>

    <section class="resume-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-activity"></i> Ringkasan Temuan</h4>
          <p>Temuan otomatis dari subjektif, objektif, dan status pasien sebagai bahan resume.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="finding-list">
          <div class="finding-item">
            <i class="bi bi-exclamation-triangle-fill warning"></i>
            <div>
              <strong>Hipertensi Grade 1</strong>
              <span>Tekanan darah 140/90 mmHg, melampaui batas normal.</span>
            </div>
          </div>
          <div class="finding-item">
            <i class="bi bi-exclamation-triangle-fill warning"></i>
            <div>
              <strong>Gula Darah Tinggi</strong>
              <span>GDS 198 mg/dL, perlu evaluasi lanjutan untuk diabetes.</span>
            </div>
          </div>
          <div class="finding-item">
            <i class="bi bi-info-circle-fill info"></i>
            <div>
              <strong>Dislipidemia</strong>
              <span>Kolesterol total 215 mg/dL, di atas batas normal (&lt;200 mg/dL).</span>
            </div>
          </div>
          <div class="finding-item">
            <i class="bi bi-check-circle-fill success"></i>
            <div>
              <strong>Tidak Merokok</strong>
              <span>Pasien tidak memiliki riwayat merokok aktif.</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="resume-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-send-check"></i> Composition FHIR</h4>
          <p>Composition.type - LOINC 74213-0 untuk dokumen laporan skrining PTM.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="composition-grid">
          <div class="form-field">
            <label class="form-label" for="doc_num">Nomor Dokumen</label>
            <input
              id="doc_num"
              class="form-control bg-light"
              type="text"
              value="PTM-M3F2KJH9A"
              readonly
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="comp_date">Tanggal Komposisi</label>
            <input id="comp_date" class="form-control" type="date" value="2026-06-10" />
          </div>

          <div class="form-field">
            <label class="form-label" for="comp_status">Status Dokumen</label>
            <select id="comp_status" class="form-select">
              <option value="preliminary" selected>Preliminary</option>
              <option value="final">Final</option>
              <option value="amended">Amended</option>
            </select>
          </div>

          <div class="form-field span-full">
            <label class="form-label" for="comp_title">Judul Composition</label>
            <input
              id="comp_title"
              class="form-control"
              type="text"
              value="Ringkasan Skrining PTM"
              placeholder="Ringkasan Skrining PTM"
            />
          </div>

          <div class="form-field span-full">
            <label class="form-label" for="catatan_penutup">Catatan Penutup</label>
            <textarea
              id="catatan_penutup"
              class="form-control"
              rows="4"
              placeholder="Catatan tambahan dokter/perawat sebelum pengiriman"
            >Pasien disarankan kontrol ulang 2 minggu, edukasi gizi, dan pemeriksaan HbA1c lanjutan.</textarea>
          </div>
        </div>

        <div class="fhir-preview">
          <div class="preview-header">
            <span>Resource yang dirangkum</span>
            <code>Encounter, QuestionnaireResponse, Observation, Condition, Procedure, CarePlan, Composition</code>
          </div>
          <pre>{{ compositionPreview }}</pre>
        </div>
      </div>
    </section>

    <div class="form-actions">
      <div class="send-status">
        10/12 data utama lengkap sebelum pengiriman.
      </div>
      <div class="action-buttons">
        <button type="button" class="finish-button">
          <i class="bi bi-check2-circle"></i>
          <span>Akhiri Pelayanan</span>
        </button>
        <button type="button" class="send-button">
          <i class="bi bi-send-check"></i>
          <span>Kirim Satu Sehat</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  DataPasien: Object,
  formData: Object,
  tindakan: Array,
  DataTindakan: Array,
});

const compositionPreview = `{
  "resourceType": "Composition",
  "status": "preliminary",
  "type": {
    "coding": [
      {
        "system": "http://loinc.org",
        "code": "74213-0",
        "display": "Skrining PTM"
      }
    ]
  },
  "title": "Ringkasan Skrining PTM",
  "date": "2026-06-10",
  "subject": {
    "display": "Siti Rahayu",
    "identifier": "3509012345670001"
  },
  "section": [
    {
      "title": "Ringkasan Skrining PTM",
      "text": {
        "status": "generated",
        "div": "10/12 data utama lengkap"
      }
    },
    {
      "title": "Ringkasan Temuan",
      "text": {
        "status": "generated",
        "div": "Hipertensi Grade 1; Gula Darah Tinggi; Dislipidemia; Tidak Merokok"
      }
    }
  ]
}`;
</script>

<style scoped src="./FormPemeriksaan.css"></style>