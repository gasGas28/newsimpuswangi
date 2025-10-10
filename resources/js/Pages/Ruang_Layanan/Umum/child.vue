<template>
  <AppLayouts>
    <div class="card m-4 rounded-4 rounded-bottom-0">
      <div
        class="card-header bg-info d-flex justify-content-between p-3 rounded-4 rounded-bottom-0"
        style="background: linear-gradient(135deg, #3b82f6, #10b981);"
      >
        <h1 class="fs-5 text-white">BP GIGI</h1>
        <Link :href="backRoute" class="btn bg-white bg-opacity-25 border border-1 btn-sm text-white">
          <i class="fas fa-arrow-left me-1 text-white"></i> Kembali
        </Link>
      </div>

      <div class="card-body">
        <PelayananPasien :isMelayani="isMelayani" @ubah-melayani="handleMelayani">
          <div class="shadow-sm rounded-5">
            <NavigasiFormPemeriksaan
              :currentTab="currentTab"
              @change-currentTab="currentTab = $event"
            />
            <div class="m-4 pb-4 row gx-5">
              <FormPelayananSubjective v-if="currentTab === 'subjective'" />
              <FormPelayananObjective v-if="currentTab === 'objective'" :currrentSub="true" halaman="gigi" />
              <FormPelayananAssesment v-if="currentTab === 'assesment'" />

              <!-- TAB PLANNING -->
              <FormPelayananPlanning v-if="currentTab === 'planning'">
                <div class="mb-3 row">
                  <div class="col-12 col-lg-8">
                    <div class="border p-3 rounded-3 bg-white">
                      <!-- ODONTOGRAM SVG -->
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold">Odontogram</div>
                        <div class="d-flex gap-2">
                          <button type="button" class="btn btn-sm btn-outline-secondary" @click="resetSelection">
                            Reset
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-primary" @click="selectAll">
                            Pilih Semua
                          </button>
                        </div>
                      </div>

                      <div class="odontogram-wrapper">
                        <svg
                          :viewBox="`0 0 ${SVG_WIDTH} ${SVG_HEIGHT}`"
                          class="w-100 h-auto"
                          role="img"
                          aria-label="Odontogram"
                        >
                          <!-- Garis tengah -->
                          <line
                            :x1="MARGIN"
                            :x2="SVG_WIDTH - MARGIN"
                            :y1="CENTER_Y"
                            :y2="CENTER_Y"
                            stroke="#adb5bd"
                            stroke-dasharray="4 4"
                            stroke-width="1"
                          />

                          <!-- Label sisi kanan/kiri -->
                          <text :x="MARGIN" :y="CENTER_Y - 8" font-size="8" fill="#6c757d">Rahang Atas</text>
                          <text :x="MARGIN" :y="CENTER_Y + 18" font-size="8" fill="#6c757d">Rahang Bawah</text>

                          <!-- ROW 1: Permanen Atas (18→11, 21→28) -->
                          <g v-for="t in teethRows.permUpper" :key="'U'+t.id">
                            <rect
                              :x="t.x" :y="t.y"
                              :width="TOOTH_W" :height="TOOTH_H"
                              :class="['tooth-rect', { selected: selectedIdsSet.has(t.id) }]"
                              rx="4" ry="4"
                              @click="toggleTooth(t.id)"
                            />
                            <text
                              :x="t.x + TOOTH_W/2" :y="t.y + TOOTH_H/2 + 3"
                              text-anchor="middle"
                              font-size="10"
                              class="unselectable"
                            >{{ t.id }}</text>
                          </g>

                          <!-- ROW 2: Sulung Atas (55→51, 61→65) -->
                          <g v-for="t in teethRows.decUpper" :key="'Du'+t.id">
                            <rect
                              :x="t.x" :y="t.y"
                              :width="TOOTH_W" :height="TOOTH_H"
                              :class="['tooth-rect', 'deciduous', { selected: selectedIdsSet.has(t.id) }]"
                              rx="4" ry="4"
                              @click="toggleTooth(t.id)"
                            />
                            <text
                              :x="t.x + TOOTH_W/2" :y="t.y + TOOTH_H/2 + 3"
                              text-anchor="middle"
                              font-size="10"
                              class="unselectable"
                            >{{ t.id }}</text>
                          </g>

                          <!-- ROW 3: Sulung Bawah (85→81, 71→75) -->
                          <g v-for="t in teethRows.decLower" :key="'Dl'+t.id">
                            <rect
                              :x="t.x" :y="t.y"
                              :width="TOOTH_W" :height="TOOTH_H"
                              :class="['tooth-rect', 'deciduous', { selected: selectedIdsSet.has(t.id) }]"
                              rx="4" ry="4"
                              @click="toggleTooth(t.id)"
                            />
                            <text
                              :x="t.x + TOOTH_W/2" :y="t.y + TOOTH_H/2 + 3"
                              text-anchor="middle"
                              font-size="10"
                              class="unselectable"
                            >{{ t.id }}</text>
                          </g>

                          <!-- ROW 4: Permanen Bawah (48→41, 31→38) -->
                          <g v-for="t in teethRows.permLower" :key="'L'+t.id">
                            <rect
                              :x="t.x" :y="t.y"
                              :width="TOOTH_W" :height="TOOTH_H"
                              :class="['tooth-rect', { selected: selectedIdsSet.has(t.id) }]"
                              rx="4" ry="4"
                              @click="toggleTooth(t.id)"
                            />
                            <text
                              :x="t.x + TOOTH_W/2" :y="t.y + TOOTH_H/2 + 3"
                              text-anchor="middle"
                              font-size="10"
                              class="unselectable"
                            >{{ t.id }}</text>
                          </g>
                        </svg>
                      </div>

                      <div class="small text-muted mt-2">
                        <span class="legend-box me-1"></span> Permanen
                        <span class="legend-box deciduous ms-3 me-1"></span> Sulung (Deciduous)
                        <span class="legend-box selected ms-3 me-1"></span> Terpilih
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-3 row align-items-center">
                  <label class="col-sm-2 col-form-label fw-bold">Keterangan Gigi</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control bg-light" :value="keterangangigi" disabled />
                  </div>
                </div>
              </FormPelayananPlanning>

              <FormPelayananStatusPasien v-if="currentTab === 'status_pasien'" />
            </div>
          </div>
        </PelayananPasien>
      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';
