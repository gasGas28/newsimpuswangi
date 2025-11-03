<template>
  <div class="card shadow-sm border-0 p-4 form-card">
    <h5 class="mb-3 text-danger">Riwayat Kematian Bayi Lahir Mati</h5>
    <form @submit.prevent="submitForm">

    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  nama_ibu: '',
  usia_kehamilan: '',
  tanggal_kematian: ''
})

const emit = defineEmits(['close'])

const submitForm = () => {
  console.log('Data Bayi Lahir Mati:', form.value)
  alert('Data berhasil disimpan!')
  form.value = { nama_ibu: '', usia_kehamilan: '', tanggal_kematian: '' }
  emit('close')
}
</script>

<style scoped>
.form-card {
  width: 50%;
  margin: 0;
}
</style>
