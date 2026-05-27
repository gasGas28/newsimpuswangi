<template>
  <div class="genetik-form">
    <section class="genetik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-shield-plus"></i> Kanker</h4>
          <p>Procedure dan Observation untuk skrining serviks, payudara, paru, dan kolorektal.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="section-title">
          <h5>Serviks &amp; Payudara</h5>
          <span>Khusus perempuan - IVA, SADANIS, HPV-DNA</span>
        </div>

        <div class="cancer-grid">
          <div class="form-field">
            <label class="form-label" for="inspekulo">Pemeriksaan Inspekulo</label>
            <select id="inspekulo" name="inspekulo" class="form-select" v-model="form.inspekulo">
              <option value="">Tidak dilakukan</option>
              <option value="normal">Normal</option>
              <option value="radang">Radang/Servisitis</option>
              <option value="polip">Polip</option>
              <option value="curiga_kanker">Curiga Kanker</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="iva">Hasil IVA</label>
            <select id="iva" name="iva" class="form-select" v-model="form.iva">
              <option value="">Tidak dilakukan</option>
              <option value="negatif">IVA Negatif</option>
              <option value="positif">IVA Positif</option>
              <option value="curiga_kanker">Curiga Kanker Serviks</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="hpv">HPV-DNA</label>
            <select id="hpv" name="hpv" class="form-select" v-model="form.hpv">
              <option value="">Tidak dilakukan</option>
              <option value="negatif">Negatif</option>
              <option value="positif">Positif</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="sadanis">SADANIS (Payudara)</label>
            <select id="sadanis" name="sadanis" class="form-select" v-model="form.sadanis">
              <option value="">Tidak dilakukan</option>
              <option value="normal">Normal</option>
              <option value="curiga">Curiga kelainan</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="usg_py">USG Payudara</label>
            <select id="usg_py" name="usg_py" class="form-select" v-model="form.usg_py">
              <option value="">Tidak dilakukan</option>
              <option value="normal">Normal</option>
              <option value="curiga">Curiga kelainan</option>
            </select>
          </div>
        </div>

        <div v-if="form.iva === 'positif'" class="follow-up-panel">
          <div class="follow-up-alert">
            <i class="bi bi-exclamation-circle"></i>
            <span>IVA positif, pilih tindak lanjut yang sesuai.</span>
          </div>
          <div class="check-grid">
            <label class="check-option">
              <input v-model="form.krioterapi" type="checkbox" />
              <span>Krioterapi</span>
            </label>
            <label class="check-option">
              <input v-model="form.thermal" type="checkbox" />
              <span>Thermal ablation</span>
            </label>
            <label class="check-option">
              <input v-model="form.tca" type="checkbox" />
              <span>Trichloroacetic acid</span>
            </label>
            <label class="check-option">
              <input v-model="form.rujuk_serviks" type="checkbox" />
              <span>Rujuk faskes lanjut</span>
            </label>
          </div>
        </div>

        <div class="section-title with-gap">
          <h5>Kanker Paru</h5>
          <span>QuestionnaireResponse Q0019</span>
        </div>

        <div class="question-grid">
          <div class="form-field">
            <label class="form-label" for="kp1">Pernah didiagnosis/menderita kanker?</label>
            <select id="kp1" name="kp1" class="form-select" v-model="form.kp1">
              <option value="">Pilih jawaban</option>
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp2">Ada keluarga yang didiagnosis kanker?</label>
            <select id="kp2" name="kp2" class="form-select" v-model="form.kp2">
              <option value="">Pilih jawaban</option>
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp3">Riwayat merokok/paparan asap rokok?</label>
            <select id="kp3" name="kp3" class="form-select" v-model="form.kp3">
              <option value="">Pilih jawaban</option>
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp4">Tempat kerja mengandung zat karsinogenik?</label>
            <select id="kp4" name="kp4" class="form-select" v-model="form.kp4">
              <option value="">Pilih jawaban</option>
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp5">Lingkungan dekat pabrik/pertambangan?</label>
            <select id="kp5" name="kp5" class="form-select" v-model="form.kp5">
              <option value="">Pilih jawaban</option>
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp6">Lingkungan dalam rumah tidak sehat?</label>
            <select id="kp6" name="kp6" class="form-select" v-model="form.kp6">
              <option value="">Pilih jawaban</option>
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp7">Pernah didiagnosis penyakit paru kronik?</label>
            <select id="kp7" name="kp7" class="form-select" v-model="form.kp7">
              <option value="">Pilih jawaban</option>
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>

          <div class="form-field result-field">
            <label class="form-label" for="hasil_kp">Hasil Kuesioner Kanker Paru</label>
            <select id="hasil_kp" name="hasil_kp" class="form-select" v-model="form.hasil_kp">
              <option value="">Pilih hasil</option>
              <option value="rendah">Risiko Rendah</option>
              <option value="tinggi">Risiko Tinggi</option>
            </select>
          </div>
        </div>

        <div class="section-title with-gap">
          <h5>Kanker Kolorektal</h5>
          <span>QuestionnaireResponse Q0020 - diutamakan usia &gt;50 tahun</span>
        </div>

        <div class="kolorektal-grid">
          <div class="form-field">
            <label class="form-label" for="kkr1">Riwayat keluarga generasi pertama kanker kolorektal?</label>
            <select id="kkr1" name="kkr1" class="form-select" v-model="form.kkr1">
              <option value="">Pilih jawaban</option>
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kkr2">Apakah peserta merokok?</label>
            <select id="kkr2" name="kkr2" class="form-select" v-model="form.kkr2">
              <option value="">Pilih jawaban</option>
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="hasil_kkr">Hasil Skoring Kolorektal</label>
            <select id="hasil_kkr" name="hasil_kkr" class="form-select" v-model="form.hasil_kkr">
              <option value="">Pilih hasil</option>
              <option value="rendah">Risiko Rendah</option>
              <option value="tinggi">Risiko Tinggi</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="colok_dubur">Pemeriksaan Colok Dubur</label>
            <select id="colok_dubur" name="colok_dubur" class="form-select" v-model="form.colok_dubur">
              <option value="">Tidak dilakukan</option>
              <option value="normal">Normal</option>
              <option value="curiga">Curiga</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="darah_samar">Darah Samar Feses</label>
            <select id="darah_samar" name="darah_samar" class="form-select" v-model="form.darah_samar">
              <option value="">Tidak dilakukan</option>
              <option value="negatif">Negatif</option>
              <option value="positif">Positif</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <section class="genetik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-droplet-half"></i> Thalasemia</h4>
          <p>Observation hematology analyzer untuk deteksi dini thalasemia.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="hematologi-grid">
          <div class="form-field">
            <label class="form-label" for="hb">Hemoglobin (Hb)</label>
            <div class="input-with-addon">
              <input
                id="hb"
                name="hb"
                type="number"
                step="0.1"
                class="form-control"
                v-model.number="form.hb"
                placeholder="0.0"
              />
              <span>g/dL</span>
            </div>
            <small>L: 13-17, P: 12-16</small>
          </div>

          <div class="form-field">
            <label class="form-label" for="mcv">MCV</label>
            <div class="input-with-addon">
              <input
                id="mcv"
                name="mcv"
                type="number"
                step="0.1"
                class="form-control"
                v-model.number="form.mcv"
                placeholder="0.0"
              />
              <span>fL</span>
            </div>
            <small>Normal 80-100 fL</small>
          </div>

          <div class="form-field">
            <label class="form-label" for="mch">MCH</label>
            <div class="input-with-addon">
              <input
                id="mch"
                name="mch"
                type="number"
                step="0.1"
                class="form-control"
                v-model.number="form.mch"
                placeholder="0.0"
              />
              <span>pg</span>
            </div>
            <small>Normal 27-33 pg</small>
          </div>

          <div class="form-field">
            <label class="form-label" for="rbc">Eritrosit / RBC Count</label>
            <div class="input-with-addon">
              <input
                id="rbc"
                name="rbc"
                type="number"
                step="0.01"
                class="form-control"
                v-model.number="form.rbc"
                placeholder="0.00"
              />
              <span>juta/uL</span>
            </div>
            <small>Normal 4.5-5.5</small>
          </div>

          <div class="form-field">
            <label class="form-label" for="rdw">RDW</label>
            <div class="input-with-addon">
              <input
                id="rdw"
                name="rdw"
                type="number"
                step="0.1"
                class="form-control"
                v-model.number="form.rdw"
                placeholder="0.0"
              />
              <span>%</span>
            </div>
            <small>Normal 11.5-14.5%</small>
          </div>

          <div class="form-field">
            <label class="form-label" for="hasil_thalasemia">Kesimpulan Skrining</label>
            <select id="hasil_thalasemia" name="hasil_thalasemia" class="form-select" v-model="form.hasil_thalasemia">
              <option value="">Belum dinilai</option>
              <option value="normal">Normal</option>
              <option value="curiga_carrier">Curiga carrier thalasemia</option>
              <option value="rujuk">Perlu rujukan lanjutan</option>
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

  const form = props.formData?.objektif || {};

  form.inspekulo = form.inspekulo || '';
  form.iva = form.iva || '';
  form.hpv = form.hpv || '';
  form.sadanis = form.sadanis || '';
  form.usg_py = form.usg_py || '';
  form.krioterapi = form.krioterapi || false;
  form.thermal = form.thermal || false;
  form.tca = form.tca || false;
  form.rujuk_serviks = form.rujuk_serviks || false;
  form.kp1 = form.kp1 || '';
  form.kp2 = form.kp2 || '';
  form.kp3 = form.kp3 || '';
  form.kp4 = form.kp4 || '';
  form.kp5 = form.kp5 || '';
  form.kp6 = form.kp6 || '';
  form.kp7 = form.kp7 || '';
  form.hasil_kp = form.hasil_kp || '';
  form.kkr1 = form.kkr1 || '';
  form.kkr2 = form.kkr2 || '';
  form.hasil_kkr = form.hasil_kkr || '';
  form.colok_dubur = form.colok_dubur || '';
  form.darah_samar = form.darah_samar || '';
  form.hb = form.hb ?? '';
  form.mcv = form.mcv ?? '';
  form.mch = form.mch ?? '';
  form.rbc = form.rbc ?? '';
  form.rdw = form.rdw ?? '';
  form.hasil_thalasemia = form.hasil_thalasemia || '';
