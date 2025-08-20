<template>
     <div class="container">
       <div class="card">
        <div class="border rounded rounded-bottom-0 shadow-sm d-flex gap-4 p-3">
            <a href="" class="text-decoration-none text-primary" :class="{ 'text-primary fw-bold': currrentSubTabPlanning === 'tindakan' }" @click.prevent="currrentSubTabPlanning = 'tindakan'">Tindakan ></a>
            <a href="" class="text-decoration-none text-primary" :class="{ 'text-primary fw-bold': currrentSubTabPlanning === 'pengobatan' }" @click.prevent="currrentSubTabPlanning = 'pengobatan'">Pengobatan ></a>
        </div>
      <div class="card-body" v-if="currrentSubTabPlanning === 'tindakan'">
        <form action="">
        <slot></slot>
        <!-- Kode Tindakan + Tombol -->
        <div class="mb-3 row align-items-center">
          <label class="col-sm-2 col-form-label fw-bold">Kode Tindakan</label>
          <div class="col-sm-4">
            <div class="input-group">
              <input type="text" class="form-control bg-light" disabled />
              <button type="button" class="btn btn-info ">Cari</button>
              <button type="button" class="btn btn-danger ">Del</button>
            </div>
          </div>
        </div>

        <!-- Nama Tindakan -->
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label fw-bold">Nama Tindakan</label>
          <div class="col-sm-4">
            <input type="text" class="form-control bg-light" disabled />
          </div>
        </div>

        <!-- Nama Tindakan (Ind) -->
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label fw-bold">Nama Tindakan (Ind)</label>
          <div class="col-sm-4">
            <input type="text" class="form-control bg-light" disabled />
          </div>
        </div>

        <!-- Keterangan -->
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label fw-bold">Keterangan</label>
          <div class="col-sm-4">
            <textarea class="form-control" rows="2"></textarea>
          </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="row">
          <div class=" col-sm-4">
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </div>
      </form>
      <hr>
      <div class="table-responsive mt-4">
        <table class="table table-bordered table-sm text-center">
          <thead class="table-primary">
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama Tindakan</th>
              <th>Peraturan</th>
              <th>Harga</th>
              <th>Bayar</th>
              <th>Poli</th>
              <th>Keterangan</th>
              <th>Ket gigi</th>
              <th>Created by</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data rows here -->
          </tbody>
        </table>
      </div>
      </div>
      <div class="card-body" v-if="currrentSubTabPlanning === 'pengobatan'">
       <form action="">
         <div class="my-4">
        <!-- Baris: Pilihan Puyer -->
        <div class="row mb-3 align-items-center">
          <div class="col-sm-2 fw-bold w-auto">Puyer/Bukan puyer</div>
          <div class="col-sm-4">
            <select class="form-select">
              <option selected>BUKAN PUYER</option>
              <option>PUYER</option>
            </select>
          </div>
          <div class="col-sm-4 text-start">
            <button class="btn btn-success">Simpan Resep</button>
          </div>
        </div>
        </div>
       </form>
       <div class="table-responsive mt-4">
          <table class="table table-bordered table-sm text-center">
            <thead class="table-primary">
              <tr>
                <th>Poli</th>
                <th>Jenis/Nama Puyer</th>
                <th>Jumlah Obat/Puyer</th>
                <th>Dosis Pakai</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              
              <!-- Tambah baris lainnya sesuai data -->
            </tbody>
          </table>
        </div>
      </div>
      </div>
     </div>
</template>

<script setup>
import { ref } from 'vue';
const currrentSubTabPlanning = ref('tindakan')
</script>