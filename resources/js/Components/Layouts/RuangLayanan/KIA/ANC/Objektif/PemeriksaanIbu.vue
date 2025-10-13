<template>
  <h5 class="fw-semibold text-danger">Pemeriksaan Ibu</h5>
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
      <form @submit.prevent="saveForm">
        <div class="row g-3">
          <!-- Kolom Kiri -->
          <div class="col-md-6">
            <div class="mb-2">
              <label class="form-label">Berat Badan</label>
              <div class="input-group">
                <input type="number" class="form-control" placeholder="Kg" />
                <span class="input-group-text">Kg</span>
              </div>
            </div>

            <div class="mb-2">
              <label class="form-label">Lingkar Lengan</label>
              <div class="input-group">
                <input type="number" class="form-control" placeholder="Cm" />
                <span class="input-group-text">Cm</span>
              </div>
            </div>

            <div class="mb-2">
              <label class="form-label">Status Lingkar Lengan Atas</label>
              <select class="form-select">
                <option selected>Kurang Energi Kronis (KEK) / LiLA &lt; 23 cm</option>
                <option>Risiko Kurang Energi Kronis (KEK) / LiLA - &lt; 23 cm</option>
                <option>Normal / LiLA >= 23.5 cm</option>
              </select>
            </div>

            <div class="mb-2">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" rows="4" placeholder="Enter ..."></textarea>
            </div>

            <div class="mb-2">
              <label class="form-label">Tinggi Fundus</label>
              <div class="input-group">
                <input type="number" class="form-control" placeholder="Cm" />
                <span class="input-group-text">Cm</span>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-2">
                <label class="form-label">Sistolik</label>
                <div class="input-group">
                  <input type="number" class="form-control" placeholder="mmHg" />
                  <span class="input-group-text">mm[Hg]</span>
                </div>
              </div>
              <div class="col-md-6 mb-2">
                <label class="form-label">Diastolik</label>
                <div class="input-group">
                  <input type="number" class="form-control" placeholder="mmHg" />
                  <span class="input-group-text">mm[Hg]</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Kolom Kanan -->
          <div class="col-md-6">
            <div class="mb-2">
              <label class="form-label">Nadi</label>
              <div class="input-group">
                <input type="number" class="form-control" placeholder="BPM" />
                <span class="input-group-text">/min</span>
              </div>
            </div>

            <div class="mb-2">
              <label class="form-label">Suhu</label>
              <div class="input-group">
                <input type="number" class="form-control" placeholder="°C" />
                <span class="input-group-text">°C</span>
              </div>
            </div>

            <div class="mb-2">
              <label class="form-label">Pernapasan</label>
              <div class="input-group">
                <input type="number" class="form-control" placeholder="BPM" />
                <span class="input-group-text">/min</span>
              </div>
            </div>

            <div class="mb-2">
              <label class="form-label">Golongan Darah</label>
              <select class="form-select">
                <option>O</option>
                <option>A</option>
                <option>B</option>
                <option>AB</option>
              </select>
            </div>

            <div class="mb-2">
              <label class="form-label">Rhesus</label>
              <select class="form-select">
                <option>+</option>
                <option>-</option>
              </select>
            </div>

            <div class="form-check mt-3">
              <input
                class="form-check-input"
                type="checkbox"
                id="makananTambahan"
                v-model="showMTForm"
              />
              <label class="form-check-label" for="makananTambahan">
                Pemberian Makanan Tambahan (MT)
              </label>
            </div>

            <div v-if="showMTForm" class="mt-3">
              <div class="mb-2">
                <label class="fw-semibold">1.1 Jika LiLA < 23.5 Apakah mendapatkan MT?</label>
                <select v-model="form.mt_status" class="form-select">
                  <option value="ya">Ya</option>
                  <option value="tidak">Tidak</option>
                </select>
              </div>
              <div class="mb-2">
                <label class="fw-semibold">Deskripsi</label>
                <textarea v-model="form.deskripsi" class="form-control" rows="2"></textarea>
              </div>

              <div class="mb-2">
                <label class="fw-semibold">Jenis MT</label>
                <select v-model="form.jenis_mt" class="form-select">
                  <option value="MT Lokal">MT Lokal</option>
                  <option value="MT Pabrikan">MT Pabrikan</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="mt-4 text-end">
          <button type="submit" class="btn btn-success w-50 fw-semibold">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
  import { ref } from 'vue';

  const showMTForm = ref(false);
  const form = ref({
    mt_status: '',
    deskripsi: '',
    jenis_mt: '',
  });
  const saveForm = () => {
    alert('Data berhasil disimpan!');
  };
</script>

<style scoped>
  .form-label {
    font-weight: 600;
    color: #333;
  }

  .form-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 16px;
  }

  .input-group-text {
    background-color: #f8f9fa;
    font-weight: 500;
  }

  .btn-success {
    background-color: #28a745;
    border: none;
    transition: 0.2s;
  }

  .btn-success:hover {
    background-color: #218838;
  }
</style>
