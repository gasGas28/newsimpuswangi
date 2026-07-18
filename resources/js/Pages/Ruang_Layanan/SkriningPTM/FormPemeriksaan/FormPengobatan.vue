<template>
  <div class="fade-in pengobatan-form">
    <section class="planning-panel pengobatan-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-capsule"></i> Obat Tunggal / Non Racikan</h4>
          <p>Lengkapi obat, jumlah, dan aturan pakai sebelum ditambahkan ke daftar resep.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="pengobatan-grid">
          <div class="form-field obat-field">
            <label class="form-label">Obat <span class="required">*</span></label>
            <div class="obat-selected-wrap" v-if="selectedObat">
              <div class="obat-selected-info">
                <span class="code-pill">{{ getObatKode(selectedObat) }}</span>
                <div class="obat-selected-main">
                  <span class="obat-selected-nama">{{ getObatNama(selectedObat) }}</span>
                  <span class="satuan-badge">{{
                    getObatSatuan(selectedObat) || 'Satuan belum tersedia'
                  }}</span>
                </div>
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

          <div class="form-field">
            <label class="form-label" for="jumlah-obat"
              >Jumlah <span class="required">*</span></label
            >
            <div class="input-unit">
              <input
                id="jumlah-obat"
                type="number"
                class="form-control"
                v-model="formObat.jumlah"
                placeholder="0"
                min="1"
              />
              <span>{{ getObatSatuan(selectedObat) || 'tablet' }}</span>
            </div>
          </div>

          <div class="form-field dosage-field">
            <label class="form-label">Aturan Dosis</label>
            <div class="dose-grid dose-grid-readable">
              <div class="dose-input">
                <input
                  id="frekuensi-obat"
                  type="number"
                  class="form-control"
                  v-model="formObat.frekuensi"
                  placeholder="3"
                  min="1"
                />
                <span>x sehari</span>
              </div>
              <div class="dose-input">
                <input
                  id="interval-obat"
                  type="number"
                  class="form-control"
                  v-model="formObat.intervalJam"
                  placeholder="8"
                  min="1"
                />
                <span>jam sekali</span>
              </div>
            </div>
          </div>

          <div class="form-field schedule-field">
            <label class="form-label">Waktu Minum</label>
            <div class="check-list">
              <label class="check-item" v-for="w in ['Pagi', 'Siang', 'Malam']" :key="w">
                <input type="checkbox" :value="w.toLowerCase()" v-model="formObat.waktu" />
                <span>{{ w }}</span>
              </label>
            </div>
          </div>

          <div class="form-field schedule-field">
            <label class="form-label">Kondisi</label>
            <div class="check-list">
              <label
                class="check-item"
                v-for="k in ['Sebelum Makan', 'Saat Makan', 'Setelah Makan']"
                :key="k"
              >
                <input type="checkbox" :value="k.toLowerCase()" v-model="formObat.kondisi" />
                <span>{{ k }}</span>
              </label>
            </div>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="catatan-obat">Catatan</label>
            <input
              id="catatan-obat"
              type="text"
              class="form-control"
              v-model="formObat.catatan"
              placeholder="Instruksi khusus, contoh: diminum bila nyeri"
            />
          </div>
        </div>

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

    <section class="planning-panel pengobatan-panel">
      <div class="panel-header compact">
        <div>
          <h4><i class="bi bi-journal-medical"></i> Daftar Resep</h4>
          <p>{{ props.ResepObat.length }} item resep tercatat.</p>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table planning-table mb-0">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th>Obat</th>
              <th>Jumlah</th>
              <th>Aturan Pakai</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="props.ResepObat.length === 0">
              <td colspan="5" class="empty-state">
                <i class="bi bi-capsule"></i>
                <span>Belum ada resep ditambahkan.</span>
              </td>
            </tr>
            <tr v-for="(item, index) in props.ResepObat" :key="item.id_resep ?? index">
              <td class="text-muted col-no">{{ index + 1 }}</td>
              <td>
                <div class="resep-obat-cell">
                  <span class="code-pill">{{ item.nama_puyer || item.KODE_OBAT || '-' }}</span>
                  <span class="fw-semibold">{{ item.NAMA || item.nama_obat || '-' }}</span>
                </div>
              </td>
              <td class="fw-semibold jumlah-cell">{{ item.jumlah_puyer || item.jumlah || '-' }}</td>
              <td class="table-muted aturan-cell">{{ getResepAturanPakai(item) }}</td>
              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm btn-hapus-resep"
                  @click="hapusResep(item.id_resep ?? index)"
                >
                  <i class="bi bi-trash"></i>
                  <span>Hapus</span>
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
  />
</template>

