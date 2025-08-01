<template>
  <AppLayouts title="Pencarian Pasien">
    <div class="container py-4">
      <div class="card">
        <div class="card-header bg-info text-white fw-bold">Form Pencarian Pasien</div>
        <div class="card-body">
          <form @submit.prevent="searchPasien">
            <div class="row">
              <!-- Kolom Kiri -->
              <div class="col-md-6">
                <div class="mb-2" v-for="field in kolom1" :key="field.key">
                  <div class="row">
                    <div class="col-4">
                      <div class="form-check">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          :id="field.key"
                          v-model="form[field.key].enabled"
                        />
                        <label class="form-check-label fw-bold" :for="field.key">
                          {{ field.label }}
                        </label>
                      </div>
                    </div>
                    <div class="col-8">
                      <input
                        v-if="field.type === 'text'"
                        type="text"
                        class="form-control form-control-sm"
                        v-model="form[field.key].value"
                        :disabled="!form[field.key].enabled"
                      />
                    </div>
                  </div>
                </div>

                <div class="mb-2" v-for="field in kolom2" :key="field.key">
                  <div class="row">
                    <div class="col-4">
                      <div class="form-check">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          :id="field.key"
                          v-model="form[field.key].enabled"
                        />
                        <label class="form-check-label fw-bold" :for="field.key">
                          {{ field.label }}
                        </label>
                      </div>
                    </div>
                    <div class="col-8">
                      <template v-if="field.type === 'text'">
                        <input
                          type="text"
                          class="form-control form-control-sm"
                          v-model="form[field.key].value"
                          :disabled="!form[field.key].enabled"
                        />
                      </template>
                      <template v-else-if="field.type === 'select'">
                        <select
                          class="form-select form-select-sm"
                          v-model="form[field.key].value"
                          :disabled="!form[field.key].enabled"
                        >
                          <option value="">- Pilih -</option>
                          <option value="Laki-laki">Laki-laki</option>
                          <option value="Perempuan">Perempuan</option>
                        </select>
                      </template>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Kolom Kanan -->
              <div class="col-md-6">
                <div class="mb-2" v-for="field in kolom3" :key="field.key">
                  <div class="row">
                    <div class="col-4">
                      <div class="form-check">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          :id="field.key"
                          v-model="form[field.key].enabled"
                        />
                        <label class="form-check-label fw-bold" :for="field.key">
                          {{ field.label }}
                        </label>
                      </div>
                    </div>
                    <div class="col-8">
                      <template v-if="field.type === 'text'">
                        <input
                          type="text"
                          class="form-control form-control-sm"
                          v-model="form[field.key].value"
                          :disabled="!form[field.key].enabled"
                        />
                      </template>
                      <template v-else-if="field.type === 'select'">
                        <select
                          class="form-select form-select-sm"
                          v-model="form[field.key].value"
                          :disabled="!form[field.key].enabled"
                        >
                          <option value="">- Pilih -</option>
                          <option value="Kecamatan A">Kecamatan A</option>
                          <option value="Kecamatan B">Kecamatan B</option>
                        </select>
                      </template>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <hr />
            <div class="d-flex justify-content-between">
              <button class="btn btn-primary btn-sm" type="submit">🔍 CARI PASIEN</button>
              <Link :href="route('loket.pasien')" class="btn btn-success btn-sm">
                ➕ TAMBAH PASIEN BARU
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
  import AppLayouts from '@/Components/Layouts/AppLayouts.vue';
  import { Link } from '@inertiajs/vue3';
  import { reactive } from 'vue';

  const form = reactive({
    nama: { enabled: false, value: '' },
    nik: { enabled: false, value: '' },
    no_mr: { enabled: false, value: '' },
    no_bpjs: { enabled: false, value: '' },
    jenis_kelamin: { enabled: false, value: '' },
    kecamatan: { enabled: false, value: '' },
    alamat: { enabled: false, value: '' },
  });

  const kolom1 = [
    { key: 'nama', label: 'Nama', type: 'text' },
    { key: 'nik', label: 'NIK', type: 'text' },
    { key: 'no_mr', label: 'NO MR', type: 'text' },
  ];
  const kolom2 = [
    { key: 'no_bpjs', label: 'NO BPJS', type: 'text' },
    { key: 'jenis_kelamin', label: 'Jenis Kelamin', type: 'select' },
  ];
  const kolom3 = [
    { key: 'kecamatan', label: 'Kecamatan', type: 'select' },
    { key: 'alamat', label: 'ALAMAT', type: 'text' },
  ];

  function searchPasien() {
    const filtered = Object.fromEntries(Object.entries(form).filter(([_, f]) => f.enabled));
    console.log('Data yang dikirim:', filtered);
  }
</script>
