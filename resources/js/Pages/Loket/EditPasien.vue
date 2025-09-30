<template>
  <AppLayouts>
    <div class="container py-4">
      <div class="card mt-4 mb-4">
        <div class="card-body">
          <div class="text-header">
            <h5 class="text-primary fw-semibold fs-3 mt-2 mb-3 ms-2 gap-4">
              <i class="bi bi-people"></i> Edit Data Pasien
            </h5>
          </div>
          <hr />
          <div class="container">
            <form @submit.prevent="submit">
              <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                  <div class="row mb-2 align-items-center">
                    <label class="col-sm-4 col-form-label fw-bold">NIK</label>
                    <div class="col-sm-8 d-flex">
                      <input v-model="form.nik" type="text" class="form-control me-2" />
                      <button type="button" class="btn btn-info text-white">CEK..!</button>
                    </div>
                  </div>

                  <div class="row mb-2 align-items-center">
                    <label class="col-sm-4 col-form-label fw-bold">Noka BPJS</label>
                    <div class="col-sm-8 d-flex">
                      <input v-model="form.no_kartu" type="text" class="form-control me-2" />
                      <button type="button" class="btn btn-info text-white">CEK..!</button>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">kdProvider</label>
                    <div class="col-sm-8">
                      <input v-model="form.provider" type="text" class="form-control" />
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Nama Lengkap</label>
                    <div class="col-sm-8">
                      <input v-model="form.nama" type="text" class="form-control" />
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Tempat, Tgl Lahir</label>
                    <div class="col-sm-8 d-flex gap-1">
                      <input
                        v-model="form.tempat_lahir"
                        type="text"
                        class="form-control"
                        placeholder="Tempat"
                      />
                      <input v-model="form.tanggal_lahir" type="date" class="form-control" />
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Jenis Kelamin</label>
                    <div class="col-sm-8">
                      <select v-model="form.jenis_kelamin" class="form-select">
                        <option value="1">Laki-laki</option>
                        <option value="2">Perempuan</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Jenis Pekerjaan</label>
                    <div class="col-sm-8">
                      <select v-model="form.jenis_pkrjn" class="form-select">
                        <option></option>
                        <option v-for="pkrjn in pekerjaan" :id="pkrjn.NO" :value="pkrjn.NO">
                          {{ pkrjn.DESCRIP }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Agama</label>
                    <div class="col-sm-8">
                      <select v-model="form.agama" class="form-select">
                        <option value=""></option>
                        <option v-for="agm in agama" :key="agm.NO" :value="agm.DESCRIP">
                          {{ agm.DESCRIP }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Hub. Keluarga</label>
                    <div class="col-sm-8">
                      <select v-model="form.hub_family" class="form-select">
                        <option value=""></option>
                        <option v-for="fam in hubKeluarga" :id="fam.KODE" :value="fam.KODE">
                          {{ fam.KET }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">IHS Pasien</label>
                    <div class="col-sm-8">
                      <input v-model="form.ihs_pasien" type="text" class="form-control" />
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">No KK</label>
                    <div class="col-sm-8">
                      <input v-model="form.no_kk" type="text" class="form-control" />
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Nama KK</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" style="background-color: yellow" />
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Provinsi</label>
                    <div class="col-sm-8">
                      <select v-model="form.provinsi" class="form-select">
                        <option></option>
                        <option v-for="prop in provinsi" :key="prop.NO_PROP" :value="prop.NO_PROP">
                          {{ prop.NAMA_PROP }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Kabupaten</label>
                    <div class="col-sm-8">
                      <select v-model="form.kabupaten" class="form-select">
                        <option></option>
                        <option v-for="kab in filteredKabupaten" :key="kab.NO_KAB" :value="kab.NO_KAB">
                          {{ kab.NAMA_KAB }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Kecamatan</label>
                    <div class="col-sm-8">
                      <select v-model="form.kecamatan" class="form-select">
                        <option></option>
                        <option v-for="kec in filteredKecamatan" :key="kec.NO_KEC" :value="kec.NO_KEC">{{ kec.NAMA_KEC }}</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Desa/Kelurahan</label>
                    <div class="col-sm-8">
                      <select v-model="form.kelurahan" class="form-select">
                        <option></option>
                        <option v-for="kel in filteredKelurahan" :key="kel.NO_KEL" :value="kel.NO_KEL">{{ kel.NAMA_KEL }}</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">Alamat</label>
                    <div class="col-sm-8">
                      <input v-model="form.alamat" type="text" class="form-control" />
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">RT/RW</label>
                    <div class="col-sm-8 d-flex gap-2">
                      <input
                        v-model="form.no_rt"
                        type="text"
                        class="form-control"
                        placeholder="RT"
                      />
                      <input
                        v-model="form.no_rw"
                        type="text"
                        class="form-control"
                        placeholder="RW"
                      />
                    </div>
                  </div>

                  <div class="row mb-2">
                    <label class="col-sm-4 col-form-label fw-bold">HP</label>
                    <div class="col-sm-8">
                      <input v-model="form.phone" type="text" class="form-control" />
                    </div>
                  </div>
                </div>
              </div>

              <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary" @click="submit">SIMPAN</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayouts>
</template>
<script setup>
  import AppLayouts from '@/Components/Layouts/AppLayouts.vue';
  import { useForm, router, Link, Head } from '@inertiajs/vue3';
  import { ref, computed, watch } from "vue";


  const props = defineProps({
    pasien: Object,
    agama: Array,
    pekerjaan: Array,
    hubKeluarga: Array,
    kabupaten: Array,
    provinsi: Array,
    kecamatan: Array,
    kelurahan: Array,
  });

  // nilai awal form langsung diisi dari props.pasien
  const form = useForm({
    nama: props.pasien.NAMA_LGKP ?? '',
    nik: props.pasien.NIK ?? '',
    provinsi: props.pasien.NO_PROP ?? '',
    kabupaten: props.pasien.NO_KAB ?? '',
    kecamatan: props.pasien.NO_KEC ?? '',
    kelurahan: props.pasien.NO_KEL ?? '',
    alamat: props.pasien.ALAMAT ?? '',
    agama: props.pasien.AGAMA ?? '',
    provider: props.pasien.kdProvider ?? '',
    tanggal_lahir: props.pasien.TGL_LHR ?? '',
    tempat_lahir: props.pasien.TMPT_LHR ?? '',
    jenis_kelamin: props.pasien.JENIS_KLMIN ?? '',
    no_kk: props.pasien.NO_KK ?? '',
    no_kartu: props.pasien.noKartu ?? '',
    no_rt: props.pasien.NO_RT ?? '',
    no_rw: props.pasien.NO_RW ?? '',
    phone: props.pasien.PHONE ?? '',
    jenis_pkrjn: props.pasien.JENIS_PKRJN ?? '',
    hub_family: props.pasien.STAT_HBKEL ?? '',
    ihs_pasien: props.pasien.IHS_NUMBER ?? '',
  });

  // const submit = () => {
  //   // form.put(route('pasien.update', props.pasien.ID), form.data());
  //   console.log(route().current()); // cek route aktif
  //   console.log(route('pasien.update', props.pasien.ID)); // cek url hasil generate
  //   console.log('Update route =>', route('pasien.update', props.pasien.ID));
  //   form.post(route('pasien.update', props.pasien.ID));
  // }

  const submit = () => {
    form.post(route('pasien.update', props.pasien.ID), {
      onSuccess: () => {
        router.visit(route('loket.search'));
      },
    });
  };

  const filteredKabupaten = computed(() =>
    props.kabupaten.filter((kab) => kab.NO_PROP === form.provinsi)
  );

  const filteredKecamatan = computed(() =>
    props.kecamatan.filter((kec) => kec.NO_PROP === form.provinsi && kec.NO_KAB === form.kabupaten)
  );

  const filteredKelurahan = computed(() =>
    props.kelurahan.filter(
      (kel) =>
        kel.NO_PROP === form.provinsi &&
        kel.NO_KAB === form.kabupaten &&
        kel.NO_KEC === form.kecamatan
    )
  );
  // const selectedProvinsi = ref('');
  // const
  // const cekData = () => {
  //   form.put(`/pasien/${props.pasien.ID}`);
  // };
</script>
