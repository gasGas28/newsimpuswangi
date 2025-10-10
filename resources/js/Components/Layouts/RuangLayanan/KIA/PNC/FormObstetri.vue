<template>
  <div class="card shadow-sm border-0 p-4 rounded-4">
    <h5 class="fw-bold text-primary mb-3">Pemeriksaan Obstetri</h5>

    <form @submit.prevent="saveForm" class="w-100">
      <!-- Gravida -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Gravida</label>
        <input type="number" class="form-control w-100" />
      </div>

      <!-- Partus -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Partus</label>
        <input type="number" class="form-control w-100" />
      </div>

      <!-- Abortus -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Abortus</label>
        <input type="number" class="form-control w-100" />
      </div>

      <!-- Tanggal TPHT -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Tanggal TPHT</label>
        <input type="date" class="form-control w-100" />
      </div>

      <!-- Berat Badan Sebelum Hamil -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Berat Badan Sebelum Hamil (Kg)</label>
        <input type="number" class="form-control w-100" />
      </div>

      <!-- Tinggi Badan -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Tinggi Badan (cm)</label>
        <input type="number" class="form-control w-100" />
      </div>

      <!-- Target Kenaikan Berat Badan -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Target Kenaikan Berat Badan</label>
        <div class="input-group w-100">
          <input type="number" class="form-control" />
          <span class="input-group-text">Kg</span>
        </div>
      </div>

      <!-- Status Imunisasi -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Status Imunisasi Tetanus</label>
        <select class="form-select w-100">
          <option value="Belum Pernah">Belum Pernah</option>
          <option value="Sudah">Sudah</option>
        </select>
      </div>

      <!-- Tombol Simpan -->
      <div class="text-end mt-3">
        <button class="btn btn-success">
          <i class="bi bi-save me-1"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
const saveForm = () => {
  console.log('Data Obstetri berhasil disimpan!')
  alert('Data Obstetri berhasil disimpan!')
}
</script>

<style scoped>
.card {
  width: 100%;
  background-color: #ffffff;
}
</style>
