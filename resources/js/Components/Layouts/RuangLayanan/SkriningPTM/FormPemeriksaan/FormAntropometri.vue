<template>
  <div class="p-3">
    <h5 class="mb-4 fw-semibold text-success">Antropometri dan Tekanan Darah</h5>

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

      <div class="col-md-12">
        <label class="form-label fw-semibold">Interpretasi Hipertensi</label>
        <input
          v-model="form.interpretasi_hipertensi"
          type="text"
          class="form-control bg-light"
          readonly
        />
      </div>
    </div>
  </div>
</template>

<script setup>
  import { reactive, watch } from 'vue';

  defineProps({
    DataPasien: Array,
  });

  const form = reactive({
    tinggi_badan: '',
    berat_badan: '',
    imt: '',
    interpretasi_imt: '',
    lingkar_pinggang: '',
    sistolik: '',
    diastolik: '',
    interpretasi_hipertensi: '',
  });

  const getInterpretasiImt = (imt) => {
    if (imt < 18.5) return 'Berat Badan Kurang';
    if (imt <= 22.9) return 'Berat Badan Normal';
    if (imt <= 24.9) return 'Kelebihan Berat Badan';
    if (imt <= 29.9) return 'Obes I';
    return 'Obes II';
  };

  const getInterpretasiHipertensi = (sistolik, diastolik) => {
    if (sistolik >= 180 || diastolik >= 120) return 'Krisis Hipertensi';
    if (sistolik >= 160 || diastolik >= 100) return 'Hipertensi Derajat 2';
    if (sistolik >= 140 || diastolik >= 90) return 'Hipertensi Derajat 1';
    if (sistolik >= 130 || diastolik >= 85) return 'Normal Tinggi';
    if (sistolik >= 120 || diastolik >= 80) return 'Normal';
    return 'Optimal';
  };

  watch(
    () => [form.tinggi_badan, form.berat_badan],
    ([tinggiBadan, beratBadan]) => {
      const tinggi = Number(tinggiBadan);
      const berat = Number(beratBadan);

      if (!tinggi || !berat) {
        form.imt = '';
        form.interpretasi_imt = '';
        return;
      }

      const tinggiMeter = tinggi / 100;
      const imt = berat / (tinggiMeter * tinggiMeter);

      form.imt = imt.toFixed(2);
      form.interpretasi_imt = getInterpretasiImt(imt);
    }
  );

  watch(
    () => [form.sistolik, form.diastolik],
    ([sistolikValue, diastolikValue]) => {
      const sistolik = Number(sistolikValue);
      const diastolik = Number(diastolikValue);

      if (!sistolik || !diastolik) {
        form.interpretasi_hipertensi = '';
        return;
      }

      form.interpretasi_hipertensi = getInterpretasiHipertensi(sistolik, diastolik);
    }
  );
</script>
