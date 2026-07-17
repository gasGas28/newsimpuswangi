<template>
  <section class="resume-panel">
    <div class="panel-header log-panel-header" @click="showLog = !showLog">
      <div class="log-header-left">
        <h4>
          <i class="bi bi-journal-text"></i> Log Pengiriman
          <span v-if="logs.length" class="log-count-badge">{{ logs.length }}</span>
        </h4>
        <p>{{ description }}</p>
      </div>
      <div class="log-header-actions" @click.stop>
        <button
          v-if="logs.length && showLog"
          type="button"
          class="finish-button log-clear-btn"
          @click="emit('clear')"
        >
          <i class="bi bi-trash"></i> Bersihkan
        </button>
        <button
          type="button"
          class="log-toggle-btn"
          :class="{ 'log-toggle-btn--open': showLog }"
          @click="showLog = !showLog"
          :title="showLog ? 'Tutup log' : 'Buka log'"
        >
          <i class="bi bi-chevron-down log-chevron"></i>
        </button>
      </div>
    </div>

    <transition name="log-slide">
      <div v-show="showLog" class="panel-body">

        <!-- Kosong -->
        <div v-if="!logs.length" class="log-empty">
          <i class="bi bi-inbox log-empty-icon"></i>
          <p>Belum ada log pengiriman.</p>
          <span>Tekan tombol <strong>Kirim ke SATUSEHAT</strong> untuk memulai pengiriman.</span>
        </div>

        <!-- Daftar Log -->
        <div v-else class="log-list">
          <div
            v-for="log in logs"
            :key="log.id"
            class="log-item"
            :class="`log-item--${log.status}`"
          >
            <!-- Ikon Status -->
            <div class="log-icon">
              <i
                class="bi"
                :class="{
                  'bi-check-circle-fill text-success-icon':   log.status === 'success',
                  'bi-x-circle-fill text-danger-icon':        log.status === 'error',
                  'bi-clock-fill text-warning-icon':          log.status === 'pending',
                  'bi-slash-circle-fill text-cancelled-icon': log.status === 'cancelled',
                }"
              ></i>
            </div>

            <!-- Konten Log -->
            <div class="log-content">
              <div class="log-header-row">
                <span class="log-title">{{ log.title }}</span>
                <span class="log-badge" :class="`log-badge--${log.status}`">
                  {{ log.statusLabel }}
                </span>
              </div>

              <div class="log-meta">
                <span><i class="bi bi-clock"></i> {{ log.timestamp }}</span>
                <span v-if="log.resourceId">
                  <i class="bi bi-fingerprint"></i> ID: {{ log.resourceId }}
                </span>
                <span v-if="log.httpStatus">
                  <i class="bi bi-hdd-network"></i> HTTP {{ log.httpStatus }}
                </span>
              </div>

              <div class="log-message" v-if="log.message">
                {{ log.message }}
              </div>

              <!-- Detail FHIR jika ada -->
              <div class="log-detail" v-if="log.detail">
                <button
                  type="button"
                  class="log-toggle"
                  @click="log.showDetail = !log.showDetail"
                >
                  <i class="bi" :class="log.showDetail ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                  {{ log.showDetail ? 'Sembunyikan' : 'Lihat Detail Respons' }}
                </button>
                <pre v-if="log.showDetail" class="log-pre">{{ log.detail }}</pre>
              </div>
            </div>
          </div>
        </div>

      </div>
    </transition>
  </section>
</template>

<script setup>
  import { ref } from 'vue';

  defineProps({
    logs: {
      type: Array,
      required: true,
    },
    description: {
      type: String,
      default: 'Riwayat percobaan pengiriman data ke platform SATUSEHAT.',
    },
  });

  const emit = defineEmits(['clear']);

  const showLog = ref(true);
</script>

<style scoped src="@/css/FormPemeriksaan.css"></style>

<style scoped>
.log-clear-btn {
  align-self: flex-start;
  min-height: 34px;
  padding: 6px 12px;
  font-size: 0.82rem;
}

