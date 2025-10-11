<template>
  <div>
    <h5 class="fw-bold text-primary mb-3">Pemeriksaan Obstetri</h5>
    <form @submit.prevent="saveForm">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Gravida</label>
          <select class="form-select" v-model="gravida">
            <option v-for="n in 21" :key="n - 1" :value="n - 1">{{ n - 1 }}</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Partus</label>
          <input type="number" class="form-control" />
        </div>

        <div class="col-md-4">
          <label class="form-label">Abortus</label>
          <input type="number" class="form-control" />
        </div>

        <div class="col-md-6">
          <label class="form-label">Tanggal TPHT</label>
          <input type="date" class="form-control" />
        </div>

        <div class="col-md-6">
          <label class="form-label">Berat Badan Sebelum Hamil</label>
          <input type="number" class="form-control" />
        </div>

        <div class="col-md-6">
          <label class="form-label">Tinggi Badan</label>
          <input type="number" class="form-control" />
        </div>

        <div class="col-md-6">
          <label class="form-label">Target Kenaikan Berat Badan</label>
          <div class="input-group">
            <input type="number" class="form-control" />
            <span class="input-group-text">Kg</span>
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Status Imunisasi Tetanus</label>
          <select class="form-select">
            <option value="Belum Pernah">Belum Pernah</option>
            <option value="Sudah">Sudah</option>
          </select>
        </div>
      </div>

      <div class="mt-3 text-end">
        <button class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
      </div>
    </form>
  </div>
</template>

<script setup>
  import { ref } from 'vue';
  const gravida = ref(0);


  const saveForm = () => {
    console.log('Data Obstetri:', form.value);
    alert('Data Obstetri berhasil disimpan!');
  };
</script>
