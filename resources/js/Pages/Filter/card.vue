<template>
  <AppLayout>
    <div class="container py-4">
      <div class="card shadow-sm">
        <div class="card-header bg-white text-start">
          <h5 class="fw-semibold text-dark">Filter Laporan</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <!-- Deskripsi -->
            <div class="col-md-6">
              <h6 class="fw-semibold mb-0">Kunjungan</h6>
              <p class="text-muted">Filter berdasarkan informasi kunjungan pasien</p>
            </div>

            <!-- Form -->
            <div class="col-md-6">
              <form>
                <div class="mb-3">
                  <label for="selectTempat" class="form-label fw-semibold">Tempat Kunjungan</label>
                  <select id="selectTempat" class="form-select">
                    <option>-- Pilih Tempat --</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="unit1" class="form-label fw-semibold">Unit</label>
                  <select id="unit1" class="form-select">
                    <option>-- Pilih Unit --</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="unit2" class="form-label fw-semibold">Unit</label>
                  <select id="unit2" class="form-select">
                    <option>-- Pilih Unit --</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="unit3" class="form-label fw-semibold">Unit</label>
                  <select id="unit3" class="form-select">
                    <option>-- Pilih Unit --</option>
                  </select>
                </div>
              </form>
            </div>
            <hr />
            <div class="col-md-6">
              <h6 class="fw-semibold mb-0">Kunjungan</h6>
              <p class="text-muted">Filter berdasarkan informasi kunjungan pasien</p>
            </div>

            <!-- Form -->
            <div class="col-md-6">
              <form>
                <div class="mb-3">
                  <label for="selectTempat" class="form-label fw-semibold">Tempat Kunjungan</label>
                  <select id="selectTempat" class="form-select">
                    <option>-- Pilih Tempat --</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="unit1" class="form-label fw-semibold">Unit</label>
                  <select id="unit1" class="form-select">
                    <option>-- Pilih Unit --</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="unit2" class="form-label fw-semibold">Unit</label>
                  <select id="unit2" class="form-select">
                    <option>-- Pilih Unit --</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="unit3" class="form-label fw-semibold">Unit</label>
                  <select id="unit3" class="form-select">
                    <option>-- Pilih Unit --</option>
                  </select>
                </div>
              </form>
            </div>
            <hr />
            <div class="col-md-6">
              <h6 class="fw-semibold mb-0">Kunjungan</h6>
              <p class="text-muted">Filter berdasarkan informasi kunjungan pasien</p>
            </div>

            <!-- Form -->
            <div class="col-md-6">
              <form>
                <div class="mb-3">
                  <label for="selectTempat" class="form-label fw-semibold">Tempat Kunjungan</label>
                  <select id="selectTempat" class="form-select">
                    <option>-- Pilih Tempat --</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="unit1" class="form-label fw-semibold">Unit</label>
                  <select id="unit1" class="form-select">
                    <option>-- Pilih Unit --</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="unit2" class="form-label fw-semibold">Unit</label>
                  <select id="unit2" class="form-select">
                    <option>-- Pilih Unit --</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="unit3" class="form-label fw-semibold">Unit</label>
                  <select id="unit3" class="form-select">
                    <option>-- Pilih Unit --</option>
                  </select>
                </div>
              </form>
            </div>
            <hr />
          </div>
          <div class="d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-primary text-white fw-semibold">Cancel</button>
            <button type="button" class="btn btn-primary text-white fw-semibold px-4">Save</button>
            <button type="button" class="btn btn-primary text-white fw-semibold px-4">
              Submit
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
</script>
<style scoped>
  .card-header {
    background-color: #b0e0e6;
  }
  .btn-data {
    background-color: #e6b0ce;
  }
  .btn-html {
    background-color: #eebd95;
  }
  .btn-excel {
    background-color: #98ec82;
  }
</style>
