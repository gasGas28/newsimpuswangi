<template>
  <section class="section-title">
    <div class="section-title-left">
      <i class="bi bi-clipboard2-pulse"></i>
      <h2>{{ title }}</h2>
    </div>

    <button
      type="button"
      class="btn-akhiri-pelayanan"
      :disabled="loading"
      @click="$emit('akhiri-pelayanan')"
    >
      <i class="bi" :class="loading ? 'bi-arrow-repeat' : 'bi-check2-circle'"></i>
      <span>{{ loading ? 'Memproses...' : 'Akhiri Pelayanan' }}</span>
    </button>
  </section>
</template>

<script setup>
const props = defineProps({
  title: String,
  loading: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['akhiri-pelayanan']);
</script>

<style scoped>
.section-title {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  margin-top: 16px;
  margin-bottom: 12px;
  padding: 14px 16px;
  border: 1px solid #bbf7d0;
  border-radius: 8px;
  background: #bbf7d0;
  color: #0f6b4c;
}

.section-title-left {
  display: flex;
  align-items: center;
  gap: 10px;
}

.section-title i {
  font-size: 1.1rem;
}

.section-title h2 {
  margin: 0;
  font-size: 1rem;
  font-weight: 750;
}

.btn-akhiri-pelayanan {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  font-size: 0.85rem;
  font-weight: 600;
  color: #fff;
  background: #0f6b4c;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.15s ease;
  white-space: nowrap;
}

.btn-akhiri-pelayanan:hover:not(:disabled) {
  background: #0c5a40;
}

.btn-akhiri-pelayanan:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>