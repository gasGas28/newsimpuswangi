<template>
    <!-- ==================== PENGOBATAN ==================== -->
    <div class="fade-in">

      <!-- Toggle Tipe Resep -->
      <div class="resep-type-toggle">
        <button
          type="button"
          class="type-btn"
          :class="{ active: jenisPuyer === 'Bukan Puyer' }"
          @click="setJenis('Bukan Puyer')"
        >
          <i class="bi bi-capsule"></i>
          <span>Obat Tunggal</span>
          <small>Non Racikan</small>
        </button>
        <button
          type="button"
          class="type-btn"
          :class="{ active: jenisPuyer === 'Puyer' }"
          @click="setJenis('Puyer')"
        >
          <i class="bi bi-prescription2"></i>
          <span>Puyer</span>
          <small>Obat Racikan</small>
        </button>
      </div>

      <!-- ── FORM OBAT TUNGGAL (Bukan Puyer) ── -->
      <section v-if="jenisPuyer === 'Bukan Puyer'" class="planning-panel">
        <div class="panel-header">
          <div>
            <h4><i class="bi bi-capsule"></i> Obat Tunggal / Non Racikan</h4>
            <p>Satu item resep untuk satu jenis obat.</p>
          </div>
        </div>

        <div class="panel-body">
          <div class="form-grid">

            <!-- Pilih Obat -->
            <div class="form-field span-2">
              <label class="form-label">Obat <span class="required">*</span></label>
              <div class="obat-selected-wrap" v-if="selectedObat">
                <div class="obat-selected-info">
                  <span class="code-pill">{{ getObatKode(selectedObat) }}</span>
                  <span class="obat-selected-nama">{{ getObatNama(selectedObat) }}</span>
                  <span class="satuan-badge">{{ getObatSatuan(selectedObat) }}</span>
                </div>
                <button type="button" class="btn-ganti" @click="showObatModal = true">
                  <i class="bi bi-arrow-repeat"></i> Ganti
                </button>
              </div>
              <button v-else type="button" class="btn-pilih-obat" @click="showObatModal = true">
                <i class="bi bi-search"></i>
                <span>Pilih obat dari daftar...</span>
              </button>
            </div>

            <!-- Jumlah -->
            <div class="form-field">
              <label class="form-label">Jumlah <span class="required">*</span></label>
              <div class="input-unit">
                <input type="number" class="form-control" v-model="formObat.jumlah" placeholder="0" min="1" />
                <span>{{ getObatSatuan(selectedObat) || 'tablet' }}</span>
              </div>
            </div>

            <!-- Frekuensi -->
            <div class="form-field">
              <label class="form-label">Frekuensi</label>
              <div class="dose-grid-single">
                <input type="number" class="form-control" v-model="formObat.frekuensi" placeholder="3" min="1" />
                <span>x sehari</span>
              </div>
            </div>

            <!-- Waktu -->
            <div class="form-field">
              <label class="form-label">Waktu Minum</label>
              <div class="check-list">
                <label class="check-item" v-for="w in ['Pagi', 'Siang', 'Malam']" :key="w">
                  <input type="checkbox" :value="w.toLowerCase()" v-model="formObat.waktu" />
                  <span>{{ w }}</span>
                </label>
              </div>
            </div>

            <!-- Kondisi -->
            <div class="form-field">
              <label class="form-label">Kondisi</label>
              <div class="check-list">
                <label class="check-item" v-for="k in ['Sebelum Makan', 'Saat Makan', 'Setelah Makan']" :key="k">
                  <input type="checkbox" :value="k.toLowerCase()" v-model="formObat.kondisi" />
                  <span>{{ k }}</span>
                </label>
              </div>
            </div>

            <!-- Catatan -->
            <div class="form-field span-2">
              <label class="form-label">Catatan</label>
              <input type="text" class="form-control" v-model="formObat.catatan" placeholder="Instruksi khusus (opsional)" />
            </div>
          </div>

          <!-- Error -->
          <p v-if="errorResep" class="resep-error">
            <i class="bi bi-exclamation-circle"></i> {{ errorResep }}
          </p>
        </div>

        <div class="panel-footer">
          <button type="button" class="btn btn-outline-secondary" @click="resetFormObat">
            <i class="bi bi-x-lg"></i> Bersihkan
          </button>
          <button type="button" class="btn btn-success" @click="tambahResepTunggal">
            <i class="bi bi-plus-circle"></i> Tambah ke Daftar
          </button>
        </div>
      </section>

      <!-- ── FORM PUYER (Racikan) ── -->
      <section v-if="jenisPuyer === 'Puyer'" class="planning-panel">
        <div class="panel-header">
          <div>
            <h4><i class="bi bi-prescription2"></i> Puyer / Obat Racikan</h4>
            <p>Tambahkan satu per satu obat yang akan diracik.</p>
          </div>
        </div>

        <div class="panel-body">

          <!-- Daftar obat yang diracik -->
          <div class="racikan-list" v-if="racikanItems.length > 0">
            <div class="racikan-label">
              <i class="bi bi-list-ul"></i> Komposisi Racikan ({{ racikanItems.length }} obat)
            </div>
            <div class="racikan-item" v-for="(item, i) in racikanItems" :key="i">
              <span class="code-pill">{{ item.kode }}</span>
              <span class="racikan-nama">{{ item.nama }}</span>
              <button type="button" class="racikan-remove" @click="hapusRacikan(i)" title="Hapus">
                <i class="bi bi-x"></i>
              </button>
            </div>
          </div>

          <!-- Tambah obat ke racikan -->
          <div class="tambah-racikan-wrap">
            <div class="obat-selected-wrap" v-if="selectedObat">
              <div class="obat-selected-info">
                <span class="code-pill">{{ getObatKode(selectedObat) }}</span>
                <span class="obat-selected-nama">{{ getObatNama(selectedObat) }}</span>
              </div>
              <div class="racikan-actions">
                <button type="button" class="btn-ganti" @click="showObatModal = true">
                  <i class="bi bi-arrow-repeat"></i> Ganti
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" @click="tambahKeRacikan">
                  <i class="bi bi-plus"></i> Tambah ke Racikan
                </button>
              </div>
            </div>
            <button v-else type="button" class="btn-pilih-obat" @click="showObatModal = true">
              <i class="bi bi-search"></i>
              <span>Pilih obat untuk diracik...</span>
            </button>
          </div>

          <div class="divider"></div>

          <!-- Aturan Pakai Puyer -->
          <div class="puyer-section">
            <div class="section-subtitle">
              <i class="bi bi-clipboard2-pulse"></i>
              <span>Aturan Pakai</span>
            </div>

            <div class="form-grid">
              <!-- Jumlah bungkus -->
              <div class="form-field">
                <label class="form-label">Jumlah Bungkus <span class="required">*</span></label>
                <div class="input-unit">
                  <input type="number" class="form-control" v-model="formPuyer.jumlah" placeholder="0" min="1" />
                  <span>bungkus</span>
                </div>
              </div>

              <!-- Dosis -->
              <div class="form-field">
                <label class="form-label">Dosis Pakai <span class="required">*</span></label>
                <div class="dose-grid">
                  <input type="number" class="form-control" v-model="formPuyer.frekuensi" placeholder="3" min="1" />
                  <span>x sehari, tiap</span>
                  <input type="number" class="form-control" v-model="formPuyer.intervalJam" placeholder="8" min="1" />
                  <span>jam</span>
                </div>
              </div>

              <!-- Waktu -->
              <div class="form-field">
                <label class="form-label">Waktu Minum</label>
                <div class="check-list">
                  <label class="check-item" v-for="w in ['Pagi', 'Siang', 'Malam']" :key="w">
                    <input type="checkbox" :value="w.toLowerCase()" v-model="formPuyer.waktu" />
                    <span>{{ w }}</span>
                  </label>
                </div>
              </div>

              <!-- Kondisi -->
              <div class="form-field">
                <label class="form-label">Kondisi</label>
                <div class="check-list">
                  <label class="check-item" v-for="k in ['Sebelum Makan', 'Saat Makan', 'Setelah Makan']" :key="k">
                    <input type="checkbox" :value="k.toLowerCase()" v-model="formPuyer.kondisi" />
                    <span>{{ k }}</span>
                  </label>
                </div>
              </div>

              <!-- Catatan -->
              <div class="form-field span-2">
                <label class="form-label">Catatan</label>
                <input type="text" class="form-control" v-model="formPuyer.catatan" placeholder="Instruksi khusus (opsional)" />
              </div>
            </div>
          </div>

          <p v-if="errorResep" class="resep-error">
            <i class="bi bi-exclamation-circle"></i> {{ errorResep }}
          </p>
        </div>

        <div class="panel-footer">
          <button type="button" class="btn btn-outline-secondary" @click="resetFormPuyer">
            <i class="bi bi-x-lg"></i> Bersihkan
          </button>
          <button type="button" class="btn btn-success" @click="tambahResepPuyer" :disabled="racikanItems.length === 0">
            <i class="bi bi-plus-circle"></i> Tambah Puyer ke Daftar
          </button>
        </div>
      </section>

      <!-- Daftar Resep -->
      <section class="planning-panel">
        <div class="panel-header compact">
          <div>
            <h4><i class="bi bi-journal-medical"></i> Daftar Resep</h4>
            <p>{{ dataResep.length }} item resep tercatat.</p>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table planning-table mb-0">
            <thead>
              <tr>
                <th>No</th>
                <th>Tipe</th>
                <th>Obat / Komposisi</th>
                <th>Jumlah</th>
                <th>Aturan Pakai</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="dataResep.length === 0">
                <td colspan="6" class="empty-state">
                  <i class="bi bi-capsule"></i>
                  <span>Belum ada resep ditambahkan.</span>
                </td>
              </tr>
              <tr v-for="(item, index) in dataResep" :key="index">
                <td class="text-muted" style="width:44px">{{ index + 1 }}</td>
                <td>
                  <span class="tipe-badge" :class="item.tipe === 'Puyer' ? 'tipe-puyer' : 'tipe-tunggal'">
                    <i :class="item.tipe === 'Puyer' ? 'bi bi-prescription2' : 'bi bi-capsule'"></i>
                    {{ item.tipe }}
                  </span>
                </td>
                <td>
                  <!-- Obat tunggal -->
                  <template v-if="item.tipe === 'Bukan Puyer'">
                    <span class="code-pill me-1">{{ item.kode }}</span>
                    <span class="fw-semibold">{{ item.nama }}</span>
                  </template>
                  <!-- Puyer: tampilkan komposisi -->
                  <template v-else>
                    <div class="komposisi-list">
                      <span v-for="(r, i) in item.racikan" :key="i" class="komposisi-item">
                        <span class="code-pill">{{ r.kode }}</span> {{ r.nama }}
                      </span>
                    </div>
                  </template>
                </td>
                <td class="fw-semibold">{{ item.jumlah }}</td>
                <td class="table-muted">{{ item.aturanPakai }}</td>
                <td class="text-center">
                  <button class="btn btn-outline-danger btn-sm btn-icon" @click="hapusResep(index)">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  <ObatModal
    :show="showObatModal"
    :Obat="props.DataObat"
    @close="showObatModal = false"
    @select="pilihObat"
  /></template>

