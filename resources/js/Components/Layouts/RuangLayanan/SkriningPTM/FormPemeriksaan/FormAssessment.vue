<template>
  <div class="assessment-form">
    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-check"></i> Masalah Hasil Skrining</h4>
          <p>
            Konfirmasi masalah atau faktor risiko PTM dari data subjektif, objektif, dan penunjang.
          </p>
        </div>
        <span class="status-pill complete">3 saran</span>
      </div>

      <div class="panel-body">
        <div class="diagnosis-grid">
          <label class="diagnosis-option" for="obesitas">
            <input id="obesitas" class="form-check-input" type="checkbox" />
            <span>
              <strong>Obesitas / berat badan lebih</strong>
              <small>ICD-10 - E66</small>
            </span>
          </label>

          <label class="diagnosis-option checked" for="hipertensi">
            <input id="hipertensi" class="form-check-input" type="checkbox" checked />
            <span>
              <strong>Hipertensi / tekanan darah tinggi</strong>
              <small>ICD-10 - I10</small>
              <small class="suggestion-hint">Disarankan dari hasil skrining</small>
            </span>
          </label>

          <label class="diagnosis-option" for="risiko_diabetes">
            <input id="risiko_diabetes" class="form-check-input" type="checkbox" />
            <span>
              <strong>Risiko prediabetes</strong>
              <small>ICD-10 - R73.0</small>
            </span>
          </label>

          <label class="diagnosis-option checked" for="diabetes_melitus">
            <input id="diabetes_melitus" class="form-check-input" type="checkbox" checked />
            <span>
              <strong>Diabetes melitus</strong>
              <small>ICD-10 - E11</small>
              <small class="suggestion-hint">Disarankan dari hasil skrining</small>
            </span>
          </label>

          <label class="diagnosis-option checked" for="dislipidemia">
            <input id="dislipidemia" class="form-check-input" type="checkbox" checked />
            <span>
              <strong>Dislipidemia</strong>
              <small>ICD-10 - E78.5</small>
              <small class="suggestion-hint">Disarankan dari hasil skrining</small>
            </span>
          </label>

          <label class="diagnosis-option" for="risiko_kardiovaskular">
            <input id="risiko_kardiovaskular" class="form-check-input" type="checkbox" />
            <span>
              <strong>Risiko penyakit kardiovaskular</strong>
              <small>SNOMED CT - 395112001</small>
            </span>
          </label>

          <label class="diagnosis-option" for="perilaku_berisiko">
            <input id="perilaku_berisiko" class="form-check-input" type="checkbox" />
            <span>
              <strong>Perilaku berisiko PTM</strong>
              <small>SNOMED CT - 160573003</small>
            </span>
          </label>
        </div>
      </div>
    </section>

    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-pulse"></i> Diagnosis Klinis</h4>
          <p>Keputusan diagnosis oleh petugas berdasarkan masalah yang sudah dikonfirmasi.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="diagnosis-form-grid">
          <div class="form-field diagnosis-main-field">
            <label class="form-label" for="diagnosis_utama">Diagnosis Utama</label>
            <select id="diagnosis_utama" name="diagnosis_utama" class="form-select">
              <option value="">Gunakan saran otomatis: E11 - Diabetes melitus tipe 2</option>
              <option value="Z13.6 - Skrining penyakit kardiovaskular">Z13.6 - Skrining penyakit kardiovaskular</option>
              <option value="E66 - Obesitas / berat badan lebih">E66 - Obesitas / berat badan lebih</option>
              <option value="I10 - Hipertensi esensial">I10 - Hipertensi esensial</option>
              <option value="R73.0 - Risiko prediabetes">R73.0 - Risiko prediabetes</option>
              <option value="E11 - Diabetes melitus tipe 2" selected>E11 - Diabetes melitus tipe 2</option>
              <option value="E78.5 - Dislipidemia">E78.5 - Dislipidemia</option>
            </select>
            <span class="field-hint">Saran sistem hanya membantu pengisian. Diagnosis final tetap ditentukan petugas.</span>
          </div>

          <div class="form-field">
            <label class="form-label" for="status_klinis">Status Klinis</label>
            <select id="status_klinis" name="status_klinis" class="form-select">
              <option value="active" selected>Aktif</option>
              <option value="recurrence">Berulang</option>
              <option value="remission">Remisi</option>
              <option value="resolved">Selesai / teratasi</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="verification_status">Status Verifikasi Diagnosis</label>
            <select id="verification_status" name="verification_status" class="form-select status-provisional">
              <option value="unconfirmed">Belum dikonfirmasi (Unconfirmed)</option>
              <option value="provisional" selected>Diagnosis sementara (Provisional)</option>
              <option value="confirmed">Sudah dikonfirmasi (Confirmed)</option>
              <option value="refuted">Dibatalkan / Disangkal (Refuted)</option>
            </select>
            <span class="field-hint verification-hint status-provisional">
              <i class="bi bi-clock"></i>
              Diagnosis sementara, menunggu data penunjang tambahan.
            </span>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="catatan_diagnosis">Catatan Diagnosis / Komorbid</label>
            <textarea
              id="catatan_diagnosis"
              name="catatan_diagnosis"
              class="form-control"
              rows="3"
              placeholder="Diagnosis tambahan, komorbid, atau pertimbangan singkat bila ada"
            >Pasien dengan DM tipe 2 disertai hipertensi dan dislipidemia. Perlu kontrol gula darah dan tekanan darah rutin.</textarea>
          </div>
        </div>
      </div>
    </section>

    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-journal-check"></i> Kesimpulan Assessment</h4>
          <p>Kesimpulan akhir dari masalah hasil skrining dan diagnosis klinis.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="summary-grid">
          <div class="summary-item">
            <span>Diagnosis Final / Saran</span>
            <strong>E11 - Diabetes melitus tipe 2</strong>
          </div>

          <div class="summary-item">
            <span>Masalah Hasil Skrining</span>
            <strong>Hipertensi / tekanan darah tinggi, Diabetes melitus, Dislipidemia</strong>
          </div>

          <div class="summary-item">
            <span>Kategori Risiko</span>
            <strong>Risiko Tinggi</strong>
          </div>

          <div class="summary-item">
            <span>Status Verifikasi</span>
            <strong class="verification-badge status-provisional">
              <i class="bi bi-clock"></i>
              Diagnosis sementara
            </strong>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="ringkasan_klinis">Ringkasan Klinis</label>
            <textarea
              id="ringkasan_klinis"
              name="ringkasan_klinis"
              class="form-control"
              rows="3"
              placeholder="Kesimpulan hasil skrining, diagnosis kerja, dan pertimbangan klinis"
            >Pasien Siti Rahayu dengan hasil skrining menunjukkan DM tipe 2, hipertensi grade 1, dan dislipidemia. Risiko kardiovaskular tinggi. Disarankan penatalaksanaan komprehensif dan kontrol berkala.</textarea>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="catatan_assessment">Catatan Tambahan</label>
            <textarea
              id="catatan_assessment"
              name="catatan_assessment"
              class="form-control"
              rows="3"
              placeholder="Catatan tambahan bila ada"
            ></textarea>
          </div>
        </div>
      </div>
    </section>

    <div class="form-actions">
      <div class="save-status">
        Simpan setelah diagnosis dan kesimpulan assessment selesai diisi.
      </div>
      <button type="button" class="save-button">
        <i class="bi bi-save"></i>
        <span>Simpan Assessment</span>
      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  DataPasien: Object,
  formData: Object,
  tindakan: Array,
});
</script>