<script setup>
  import { ref } from 'vue';
  import ObatModal from '../../../../Components/Layouts/RuangLayanan/SkriningPTM/ObatModal.vue';
  import { router } from '@inertiajs/vue3';

  const props = defineProps({
    DataPasien: Object,
    DataObat: Array,
    ResepObat: Array,
  });

  const selectedObat = ref(null);
  const showObatModal = ref(false);
  const errorResep = ref('');

  const formObat = ref({
    loketId: props.DataPasien.idLoket || '',
    pelayananId: props.DataPasien.idpelayanan || '',
    jumlah: '',
    frekuensi: '',
    intervalJam: '',
    waktu: [],
    kondisi: [],
    catatan: '',
  });

  const resetFormObat = () => {
    selectedObat.value = null;
    formObat.value = {
      loketId: props.DataPasien.idLoket || '',
      pelayananId: props.DataPasien.idpelayanan || '',
      jumlah: '',
      frekuensi: '',
      intervalJam: '',
      waktu: [],
      kondisi: [],
      catatan: '',
    };
    errorResep.value = '';
  };

  const getObatId = (item) => item?.OBAT_ID || '-';
  const getObatKode = (item) => item?.KODE_OBAT || item?.kode_obat || item?.kode || '-';
  const getObatNama = (item) => item?.NAMA || item?.nama || item?.nama_obat || '-';
  const getObatSatuan = (item) => item?.SATUAN || item?.satuan || item?.satuan_obat || '';

  const getResepAturanPakai = (item) => {
    if (item?.aturan_pakai || item?.aturanPakai) {
      return item.aturan_pakai || item.aturanPakai;
    }
    const parts = [];
    if (item?.dosis_pakai_puyer) parts.push(`${item.dosis_pakai_puyer}x sehari`);
    else if (item?.frekuensi) parts.push(`${item.frekuensi}x sehari`);
    if (item?.tiapJam || item?.interval_jam)
      parts.push(`tiap ${item.tiapJam || item.interval_jam} jam`);
    if (item?.waktu) parts.push(item.waktu);
    if (item?.kondisi) parts.push(item.kondisi);
    if (item?.catatan) parts.push(item.catatan);
    return parts.join(' - ') || '-';
  };

  const pilihObat = (item) => {
    selectedObat.value = item;
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

    const waktuString = (formObat.value.waktu || []).join(', ');
    const kondisiString = (formObat.value.kondisi || []).join(', ');

    const payload = {
      loketId: formObat.value.loketId,
      pelayananId: formObat.value.pelayananId,
      obat_id: getObatId(selectedObat.value),
      nama_obat: getObatNama(selectedObat.value),
      jumlah: formObat.value.jumlah,
      frekuensi: formObat.value.frekuensi,
      intervalJam: formObat.value.intervalJam,
      waktu: waktuString,
      kondisi: kondisiString,
      catatan: formObat.value.catatan,
      jenis: 'Bukan Puyer',
      kategori: '0',
      status: '0',
      nama: props.DataPasien.NAMA_LGKP,
      unit: props.DataPasien.puskId,
      nama_poli: props.DataPasien.nmPoli,
    };

    router.post(route('pelayanan.simpan-resep-ptm'), payload, {
      preserveScroll: true,
      showGlobalLoader: false,
      only: ['ResepObat'],
      onSuccess: () => {
        resetFormObat();
      },
      onError: (errors) => {
        errorResep.value = Object.values(errors)[0] || 'Gagal menyimpan resep.';
      },
    });
  };

  const hapusResep = (id) => {
    router.delete(route('pelayanan.hapus-resep-ptm', id), {
      preserveScroll: true,
      showGlobalLoader: false,
      only: ['DataPasien', 'ResepObat'],
      onError: (errors) => {
        errorResep.value = Object.values(errors)[0] || 'Gagal menghapus resep.';
      },
    });
  };
</script>

<style scoped>
  .pengobatan-form {
    display: grid;
    gap: 18px;
  }

  .pengobatan-panel {
    margin-bottom: 0;
  }

  .pengobatan-grid {
    display: grid;
    grid-template-columns: minmax(280px, 1.5fr) minmax(180px, 0.65fr);
    gap: 16px;
    align-items: start;
  }

  .pengobatan-grid .form-field {
    min-width: 0;
    padding: 14px;
    border: 1px solid #edf2f7;
    border-radius: 8px;
    background: #ffffff;
  }

  .obat-field,
  .note-field {
    grid-column: 1 / -1;
  }

  .obat-selected-wrap,
  .btn-pilih-obat {
    min-height: 58px;
  }

  .obat-selected-info {
    min-width: 0;
  }

  .obat-selected-main,
  .resep-obat-cell {
    display: grid;
    gap: 5px;
    min-width: 0;
  }

  .obat-selected-nama,
  .resep-obat-cell .fw-semibold {
    overflow-wrap: anywhere;
  }

  .dose-grid-readable {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .dose-input {
    display: grid;
    grid-template-columns: minmax(70px, 1fr) auto;
    gap: 8px;
    align-items: center;
  }

  .schedule-field .check-list {
    gap: 10px;
  }

  .resep-error {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    margin: 16px 0 0;
    padding: 10px 12px;
    border: 1px solid #fecaca;
    border-radius: 8px;
    background: #fff5f5;
    color: #b91c1c;
    font-size: 0.86rem;
    font-weight: 700;
  }

  .col-no {
    width: 52px;
  }

  .jumlah-cell {
    white-space: nowrap;
  }

  .aturan-cell {
    min-width: 240px;
    line-height: 1.5;
  }

  .btn-hapus-resep {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
  }

  @media (max-width: 992px) {
    .pengobatan-grid,
    .dose-grid-readable {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 576px) {
    .pengobatan-grid .form-field {
      padding: 12px;
    }

    .obat-selected-wrap {
      align-items: stretch;
      flex-direction: column;
    }

    .btn-ganti,
    .btn-hapus-resep {
      justify-content: center;
      width: 100%;
    }

    .dose-input {
      grid-template-columns: 1fr;
      gap: 6px;
    }
  }
</style>
