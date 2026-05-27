<template>
  <div class="risk-form">
    <section class="risk-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-exclamation-triangle"></i> Status Merokok</h4>
          <p>Observation LOINC 72166-2 dan kuesioner Q0013 linkId 1.1-1.5.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="smoking-grid">
          <div class="form-field">
            <label class="form-label">Apakah Pernah Merokok?</label>
            <div class="radio-group">
              <label class="radio-option" :class="{ checked: form.merokok === 'tidak' }">
                <input v-model="form.merokok" type="radio" name="merokok" value="tidak" />
                <span>Tidak</span>
              </label>
              <label class="radio-option" :class="{ checked: form.merokok === 'ya' }">
                <input v-model="form.merokok" type="radio" name="merokok" value="ya" />
                <span>Ya</span>
              </label>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="status_merokok">Status Merokok Saat Ini</label>
            <select id="status_merokok" name="status_merokok" class="form-select" v-model="form.status_merokok">
              <option value="">Pilih status merokok</option>
              <option value="never">Tidak pernah merokok</option>
              <option value="current">Merokok aktif</option>
              <option value="ex">Mantan perokok</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="btg_rokok">Rata-rata Batang Rokok/Hari</label>
            <input
              id="btg_rokok"
              name="btg_rokok"
              type="number"
              min="0"
              class="form-control"
              v-model.number="form.btg_rokok"
              placeholder="0"
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="lama_rokok">Lama Merokok</label>
            <div class="input-with-addon">
              <input
                id="lama_rokok"
                name="lama_rokok"
                type="number"
                min="0"
                class="form-control"
                v-model.number="form.lama_rokok"
                placeholder="0"
              />
              <span>tahun</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="paparan_rokok">Terpapar Asap Rokok Orang Lain (1 Bulan)</label>
            <select id="paparan_rokok" name="paparan_rokok" class="form-select" v-model="form.paparan_rokok">
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <section class="risk-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-activity"></i> Pola Makan &amp; Aktivitas</h4>
          <p>Faktor risiko perilaku Q0013 linkId 1.6-1.11.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="habit-grid">
          <div class="form-field">
            <label class="form-label" for="gula">Konsumsi Gula &gt;4 sdm/hari?</label>
            <select id="gula" name="gula" class="form-select" v-model="form.gula">
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="garam">Konsumsi Garam &gt;1 sdt/hari?</label>
            <select id="garam" name="garam" class="form-select" v-model="form.garam">
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="minyak">Konsumsi Minyak &gt;5 sdm/hari?</label>
            <select id="minyak" name="minyak" class="form-select" v-model="form.minyak">
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="sayur">Kurang Sayur/Buah (&lt;5 porsi/hari)?</label>
            <select id="sayur" name="sayur" class="form-select" v-model="form.sayur">
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="aktivitas">Aktivitas Fisik Kurang?</label>
            <select id="aktivitas" name="aktivitas" class="form-select" v-model="form.aktivitas">
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="alkohol">Konsumsi Alkohol 1 Bulan Terakhir?</label>
            <select id="alkohol" name="alkohol" class="form-select" v-model="form.alkohol">
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <section class="risk-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-journal-medical"></i> Riwayat PTM</h4>
          <p>Condition FHIR, FamilyMemberHistory, dan MedicationStatement.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="history-grid">
          <div class="form-field">
            <label class="form-label" for="r_htn">Riwayat Hipertensi</label>
            <select id="r_htn" name="r_htn" class="form-select" v-model="form.r_htn">
              <option value="tidak">Tidak</option>
              <option value="aktif">Ya, sedang aktif</option>
              <option value="dahulu">Ya, dahulu</option>
              <option value="tidak_tahu">Tidak tahu</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="r_dm">Riwayat Diabetes Melitus</label>
            <select id="r_dm" name="r_dm" class="form-select" v-model="form.r_dm">
              <option value="tidak">Tidak</option>
              <option value="aktif">Ya, sedang aktif</option>
              <option value="dahulu">Ya, dahulu</option>
              <option value="tidak_tahu">Tidak tahu</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="r_stroke">Riwayat Stroke / Penyakit Jantung</label>
            <select id="r_stroke" name="r_stroke" class="form-select" v-model="form.r_stroke">
              <option value="tidak">Tidak</option>
              <option value="aktif">Ya, sedang aktif</option>
              <option value="dahulu">Ya, dahulu</option>
              <option value="tidak_tahu">Tidak tahu</option>
            </select>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="r_pribadi_lain">Riwayat PTM Pribadi Lain</label>
            <textarea
              id="r_pribadi_lain"
              name="r_pribadi_lain"
              class="form-control"
              rows="3"
              v-model="form.r_pribadi_lain"
              placeholder="Dislipidemia, kanker, ginjal kronis, dll."
            ></textarea>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="r_keluarga">Riwayat PTM Keluarga</label>
            <textarea
              id="r_keluarga"
              name="r_keluarga"
              class="form-control"
              rows="3"
              v-model="form.r_keluarga"
              placeholder="Hipertensi, DM, stroke, jantung, kanker, dll."
            ></textarea>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="obat">Obat yang Sedang Dikonsumsi</label>
            <textarea
              id="obat"
              name="obat"
              class="form-control"
              rows="3"
              v-model="form.obat"
              placeholder="Antihipertensi, antidiabetes, statin, dll."
            ></textarea>
          </div>

          <div class="form-field">
            <label class="form-label" for="kesiapan">Kesiapan Berubah</label>
            <select id="kesiapan" name="kesiapan" class="form-select" v-model="form.kesiapan">
              <option value="tidak_siap">Tidak siap</option>
              <option value="ragu">Ragu</option>
              <option value="siap">Siap</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="dukung">Dukungan Keluarga</label>
            <select id="dukung" name="dukung" class="form-select" v-model="form.dukung">
              <option value="kurang">Kurang</option>
              <option value="cukup">Cukup</option>
              <option value="baik">Baik</option>
            </select>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });

  const form = props.formData?.subjektif || {};

  form.merokok = form.merokok || '';
  form.status_merokok = form.status_merokok || '';
  form.btg_rokok = form.btg_rokok ?? 0;
  form.lama_rokok = form.lama_rokok ?? 0;
  form.paparan_rokok = form.paparan_rokok || 'tidak';
  form.gula = form.gula || 'tidak';
  form.garam = form.garam || 'tidak';
  form.minyak = form.minyak || 'tidak';
  form.sayur = form.sayur || 'tidak';
  form.aktivitas = form.aktivitas || 'tidak';
  form.alkohol = form.alkohol || 'tidak';
  form.r_htn = form.r_htn || 'tidak';
  form.r_dm = form.r_dm || 'tidak';
  form.r_stroke = form.r_stroke || 'tidak';
  form.r_pribadi_lain = form.r_pribadi_lain || '';
  form.r_keluarga = form.r_keluarga || '';
  form.obat = form.obat || '';
  form.kesiapan = form.kesiapan || 'tidak_siap';
  form.dukung = form.dukung || 'kurang';