<script setup>
import { ref } from 'vue';
import ObatModal from '../../../../Components/Layouts/RuangLayanan/SkriningPTM/ObatModal.vue';

const props = defineProps({
  DataObat: Array,
});

const jenisPuyer = ref('Bukan Puyer');
const selectedObat = ref(null);
const showObatModal = ref(false);
const dataResep = ref([]);
const errorResep = ref('');
const racikanItems = ref([]);

const formObat = ref({ jumlah: '', frekuensi: '', waktu: [], kondisi: [], catatan: '' });
const formPuyer = ref({ jumlah: '', frekuensi: '', intervalJam: '', waktu: [], kondisi: [], catatan: '' });

const setJenis = (jenis) => {
  jenisPuyer.value = jenis;
  resetFormObat();
  resetFormPuyer();
};

const resetFormObat = () => {
  selectedObat.value = null;
  formObat.value = { jumlah: '', frekuensi: '', waktu: [], kondisi: [], catatan: '' };
  errorResep.value = '';
};

const resetFormPuyer = () => {
  selectedObat.value = null;
  racikanItems.value = [];
  formPuyer.value = { jumlah: '', frekuensi: '', intervalJam: '', waktu: [], kondisi: [], catatan: '' };
  errorResep.value = '';
};

const getObatKode = (item) => item?.KODE_OBAT || item?.kode_obat || item?.kode || '-';
const getObatNama = (item) => item?.NAMA || item?.nama || item?.nama_obat || '-';
const getObatSatuan = (item) => item?.SATUAN || item?.satuan || item?.satuan_obat || '';

