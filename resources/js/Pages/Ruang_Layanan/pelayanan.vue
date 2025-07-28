<template>
  <AppLayouts>
    <div class="card m-4">
      <div class="card-header d-flex justify-content-between">
        <h1 class="fs-5">BP Umum</h1>
        <Link href="/simpus/umum" class="btn btn-primary">Kembali</Link>
      </div>

      <div class="card-body">
        <table class="table table-borderless mb-0">
          <tbody>
            <tr class="border-bottom">
              <td class="col-md-3 py-2 ps-3 pe-0 text-start">
                <span class="text-secondary">Nama</span>
              </td>
              <td class="col-md-3 py-2 ps-0 pe-3 text-start">
                <span class="fw-bold text-dark">AJENG NINA RISKI (-)</span>
              </td>
              <td class="col-md-3 py-2 ps-3 pe-0 text-start">
                <span class="text-secondary">Alamat</span>
              </td>
              <td class="col-md-3 py-2 ps-0 pe-3 text-start">
                <span class="fw-bold text-dark">
                  DUSUN KRAJAN II, Genteng - Setail
                </span>
              </td>
            </tr>

            <tr class="border-bottom">
              <td class="col-md-3 py-2 ps-3 pe-0 text-start">
                <span class="text-secondary">Jk / Umur</span>
              </td>
              <td class="col-md-3 py-2 ps-0 pe-3 text-start">
                <span class="fw-bold text-dark">P / 20 Tahun - 7 Bulan - 22 Hari</span>
              </td>
              <td class="col-md-3 py-2 ps-3 pe-0 text-start">
                <span class="text-secondary">Jenis/Poli</span>
              </td>
              <td class="col-md-3 py-2 ps-0 pe-3 text-start">
                <span class="fw-bold text-dark">Kunjungan Sakit ( Umum / )</span>
              </td>
            </tr>

            <tr class="border-bottom">
              <td class="col-md-3 py-2 ps-3 pe-0 text-start">
                <span class="text-secondary">Tanggal Kunjungan</span>
              </td>
              <td class="col-md-3 py-2 ps-0 pe-3 text-start">
                <span class="fw-bold text-dark">24-07-2025</span>
              </td>
              <td class="col-md-3 py-2 ps-3 pe-0 text-start">
                <span class="text-secondary">No. BPJS/Provider</span>
              </td>
              <td class="col-md-3 py-2 ps-0 pe-3 text-start">
                <span class="fw-bold text-dark">/</span>
              </td>
            </tr>

            <tr>
              <td class="col-md-3 py-2 ps-3 pe-0 text-start">
                <span class="text-secondary">No. RM / NIK</span>
              </td>
              <td class="col-md-3 py-2 ps-0 pe-3 text-start">
                <span class="fw-bold text-dark">0139184 / 3510094312040003</span>
              </td>
              <td class="col-md-3 py-2 ps-3 pe-0 text-start"></td>
              <td class="col-md-3 py-2 ps-0 pe-3 text-start"></td>
            </tr>
          </tbody>
        </table>

        <div class="d-flex gap-3 m-3">
          <Link href="/surat-keterangan" class="btn btn-primary">
            <i class="far fa-envelope me-2"></i>SURAT KETERANGAN
          </Link>
          <Link href="/surat-rujukan" class="btn btn-primary">
            <i class="far fa-envelope me-2"></i>SURAT RUJUKAN
          </Link>
          <Link href="/riwayat-pasien" class="btn btn-success">
            <i class="fas fa-notes-medical me-2"></i>RIWAYAT PASIEN
          </Link>
          <Link href="/cppt" class="btn btn-success">
            <i class="fas fa-notes-medical me-2"></i>CPPT
          </Link>
        </div>

        <hr />

        <div class="mt-4">
          <Link href="/surat-keterangan" class="btn btn-outline-primary">
            <i class="far fa-envelope me-2"></i>MULAI MELAYANI PEMERIKSAAN PASIEN
          </Link>
        </div>

        <hr />
        <div class="card mt-4">
        <div class="card-header p-4 d-flex gap-4 align-items-center">
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'subjective' }" @click.prevent="currentTab = 'subjective'">Subjective</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'objective' }" @click.prevent="currentTab = 'objective'">Objective</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'assesment' }" @click.prevent="currentTab = 'assesment'">Assessment</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'planning' }" @click.prevent="currentTab = 'planning'">Planning</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'status_pasien' }" @click.prevent="currentTab = 'status_pasien'">Status Pasien</a>
          <Link href="#" class="btn btn-success">Kirim RME v.1 ke SATU SEHAT</Link>
        </div>

  <form class="m-4 row gx-5">
      <div class="container" v-if="currentTab === 'subjective'">
        <!-- Row 1 -->
        <div class="row mb-3">
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Tgl Anamnesi</label>
            <div class="col-8">
              <input type="date" class="form-control" />
            </div>
          </div>
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Alergi Makanan</label>
            <div class="col-8">
              <select class="form-select">
                <option>Tidak Ada Alergi</option>
                <option>Alergi 1</option>
                <option>Alergi 2</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Row 2 -->
        <div class="row mb-3">
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Keluhan Utama/Keperluan</label>
            <div class="col-8">
              <textarea class="form-control"></textarea>
            </div>
          </div>
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Alergi Obat</label>
            <div class="col-8">
              <select class="form-select">
                <option>Tidak Ada Alergi</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Row 3 -->
        <div class="row mb-3">
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Keluhan Tambahan</label>
            <div class="col-8">
              <textarea class="form-control"></textarea>
            </div>
          </div>
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Keterangan Alergi</label>
            <div class="col-8">
              <textarea class="form-control"></textarea>
            </div>
          </div>
        </div>

        <!-- Row 4 -->
        <div class="row mb-3">
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Riwayat Penyakit Sekarang</label>
            <div class="col-8">
              <textarea class="form-control"></textarea>
            </div>
          </div>
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Tindakan/Terapi</label>
            <div class="col-8">
              <textarea class="form-control"></textarea>
            </div>
          </div>
        </div>

        <!-- Row 5 -->
        <div class="row mb-3">
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Riwayat Penyakit Dahulu</label>
            <div class="col-8">
              <textarea class="form-control"></textarea>
            </div>
          </div>
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Obat yang Sering Digunakan</label>
            <div class="col-8">
              <textarea class="form-control"></textarea>
            </div>
          </div>
        </div>

        <!-- Row 6 -->
        <div class="row mb-3">
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Riwayat Penyakit Keluarga</label>
            <div class="col-8">
              <textarea class="form-control"></textarea>
            </div>
          </div>
          <div class="col-md-6 row">
            <label class="text-end col-4 col-form-label">Obat yang Sering Dikonsumsi</label>
            <div class="col-8">
              <textarea class="form-control"></textarea>
            </div>
          </div>
        </div>
          <button>SIMPAN</button>
      </div>

      <div class="container" v-if="currentTab === 'objective'">
        <div class="card">
          <div class="card-header d-flex gap-4 p-3">
            <a href="" class="text-decoration-none" @click.prevent="currentTab = 'pemeriksa_fisik'">Pemeriksaan Fisik</a>
            <a href="" class="text-decoration-none" @click.prevent="currentTab = 'satatus_generalis'">Status Generalis</a>
            <a href="" class="text-decoration-none" @click.prevent="currentTab = 'tenaga_medis'">Tenaga Medis</a>

          </div>
          <div class="card-body row">
            <div class="col-4">
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Tanggal Anamnesa</label>
                <input type="date" id="tanggal-anamnesa" class="form-control">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Keadaan Umum</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Kesadaran</label>
                <select type="text" id="tanggal-anamnesa" class="form-control"> </select>
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">IMT</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
              </div>
              <div class="mb-3">
                <label for="tanggal-anamnesa" class="form-label">Keterangan IMT</label>
                <input type="text" id="tanggal-anamnesa" class="form-control">
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
        </div>
      </div>

      <div class="container" v-if="currentTab === 'planning'">
        <div class="card">
          <div class="card-header d-flex gap-4">
            <a href="" class="text-decoration-none">Tindakan</a>
            <a href="" class="text-decoration-none">Pengobatan</a>
          </div>
          <div class="card-body">
      <form action="">
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label text-end">Kode Tindakan</label>
          <div class="col-sm-4">
            <input type="text" class="form-control">
          </div>
        </div>
        
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label text-end">Nama Tindakan</label>
          <div class="col-sm-4">
            <input type="text" class="form-control">
          </div>
        </div>
        
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label text-end">Nama Tindakan (Ind)</label>
          <div class="col-sm-4">
            <input type="text" class="form-control">
          </div>
        </div>
        
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label text-end">Keterangan</label>
          <div class="col-sm-4">
            <input type="text" class="form-control">
          </div>
        </div>
        <button class="btn btn-success">simpan</button>
      </form>

      <div class="mt-4">
        <table class="table border">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama Tindakan</th>
              <th>Peraturan</th>
              <th>Harga</th>
              <th>Bayar</th>
              <th>Poli</th>
              <th>Keterangan</th>
              <th>Ket Gigi</th>
              <th>Create By</th>
              <th>Action</th>
              
            </tr>
          </thead>
        </table>
      </div>
    </div>


        </div>
      </div>

      <div class="container" v-if="currentTab === 'status_pasien'">
        <div class="card">
        <div class="card-body">
      <form action="">
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label text-end fw-bold">Status Pulang</label>
          <div class="col-sm-4">
            <select class="form-control" name="" id="">
              <option value="" v-for="index in 10">Berobat Jalan</option>
            </select>
          </div>
        </div>
        
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label text-end fw-bold">Tenaga Medis</label>
          <div class="col-sm-4">
           <select class="form-control" name="" id="">
              <option value="" v-for="index in 10">Practioner 1</option>
            </select>
          </div>
        </div>

        <button class="btn btn-success">simpan</button>
      </form>

      <div class="mt-4">
        <table class="table border">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama Tindakan</th>
              <th>Peraturan</th>
              <th>Harga</th>
              <th>Bayar</th>
              <th>Poli</th>
              <th>Keterangan</th>
              <th>Ket Gigi</th>
              <th>Create By</th>
              <th>Action</th>
              
            </tr>
          </thead>
        </table>
      </div>
    </div>


        </div>
      </div>

      <div v-if="currentTab === 'assesment'"> 
        <div class="row">
          <div class="col-6">
            <div class="row mb-3">
              <div class="col-3">
                <input type="text" class="form-control" placeholder="" disabled>
              </div>
              <div class="col-9">
                <input type="text" class="form-control" placeholder="" disabled>
              </div>
            </div>
             <div class="row  mb-3">
              <div class="col-3 d-flex flex-column">
                <label for=""> Alergi Makan</label>
                <input class="form-control"  type="text">
              </div>
              <div class="col-3  d-flex flex-column">
                <label for=""> Alergi Makan</label>
                <input  class="form-control" type="text">
              </div>
              <div class="col-6 d-flex flex-column">
                <label for=""> Alergi Makan</label>
                <input  class="form-control" type="text">
              </div>
            </div>
            <div class="row  mb-3">
              <div class="col-3  d-flex flex-column">
                <label for="">Kunjungan Khusus</label>
                <select class="form-control" name="" id="">
                  <option value="">Pilih</option>
                </select>
              </div>
              <div class="col-9  d-flex flex-column">
                <label for="">Keterangan</label>
                <input  class="form-control" type="text">

              </div>

            </div>
            <Link href="/surat-keterangan" class="btn btn-success">
            <i class="far fa-envelope me-2"></i>SIMPAN DIAGNOSA MEDIS
          </Link>
          </div>
          
          <div class="col-6">
            <div class="row mb-4">
              <div class="col-5">
                <Link href="/surat-keterangan" class="btn btn-info">
                <i class="fas fa-bars me-2"></i>Laboratorium
              </Link>
              </div>
              <div class="col-7 d-flex gap-3 flex-wrap">
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
            <div class="row">   
              <select class="form-control" name="" id="">
                <option value="">pilih</option>
              </select>
              <Link href="/surat-keterangan" class="btn btn-success mt-4">
                <i class="far fa-envelope me-2"></i>Simpan Diagnosa Keperawatan
              </Link>
            </div>
          
          </div>

          <div class="row mt-4">
            <div class="col-6">
              <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>No</th>
                  <th>	Nama Diagnosa Medis</th>
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


  </form>
</div>

      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayouts from '../../Components/Layouts/AppLayouts.vue'
import { ref } from 'vue'
const currentTab = ref('subjective')

</script>
