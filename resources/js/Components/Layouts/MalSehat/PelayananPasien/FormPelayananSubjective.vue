<template>
  <div class="card m-2 rounded-4 shadow-sm overflow-hidden">
    <!-- Header -->
    <div
      class="card-header d-flex justify-content-between align-items-center p-3"
      style="background: linear-gradient(135deg, #4682B4, #5A9BD5);"
    >
      <h5 class="text-white fw-bold m-0">
        <i class="fas fa-stethoscope me-2"></i> Form Anamnesa
      </h5>
    </div>

    <!-- Body -->
    <div class="card-body p-4">
      <form>
        <div class="row g-4">
          <!-- Kolom Kiri -->
          <div class="col-12 col-md-6">
            <div class="mb-3">
              <label class="form-label fw-bold">Tgl Anamnesa</label>
              <input type="date" class="form-control shadow-sm rounded-3" />
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Keluhan Utama/Keperluan</label>
              <textarea class="form-control shadow-sm rounded-3" rows="2"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Keluhan Tambahan</label>
              <textarea class="form-control shadow-sm rounded-3" rows="2"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Riwayat Penyakit Sekarang</label>
              <textarea class="form-control shadow-sm rounded-3" rows="2"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Riwayat Penyakit Dahulu</label>
              <textarea class="form-control shadow-sm rounded-3" rows="2"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Riwayat Penyakit Keluarga</label>
              <textarea class="form-control shadow-sm rounded-3" rows="2"></textarea>
            </div>
          </div>

          <!-- Kolom Kanan -->
          <div class="col-12 col-md-6">
            <div class="mb-3">
              <label class="form-label fw-bold">Alergi Makanan</label>
              <select class="form-select shadow-sm rounded-3 bg-primary bg-opacity-10" v-model="selectedMakanan">
                <option value="">Tidak Ada Alergi</option>
                <option
                  v-for="a in alergiMakanan"
                  :key="a.id"
                  :value="a.namaAlergiBpjs"
                >
                  {{ a.namaAlergiBpjs }}
                </option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Alergi Obat</label>
              <select class="form-select shadow-sm rounded-3 bg-primary bg-opacity-10" v-model="selectedObat">
                <option value="">Tidak Ada Alergi</option>
                <option
                  v-for="a in alergiObat"
                  :key="a.id"
                  :value="a.namaAlergiBpjs"
                >
                  {{ a.namaAlergiBpjs }}
                </option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Keterangan Alergi</label>
              <textarea
                class="form-control shadow-sm rounded-3 bg-primary bg-opacity-10"
                rows="2"
              ></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Tindakan/Terapi</label>
              <textarea class="form-control shadow-sm rounded-3" rows="2"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Obat yang Sering Digunakan</label>
              <textarea class="form-control shadow-sm rounded-3" rows="2"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Obat yang Sering Dikonsumsi</label>
              <textarea class="form-control shadow-sm rounded-3" rows="2"></textarea>
            </div>

            <div class="d-flex justify-content-end">
              <button
                type="submit"
                class="btn btn-success d-flex align-items-center gap-2 px-4 fw-semibold text-white shadow-sm rounded-pill"
                style="background: linear-gradient(135deg, #4682B4, #5A9BD5); border: none;"
              >
                <i class="fas fa-save"></i> SIMPAN
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
  alergiMakanan: Array,
  alergiObat: Array,
});

const selectedMakanan = ref("");
const selectedObat = ref("");

function goBack() {
  window.history.back();
}
</script>