<style scoped src="./FormPemeriksaan.css"></style>

<style scoped>
  .verification-hint {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    margin-top: 0.35rem;
  }

  .verification-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.875rem;
  }

  .status-unconfirmed { color: #6c757d; }
  .status-provisional  { color: #fd7e14; }
  .status-confirmed    { color: #198754; }
  .status-refuted      { color: #dc3545; }

  .modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
  }

  .modal-content {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 10px 20px rgba(0, 0, 0, 0.15);
    max-width: 450px;
    width: 90%;
    animation: modalSlideIn 0.3s ease-out;
  }

  @keyframes modalSlideIn {
    from { opacity: 0; transform: translateY(-20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
    border-bottom: 1px solid #e9ecef;
  }

  .modal-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .success-icon { color: #28a745; font-size: 1.5rem; }

  .btn-close {
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    font-size: 1.5rem;
    padding: 0;
    line-height: 1;
  }
  .btn-close:hover { color: #2c3e50; }

  .modal-body { padding: 1.5rem; }
  .modal-body > p { margin: 0 0 1rem 0; color: #495057; font-size: 0.95rem; }

  .save-summary {
    background: #f8f9fa;
    border-left: 4px solid #28a745;
    padding: 1rem;
    border-radius: 4px;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    font-size: 0.9rem;
  }
  .summary-row span { color: #6c757d; }
  .summary-row strong { color: #2c3e50; text-align: right; flex: 1; margin-left: 1rem; }

  .modal-footer {
    display: flex;
    justify-content: flex-end;
    padding: 1rem 1.5rem;
    border-top: 1px solid #e9ecef;
    gap: 0.5rem;
  }

  .btn {
    padding: 0.5rem 1.5rem;
    border: none;
    border-radius: 4px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
  }

  .btn-primary { background: #0d6efd; color: white; }
  .btn-primary:hover { background: #0b5ed7; box-shadow: 0 2px 4px rgba(13, 110, 253, 0.25); }
</style>