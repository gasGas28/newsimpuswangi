<template>
  <AppLayouts>
    <div class="container py-4">
      <div class="card border-success">
        <div class="card-header bg-info text-white fw-bold">Data Pasien</div>
        <div class="card-body">
          <form>
            <div class="row">
              <!-- Kolom Kiri -->
              <div class="col-md-6">
                <div
                  class="mb-2"
                  v-for="(field, index) in [{ label: 'NIK' }, { label: 'Noka BPJS' }]"
                  :key="index"
                >
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">{{ field.label }}</label>
                    </div>
                    <div class="col-8">
                      <div class="input-group">
                        <input type="text" class="form-control form-control-sm" />
                        <button class="btn btn-info btn-sm text-white">CEK..!</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div
                  class="mb-2"
                  v-for="field in [
                    'kdProvider',
                    'Nama Lengkap',
                    'Jenis Pekerjaan',
                    'Agama',
                    'Hub. Keluarga',
                  ]"
                  :key="field"
                >
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">{{ field }}</label>
                    </div>
                    <div class="col-8">
                      <input type="text" class="form-control form-control-sm" />
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Tempat, Tgl Lahir</label>
                    </div>
                    <div class="col-8 d-flex gap-1">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Tempat"
                      />
                      <select class="form-select form-select-sm">
                        <option>24</option>
                      </select>
                      <select class="form-select form-select-sm">
                        <option>Juli</option>
                      </select>
                      <select class="form-select form-select-sm">
                        <option>2024</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Jenis Kelamin</label>
                    </div>
                    <div class="col-8">
                      <select class="form-select form-select-sm">
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Kolom Kanan -->
              <div class="col-md-6">
                <div
                  class="mb-2"
                  v-for="(field, index) in ['IHS Pasien', 'No KK', 'Nama KK']"
                  :key="index"
                >
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">{{ field }}</label>
                    </div>
                    <div class="col-8">
                      <input
                        :style="field === 'Nama KK' ? 'background-color: yellow' : ''"
                        type="text"
                        class="form-control form-control-sm"
                      />
                    </div>
                  </div>
                </div>

                <div
                  class="mb-2"
                  v-for="(label, index) in [
                    'Provinsi',
                    'Kabupaten',
                    'Kecamatan *',
                    'Desa/Kelurahan *',
                  ]"
                  :key="index"
                >
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">{{ label }}</label>
                    </div>
                    <div class="col-8">
                      <select class="form-select form-select-sm">
                        <option>{{ label }}</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Alamat</label>
                    </div>
                    <div class="col-8">
                      <input type="text" class="form-control form-control-sm" />
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">RT/RW</label>
                    </div>
                    <div class="col-8 d-flex gap-2">
                      <input type="text" class="form-control form-control-sm" placeholder="RT" />
                      <input type="text" class="form-control form-control-sm" placeholder="RW" />
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">HP</label>
                    </div>
                    <div class="col-8">
                      <input type="text" class="form-control form-control-sm" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center mt-3">
              <button type="submit" class="btn btn-primary">SIMPAN</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
  import AppLayouts from '@/Components/Layouts/AppLayouts.vue';
</script>
