<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="show" class="obat-overlay" @click.self="closeModal">
        <div class="obat-dialog">

          <!-- Header -->
          <div class="obat-header">
            <div class="obat-header-left">
              <div class="obat-header-icon">
                <i class="bi bi-capsule-pill"></i>
              </div>
              <div>
                <h5 class="obat-title">Pilih Obat</h5>
                <p class="obat-subtitle">{{ filteredObat.length }} obat tersedia</p>
              </div>
            </div>
            <button class="obat-close" @click="closeModal" aria-label="Tutup">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>

          <!-- Search -->
          <div class="obat-search">
            <div class="search-wrap">
              <i class="bi bi-search search-icon"></i>
              <input
                ref="searchInput"
                type="text"
                v-model="keyword"
                class="search-input"
                placeholder="Cari kode atau nama obat..."
                autocomplete="off"
              />
              <button v-if="keyword" class="search-clear" @click="keyword = ''" aria-label="Hapus">
                <i class="bi bi-x"></i>
              </button>
            </div>
          </div>

          <!-- Table -->
          <div class="obat-body">
            <table class="obat-table">
              <thead>
                <tr>
                  <th class="col-no">No</th>
                  <th class="col-kode">Kode</th>
                  <th class="col-nama">Nama Obat</th>
                  <th class="col-satuan">Satuan</th>
                  <th class="col-aksi">Pilih</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in paginatedObat"
                  :key="item.id || item.OBAT_ID || item.KODE_OBAT"
                  class="obat-row"
                  @click="pilihObat(item)"
                >
                  <td class="col-no text-muted">
                    {{ (currentPage - 1) * perPage + index + 1 }}
                  </td>
                  <td class="col-kode">
                    <span class="kode-badge">{{ getKode(item) }}</span>
                  </td>
                  <td class="col-nama">
                    <span class="nama-text" v-html="highlight(getNama(item))"></span>
                  </td>
                  <td class="col-satuan">
                    <span class="satuan-badge">{{ getSatuan(item) }}</span>
                  </td>
                  <td class="col-aksi">
                    <button class="pilih-btn" @click.stop="pilihObat(item)">
                      <i class="bi bi-check2"></i>
                    </button>
                  </td>
                </tr>

                <!-- Empty state -->
                <tr v-if="paginatedObat.length === 0">
                  <td colspan="5" class="empty-state">
                    <div class="empty-inner">
                      <i class="bi bi-capsule"></i>
                      <p>Obat tidak ditemukan</p>
                      <small>Coba kata kunci lain atau periksa ejaan</small>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Footer Pagination -->
          <div class="obat-footer">
            <span class="page-info">
              {{ filteredObat.length === 0 ? '0' : startItem }}–{{ endItem }}
              dari <strong>{{ filteredObat.length }}</strong> obat
            </span>

            <div class="pagination">
              <button
                class="page-btn"
                :disabled="currentPage === 1"
                @click="currentPage = 1"
                title="Halaman pertama"
              >
                <i class="bi bi-chevron-double-left"></i>
              </button>
              <button
                class="page-btn"
                :disabled="currentPage === 1"
                @click="prevPage"
                title="Sebelumnya"
              >
                <i class="bi bi-chevron-left"></i>
              </button>

              <template v-for="(page, i) in visiblePages" :key="i">
                <span v-if="page === '...'" class="page-ellipsis">···</span>
                <button
                  v-else
                  class="page-btn page-num"
                  :class="{ active: page === currentPage }"
                  @click="goToPage(page)"
                >
                  {{ page }}
                </button>
              </template>

              <button
                class="page-btn"
                :disabled="currentPage >= totalPages"
                @click="nextPage"
                title="Berikutnya"
              >
                <i class="bi bi-chevron-right"></i>
              </button>
              <button
                class="page-btn"
                :disabled="currentPage >= totalPages"
                @click="currentPage = totalPages"
                title="Halaman terakhir"
              >
                <i class="bi bi-chevron-double-right"></i>
              </button>
            </div>
          </div>

        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed, ref, watch, nextTick } from 'vue';

const props = defineProps({
  show: Boolean,
  Obat: Array,
});

const emit = defineEmits(['close', 'select']);

const keyword    = ref('');
const currentPage = ref(1);
const perPage    = 12;
const searchInput = ref(null);

// Auto-focus search saat modal buka
watch(() => props.show, (val) => {
  if (val) {
    keyword.value = '';
    currentPage.value = 1;
    nextTick(() => searchInput.value?.focus());
  }
});

const filteredObat = computed(() => {
  if (!keyword.value) return props.Obat || [];
  const q = keyword.value.toLowerCase();
  return (props.Obat || []).filter((item) => {
    const kode = String(item.KODE_OBAT || item.kode_obat || item.kode || '').toLowerCase();
    const nama = String(item.NAMA     || item.nama      || item.nama_obat || '').toLowerCase();
    return kode.includes(q) || nama.includes(q);
  });
});