</script>

<style scoped>
  .risk-form {
    display: grid;
    gap: 18px;
  }

  .risk-panel {
    overflow: hidden;
    border: 1px solid #d9e5df;
    border-radius: 8px;
    background: #ffffff;
  }

  .panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    padding: 18px 20px;
    border-bottom: 1px solid #e5edf0;
    background: #f8fafc;
  }

  .panel-header h4 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    color: #0f3d2e;
    font-size: 1rem;
    font-weight: 750;
  }

  .panel-header p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 0.86rem;
  }

  .panel-body {
    padding: 20px;
  }

  .smoking-grid,
  .habit-grid,
  .history-grid {
    display: grid;
    gap: 16px;
    align-items: start;
  }

  .smoking-grid {
    grid-template-columns: repeat(6, minmax(0, 1fr));
  }

  .smoking-grid .form-field:nth-child(1),
  .smoking-grid .form-field:nth-child(2) {
    grid-column: span 3;
  }

  .smoking-grid .form-field:nth-child(3),
  .smoking-grid .form-field:nth-child(4),
  .smoking-grid .form-field:nth-child(5) {
    grid-column: span 2;
  }

  .habit-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }

  .history-grid {
    grid-template-columns: repeat(6, minmax(0, 1fr));
  }

  .history-grid .form-field:nth-child(1),
  .history-grid .form-field:nth-child(2),
  .history-grid .form-field:nth-child(3) {
    grid-column: span 2;
  }

  .history-grid .note-field {
    grid-column: span 2;
  }

  .history-grid .form-field:nth-last-child(2),
  .history-grid .form-field:nth-last-child(1) {
    grid-column: span 3;
  }

  .form-field {
    min-width: 0;
    padding: 14px;
    border: 1px solid #edf2f7;
    border-radius: 8px;
    background: #ffffff;
  }

  .form-label {
    margin-bottom: 6px;
    color: #334155;
    font-size: 0.86rem;
    font-weight: 700;
  }

  .form-control,
  .form-select {
    width: 100%;
    min-height: 42px;
    border: 1px solid #cfd9e3;
    border-radius: 8px;
    color: #0f172a;
  }

  textarea.form-control {
    min-height: 92px;
    resize: vertical;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #16a36f;
    box-shadow: 0 0 0 0.2rem rgba(22, 163, 111, 0.14);
  }

  .radio-group {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
  }

  .radio-option {
    display: flex;
    align-items: center;
    gap: 8px;
    min-height: 42px;
    padding: 9px 12px;
    border: 1px solid #cfd9e3;
    border-radius: 8px;
    color: #334155;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
  }

  .radio-option.checked {
    border-color: #16a36f;
    background: #effaf5;
    color: #0f6b4c;
  }

  .input-with-addon {
    display: flex;
  }

  .input-with-addon .form-control {
    min-width: 0;
  }

  .input-with-addon .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
  }

  .input-with-addon span {
    display: inline-flex;
    align-items: center;
    min-height: 42px;
    padding: 0 12px;
    border: 1px solid #cfd9e3;
    border-left: 0;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
    background: #f8fafc;
    color: #475569;
    font-size: 0.86rem;
    font-weight: 700;
  }

  @media (max-width: 992px) {
    .smoking-grid,
    .habit-grid,
    .history-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .smoking-grid .form-field:nth-child(n),
    .history-grid .form-field:nth-child(n),
    .history-grid .note-field,
    .history-grid .form-field:nth-last-child(2),
    .history-grid .form-field:nth-last-child(1) {
      grid-column: auto;
    }
  }

  @media (max-width: 576px) {
    .smoking-grid,
    .habit-grid,
    .history-grid,
    .radio-group {
      grid-template-columns: 1fr;
    }

    .panel-body {
      padding: 16px;
    }
  }
</style>
