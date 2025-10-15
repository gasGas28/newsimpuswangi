<template>
  <h5 class="fw-semibold text-danger">Data Kunjungan</h5>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form @submit.prevent="saveForm">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Tanggal Kunjungan</label>
            <input type="date" class="form-control" />
          </div>

          <div class="col-md-6">
            <label class="form-label">Usia Kehamilan</label>
            <input type="text" class="form-control" placeholder="Masukkan usia kehamilan" />
          </div>

          <div class="col-md-6">
            <label class="form-label">Trimester</label>
            <select class="form-select">
              <option value="">Pilih Trimester</option>
              <option value="1">Trimester 1</option>
              <option value="2">Trimester 2</option>
              <option value="3">Trimester 3</option>
            </select>
          </div>
        </div>

        <div class="mt-4 text-end">
          <button type="submit" class="btn btn-success shadow-sm rounded-pill px-4">
            <i class="bi bi-save me-1"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
  const saveForm = () => {
    alert('Data Kunjungan berhasil disimpan!');
  };
</script>

<style scoped>
  .card {
    border-radius: 12px;
  }
  .form-label {
    font-weight: 600;
    color: #333;
  }
</style>