const totalPages   = computed(() => Math.max(1, Math.ceil(filteredObat.value.length / perPage)));
const paginatedObat = computed(() => {
  const start = (currentPage.value - 1) * perPage;
  return filteredObat.value.slice(start, start + perPage);
});

const startItem = computed(() =>
  filteredObat.value.length === 0 ? 0 : (currentPage.value - 1) * perPage + 1
);
const endItem = computed(() =>
  Math.min(currentPage.value * perPage, filteredObat.value.length)
);

const visiblePages = computed(() => {
  const total   = totalPages.value;
  const current = currentPage.value;
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
  if (current <= 4) return [1, 2, 3, 4, 5, '...', total];
  if (current >= total - 3) return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
  return [1, '...', current - 1, current, current + 1, '...', total];
});

const goToPage = (p) => { if (p >= 1 && p <= totalPages.value) currentPage.value = p; };
const prevPage = () => { if (currentPage.value > 1) currentPage.value--; };
const nextPage = () => { if (currentPage.value < totalPages.value) currentPage.value++; };

watch(keyword, () => (currentPage.value = 1));

// Highlight keyword di nama obat
const highlight = (text) => {
  if (!keyword.value) return text;
  const escaped = keyword.value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  return text.replace(new RegExp(`(${escaped})`, 'gi'), '<mark>$1</mark>');
};

const closeModal = () => emit('close');
const pilihObat  = (item) => { emit('select', item); emit('close'); };

const getKode   = (item) => item.KODE_OBAT  || item.kode_obat  || item.kode      || '-';
const getNama   = (item) => item.NAMA        || item.nama       || item.nama_obat || '-';
const getSatuan = (item) => item.SATUAN      || item.satuan     || item.satuan_obat || '-';
</script>

<style scoped>
/* ── Overlay ───────────────────────────────────────────────── */
.obat-overlay {
  position: fixed;
  inset: 0;
  z-index: 1055;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  background: rgba(15, 23, 42, 0.48);
  backdrop-filter: blur(3px);
}

/* ── Dialog ────────────────────────────────────────────────── */
.obat-dialog {
  display: flex;
  flex-direction: column;
  width: min(900px, 100%);
  max-height: min(780px, 92vh);
  border-radius: 12px;
  background: #ffffff;
  box-shadow: 0 24px 64px rgba(15, 23, 42, 0.2);
  overflow: hidden;
}

/* ── Header ────────────────────────────────────────────────── */
.obat-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 18px 22px;
  border-bottom: 1px solid #e5edf0;
  background: #f8fafc;
}

.obat-header-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.obat-header-icon {
  display: grid;
  place-items: center;
  width: 40px;
  height: 40px;
  border-radius: 8px;
  background: #e7f5ef;
  color: #0f6b4c;
  font-size: 1.1rem;
  flex-shrink: 0;
}

.obat-title {
  margin: 0;
  color: #0f172a;
  font-size: 1rem;
  font-weight: 750;
  line-height: 1.2;
}

.obat-subtitle {
  margin: 2px 0 0;
  color: #64748b;
  font-size: 0.8rem;
}

.obat-close {
  display: grid;
  place-items: center;
  width: 34px;
  height: 34px;
  flex-shrink: 0;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  background: transparent;
  color: #64748b;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
}

.obat-close:hover {
  background: #fee2e2;
  border-color: #fca5a5;
  color: #dc2626;
}

/* ── Search ────────────────────────────────────────────────── */
.obat-search {
  padding: 14px 22px;
  border-bottom: 1px solid #e5edf0;
  background: #ffffff;
}

.search-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.search-icon {
  position: absolute;
  left: 12px;
  color: #94a3b8;
  font-size: 0.9rem;
  pointer-events: none;
}

.search-input {
  width: 100%;
  height: 40px;
  padding: 0 36px 0 38px;
  border: 1px solid #d1d9e0;
  border-radius: 8px;
  background: #f8fafc;
  color: #0f172a;
  font-size: 0.875rem;
  outline: none;
  transition: border-color 0.15s, box-shadow 0.15s;
}

.search-input:focus {
  border-color: #16a36f;
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(22, 163, 111, 0.12);
}

.search-input::placeholder {
  color: #94a3b8;
}

.search-clear {
  position: absolute;
  right: 10px;
  display: grid;
  place-items: center;
  width: 22px;
  height: 22px;
  border: 0;
  border-radius: 50%;
  background: #e2e8f0;
  color: #64748b;
  font-size: 0.8rem;
  cursor: pointer;
}

.search-clear:hover {
  background: #cbd5e1;
}

/* ── Table Body ────────────────────────────────────────────── */
.obat-body {
  flex: 1;
  overflow-y: auto;
}

