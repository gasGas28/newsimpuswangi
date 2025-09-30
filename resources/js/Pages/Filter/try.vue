<template>
  <div class="col-md-4">
    <!-- Form Puskesmas -->
    <div class="mb-2">
      <label class="form-label fw-semibold">Puskesmas</label>
      <select class="form-select" v-model="formData.puskesmas">
        <option disabled value="">-- Pilih Puskesmas --</option>
        <option>Wongsorejo</option>
        <option>Blimbingsari</option>
        <option>Kalipuro</option>
      </select>
    </div>

    <!-- Form Kecamatan -->
    <div class="mb-2">
      <label class="form-label fw-semibold">Kecamatan</label>
      <input type="text" class="form-control" v-model="formData.kecamatan" />
    </div>

    <!-- Form Nama Dokter -->
    <div class="mb-2">
      <label class="form-label fw-semibold">Nama Dokter</label>
      <input type="text" class="form-control" v-model="formData.dokter" />
    </div>

    <!-- Tombol -->
    <button class="btn btn-primary w-100" @click="tampilkanCard">Tampilkan</button>
  </div>

  <!-- Container Card -->
  <div class="mt-3">
    <div v-for="(item, index) in cards" :key="index" class="card mb-2">
      <div class="card-body">
        <h5 class="card-title">{{ item.puskesmas }}</h5>
        <p class="card-text">
          Kecamatan: {{ item.kecamatan }} <br />
          Dokter: {{ item.dokter }}
        </p>
        <button class="btn btn-sm btn-danger" @click="hapusCard(index)">Hapus</button>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        formData: {
          puskesmas: '',
          kecamatan: '',
          dokter: '',
        },
        cards: [],
      };
    },
    methods: {
      tampilkanCard() {
        if (this.formData.puskesmas && this.formData.kecamatan && this.formData.dokter) {
          this.cards.push({ ...this.formData }); // copy object
          this.formData = { puskesmas: '', kecamatan: '', dokter: '' }; // reset form
        }
      },
      hapusCard(index) {
        this.cards.splice(index, 1);
      },
    },
  };
</script>
