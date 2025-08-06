<template>
  <AppLayout>
    <div class="container py-4">
      <div class="card shadow-sm">
        <div class="card-header text-start">
          <h5 class="text-dark fw-semibold mt-2">Filter Laporan</h5>
        </div>
        <div class="card-body">
          <div class="container">
            <form>
              <div class="row">
                <!-- Kolom Pertama -->
                <div class="col-md-4">
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <label class="col-form-label fw-semibold mb-0">Puskesmas</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectPuskesmas">
                        <option>Wongsorejo</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-2 align-items-center">
                    <label class="col-sm-4 col-form-label fw-semibold">Tanggal Awal</label>
                    <div class="col-sm-8">
                      <input type="date" class="form-control" />
                    </div>
                  </div>
                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-semibold">Tanggal Akhir</label>
                    <div class="col-sm-8">
                      <input type="date" class="form-control" />
                    </div>
                  </div>

                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <label for="tempatKunjungan" class="col-form-label fw-semibold mb-0">
                        Tempat Kunjungan
                      </label>
                    </div>
                    <div class="col-sm-8">
                      <div class="mb-2">
                        <select class="form-select" id="selectTempat" v-model="selectedKategori">
                          <option disabled value="">-- Pilih Tempat --</option>
                          <option
                            v-for="kategori in tempat_kunjungan"
                            :key="kategori.id_kategori"
                            :value="kategori.id_kategori"
                          >
                            {{ kategori.kategori }}
                          </option>
                        </select>
                      </div>

                      <div class="mb-3">
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

                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        class="form-check-input me-2 mt-2"
                        id="cekKasus"
                        v-model="aktif.kasus"
                      />
                      <label for="cekKasus" class="col-form-label fw-semibold mb-0"
                        >Kunjungan Kasus</label
                      >
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectKasus" :disabled="!aktif.kasus">
                        <option></option>
                        <option v-for="kunjungan in kunj_kasus" :key="kunjungan.id" :value="kunjungan.id">{{ kunjungan.kasus }}</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        class="form-check-input me-2 mt-2"
                        id="cekKunjungan"
                        v-model="aktif.kunjungan"
                      />
                      <label for="cekKunjungan" class="col-form-label fw-semibold">Kunjungan</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select
                        class="form-select mb-2"
                        id="selectKunjungan"
                        :disabled="!aktif.kunjungan"
                      >
                        <option></option>
                        <option>Lama</option>
                        <option>Kasus Baru</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Kolom Kedua -->
                <div class="col-md-4">
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        class="form-check-input me-2"
                        id="cekNama"
                        v-model="aktif.nama"
                      />
                      <label for="cekNama" class="col-form-label mb-0 fw-semibold">Nama</label>
                    </div>
                    <div class="col-sm-8">
                      <input
                        type="text"
                        class="form-control"
                        id="selectNama"
                        :disabled="!aktif.nama"
                      />
                    </div>
                  </div>
                  
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        class="form-check-input me-2"
                        id="cekUmur"
                        v-model="aktif.umur"
                      />
                      <label for="cekUmur" class="col-form-label fw-semibold mb-0">Umur</label>
                    </div>
                    <div class="col-auto d-flex">
                      <input
                        type="text"
                        class="form-control text-center"
                        placeholder="0"
                        style="width: 60px"
                        :disabled="!aktif.umur"
                      />
                    </div>
                    <div class="col-auto fw-semibold">s/d</div>
                    <div class="col-auto">
                      <input
                        type="text"
                        class="form-control text-center"
                        placeholder="0"
                        style="width: 60px"
                        :disabled="!aktif.umur"
                      />
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekJK"
                        class="form-check-input me-2"
                        v-model="aktif.JK"
                      />
                      <label for="cekJK" class="col-form-label mb-0 fw-semibold"
                        >Jenis Kelamin</label
                      >
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectJK" :disabled="!aktif.JK">
                        <option></option>
                        <option>Laki-Laki</option>
                        <option>Perempuan</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekAsal"
                        class="form-check-input me-2"
                        v-model="aktif.asal"
                      />
                      <label class="col-form-label fw-semibold">Asal</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectAsal" :disabled="!aktif.asal">
                        <option></option>
                        <option v-for="asl in asal" :key="asl.id_wilayah" :value="asl.id_wilayah">{{ asl.wilayah }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekKecamatan"
                        class="form-check-input me-2"
                        v-model="aktif.kecamatan"
                      />
                      <label class="col-form-label fw-semibold">Kecamatan</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectKecamatan" :disabled="!aktif.kecamatan">
                        <option></option>
                        <option>Kecamatan</option>
                        <option>Kecamatan</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="selectDesa"
                        class="form-check-input me-2"
                        v-model="aktif.desa"
                      />
                      <label class="col-form-label fw-semibold">Desa</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectDesa" :disabled="!aktif.desa">
                        <option></option>
                        <option>Desa</option>
                        <option>Desa</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekKepesertaan"
                        class="form-check-input me-2"
                        v-model="aktif.kepesertaan"
                      />
                      <label class="col-form-label fw-semibold">Kepesertaan</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select
                        class="form-select"
                        id="selectKepesertaan"
                        :disabled="!aktif.kepesertaan"
                      >
                        <option></option>
                        <option>BPJS</option>
                        <option>Non BPJS</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekKategori"
                        class="form-check-input me-2"
                        v-model="aktif.kategori"
                      />
                      <label class="col-form-label fw-semibold">Kategori</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectKategori" :disabled="!aktif.kategori">
                        <option></option>
                        <option>Non BPJS</option>
                        <option>JKN PBI</option>
                        <option>Non JKN PBI</option>
                      </select>
                    </div>
                  </div>
                </div>
                <!-- Kolom Ketiga -->
                <div class="col-md-4">
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekRujuk"
                        class="form-check-input me-2"
                        v-model="aktif.unit"
                      />
                      <label class="col-form-label fw-semibold">Unit</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectRujuk" :disabled="!aktif.unit">
                        <option></option>
                        <option
                          v-for="provider in providers"
                          :key="provider.kdProvider"
                          :value="provider.kdProvider"
                        >
                          {{ provider.nmProvider }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekRujuk"
                        class="form-check-input me-2"
                        v-model="aktif.rujukLanjut"
                      />
                      <label class="col-form-label fw-semibold">Rujuk Lanjut</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectRujuk" :disabled="!aktif.rujukLanjut">
                        <option></option>
                        <option
                          v-for="provider in providers"
                          :key="provider.kdProvider"
                          :value="provider.kdProvider"
                        >
                          {{ provider.nmProvider }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekIsiDiagnosa"
                        class="form-check-input me-2"
                        v-model="aktif.isiDiagnosa"
                      />
                      <label class="col-form-label fw-semibold">Isi Diagnosa</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select
                        class="form-select"
                        id="selectIsiDiagnosa"
                        :disabled="!aktif.isiDiagnosa"
                      >
                        <option>--Pilih--</option>
                        <option>Diagnosa Kosong</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekDiagnosa"
                        class="form-check-input me-2"
                        v-model="aktif.diagnosa"
                      />
                      <label class="col-form-label fw-semibold">Diagnosa</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <input
                        type="text"
                        class="form-control"
                        id="selectDiagnosa"
                        v-model="selectedTindakan"
                        @click="isModalOpen = true"
                        readonly
                        :disabled="!aktif.diagnosa"
                      />
                      <Modal
                        :show="isModalOpen"
                        title="Pilih Diagnosa"
                        :items="dataTindakan"
                        @close="isModalOpen = false"
                        @select="handleSelect"
                      />
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekTindakan"
                        class="form-check-input me-2"
                        v-model="aktif.tindakan"
                      />
                      <label class="col-form-label fw-semibold">Tindakan</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <input
                        type="text"
                        id="selectDiagnosa"
                        class="form-control"
                        readonly
                        :disabled="!aktif.tindakan"
                        v-model="selectedTindakan"
                        @click="isModalOpen = true"
                      />
                      <Modal
                        :show="isModalOpen"
                        title="Pilih Tindakan"
                        :items="dataTindakan"
                        @close="isModalOpen = false"
                        @select="handleSelect"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-start gap-2 mt-4">
                <button class="btn btn-data fw-semibold">
                  <i class="bi bi-eye"></i> Tampilkan Data
                </button>
                <button class="btn btn-html fw-semibold">
                  <i class="bi bi-filetype-html"></i>
                  Tampilkan Data HTML
                </button>
                <button class="btn btn-excel fw-semibold">
                  <i class="bi bi-download"></i> Download Excel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
  import { ref, computed, watch } from 'vue';
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
  import { reactive } from 'vue';
  import Modal from '@/Components/Layouts/Modal.vue';

  const aktif = reactive({
    tempat: false,
    kasus: false,
    kunjungan: false,
    nama: false,
    umur: false,
    jk: false,
    asal: false,
    kecamatan: false,
    desa: false,
    kepesertaan: false,
    kategori: false,
    isiDiagnosa: false,
    diagnosa: false,
    tindakan: false,
    rujukLanjut: false,
    unit: false,
  });

  const isModalOpen = ref(false);
  const selectedTindakan = ref('');
  const dataTindakan = ref([
    {
      kode: '00.01',
      nama: 'Therapeutic ultrasound of vessels of head and neck',
      translate: 'USG Terapi pembuluh kepala dan leher',
    },
    {
      kode: '00.02',
      nama: 'Therapeutic ultrasound of heart',
      translate: 'USG Terapi hati',
    },
    // Tambahkan data lainnya sesuai kebutuhan
  ]);

  const handleSelect = (item) => {
    selectedTindakan.value = `${item.kode} - ${item.nama}`;
    openModal.value = false;
  };
  const props = defineProps({
    providers: Array,
    tempat_kunjungan: Array,
    detail_tempat_kunjungan: Array,
    kunj_kasus: Array,
    asal: Array,
  });

  const selectedKategori = ref('');
  const selectedUnit = ref('');

  const filteredUnits = computed(() => {
    if (!selectedKategori.value) return [];

    return props.detail_tempat_kunjungan.filter(
      (unit) => unit.id_kategori == selectedKategori.value
    );
  });

  watch(selectedKategori, () => {
    selectedUnit.value = '';
  });
  // Opsi 2: Jika menggunakan detail_tempat_kunjungan sebagai array terpisah
  // return props.detail_tempat_kunjungan.filter(
  //   (item) => item.id_kategori === selectedKategori.value
</script>
<style scoped>
  .card-header {
    background-color: #b0e0e6;
  }
  .btn-data {
    background-color: #e6b0ce;
  }
  .btn-html {
    background-color: #eebd95;
  }
  .btn-excel {
    background-color: #98ec82;
  }
</style>