.obat-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.855rem;
}

.obat-table thead th {
  position: sticky;
  top: 0;
  z-index: 1;
  padding: 10px 14px;
  border-bottom: 1px solid #c9ded6;
  background: #e7f5ef;
  color: #174236;
  font-size: 0.73rem;
  font-weight: 750;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  white-space: nowrap;
}

.obat-row {
  cursor: pointer;
  transition: background 0.1s;
}

.obat-row:hover td {
  background: #f0faf5;
}

.obat-table td {
  padding: 11px 14px;
  border-bottom: 1px solid #f0f4f7;
  color: #1e293b;
  vertical-align: middle;
}

.obat-table tbody tr:last-child td {
  border-bottom: 0;
}

/* Column widths */
.col-no     { width: 44px;  text-align: center; color: #94a3b8; font-size: 0.78rem; }
.col-kode   { width: 140px; }
.col-nama   { }
.col-satuan { width: 110px; }
.col-aksi   { width: 64px;  text-align: center; }

/* Badges */
.kode-badge {
  display: inline-flex;
  align-items: center;
  height: 26px;
  padding: 0 8px;
  border: 1px solid #bfdbfe;
  border-radius: 6px;
  background: #eff6ff;
  color: #1d4ed8;
  font-size: 0.78rem;
  font-weight: 700;
  white-space: nowrap;
  font-family: ui-monospace, monospace;
}

.nama-text {
  font-weight: 600;
  color: #0f172a;
  line-height: 1.4;
}

.nama-text :deep(mark) {
  background: #fef9c3;
  color: #854d0e;
  border-radius: 3px;
  padding: 0 2px;
}

.satuan-badge {
  display: inline-flex;
  align-items: center;
  height: 24px;
  padding: 0 8px;
  border-radius: 999px;
  background: #f1f5f9;
  color: #475569;
  font-size: 0.76rem;
  font-weight: 650;
}

/* Pilih button */
.pilih-btn {
  display: grid;
  place-items: center;
  width: 32px;
  height: 32px;
  margin: 0 auto;
  border: 1px solid #bbf7d0;
  border-radius: 8px;
  background: #f0fdf4;
  color: #15803d;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.15s, border-color 0.15s, color 0.15s;
}

.pilih-btn:hover {
  background: #16a36f;
  border-color: #16a36f;
  color: #ffffff;
}

/* Empty state */
.empty-state {
  padding: 0 !important;
}

.empty-inner {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 56px 24px;
  color: #94a3b8;
  text-align: center;
}

.empty-inner i {
  font-size: 2rem;
  color: #cbd5e1;
}

.empty-inner p {
  margin: 0;
  color: #475569;
  font-weight: 650;
  font-size: 0.9rem;
}

.empty-inner small {
  color: #94a3b8;
  font-size: 0.8rem;
}

/* ── Footer Pagination ─────────────────────────────────────── */
.obat-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 12px 22px;
  border-top: 1px solid #e5edf0;
  background: #f8fafc;
  flex-wrap: wrap;
}

.page-info {
  color: #64748b;
  font-size: 0.82rem;
}

.pagination {
  display: flex;
  align-items: center;
  gap: 4px;
}

.page-btn {
  display: grid;
  place-items: center;
  min-width: 32px;
  height: 32px;
  padding: 0 6px;
  border: 1px solid #e2e8f0;
  border-radius: 7px;
  background: #ffffff;
  color: #374151;
  font-size: 0.82rem;
  font-weight: 650;
  cursor: pointer;
  transition: background 0.12s, border-color 0.12s, color 0.12s;
}

.page-btn:hover:not(:disabled):not(.active) {
  background: #f0faf5;
  border-color: #a7f3d0;
  color: #0f6b4c;
}

.page-btn.active {
  background: #0f6b4c;
  border-color: #0f6b4c;
  color: #ffffff;
  font-weight: 750;
}

.page-btn:disabled {
  opacity: 0.38;
  cursor: not-allowed;
}

.page-ellipsis {
  display: grid;
  place-items: center;
  width: 32px;
  height: 32px;
  color: #94a3b8;
  font-size: 0.82rem;
  letter-spacing: 0.05em;
}

/* ── Transition ────────────────────────────────────────────── */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}

.modal-enter-active .obat-dialog,
.modal-leave-active .obat-dialog {
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .obat-dialog {
  transform: translateY(12px);
  opacity: 0;
}

.modal-leave-to .obat-dialog {
  transform: translateY(8px);
  opacity: 0;
}

/* ── Responsive ────────────────────────────────────────────── */
@media (max-width: 640px) {
  .obat-footer {
    flex-direction: column;
    align-items: stretch;
    text-align: center;
  }

  .pagination {
    justify-content: center;
  }

  .col-satuan,
  .col-no {
    display: none;
  }
}
</style>
