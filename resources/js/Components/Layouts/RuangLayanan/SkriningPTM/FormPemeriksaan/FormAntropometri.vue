<template>
  <div class="p-3">
    <h5 class="mb-4 fw-semibold text-success">Skrining PTM - Antropometri dan Data Risiko</h5>

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Tinggi Badan (cm)</label>
        <div class="input-group">
          <input
            v-model.number="form.tinggi_badan"
            type="number"
            min="0"
            step="0.1"
            class="form-control"
            placeholder="Masukkan tinggi badan"
          />
          <span class="input-group-text">cm</span>
        </div>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Berat Badan (kg)</label>
        <div class="input-group">
          <input
            v-model.number="form.berat_badan"
            type="number"
            min="0"
            step="0.1"
            class="form-control"
            placeholder="Masukkan berat badan"
          />
          <span class="input-group-text">kg</span>
        </div>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">IMT</label>
        <input v-model="form.imt" type="text" class="form-control bg-light" readonly />
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Interpretasi IMT</label>
        <input v-model="form.interpretasi_imt" type="text" class="form-control bg-light" readonly />
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Lingkar Pinggang (cm)</label>
        <div class="input-group">
          <input
            v-model.number="form.lingkar_pinggang"
            type="number"
            min="0"
            step="0.1"
            class="form-control"
            placeholder="Masukkan lingkar pinggang"
          />
          <span class="input-group-text">cm</span>
        </div>
      </div>

      <div class="col-md-3">
        <label class="form-label fw-semibold">Sistolik (mmHg)</label>
        <input
          v-model.number="form.sistolik"
          type="number"
          min="0"
          class="form-control"
          placeholder="Sistolik"
        />
      </div>

      <div class="col-md-3">
        <label class="form-label fw-semibold">Diastolik (mmHg)</label>
        <input
          v-model.number="form.diastolik"
          type="number"
          min="0"
          class="form-control"
          placeholder="Diastolik"
        />
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Gula Darah Sewaktu (mg/dL)</label>
        <input
          v-model.number="form.gula_darah"
          type="number"
          min="0"
          class="form-control"
          placeholder="Masukkan gula darah"
        />
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Kolesterol Total (mg/dL)</label>
        <input
          v-model.number="form.kolesterol_total"
          type="number"
          min="0"
          class="form-control"
          placeholder="Masukkan kolesterol total"
        />
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Kategori Risiko PTM</label>
        <input :value="form.kategori_risiko" type="text" class="form-control bg-light" readonly />
      </div>

      <div class="col-md-12">
        <label class="form-label fw-semibold">Interpretasi Hipertensi</label>
        <input
          v-model="form.interpretasi_hipertensi"
          type="text"
          class="form-control bg-light"
          readonly
        />
      </div>

      <div class="col-md-4" v-for="(option, index) in faktorRisiko" :key="index">
        <label class="form-label fw-semibold">{{ option.label }}</label>
        <select v-model="form[option.key]" class="form-select">
          <option value="tidak">Tidak</option>
          <option value="ya">Ya</option>
        </select>
      </div>

      <div class="col-md-6">
        <h6 class="mb-2 fw-semibold text-success">Riwayat Penyakit Pribadi</h6>
        <div class="row g-2">
          <div class="col-6" v-for="(item, index) in riwayatPenyakit" :key="index">
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                :id="item.key"
                v-model="form[item.key]"
              />
              <label class="form-check-label" :for="item.key">{{ item.label }}</label>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <h6 class="mb-2 fw-semibold text-success">Riwayat Penyakit Keluarga</h6>
        <div class="row g-2">
          <div class="col-6" v-for="(item, index) in riwayatKeluarga" :key="index">
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                :id="item.key"
                v-model="form[item.key]"
              />
              <label class="form-check-label" :for="item.key">{{ item.label }}</label>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <label class="form-label fw-semibold">Rekomendasi Tindak Lanjut</label>
        <textarea
          :value="form.rekomendasi"
          class="form-control bg-light"
          rows="3"
          readonly
        ></textarea>
      </div>

      <div class="col-md-12">
        <label class="form-label fw-semibold">Catatan Tambahan</label>
        <textarea
          v-model="form.catatan"
          class="form-control"
          rows="3"
          placeholder="Tuliskan catatan atau anjuran tambahan"
        ></textarea>
      </div>
    </div>
  </div>
</template>

<script setup>
  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });

  const form = props.formData || {};

  const faktorRisiko = [
    { key: 'merokok', label: 'Merokok' },
    { key: 'alkohol', label: 'Konsumsi Alkohol' },
    { key: 'aktivitas_fisik', label: 'Aktivitas Fisik Tidak Cukup' },
    { key: 'konsumsi_gula', label: 'Konsumsi Gula Berlebih' },
    { key: 'konsumsi_garam', label: 'Konsumsi Garam Berlebih' },
    { key: 'stress', label: 'Stres / Tekanan Emosional' },
  ];

  const riwayatPenyakit = [
    { key: 'riwayat_hipertensi', label: 'Hipertensi' },
    { key: 'riwayat_diabetes', label: 'Diabetes Melitus' },
    { key: 'riwayat_dislipidemia', label: 'Dislipidemia' },
    { key: 'riwayat_kardiovaskular', label: 'Penyakit Kardiovaskular' },
    { key: 'riwayat_stroke', label: 'Stroke' },
    { key: 'riwayat_kanker', label: 'Kanker' },
  ];

  const riwayatKeluarga = [
    { key: 'riwayat_keluarga_hipertensi', label: 'Hipertensi' },
    { key: 'riwayat_keluarga_diabetes', label: 'Diabetes Melitus' },
    { key: 'riwayat_keluarga_kardiovaskular', label: 'Penyakit Kardiovaskular' },
    { key: 'riwayat_keluarga_stroke', label: 'Stroke' },
    { key: 'riwayat_keluarga_kanker', label: 'Kanker' },
  ];
</script>