</script>

<style scoped>
  .genetik-form {
    display: grid;
    gap: 18px;
  }

  .genetik-panel {
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

  .section-title {
    display: flex;
    align-items: end;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eef3f6;
  }

  .section-title.with-gap {
    margin-top: 22px;
  }

  .section-title h5 {
    margin: 0;
    color: #0f3d2e;
    font-size: 0.94rem;
    font-weight: 750;
  }

  .section-title span {
    color: #64748b;
    font-size: 0.78rem;
    font-weight: 650;
    text-align: right;
  }

  .cancer-grid,
  .question-grid,
  .kolorektal-grid,
  .hematologi-grid,
  .check-grid {
    display: grid;
    gap: 16px;
    align-items: start;
  }

  .cancer-grid {
    grid-template-columns: repeat(5, minmax(0, 1fr));
  }

  .question-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }

  .question-grid .form-field {
    grid-column: span 2;
  }

  .question-grid .result-field {
    grid-column: span 2;
  }

  .kolorektal-grid {
    grid-template-columns: repeat(5, minmax(0, 1fr));
  }

  .kolorektal-grid .form-field:nth-child(1),
  .kolorektal-grid .form-field:nth-child(2) {
    grid-column: span 2;
  }

  .hematologi-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }

  .check-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
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

  .form-control:focus,
  .form-select:focus {
    border-color: #16a36f;
    box-shadow: 0 0 0 0.2rem rgba(22, 163, 111, 0.14);
  }

  .form-field small {
    display: block;
    margin-top: 7px;
    color: #64748b;
    font-size: 0.76rem;
    font-weight: 650;
  }

  .follow-up-panel {
    margin-top: 16px;
    padding: 14px;
    border: 1px solid #f4d5a3;
    border-radius: 8px;
    background: #fffaf0;
  }

  .follow-up-alert {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    color: #8a4b0f;
    font-size: 0.86rem;
    font-weight: 750;
  }

  .check-option {
    display: flex;
    align-items: center;
    gap: 8px;
    min-height: 42px;
    padding: 9px 12px;
    border: 1px solid #efd6a6;
    border-radius: 8px;
    background: #ffffff;
    color: #334155;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
  }

  .input-with-addon {
    display: flex;
  }

  .input-with-addon .form-control {
    min-width: 0;
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
    font-size: 0.82rem;
    font-weight: 700;
    white-space: nowrap;
  }

  @media (max-width: 1200px) {
    .cancer-grid,
    .kolorektal-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .kolorektal-grid .form-field:nth-child(n) {
      grid-column: auto;
    }
  }

  @media (max-width: 992px) {
    .cancer-grid,
    .question-grid,
    .kolorektal-grid,
    .hematologi-grid,
    .check-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .question-grid .form-field,
    .question-grid .result-field {
      grid-column: auto;
    }
  }

  @media (max-width: 576px) {
    .cancer-grid,
    .question-grid,
    .kolorektal-grid,
    .hematologi-grid,
    .check-grid {
      grid-template-columns: 1fr;
    }

    .section-title {
      align-items: start;
      flex-direction: column;
    }

    .section-title span {
      text-align: left;
    }

    .panel-body {
      padding: 16px;
    }
  }
</style>
