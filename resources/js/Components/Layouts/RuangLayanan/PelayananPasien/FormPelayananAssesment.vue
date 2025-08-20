<template>
  <div>
    <div class="row">
      <form @submit.prevent="submitForm" class="col-6">
        <div>
          <div class="text-decoration-underline fw-bold t mb-4">
            <p class="bg-warning d-inline-block px-2">Diagnosa Medis</p>
          </div>
          <div class="row mb-3">
            <div class="col-3">
              <input type="text" class="form-control" placeholder="" disabled v-model="form.kode_diagnosa">
            </div>
            <div class="col-9">
              <div class="input-group">
                <input type="text" class="form-control bg-light" disabled v-model="form.nama_diagnosa" />
                <button type="button" class="btn btn-info ">Cari</button>
                <button type="button" class="btn btn-danger ">Del</button>
              </div>
            </div>
          </div>
          <div class="row  mb-3">
            <div class="col-3 d-flex flex-column">
              <label for="" class="fw-bold"> Alergi Makan</label>
              <input class="form-control bg-warning bg-opacity-75" type="text">
            </div>
            <div class="col-3  d-flex flex-column">
              <label for="" class="fw-bold"> Alergi Obat</label>
              <input class="form-control bg-warning bg-opacity-75" type="text">
            </div>
            <div class="col-6 d-flex flex-column">
              <label for="" class="fw-bold"> Keterangan Alergi</label>
              <input class="form-control bg-warning bg-opacity-75" type="text">
            </div>
          </div>
          <div class="row  mb-3">
            <div class="col-3  d-flex flex-column">
              <label for="" class="fw-bold">Kunjungan Khusus</label>
              <select class="form-control" name="" id="" v-model="form.kunjungan_khusus">
                <option value="" selected>-Pilih-</option>
                <option value="" v-for="item in diagnosaKasus">{{ item.kasus }}</option>
              </select>
            </div>
            <div class="col-9  d-flex flex-column">
              <label for="" class="fw-bold">Keterangan</label>
              <input class="form-control" type="text" v-model="form.keterangan">
            </div>

          </div>
          <buttton type="submit" class="btn btn-success">
            <i class="far fa-envelope me-2"></i>SIMPAN DIAGNOSA MEDIS
          </buttton>
        </div>
      </form>

      <div class="col-6">

        <div class="row mb-4">
          <div class="col-5">
            <div class="text-decoration-underline fw-bold t mb-4">
              <p class="bg-warning d-inline-block px-2">Pemeriksaan Penunjang</p>
            </div>
            <Link href="/surat-keterangan" class="btn btn-info">
            <i class="fas fa-bars me-2"></i>Laboratorium
            </Link>
          </div>
          <div class="col-7 ">
            <div class="text-decoration-underline fw-bold t mb-4">
              <p class="bg-warning d-inline-block px-2">Form Lanjutan</p>
            </div>
            <div class="d-flex gap-3 flex-wrap">
              <Link href="/surat-keterangan" class="btn btn-secondary">
              <i class="far fa-envelope me-2"></i>Diare
              </Link>
              <Link href="/surat-keterangan" class="btn btn-secondary">
              <i class="far fa-envelope me-2"></i>Katarak
              </Link>
              <Link href="/surat-keterangan" class="btn btn-secondary">
              <i class="far fa-envelope me-2"></i>PTM
              </Link>
              <Link href="/surat-keterangan" class="btn btn-secondary">
              <i class="far fa-envelope me-2"></i>Hipertensi
              </Link>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="text-decoration-underline fw-bold t mb-4">
            <p class="bg-warning d-inline-block px-2">Diagnosa Keperawatan</p>
          </div>

          <select class="form-control" name="" id="">
            <option value="">pilih</option>
          </select>
          <button href="/surat-keterangan" class="btn btn-success mt-4">
            <i class="far fa-envelope me-2"></i>Simpan Diagnosa Keperawatan
          </button>
        </div>

      </div>

      <div class="row mt-4">
        <div class="col-6">
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Diagnosa Medis</th>
                <th>Keterangan</th>
                <th>Kasus</th>
                <th>Poli</th>
                <th>Action</th>
              </tr>

            </thead>
          </table>

        </div>
        <div class="col-6">
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Diagnosa Asuhan Keperawatan</th>
                <th>Keterangan</th>
                <th>Kasus</th>
                <th>Poli</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
const props = defineProps({
  diagnosaKasus: Array,
  dataPasien: Object

})
const form = useForm({
  kode_diagnosa: '',
  nama_diagnosa: '',
  keterangan_alergi: '',
  kunjungan_khusus: '',
  keterangan: '',
  pelayananId: 1,
  loketId: 1,
  kdPoli: props.dataPasien?.kdPoli ?? ''
});

function submitForm() {
  // form.post(route('ruang-layanan-umum.diagnosa-medis'))
  alert('hai')
}
</script>