import PelayananPasien from '../../../Components/Layouts/RuangLayanan/PelayananPasien/PelayananPasien.vue';
import FormPelayananSubjective from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananSubjective.vue';
import FormPelayananObjective from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananObjective.vue';
import FormPelayananAssesment from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananAssesment.vue';
import FormPelayananPlanning from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananPlanning.vue';
import FormPelayananStatusPasien from '../../../Components/Layouts/RuangLayanan/PelayananPasien/FormPelayananStatusPasien.vue';
import NavigasiFormPemeriksaan from '../../../Components/Layouts/RuangLayanan/PelayananPasien/NavigasiFormPemeriksaan.vue';
import { ref, computed, watch } from 'vue';

const isMelayani = ref(false);
const currentTab = ref('subjective');
const keterangangigi = ref('');
// backRoute diasumsikan sudah tersedia di parent/props/global; jika belum, biarkan saja seperti state awal
const backRoute = ref('#');

function handleMelayani(val) {
  isMelayani.value = val;
}

/**
 * ====== ODONTOGRAM STATE & LAYOUT ======
 * Kita membangun 4 baris:
 * 1) permanen atas: 18..11 | 21..28  (16 gigi)
 * 2) sulung  atas: 55..51 | 61..65  (10 gigi)
 * 3) sulung  bawah: 85..81 | 71..75 (10 gigi)
 * 4) permanen bawah: 48..41 | 31..38 (16 gigi)
 */
const SVG_WIDTH = 1000;
const SVG_HEIGHT = 360;
const MARGIN = 24;
const ROW_GAP = 64;
const TOOTH_W = 44;
const TOOTH_H = 32;
const COL_GAP = 10;

const CENTER_Y = SVG_HEIGHT / 2;

const ROW_Y = {
  permUpper: CENTER_Y - ROW_GAP - TOOTH_H - 10, // paling atas
  decUpper:  CENTER_Y - TOOTH_H - 16,           // atas (sulung)
  decLower:  CENTER_Y + 16,                     // bawah (sulung)
  permLower: CENTER_Y + ROW_GAP + 10            // paling bawah
};

// Helper untuk menghasilkan koordinat kolom rata kanan-kiri
function makeRowPositions(count) {
  const contentW = count * TOOTH_W + (count - 1) * COL_GAP;
  const startX = (SVG_WIDTH - contentW) / 2;
  return Array.from({ length: count }, (_, i) => startX + i * (TOOTH_W + COL_GAP));
}

// Susunan ID sesuai FDI
const permUpperIds = [
  // kanan → kiri (pasien): 18..11 lalu 21..28
  ...[18,17,16,15,14,13,12,11],
  ...[21,22,23,24,25,26,27,28]
];

const permLowerIds = [
  // kanan → kiri (pasien): 48..41 lalu 31..38
  ...[48,47,46,45,44,43,42,41],
  ...[31,32,33,34,35,36,37,38]
];

