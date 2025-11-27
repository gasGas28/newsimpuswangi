<template>
  <AppLayouts>
    <div class="container py-4">
      <div class="card border-success">
        <div class="card-header bg-info text-white fw-bold">Data Pasien</div>
        <div class="card-body">
          <form @submit.prevent="submitForm">
            <div class="row">
              <!-- Kolom Kiri -->
              <div class="col-md-6">
                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">NIK</label>
                    </div>
                    <div class="col-8">
                      <div class="input-group">
                        <input
                          type="text"
                          class="form-control form-control-sm"
                          :class="{ 'is-invalid': form.errors.NIK }"
                          v-model="form.NIK"
                          required
                        />
                        <button
                          type="button"
                          class="btn btn-info btn-sm text-white"
                          @click="cekNik"
                          :disabled="!form.NIK"
                        >
                          CEK..!
                        </button>
                        <div v-if="form.errors.NIK" class="invalid-feedback">
                          {{ form.errors.NIK }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Noka BPJS</label>
                    </div>
                    <div class="col-8">
                      <div class="input-group">
                        <input
                          type="text"
                          class="form-control form-control-sm"
                          :class="{ 'is-invalid': form.errors.noKartu }"
                          v-model="form.noKartu"
                        />
                        <button
                          type="button"
                          class="btn btn-info btn-sm text-white"
                          :disabled="!form.noKartu"
                        >
                          CEK..!
                        </button>
                        <div v-if="form.errors.noKartu" class="invalid-feedback">
                          {{ form.errors.noKartu }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">kdProvider</label>
                    </div>
                    <div class="col-8">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': form.errors.kdProvider }"
                        v-model="form.kdProvider"
                      />
                      <div v-if="form.errors.kdProvider" class="invalid-feedback">
                        {{ form.errors.kdProvider }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Nama Lengkap</label>
                    </div>
                    <div class="col-8">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': form.errors.NAMA_LGKP }"
                        v-model="form.NAMA_LGKP"
                        required
                      />
                      <div v-if="form.errors.NAMA_LGKP" class="invalid-feedback">
                        {{ form.errors.NAMA_LGKP }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Tempat, Tgl Lahir</label>
                    </div>
                    <div class="col-8 d-flex gap-1">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': form.errors.TMPT_LHR }"
                        placeholder="Tempat"
                        v-model="form.TMPT_LHR"
                        required
                      />
                      <input
                        type="date"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': form.errors.TGL_LHR }"
                        v-model="form.TGL_LHR"
                        required
                      />
                      <div v-if="form.errors.TMPT_LHR" class="invalid-feedback">
                        {{ form.errors.TMPT_LHR }}
                      </div>
                      <div v-if="form.errors.TGL_LHR" class="invalid-feedback">
                        {{ form.errors.TGL_LHR }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Jenis Kelamin</label>
                    </div>
                    <div class="col-8">
                      <select
                        class="form-select form-select-sm"
                        :class="{ 'is-invalid': form.errors.JENIS_KLMIN }"
                        v-model.number="form.JENIS_KLMIN"
                        required
                      >
                        <option value="1">Laki-laki</option>
                        <option value="2">Perempuan</option>
                      </select>
                      <div v-if="form.errors.JENIS_KLMIN" class="invalid-feedback">
                        {{ form.errors.JENIS_KLMIN }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Jenis Pekerjaan</label>
                    </div>
                    <div class="col-8">
                      <select
                        class="form-select form-select-sm"
                        :class="{ 'is-invalid': form.errors.JENIS_PKRJN }"
                        v-model.number="form.JENIS_PKRJN"
                      >
                        <option value="">- Pilih Pekerjaan -</option>
                        <option v-for="pekerjaan in pekerjaanList" :value="pekerjaan.NO">
                          {{ pekerjaan.DESCRIP }}
                        </option>
                      </select>
                      <div v-if="form.errors.JENIS_PKRJN" class="invalid-feedback">
                        {{ form.errors.JENIS_PKRJN }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Kolom Kanan -->
              <div class="col-md-6">
                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Agama</label>
                    </div>
                    <div class="col-8">
                      <select
                        class="form-select form-select-sm"
                        :class="{ 'is-invalid': form.errors.AGAMA }"
                        v-model="form.AGAMA"
                        required
                      >
                        <option value="">- Pilih Agama -</option>
                        <option v-for="agama in agamaList" :value="agama.NO">
                          {{ agama.DESCRIP }}
                        </option>
                      </select>
                      <div v-if="form.errors.AGAMA" class="invalid-feedback">
                        {{ form.errors.AGAMA }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Hub. Keluarga</label>
                    </div>
                    <div class="col-8">
                      <select
                        class="form-select form-select-sm"
                        :class="{ 'is-invalid': form.errors.STAT_HBKEL }"
                        v-model.number="form.STAT_HBKEL"
                        required
                      >
                        <option value="">- Pilih Hubungan -</option>
                        <option v-for="hubungan in hubunganKeluargaList" :value="hubungan.KODE">
                          {{ hubungan.KET }}
                        </option>
                      </select>
                      <div v-if="form.errors.STAT_HBKEL" class="invalid-feedback">
                        {{ form.errors.STAT_HBKEL }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">IHS Pasien</label>
                    </div>
                    <div class="col-8">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': form.errors.IHS_NUMBER }"
                        v-model="form.IHS_NUMBER"
                      />
                      <div v-if="form.errors.IHS_NUMBER" class="invalid-feedback">
                        {{ form.errors.IHS_NUMBER }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">No KK</label>
                    </div>
                    <div class="col-8">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': form.errors.NO_KK }"
                        v-model="form.NO_KK"
                      />
                      <div v-if="form.errors.NO_KK" class="invalid-feedback">
                        {{ form.errors.NO_KK }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Nama KK</label>
                    </div>
                    <div class="col-8">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': form.errors.NAMA_LGKP_AYAH }"
                        v-model="form.NAMA_LGKP_AYAH"
                      />
                      <div v-if="form.errors.NAMA_LGKP_AYAH" class="invalid-feedback">
                        {{ form.errors.NAMA_LGKP_AYAH }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Provinsi -->
                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Provinsi *</label>
                    </div>
                    <div class="col-8">
                      <select class="form-select form-select-sm" v-model="form.NO_PROP" required>
                        <option
                          v-for="prov in provinsiList"
                          :value="prov.NO_PROP"
                          :selected="prov.NO_PROP === '35'"
                        >
                          {{ prov.NAMA_PROP }}
                        </option>
                      </select>
                      <div v-if="form.errors.NO_PROP" class="invalid-feedback">
                        {{ form.errors.NO_PROP }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Kabupaten -->
                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Kabupaten *</label>
                    </div>
                    <div class="col-8">
                      <select
                        class="form-select form-select-sm"
                        v-model="form.NO_KAB"
                        :disabled="!form.NO_PROP || kabupatenList.length === 0"
                        required
                      >
                        <option
                          v-for="kab in kabupatenList"
                          :value="kab.NO_KAB"
                          :selected="kab.NO_KAB === '10'"
                        >
                          {{ kab.NAMA_KAB }}
                        </option>
                      </select>
                      <small v-if="form.NO_PROP && kabupatenList.length === 0" class="text-danger">
                        Data kabupaten tidak tersedia untuk provinsi ini
                      </small>
                      <div v-if="form.errors.NO_KAB" class="invalid-feedback">
                        {{ form.errors.NO_KAB }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Kecamatan *</label>
                    </div>
                    <div class="col-8">
                      <select
                        class="form-select form-select-sm"
                        :class="{ 'is-invalid': form.errors.NO_KEC }"
                        v-model="form.NO_KEC"
                        @change="loadKelurahan"
                        :disabled="!form.NO_KAB || kecamatanList.length === 0"
                        required
                      >
                        <option value="">- Pilih Kecamatan -</option>
                        <option v-for="kec in kecamatanList" :value="kec.NO_KEC">
                          {{ kec.NAMA_KEC }}
                        </option>
                      </select>
                      <div v-if="form.errors.NO_KEC" class="invalid-feedback">
                        {{ form.errors.NO_KEC }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Desa/Kelurahan *</label>
                    </div>
                    <div class="col-8">
                      <select
                        class="form-select form-select-sm"
                        :class="{ 'is-invalid': form.errors.NO_KEL }"
                        v-model="form.NO_KEL"
                        required
                        :disabled="!form.NO_KEC || kelurahanList.length === 0"
                      >
                        <option value="">- Pilih Kelurahan -</option>
                        <option v-for="kel in kelurahanList" :value="kel.NO_KEL">
                          {{ kel.NAMA_KEL }}
                        </option>
                      </select>
                      <small v-if="form.NO_KEC && kelurahanList.length === 0" class="text-danger">
                        Data kelurahan tidak tersedia untuk kecamatan ini
                      </small>
                      <div v-if="form.errors.NO_KEL" class="invalid-feedback">
                        {{ form.errors.NO_KEL }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">Alamat</label>
                    </div>
                    <div class="col-8">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': form.errors.ALAMAT }"
                        v-model="form.ALAMAT"
                        required
                      />
                      <div v-if="form.errors.ALAMAT" class="invalid-feedback">
                        {{ form.errors.ALAMAT }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">RT/RW</label>
                    </div>
                    <div class="col-8 d-flex gap-2">
                      <input
                        type="number"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': form.errors.NO_RT }"
                        placeholder="RT"
                        v-model.number="form.NO_RT"
                        required
                      />
                      <input
                        type="number"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': form.errors.NO_RW }"
                        placeholder="RW"
                        v-model.number="form.NO_RW"
                        required
                      />
                      <div v-if="form.errors.NO_RT" class="invalid-feedback">
                        {{ form.errors.NO_RT }}
                      </div>
                      <div v-if="form.errors.NO_RW" class="invalid-feedback">
                        {{ form.errors.NO_RW }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-2">
                  <div class="row">
                    <div class="col-4">
                      <label class="form-label form-label-sm fw-bold">HP</label>
                    </div>
                    <div class="col-8">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        :class="{ 'is-invalid': form.errors.PHONE }"
                        v-model="form.PHONE"
                        required
                      />
                      <div v-if="form.errors.PHONE" class="invalid-feedback">
                        {{ form.errors.PHONE }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center mt-3">
              <button
                type="submit"
                class="btn btn-primary"
                :disabled="form.processing || isLoading"
              >
                <span
                  v-if="form.processing || isLoading"
                  class="spinner-border spinner-border-sm"
                ></span>
                SIMPAN
              </button>
              <button type="button" class="btn btn-secondary ms-2" @click="resetForm">RESET</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
  import AppLayouts from '@/Components/Layouts/AppLayouts.vue';
  import { useForm, router } from '@inertiajs/vue3';
  import { ref, onMounted, watch } from 'vue';
  import axios from 'axios';

  const form = useForm({
    NIK: '',
    noKartu: '',
    kdProvider: '',
    NAMA_LGKP: '',
    JENIS_KLMIN: 1,
    TMPT_LHR: '',
    TGL_LHR: '',
    JENIS_PKRJN: null,
    AGAMA: '',
    STAT_HBKEL: null,
    NO_KK: '',
    NAMA_LGKP_AYAH: '',
    NO_PROP: '35',
    NO_KAB: '10',
    NO_KEC: '',
    NO_KEL: '',
    ALAMAT: '',
    NO_RT: null,
    NO_RW: null,
    PHONE: '',
    IHS_NUMBER: '',
  });

  const provinsiList = ref([]);
  const kabupatenList = ref([]);
  const kecamatanList = ref([]);
  const kelurahanList = ref([]);
  const isLoading = ref(false);

  // Load data saat komponen dimuat
  onMounted(async () => {
    try {
      // Load semua provinsi dari API
      const provinsiResponse = await axios.get(route('loket.api.provinsi'));
      provinsiList.value = provinsiResponse.data;

      // Load kabupaten untuk default provinsi (Jawa Timur)
      await loadKabupaten();

      // Setelah kabupaten loaded, load kecamatan untuk default kabupaten (Banyuwangi)
      setTimeout(async () => {
        await loadKecamatan();
      }, 500);
    } catch (error) {
      console.error('Error loading initial data:', error);
      alert('Gagal memuat data awal');
    }
  });

  // Watch perubahan provinsi untuk reload kabupaten
  watch(
    () => form.NO_PROP,
    (newVal) => {
      if (newVal) {
        loadKabupaten();
      }
    }
  );

  // Watch perubahan kabupaten untuk reload kecamatan
  watch(
    () => form.NO_KAB,
    (newVal) => {
      if (newVal) {
        loadKecamatan();
      }
    }
  );

  async function loadKabupaten() {
    if (!form.NO_PROP) {
      kabupatenList.value = [];
      form.NO_KAB = '';
      return;
    }

    isLoading.value = true;

    try {
      const response = await axios.get(route('loket.api.kabupaten'), {
        params: { provinsi: form.NO_PROP },
      });

      kabupatenList.value = response.data;

      // Jika provinsi diubah dari default, reset kabupaten ke pilihan pertama
      if (form.NO_PROP !== '35') {
        form.NO_KAB = response.data[0]?.NO_KAB || '';
      } else {
        // Jika kembali ke Jawa Timur, set ke Banyuwangi
        form.NO_KAB = '10';
      }

      // Reset dependent fields
      kecamatanList.value = [];
      kelurahanList.value = [];
      form.NO_KEC = '';
      form.NO_KEL = '';
    } catch (error) {
      console.error('Error loading kabupaten:', error);
      alert('Gagal memuat data kabupaten');
      kabupatenList.value = [];
    } finally {
      isLoading.value = false;
    }
  }

  async function loadKecamatan() {
    if (!form.NO_KAB) {
      kecamatanList.value = [];
      form.NO_KEC = '';
      return;
    }

    isLoading.value = true;

    try {
      const response = await axios.get(route('loket.api.kecamatan'), {
        params: {
          propinsi: form.NO_PROP,
          kabupaten: form.NO_KAB,
        },
      });

      if (response.data.error) {
            alert(response.data.error);
            return;
        }

      kecamatanList.value = response.data;

      // Reset kelurahan
      kelurahanList.value = [];
      form.NO_KEL = '';
    } catch (error) {
      console.error('Error loading kecamatan:', error);
      alert('Gagal memuat data kecamatan');
      kecamatanList.value = [];
    } finally {
      isLoading.value = false;
    }
  }

  async function loadKelurahan() {
    if (!form.NO_KEC) {
        kelurahanList.value = [];
        form.NO_KEL = '';
        return;
    }

    try {
        const response = await axios.get(route('loket.api.kelurahan'), {
            params: { 
                kecamatan: form.NO_KEC,
                propinsi: form.NO_PROP,
                kabupaten: form.NO_KAB
            },
        });
        
        if (response.data.error) {
            alert(response.data.error);
            return;
        }
        
        kelurahanList.value = response.data;
    } catch (error) {
        console.error('Error loading kelurahan:', error);
        kelurahanList.value = [];
    }
}

  defineProps({
    hubunganKeluargaList: {
      type: Array,
      required: true,
      default: () => [],
    },

    agamaList: {
      type: Array,
      required: true,
      default: () => [],
    },

    pekerjaanList: {
      type: Array,
      required: true,
      default: () => [],
    },
  });

  function cekNik() {
    if (!form.NIK) {
      alert('Silakan masukkan NIK terlebih dahulu');
      return;
    }
    alert('Fungsi cek NIK akan diimplementasikan');
  }

  function resetForm() {
    form.reset();
    // Reset ke default values
    form.NO_PROP = '35';
    form.NO_KAB = '10';
    form.NO_KEC = '';
    form.NO_KEL = '';
    kelurahanList.value = [];
    // Reload kabupaten dan kecamatan
    loadKabupaten();
    loadKecamatan();
  }

  function submitForm() {
    form.post(route('loket.pasien.store'));
  }
</script>

<style scoped>
  .form-label {
    font-size: 0.9rem;
  }
  .card-header {
    font-size: 1.1rem;
  }
  .input-group-text {
    font-size: 0.85rem;
  }
  .is-invalid {
    border-color: #dc3545;
  }
  .invalid-feedback {
    color: #dc3545;
    font-size: 0.8rem;
    margin-top: 0.25rem;
  }
</style>
