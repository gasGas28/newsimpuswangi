<template>
  <div class="quick-actions mb-2">
    <div class="action-grid">
      <a href="#" class="action-card doc-action">
        <div class="action-icon">
          <i class="bi bi-file-earmark-text"></i>
        </div>
        <div class="action-label">Surat Keterangan</div>
      </a>

      <a href="#" class="action-card doc-action">
        <div class="action-icon">
          <i class="bi bi-send"></i>
        </div>
        <div class="action-label">Surat Rujukan</div>
      </a>

      <a href="#" class="action-card history-action">
        <div class="action-icon">
          <i class="bi bi-clock-history"></i>
        </div>
        <div class="action-label">Riwayat Pasien</div>
      </a>

      <Link
        :href="
          route('ruang-layanan.cppt', {
            idPoli: DataPasien.kdPoli,
            idPasien: DataPasien.ID,
          })
        "
        class="action-card medical-action"
      >
        <div class="action-icon">
          <i class="bi bi-file-text"></i>
        </div>
        <div class="action-label">CPPT</div>
      </Link>

      <!-- Tombol Mulai -->
      <button
        v-if="!sudahMulai"
        class="action-card start-action pulse-animation"
        @click="$emit('mulai-pemeriksaan')"
      >
        <div class="action-icon">
          <i class="bi bi-person-check"></i>
        </div>
        <div class="action-label">Mulai Pemeriksaan</div>
      </button>

      <div v-else class="action-card started-action">
        <div class="action-icon">
          <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="action-label">Pemeriksaan Dimulai</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  DataPasien: Object,
  sudahMulai: Boolean,
});

defineEmits(['mulai-pemeriksaan']);
</script>

<style scoped>
.action-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 16px;
}

.action-card {
  background: white;
  border-radius: 12px;
  padding: 15px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  text-align: center;
  transition: all 0.3s ease;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  border: 1px solid rgba(0, 0, 0, 0.05);
  cursor: pointer;
  text-decoration: none;
}

.action-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.action-icon {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: white;
}

.action-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--dark-color);
}

.doc-action .action-icon {
  background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
}

.history-action .action-icon {
  background: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
}

.medical-action .action-icon {
  background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);
}

.start-action .action-icon {
  background: linear-gradient(135deg, #f8961e 0%, #f3722c 100%);
}

.start-action {
  border: 2px dashed rgba(248, 150, 30, 0.5);
  background: rgba(248, 150, 30, 0.1);
}

.pulse-animation {
  animation: pulse 2s infinite;
}

.started-action {
  background: rgba(16, 185, 129, 0.12);
  border: 2px solid rgba(16, 185, 129, 0.3);
  cursor: default;
}

.started-action .action-icon {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(248, 150, 30, 0.4);
  }
  70% {
    box-shadow: 0 0 0 10px rgba(248, 150, 30, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(248, 150, 30, 0);
  }
}
</style>