const decUpperIds = [
  // kanan → kiri (pasien): 55..51 lalu 61..65
  ...[55,54,53,52,51],
  ...[61,62,63,64,65]
];

const decLowerIds = [
  // kanan → kiri (pasien): 85..81 lalu 71..75
  ...[85,84,83,82,81],
  ...[71,72,73,74,75]
];

// Bangun posisi tiap baris
const permUpperX = makeRowPositions(permUpperIds.length);
const permLowerX = makeRowPositions(permLowerIds.length);
const decUpperX  = makeRowPositions(decUpperIds.length);
const decLowerX  = makeRowPositions(decLowerIds.length);

// Bentuk struktur data untuk SVG rendering
const teethRows = {
  permUpper: permUpperIds.map((id, i) => ({ id, x: permUpperX[i], y: ROW_Y.permUpper })),
  decUpper:  decUpperIds.map((id, i)  => ({ id, x: decUpperX[i],  y: ROW_Y.decUpper })),
  decLower:  decLowerIds.map((id, i)  => ({ id, x: decLowerX[i],  y: ROW_Y.decLower })),
  permLower: permLowerIds.map((id, i) => ({ id, x: permLowerX[i], y: ROW_Y.permLower }))
};

// State pemilihan gigi
const selectedIds = ref(new Set()); // gunakan Set agar toggle cepat
const selectedIdsSet = computed(() => selectedIds.value);

// Toggle gigi
function toggleTooth(id) {
  const next = new Set(selectedIds.value);
  if (next.has(id)) next.delete(id);
  else next.add(id);
  selectedIds.value = next;
}

// Aksi cepat
function resetSelection() {
  selectedIds.value = new Set();
}
function selectAll() {
  const all = [
    ...teethRows.permUpper.map(t => t.id),
    ...teethRows.decUpper.map(t => t.id),
    ...teethRows.decLower.map(t => t.id),
    ...teethRows.permLower.map(t => t.id)
  ];
  selectedIds.value = new Set(all);
}

// Keterangan gigi – urut sesuai urutan klinis (baris per baris seperti digambar)
const gigiTerpilih = computed(() => {
  const order = [
    ...teethRows.permUpper.map(t => t.id),
    ...teethRows.decUpper.map(t => t.id),
    ...teethRows.decLower.map(t => t.id),
    ...teethRows.permLower.map(t => t.id)
  ];
  const out = [];
  for (const id of order) {
    if (selectedIds.value.has(id)) out.push(id);
  }
  return out.join(', ');
});

watch(gigiTerpilih, (baru) => {
  keterangangigi.value = baru;
  console.log('dipilih', baru);
});
</script>

<style>
/* Tab styling (tetap dari kamu) */
.tab-item {
  border: none;
  background: transparent;
  font-weight: 500;
  border-radius: 0;
  position: relative;
  transition: all 0.3s ease;
  color: #6c757d;
}
.tab-item:hover { color: #495057 !important; }
.tab-item.active { color: #0d6efd !important; font-weight: 600; }
.tab-indicator {
  position: absolute; bottom: -16px; left: 0; height: 3px;
  background-color: #0d6efd; transition: all 0.3s ease; z-index: 1; border-radius: 3px 3px 0 0;
}

/* Odontogram styles */
.odontogram-wrapper {
  width: 100%;
  overflow-x: auto;
}
.tooth-rect {
  fill: #f8f9fa;        /* permukaan */
  stroke: #6c757d;      /* outline */
  stroke-width: 1;
  cursor: pointer;
  transition: transform .1s ease, fill .15s ease, stroke .15s ease;
}
.tooth-rect:hover {
  transform: translateY(-1px);
  fill: #eef2ff;
  stroke: #495057;
}
.tooth-rect.selected {
  fill: #dbeafe;
  stroke: #2563eb;
  stroke-width: 1.5;
}
.tooth-rect.deciduous {
  /* bedakan gigi sulung dengan fill sedikit berbeda */
  fill: #f1f3f5;
}
.tooth-rect.deciduous.selected {
  fill: #e0f2fe;
  stroke: #0ea5e9;
}

.unselectable {
  user-select: none;
  pointer-events: none;
  fill: #212529;
}

/* Legend */
.legend-box {
  display: inline-block;
  width: 14px; height: 10px;
  background: #f8f9fa;
  border: 1px solid #6c757d;
  vertical-align: middle;
}
.legend-box.deciduous {
  background: #f1f3f5;
}
.legend-box.selected {
  background: #dbeafe;
  border-color: #2563eb;
}
</style>
