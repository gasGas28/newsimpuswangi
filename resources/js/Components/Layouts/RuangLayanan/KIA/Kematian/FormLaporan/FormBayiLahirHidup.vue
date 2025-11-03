<template>
  <div class="card shadow-sm border-0 p-4 form-card">
    <h5 class="mb-3 text-primary">Riwayat Kematian Bayi Lahir Hidup</h5>
    <form @submit.prevent="submitForm">

    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  nama_bayi: '',
  tanggal_lahir: '',
  tanggal_kematian: ''
})

const submitForm = () => {
  console.log('Data Bayi Lahir Hidup:', form.value)
  alert('Data berhasil disimpan!')
  form.value = { nama_bayi: '', tanggal_lahir: '', tanggal_kematian: '' }
  // Emit event untuk menutup form
  emit('close')
}

const emit = defineEmits(['close'])
</script>

<style scoped>
.form-card {
  width: 50%;
  margin: 0;
}
</style>
