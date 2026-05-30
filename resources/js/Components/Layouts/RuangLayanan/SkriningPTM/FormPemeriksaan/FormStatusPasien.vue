<template>
  <div class="status-form">
    <section class="status-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-check"></i> Kesimpulan Klinis</h4>
          <p>Keputusan akhir encounter setelah assessment, planning, dan tindakan selesai.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="status-grid">
          <div class="form-field">
            <label class="form-label" for="kategori_risiko_ptm">Kategori Risiko PTM</label>
            <select
              id="kategori_risiko_ptm"
              v-model="form.kategori_risiko_ptm"
              name="kategori_risiko_ptm"
              class="form-select"
            >
              <option value="">Pilih kategori</option>
              <option value="rendah">Rendah</option>
              <option value="sedang">Sedang</option>
              <option value="tinggi">Tinggi</option>
              <option value="sangat_tinggi">Sangat tinggi / perlu evaluasi dokter</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="status_kasus">Status Kasus</label>
            <select
              id="status_kasus"
              v-model="form.status_kasus"
              name="status_kasus"
              class="form-select"
            >
              <option value="skrining_normal">Skrining dalam batas normal</option>
              <option value="suspek">Suspek / perlu konfirmasi</option>
              <option value="terdiagnosis">Sudah terdiagnosis</option>
              <option value="kontrol">Kontrol penyakit kronis</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="keputusan_klinis">Keputusan Klinis</label>
            <select
              id="keputusan_klinis"
              v-model="form.keputusan_klinis"
              name="keputusan_klinis"
              class="form-select"
            >
              <option value="">Pilih keputusan</option>
              <option value="edukasi">Edukasi dan modifikasi gaya hidup</option>
              <option value="kontrol_berkala">Kontrol berkala</option>
              <option value="konfirmasi_dokter">Perlu konfirmasi dokter</option>
              <option value="rujuk">Perlu rujukan</option>
              <option value="observasi">Observasi / pemantauan</option>
            </select>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="catatan_kesimpulan">Catatan Kesimpulan Klinis</label>
            <textarea
              id="catatan_kesimpulan"
              v-model="form.catatan_kesimpulan"
              name="catatan_kesimpulan"
              class="form-control"
              rows="3"
              placeholder="Kesimpulan akhir, instruksi utama, atau alasan keputusan klinis"
            ></textarea>
          </div>
        </div>
      </div>
    </section>

    <section class="status-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-door-open"></i> Status Keluar Pasien</h4>
          <p>Encounter discharge disposition, cara keluar, rujukan, dan jadwal kontrol.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="status-grid">
          <div class="form-field">
            <label class="form-label" for="kondisi_keluar">Kondisi Saat Meninggalkan Fasyankes</label>
            <select
              id="kondisi_keluar"
              v-model="form.kondisi_keluar"
              name="kondisi_keluar"
              class="form-select"
            >
              <option value="">Pilih kondisi</option>
              <option value="stabil">Stabil</option>
              <option value="membaik">Membaik</option>
              <option value="dirujuk">Dirujuk</option>
              <option value="observasi">Perlu observasi</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="cara_keluar">Cara Keluar Fasyankes</label>
            <select
              id="cara_keluar"
              v-model="form.cara_keluar"
              name="cara_keluar"
              class="form-select"
            >
              <option value="">Pilih cara keluar</option>
              <option value="pulang">Pulang sendiri</option>
              <option value="rujuk">Dirujuk</option>
              <option value="diantar">Diantar keluarga</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="jadwal_kontrol">Jadwal Kontrol Berikutnya</label>
            <input
              id="jadwal_kontrol"
              v-model="form.jadwal_kontrol"
              name="jadwal_kontrol"
              type="date"
              class="form-control"
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="rencana_rujuk">Rujukan / Konsultasi</label>
            <select
              id="rencana_rujuk"
              v-model="form.rencana_rujuk"
              name="rencana_rujuk"
              class="form-select"
            >
              <option value="tidak">Tidak</option>
              <option value="internal">Konsultasi internal puskesmas</option>
              <option value="fkrtl">Rujuk FKRTL</option>
              <option value="igd">Rujuk segera / IGD</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="transport">Transportasi Rujukan</label>
            <select
              id="transport"
              v-model="form.transport"
              name="transport"
              class="form-select"
              :disabled="form.rencana_rujuk === 'tidak'"
            >
              <option value="">Tidak berlaku</option>
              <option value="ambulan">Ambulans</option>
              <option value="kendaraan_pribadi">Kendaraan pribadi</option>
              <option value="ojek">Ojek/taksi</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="saran_tindak_lanjut">Saran Tindak Lanjut</label>
            <input
              id="saran_tindak_lanjut"
              :value="saranTindakLanjut"
              name="saran_tindak_lanjut"
              type="text"
              class="form-control readonly-field"
              readonly
            />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
  import { computed, watchEffect } from 'vue';

  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });

  const form = props.formData?.status_pasien || (props.formData.status_pasien = {});
  const assessment = computed(() => props.formData?.assessment || {});

  form.kategori_risiko_ptm = form.kategori_risiko_ptm || '';
  form.status_kasus = form.status_kasus || 'skrining_normal';
  form.keputusan_klinis = form.keputusan_klinis || '';
  form.catatan_kesimpulan = form.catatan_kesimpulan || '';
  form.kondisi_keluar = form.kondisi_keluar || '';
  form.cara_keluar = form.cara_keluar || '';
  form.jadwal_kontrol = form.jadwal_kontrol || '';
  form.rencana_rujuk = form.rencana_rujuk || 'tidak';
  form.transport = form.transport || '';

  const saranTindakLanjut = computed(() => {
    if (form.rencana_rujuk && form.rencana_rujuk !== 'tidak') return labelize(form.rencana_rujuk);
    if (form.keputusan_klinis) return labelize(form.keputusan_klinis);
    if (assessment.value.diabetes_melitus || assessment.value.risiko_kardiovaskular) {
      return 'Konsultasi internal puskesmas';
    }
    if (assessment.value.hipertensi || assessment.value.dislipidemia || assessment.value.risiko_diabetes) {
      return 'Edukasi dan kontrol berkala';
    }
    if (assessment.value.obesitas || assessment.value.perilaku_berisiko) {
      return 'Edukasi perubahan gaya hidup';
    }
    return 'Tidak ada tindak lanjut khusus';
  });

  watchEffect(() => {
    if (form.rencana_rujuk === 'tidak') form.transport = '';
    form.saran_tindak_lanjut = saranTindakLanjut.value;
  });

  function labelize(value) {
    if (!value) return '-';
    return String(value)
      .replace(/_/g, ' ')
      .replace(/\b\w/g, (char) => char.toUpperCase());
  }
</script>

<style scoped>
  .status-form {
    display: grid;
    gap: 18px;
  }

  .status-panel {
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

  .status-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    align-items: start;
  }

  .form-field {
    min-width: 0;
    padding: 14px;
    border: 1px solid #edf2f7;
    border-radius: 8px;
    background: #ffffff;
  }

  .note-field {
    grid-column: 1 / -1;
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

  .form-select:disabled {
    background: #f8fafc;
    color: #94a3b8;
  }

  textarea.form-control {
    min-height: 92px;
    resize: vertical;
  }

  .readonly-field {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #16a36f;
    box-shadow: 0 0 0 0.2rem rgba(22, 163, 111, 0.14);
  }

  @media (max-width: 992px) {
    .status-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 576px) {
    .status-grid {
      grid-template-columns: 1fr;
    }

    .panel-body {
      padding: 16px;
    }
  }
</style>
