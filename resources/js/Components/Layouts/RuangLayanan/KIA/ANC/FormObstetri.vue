<template>
  <div>
    <h5 class="fw-bold mb-3">Pemeriksaan Obstetri</h5>
    <form @submit.prevent="saveForm">
      <div class="row g-3">
        <div class="col-md-2">
          <label class="form-label fw-semibold">Gravida</label>
          <input
            v-model="form.gravida"
            type="text"
            class="form-control"
            placeholder="Gravida 0 s/d 20"
          />
        </div>

        <div class="col-md-2">
          <label class="form-label fw-semibold">Partus</label>
          <input
            v-model="form.partus"
            type="text"
            class="form-control"
            placeholder="Partus 0 s/d 20"
          />
        </div>

        <div class="col-md-2">
          <label class="form-label fw-semibold">Abortus</label>
          <input
            v-model="form.abortus"
            type="text"
            class="form-control"
            placeholder="Abortus 0 s/d 20"
          />
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">IMT</label>
          <input v-model="form.imt" type="text" class="form-control" />
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Status</label>
          <input v-model="form.status_imt" type="text" class="form-control" />
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Tanggal TPHT</label>
          <input v-model="form.tanggal_tpht" type="date" class="form-control" />
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Berat Badan Sebelum Hamil</label>
          <input v-model="form.bb_sebelum" type="number" class="form-control" />
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Tinggi Badan</label>
          <input v-model="form.tbadan" type="number" class="form-control" />
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Target Kenaikan Berat Badan</label>
          <div class="input-group">
            <input v-model="form.bbTarget" type="number" class="form-control" />
            <span class="input-group-text">Kg</span>
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Jarak Hamil</label>
          <input v-model="form.jarak_hamil" type="number" class="form-control" />
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Status Imunisasi Tetanus</label>
          <select v-model="form.statusImunisasi" class="form-select">
            <option value="0">Belum Pernah</option>
            <option value="1">Sudah Pernah</option>
          </select>
        </div>
        <div v-if="form.statusImunisasi == '1'" class="col-12 mt-3">
          <div class="row g-2">
            <div
              class="col-12 d-flex justify-content-center"
              v-for="(item, index) in form.imunisasi"
              :key="index"
            >
              <div class="col-md-3 me-2">
                <label class="form-label fw-semibold">Tanggal Imunisasi {{ index + 1 }}</label>
                <input
                  type="date"
                  v-model="item.tanggal"
                  class="form-control"
                  placeholder="dd/mm/yyyy"
                />
              </div>

              <div class="col-md-3 me-2">
                <label class="form-label fw-semibold">No Batch</label>
                <input
                  type="text"
                  v-model="item.noBatch"
                  class="form-control"
                  placeholder="No Batch"
                />
              </div>

              <div class="col-md-3 me-2">
                <label class="form-label fw-semibold">Nama Vaksin</label>
                <input
                  type="text"
                  v-model="item.namaVaksin"
                  class="form-control"
                  placeholder="Nama Vaksin"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-3 text-center">
        <button class="btn btn-success w-50 shadow-sm px-3">
          <i class="bi bi-save me-1"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
  import { ref, watch, reactive } from 'vue';
  import { route } from 'ziggy-js';
  import { router } from '@inertiajs/vue3';

  const props = defineProps({
    DataPasien: Array,
  });

  const form = ref({
    pasienId: props.DataPasien[0].ID,
    gravida: '',
    partus: '',
    abortus: '',
    tbadan: '',
    tanggal_tpht: '',
    bb_sebelum: '',
    bbTarget: '',
    imt: '',
    status_imt: '',
    jarak_hamil: '',
    statusImunisasi: '0',
    imunisasi: [
      { tanggal: '', noBatch: '', namaVaksin: '' },
      { tanggal: '', noBatch: '', namaVaksin: '' },
      { tanggal: '', noBatch: '', namaVaksin: '' },
      { tanggal: '', noBatch: '', namaVaksin: '' },
      { tanggal: '', noBatch: '', namaVaksin: '' },
    ],
  });

  const saveForm = () => {
    router.post(route('ruang-layanan-anc.obstetri'), form.value, {
      onSuccess: () => {
        alert('Data Kunjungan berhasil disimpan!');
        form.value = {
          gravida: '',
          partus: '',
          abortus: '',
          tbadan: '',
          tanggal_tpht: '',
          bb_sebelum: '',
          bbTarget: '',
          imt: '',
          status_imt: '',
          jarak_hamil: '',
          statusImunisasi: '',
        };
      },
      onError: (errors) => {
        console.error(errors);
        alert('Gagal menyimpan data.');
      },
    });
  };
</script>
