<template>
<AppLayout>
  <div class="row">
    <!-- Dropdown Kategori -->
    <div class="col-md-6">
      <div class="mb-3">
        <label for="selectKategori" class="form-label">Pilih Kategori</label>
        <select 
          id="selectKategori"
          class="form-select" 
          v-model="selectedKategori"
        >
          <option value="">-- Pilih Kategori --</option>
          <option
            v-for="kategori in tempat_kunjungan"
            :key="kategori.id_kategori"
            :value="kategori.id_kategori"
          >
            {{ kategori.kategori }}
          </option>
        </select>
      </div>
    </div>
    
    <!-- Dropdown Unit -->
    <div class="col-md-6">
      <div class="mb-3">
        <label for="selectUnit" class="form-label">Pilih Unit</label>
        <select
          id="selectUnit"
          class="form-select"
          v-model="selectedUnit"
          :disabled="!selectedKategori"
        >
          <option value="">-- Pilih Unit --</option>
          <option
            v-for="unit in filteredUnits"
            :key="unit.id_detail"
            :value="unit.id_detail"
          >
            {{ unit.nama_unit }}
          </option>
        </select>
      </div>
    </div>
  </div>
  </AppLayout>
</template>

<script>
import { ref, computed, watch } from 'vue'
import AppLayout from '@/Components/Layouts/AppLayouts.vue';

export default {
  props: {
    tempat_kunjungan: Array,
    detail_tempat_kunjungan: Array,
  },

  setup(props) {
    const selectedKategori = ref('')
    const selectedUnit = ref('')

    // Filter unit berdasarkan kategori yang dipilih
    const filteredUnits = computed(() => {
      if (!selectedKategori.value) return []
      
      return props.detail_tempat_kunjungan.filter(
        unit => unit.id_kategori == selectedKategori.value
      )
    })

    // Reset selectedUnit ketika kategori berubah
    watch(selectedKategori, () => {
      selectedUnit.value = ''
    })

    return {
      selectedKategori,
      selectedUnit,
      filteredUnits
    }
  }
}
</script>

<style scoped>
.form-select:disabled {
  background-color: #f8f9fa;
  opacity: 0.65;
}

.form-label {
  font-weight: 600;
  color: #495057;
}
</style>