<template>
    <div class="container">
      <!-- Judul -->
      <div class="text-decoration-underline fw-bold text-danger mb-4">
        <p>Anamnesa</p>
      </div>
      <form>
        <div class="row g-4">
          <!-- PASANGAN INPUT KIRI - KANAN -->
          <div class="col-12 col-md-6">
            <div class="row mb-3 ">
              <label class="col-sm-4 col-form-label fw-bold">Tgl Anamnesa</label>
              <div class="col-sm-8">
                <input type="date" class="form-control" />
              </div>
            </div>

            <div class="row mb-3 align-items-start">
              <label class="col-sm-4 col-form-label fw-bold">Keluhan Utama/Keperluan</label>
              <div class="col-sm-8">
                <textarea class="form-control" rows="2"></textarea>
              </div>
            </div>

            <div class="row mb-3 align-items-start">
              <label class="col-sm-4 col-form-label fw-bold">Keluhan Tambahan</label>
              <div class="col-sm-8">
                <textarea class="form-control" rows="2"></textarea>
              </div>
            </div>

            <div class="row mb-3 align-items-start">
              <label class="col-sm-4 col-form-label fw-bold">Riwayat Penyakit Sekarang</label>
              <div class="col-sm-8">
                <textarea class="form-control" rows="2"></textarea>
              </div>
            </div>

            <div class="row mb-3 align-items-start">
              <label class="col-sm-4 col-form-label fw-bold ">Riwayat Penyakit Dahulu</label>
              <div class="col-sm-8">
                <textarea class="form-control" rows="2"></textarea>
              </div>
            </div>

            <div class="row mb-3 align-items-start">
              <label class="col-sm-4 col-form-label fw-bold">Riwayat Penyakit Keluarga</label>
              <div class="col-sm-8">
                <textarea class="form-control" rows="2"></textarea>
              </div>
            </div>
          </div>

          <!-- KOLOM KANAN -->
          <div class="col-12 col-md-6">
            <div class="row mb-3 align-items-center">
              <label class="col-sm-4 col-form-label fw-bold ">Alergi Makanan</label>
              <div class="col-sm-8">
                <select class="form-select bg-warning">
                  <option>Tidak Ada Alergi</option>
                  <option>Alergi 1</option>
                  <option>Alergi 2</option>
                </select>
              </div>
            </div>

            <div class="row mb-3 align-items-center">
              <label class="col-sm-4 col-form-label fw-bold">Alergi Obat</label>
              <div class="col-sm-8">
                <select class="form-select bg-warning">
                  <option>Tidak Ada Alergi</option>
                </select>
              </div>
            </div>

            <div class="row mb-3 align-items-start">
              <label class="col-sm-4 col-form-label fw-bold ">Keterangan Alergi</label>
              <div class="col-sm-8">
                <textarea class="form-control bg-warning" rows="2" ></textarea>
              </div>
            </div>

            <div class="row mb-3 align-items-start">
              <label class="col-sm-4 col-form-label fw-bold ">Tindakan/Terapi</label>
              <div class="col-sm-8">
                <textarea class="form-control" rows="2"></textarea>
              </div>
            </div>

            <div class="row mb-3 align-items-start">
              <label class="col-sm-4 col-form-label fw-bold ">Obat yang Sering Digunakan</label>
              <div class="col-sm-8">
                <textarea class="form-control" rows="2"></textarea>
              </div>
            </div>

            <div class="row mb-3 align-items-start">
              <label class="col-sm-4 col-form-label fw-bold ">Obat yang Sering Dikonsumsi</label>
              <div class="col-sm-8">
                <textarea class="form-control" rows="2"></textarea>
              </div>
            </div>
            <div class="d-flex justify-content-end">
          <button class="btn btn-success btn">
            <i class="bi bi-save"></i> SIMPAN
          </button>
        </div>
          </div>

        </div>

      </form>
      </div>
</template>