.log-panel-header {
  cursor: pointer;
  user-select: none;
  transition: background 0.15s ease;
}

.log-panel-header:hover {
  filter: brightness(0.95);
}

.log-header-left {
  flex: 1;
  min-width: 0;
}

.log-header-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}

.log-count-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 22px;
  height: 22px;
  margin-left: 8px;
  padding: 0 6px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.25);
  border: 1px solid rgba(255, 255, 255, 0.45);
  color: #ffffff;
  font-size: 0.72rem;
  font-weight: 750;
  vertical-align: middle;
}

.log-toggle-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border: 1px solid rgba(255, 255, 255, 0.35);
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.15);
  color: #ffffff;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.15s ease;
}

.log-toggle-btn:hover {
  background: rgba(255, 255, 255, 0.28);
}

.log-chevron {
  transition: transform 0.25s ease;
}

.log-toggle-btn--open .log-chevron {
  transform: rotate(180deg);
}

.log-slide-enter-active,
.log-slide-leave-active {
  transition: max-height 0.3s ease, opacity 0.25s ease;
  overflow: hidden;
  max-height: 2000px;
}

.log-slide-enter-from,
.log-slide-leave-to {
  max-height: 0;
  opacity: 0;
}

.log-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 40px 20px;
  color: #94a3b8;
  text-align: center;
}

.log-empty-icon {
  font-size: 2.4rem;
  color: #cbd5e1;
}

.log-empty p {
  margin: 0;
  font-size: 0.95rem;
  font-weight: 700;
  color: #64748b;
}

.log-empty span {
  font-size: 0.82rem;
}

.log-list {
  display: grid;
  gap: 12px;
}

.log-item {
  display: flex;
  gap: 14px;
  padding: 16px;
  border: 1px solid #edf2f7;
  border-radius: 10px;
  background: #ffffff;
  transition: box-shadow 0.15s ease;
}

.log-item:hover {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
}

.log-item--success   { border-left: 4px solid #16a34a; background: #f0fdf4; }
.log-item--error     { border-left: 4px solid #dc2626; background: #fff5f5; }
.log-item--pending   { border-left: 4px solid #f59e0b; background: #fffbeb; }
.log-item--cancelled { border-left: 4px solid #94a3b8; background: #f8fafc; }

.log-icon {
  flex-shrink: 0;
  margin-top: 2px;
  font-size: 1.25rem;
}

.text-success-icon   { color: #16a34a; }
.text-danger-icon    { color: #dc2626; }
.text-warning-icon   { color: #f59e0b; }
.text-cancelled-icon { color: #94a3b8; }

.log-content {
  flex: 1;
  min-width: 0;
  display: grid;
  gap: 6px;
}

.log-header-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  flex-wrap: wrap;
}

.log-title {
  font-size: 0.9rem;
  font-weight: 750;
  color: #0f172a;
}

.log-badge {
  display: inline-flex;
  align-items: center;
  min-height: 24px;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 0.74rem;
  font-weight: 750;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.log-badge--success   { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
.log-badge--error     { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
.log-badge--pending   { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
.log-badge--cancelled { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

.log-meta {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
  font-size: 0.78rem;
  color: #64748b;
  font-weight: 600;
}

.log-meta i {
  margin-right: 4px;
}

.log-message {
  font-size: 0.84rem;
  color: #334155;
  line-height: 1.5;
  overflow-wrap: anywhere;
}

.log-toggle {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  margin-top: 4px;
  padding: 4px 0;
  border: 0;
  background: transparent;
  color: #0ea5e9;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
}

.log-toggle:hover {
  color: #0284c7;
}

.log-pre {
  margin-top: 8px;
  padding: 12px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  background: #f8fafc;
  color: #0f172a;
  font-size: 0.74rem;
  white-space: pre-wrap;
  overflow-wrap: anywhere;
  max-height: 280px;
  overflow-y: auto;
}
</style>
