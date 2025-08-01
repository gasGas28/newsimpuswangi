<template>
  <AppLayout>
    <div class="container py-4">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-start">
          <h5 class="text-white fw-bold mt-2">FILTER LAPORAN</h5>
        </div>
        <div class="card-body">
          <div class="container">
            <form>
              <div class="row">
                <!-- Kolom Pertama -->
                <div class="col-md-4">
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <label class="col-form-label fw-bold mb-0">Puskesmas</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectPuskesmas">
                        <option>Wongsorejo</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <label class="col-sm-4 col-form-label fw-bold">Tanggal Awal</label>
                    <div class="col-sm-8">
                      <input type="date" class="form-control" />
                    </div>
                  </div>
                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Tanggal Akhir</label>
                    <div class="col-sm-8">
                      <input type="date" class="form-control" />
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        class="form-check-input me-2"
                        id="tempatKunjungan"
                        v-model="aktifTempat"
                      />
                      <label for="tempatKunjungan" class="col-form-label fw-bold mb-0">
                        Tempat Kunjungan
                      </label>
                    </div>
                    <div class="col-sm-8">
                      <div class="mb-2">
                        <select class="form-select" id="selectTempat" :disabled="!aktifTempat">
                          <option></option>
                          <option>PUSKESMAS</option>
                          <option>PUSTU</option>
                          <option>POLINDES</option>
                          <option>POSYANDU</option>
                          <option>POSKESDES</option>
                          <option>PUSLING</option>
                          <option>POSKESTREN</option>
                          <option>PONKESDES</option>
                        </select>
                      </div>

                      <div>
                        <select class="form-select" :disabled="!aktifTempat">
                          <option>--Pilih--</option>
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
                        v-model="aktifKasus"
                      />
                      <label for="cekKasus" class="col-form-label fw-bold mb-0"
                        >Kunjungan Kasus</label
                      >
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectKasus" :disabled="!aktifKasus">
                        <option></option>
                        <option>Kasus Lama</option>
                        <option>Kasus Baru</option>
                        <option>Kunjungan Kasus Lama</option>
                        <option>Kunjungan Kasus Baru</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        class="form-check-input me-2 mt-2"
                        id="cekKunjungan"
                        v-model="aktifKunjungan"
                      />
                      <label for="cekKunjungan" class="col-form-label fw-bold">Kunjungan</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select
                        class="form-select mb-2"
                        id="selectKunjungan"
                        :disabled="!aktifKunjungan"
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
                        v-model="aktifNama"
                      />
                      <label for="cekNama" class="col-form-label mb-0 fw-bold">Nama</label>
                    </div>
                    <div class="col-sm-8">
                      <input
                        type="text"
                        class="form-control"
                        id="selectNama"
                        :disabled="!aktifNama"
                      />
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        class="form-check-input me-2"
                        id="cekUmur"
                        v-model="aktifUmur"
                      />
                      <label for="cekUmur" class="col-form-label fw-bold mb-0">Umur</label>
                    </div>
                    <div class="col-auto d-flex">
                      <input
                        type="text"
                        class="form-control text-center"
                        placeholder="0"
                        style="width: 60px"
                        :disabled="!aktifUmur"
                      />
                    </div>
                    <div class="col-auto fw-bold">s/d</div>
                    <div class="col-auto">
                      <input
                        type="text"
                        class="form-control text-center"
                        placeholder="0"
                        style="width: 60px"
                        :disabled="!aktifUmur"
                      />
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekJK"
                        class="form-check-input me-2"
                        v-model="aktifJK"
                      />
                      <label for="cekJK" class="col-form-label mb-0 fw-bold">Jenis Kelamin</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectJK" :disabled="!aktifJK">
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
                        v-model="aktifAsal"
                      />
                      <label class="col-form-label fw-bold">Asal</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectAsal" :disabled="!aktifAsal">
                        <option></option>
                        <option>Dalam Wilayah</option>
                        <option>Luar Wilayah</option>
                        <option>Luar Kabupaten</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekKecamatan"
                        class="form-check-input me-2"
                        v-model="aktifKecamatan"
                      />
                      <label class="col-form-label fw-bold">Kecamatan</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectKecamatan" :disabled="!aktifKecamatan">
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
                        v-model="aktifDesa"
                      />
                      <label class="col-form-label fw-bold">Desa</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectDesa" :disabled="!aktifDesa">
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
                        v-model="aktifKepesertaan"
                      />
                      <label class="col-form-label fw-bold">Kepesertaan</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select
                        class="form-select"
                        id="selectKepesertaan"
                        :disabled="!aktifKepesertaan"
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
                        v-model="aktifKategori"
                      />
                      <label class="col-form-label fw-bold">Kategori</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectKategori" :disabled="!aktifKategori">
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
                        v-model="aktifRujukLanjut"
                      />
                      <label class="col-form-label fw-bold">Rujuk Lanjut</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select class="form-select" id="selectRujuk" :disabled="!aktifRujukLanjut">
                        <option></option>
                        <option>Non BPJS</option>
                        <option>JKN PBI</option>
                        <option>Non JKN PBI</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                      <input
                        type="checkbox"
                        id="cekIsiDiagnosa"
                        class="form-check-input me-2"
                        v-model="aktifIsiDiagnosa"
                      />
                      <label class="col-form-label fw-bold">Isi Diagnosa</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <select
                        class="form-select"
                        id="selectIsiDiagnosa"
                        :disabled="!aktifIsiDiagnosa"
                      >
                        <option>--Pilih--</option>
                        <option>Diagnosa Kosong</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2 align-items-center">
                    <div class="col-sm-4 d-flex align-items-center">
                        <input type="checkbox" id="cekDiagnosa" class="form-check-input me-2" v-model="aktifDiagnosa">
                        <label class="col-form-label fw-bold">Diagnosa</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <input
                        type="text"
                        class="form-control"
                        id="selectDiagnosa"
                        v-model="selectedTindakan"
                        @click="isModalOpen = true"
                        readonly
                        :disabled="!aktifDiagnosa"
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
                        <input type="checkbox" id="cekTindakan" class="form-check-input me-2" v-model="aktifTindakan">
                        <label class="col-form-label fw-bold">Tindakan</label>
                    </div>
                    <div class="col-sm-8 d-flex">
                      <input
                        type="text"
                        id="selectDiagnosa"
                        class="form-control"
                        readonly
                        :disabled="!aktifTindakan"
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
                <button class="btn btn-primary"><i class="bi bi-eye"></i> Tampilkan Data</button>
                <button class="btn btn-dark text-white">
                  <i class="bi bi-filetype-html"></i>
                  Tampilkan Data HTML
                </button>
                <button class="btn btn-success">
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
  import { ref } from 'vue';
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
  import { reactive } from 'vue';
  import Modal from '@/Components/Layouts/Modal.vue';

  const isModalOpen = ref(false);
  const selectedTindakan = ref('');
  const aktifPuskesmas = ref(false);
  const aktifTempat = ref(false);
  const aktifKasus = ref(false);
  const aktifKunjungan = ref(false);
  const aktifNama = ref(false);
  const aktifUmur = ref(false);
  const aktifJK = ref(false);
  const aktifAsal = ref(false);
  const aktifKecamatan = ref(false);
  const aktifDesa = ref(false);
  const aktifKepesertaan = ref(false);
  const aktifKategori = ref(false);
  const aktifIsiDiagnosa = ref(false);
  const aktifDiagnosa = ref(false);
  const aktifTindakan = ref(false);
  const aktifRujukLanjut = ref(false);
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
</script>
