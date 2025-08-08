<template>
     <div class="container">
         <div class="bg-warning d-inline-block fw-bold mb-4 ">
            <span>Tombol "Simpan" ada di tab "Tenaga medis"</span>
          </div>
          <form  @submit.prevent="pesan" action="">
          <div class="card">
          <div class="card-header d-flex gap-4 p-3">
            <a href="" class="text-decoration-none text-danger"  :class="{ 'text-danger fw-bold': currrentSubTabObjective === 'pemeriksaan_fisik' }" @click.prevent="currrentSubTabObjective = 'pemeriksaan_fisik'">Pemeriksaan Fisik ></a>
            <a href="" class="text-decoration-none text-danger" :class="{ 'text-danger fw-bold': currrentSubTabObjective === 'status_generalis' }" @click.prevent="currrentSubTabObjective = 'status_generalis'">Status Generalis ></a>
            <a  v-if=" halaman === 'gigi' && currrentSub === true" href="" class="text-decoration-none text-danger" :class="{ 'text-danger fw-bold': currrentSubTabObjective === 'pemeriksaan_intra_oral_gigi' }" @click.prevent="currrentSubTabObjective = 'pemeriksaan_intra_oral_gigi'">Pemeriksaan Intra Oral Gigi ></a>
            <a href="" class="text-decoration-none text-danger" :class="{ 'text-danger fw-bold': currrentSubTabObjective === 'tenaga_medis' }" @click.prevent="currrentSubTabObjective = 'tenaga_medis'">Tenaga Medis ></a>
          </div>
          <div class="card-body row" v-if="currrentSubTabObjective === 'pemeriksaan_fisik'"> 
            <div class="col-4">
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Tanggal Anamnesa</label>
                <input type="date" id="tanggal-anamnesa" class="form-control">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Keadaan Umum</label>
                <div class="d-flex gap-4">
                   <div class="form-check">
                <input class="form-check-input" type="radio" name="radioDefault" id="radioDefault1">
                <label class="form-check-label" for="radioDefault1">
                  Baik
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioDefault" id="radioDefault2" checked>
                <label class="form-check-label" for="radioDefault2">
                  Sedang
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioDefault" id="radioDefault2" checked>
                <label class="form-check-label" for="radioDefault2">
                  Lemah
                </label>
              </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Kesadaran</label>
                <select type="text" id="tanggal-anamnesa" class="form-control"> </select>
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">IMT</label>
                <input type="text" id="tanggal-anamnesa" class="form-control bg-warning">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Keterangan IMT</label>
                <input type="text" id="tanggal-anamnesa" class="form-control bg-warning">
              </div>
            </div>
            

            <div class="col-4">
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Tinggi Badan (cm)</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Berat Badan (kg)</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Lingkar Perut (cm)</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Lingkar Lengan (cm)</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
              </div>
            </div>
            <div class="col-4">
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">- Sistole (mmHg)</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">- Diastole (mmHg)</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Resp. Rate (bpm)</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Heart Rate (bpm)</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Suhu (°C)</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
              </div>
            </div>
          </div>
           <div class="card-body" v-if="currrentSubTabObjective === 'status_generalis'">
            <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <!-- THORAX -->
                <h6 class="fw-bold text-decoration-underline">THORAX</h6>
                <div class="row mb-3">
                <div class="col-4">
                    <label class="form-label">Jantung</label>
                    <select class="form-select">
                    <option>NORMAL</option>
                    <option>ABNORMAL</option>
                    </select>
                </div>
                <div class="col-8">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control bg-light" readonly>
                </div>
                </div>

                <div class="row mb-3">
                <div class="col-4">
                    <label class="form-label">Pulmo</label>
                    <select class="form-select">
                    <option>NORMAL</option>
                    <option>ABNORMAL</option>
                    </select>
                </div>
                <div class="col-8">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control bg-light" readonly>
                </div>
                </div>

                <!-- ABDOMEN -->
                <h6 class="fw-bold mt-4">ABDOMEN</h6>
                <div class="row mb-3">
                <div class="col-4">
                    <label class="form-label">Atas</label>
                    <select class="form-select">
                    <option>NORMAL</option>
                    <option>ABNORMAL</option>
                    </select>
                </div>
                <div class="col-8">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control bg-light" readonly>
                </div>
                </div>

                <div class="row mb-3">
                <div class="col-4">
                    <label class="form-label">Bawah</label>
                    <select class="form-select">
                    <option>NORMAL</option>
                    <option>ABNORMAL</option>
                    </select>
                </div>
                <div class="col-8">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control bg-light" readonly>
                </div>
                </div>

                <!-- EXTRIMITAS -->
                <h6 class="fw-bold mt-4">EXTRIMITAS</h6>
                <div class="row mb-3">
                <div class="col-4">
                    <label class="form-label">Atas</label>
                    <select class="form-select">
                    <option>NORMAL</option>
                    <option>ABNORMAL</option>
                    </select>
                </div>
                <div class="col-8">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control bg-light" readonly>
                </div>
                </div>

                <div class="row mb-3">
                <div class="col-4">
                    <label class="form-label">Bawah</label>
                    <select class="form-select">
                    <option>NORMAL</option>
                    <option>ABNORMAL</option>
                    </select>
                </div>
                <div class="col-8">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control bg-light" readonly>
                </div>
                </div>
            </div>
            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <h6 class="fw-bold text-uppercase text-decoration-underline">Kepala</h6>

                <div class="row mb-3">
                <div class="col-4">
                    <label class="form-label">Kepala</label>
                    <select class="form-select">
                    <option>NORMAL</option>
                    <option>ABNORMAL</option>
                    </select>
                </div>
                <div class="col-8">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control bg-light" readonly>
                </div>
                </div>

                <div class="row mb-3">
                <div class="col-4">
                    <label class="form-label">Mata</label>
                    <select class="form-select">
                    <option>NORMAL</option>
                    <option>ABNORMAL</option>
                    </select>
                </div>
                <div class="col-8">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control bg-light" readonly>
                </div>
                </div>

                <div class="row mb-3">
                <div class="col-4">
                    <label class="form-label">Telinga</label>
                    <select class="form-select">
                    <option>NORMAL</option>
                    <option>ABNORMAL</option>
                    </select>
                </div>
                <div class="col-8">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control bg-light" readonly>
                </div>
                </div>

                <div class="row mb-3">
                <div class="col-4">
                    <label class="form-label">Leher</label>
                    <select class="form-select">
                    <option>NORMAL</option>
                    <option>ABNORMAL</option>
                    </select>
                </div>
                <div class="col-8">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control bg-light" readonly>
                </div>
                </div>
            </div>
            </div>
            </div>
            <div name="form-intra-oral-gigi" v-if="currrentSubTabObjective === 'pemeriksaan_intra_oral_gigi'">
              <FormPemeriksaanIntraOralGigi></FormPemeriksaanIntraOralGigi>
            </div>
            <div class="card-body" v-if="currrentSubTabObjective === 'tenaga_medis'">
                <div class="row">   
                <slot name="keterangan-gigi">
                </slot>
                <div class="col-6">
                <label for="" class="fw-bold">Tenaga Media Askep</label>
                <select class="form-control my-3" name="" id="">
                    <option value="">-Pilih_</option>
                </select>
                <button type="submit" class="btn btn-success">Simpan</button>
                </div>
                </div>
            </div>
          </div> 
          </form>
      </div>
</template>

<script setup>
import { ref } from 'vue';
const currrentSubTabObjective = ref('pemeriksaan_fisik');
import FormPemeriksaanIntraOralGigi from './FormPemeriksaanIntraOralGigi.vue';
function pesan(){
  alert('tes')
}
defineProps({
  currrentSub: String,
  halaman: String
})
</script>