const pilihObat = (item) => {
  selectedObat.value = item;
};

const tambahKeRacikan = () => {
  if (!selectedObat.value) return;
  const kode = getObatKode(selectedObat.value);
  const sudahAda = racikanItems.value.some((item) => item.kode === kode);
  if (sudahAda) {
    errorResep.value = 'Obat ini sudah ada dalam komposisi racikan.';
    return;
  }
  racikanItems.value.push({
    kode,
    nama: getObatNama(selectedObat.value),
  });
  selectedObat.value = null;
  errorResep.value = '';
};

const hapusRacikan = (index) => {
  racikanItems.value.splice(index, 1);
};

const buildAturanPakai = (form, tipe) => {
  const parts = [];
  if (tipe === 'Puyer') {
    if (form.frekuensi) parts.push(`${form.frekuensi}x sehari`);
    if (form.intervalJam) parts.push(`tiap ${form.intervalJam} jam`);
  } else if (form.frekuensi) {
    parts.push(`${form.frekuensi}x sehari`);
  }
  if (form.waktu?.length) parts.push(form.waktu.join(', '));
  if (form.kondisi?.length) parts.push(form.kondisi.join(', '));
  if (form.catatan) parts.push(form.catatan);
  return parts.join(' - ') || '-';
};

const tambahResepTunggal = () => {
  errorResep.value = '';
  if (!selectedObat.value) {
    errorResep.value = 'Pilih obat terlebih dahulu.';
    return;
  }
  if (!formObat.value.jumlah || formObat.value.jumlah < 1) {
    errorResep.value = 'Jumlah harus diisi.';
    return;
  }

  dataResep.value.push({
    tipe: 'Bukan Puyer',
    kode: getObatKode(selectedObat.value),
    nama: getObatNama(selectedObat.value),
    satuan: getObatSatuan(selectedObat.value),
    jumlah: `${formObat.value.jumlah} ${getObatSatuan(selectedObat.value) || 'tablet'}`,
    aturanPakai: buildAturanPakai(formObat.value, 'Bukan Puyer'),
    _raw: {
      kode_obat: getObatKode(selectedObat.value),
      nama_obat: getObatNama(selectedObat.value),
      jumlah: formObat.value.jumlah,
      frekuensi: formObat.value.frekuensi,
      waktu: formObat.value.waktu,
      kondisi: formObat.value.kondisi,
      catatan: formObat.value.catatan,
      jenis: 'Bukan Puyer',
    },
  });

  resetFormObat();
};

const tambahResepPuyer = () => {
  errorResep.value = '';
  if (racikanItems.value.length === 0) {
    errorResep.value = 'Tambahkan minimal 1 obat ke komposisi racikan.';
    return;
  }
  if (!formPuyer.value.jumlah || formPuyer.value.jumlah < 1) {
    errorResep.value = 'Jumlah bungkus harus diisi.';
    return;
  }

  dataResep.value.push({
    tipe: 'Puyer',
    racikan: [...racikanItems.value],
    jumlah: `${formPuyer.value.jumlah} bungkus`,
    aturanPakai: buildAturanPakai(formPuyer.value, 'Puyer'),
    _raw: {
      jenis: 'Puyer',
      racikan: [...racikanItems.value],
      jumlah: formPuyer.value.jumlah,
      frekuensi: formPuyer.value.frekuensi,
      interval_jam: formPuyer.value.intervalJam,
      waktu: formPuyer.value.waktu,
      kondisi: formPuyer.value.kondisi,
      catatan: formPuyer.value.catatan,
    },
  });

  resetFormPuyer();
};

const hapusResep = (index) => {
  dataResep.value.splice(index, 1);
};
</script>