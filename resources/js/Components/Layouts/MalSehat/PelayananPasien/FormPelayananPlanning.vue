<template>
  <div class="container">
    <div class="card shadow-sm rounded-4 border-0">
      <!-- Header Tab -->
      <div class="card-header d-flex gap-4 p-3 bg-primary bg-opacity-10 border-0 rounded-top-4">
        <a href="" class="text-decoration-none" :class="{
          'text-primary fw-bold': currrentSubTabPlanning === 'tindakan',
          'text-secondary': currrentSubTabPlanning !== 'tindakan'
        }" @click.prevent="currrentSubTabPlanning = 'tindakan'">
          Tindakan >
        </a>
        <a href="" class="text-decoration-none" :class="{
          'text-primary fw-bold': currrentSubTabPlanning === 'pengobatan',
          'text-secondary': currrentSubTabPlanning !== 'pengobatan'
        }" @click.prevent="currrentSubTabPlanning = 'pengobatan'">
          Pengobatan >
        </a>
      </div>

      <!-- Tab: Tindakan -->
      <div class="card-body" v-if="currrentSubTabPlanning === 'tindakan'">
        <form>
          <slot></slot>

          <!-- Kode Tindakan -->
          <div class="mb-3 row align-items-center">
            <label class="col-sm-2 col-form-label text-end fw-bold">Kode Tindakan</label>
            <div class="col-sm-4">
              <div class="input-group">
                <input type="text" class="form-control bg-light" disabled />
                <button type="button" class="btn btn-primary">Cari</button>
                <button type="button" class="btn btn-outline-danger">Del</button>
              </div>
            </div>
          </div>

          <!-- Nama Tindakan -->
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label text-end fw-bold">Nama Tindakan</label>
            <div class="col-sm-4">
              <input type="text" class="form-control bg-light" disabled />
            </div>
          </div>

          <!-- Nama Tindakan (Ind) -->
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label text-end fw-bold">Nama Tindakan (Ind)</label>
            <div class="col-sm-4">
              <input type="text" class="form-control bg-light" disabled />
            </div>
          </div>

          <!-- Keterangan -->
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label text-end fw-bold">Keterangan</label>
            <div class="col-sm-4">
              <textarea class="form-control bg-primary bg-opacity-25 shadow-sm rounded-3" rows="2"></textarea>
            </div>
          </div>

          <!-- Tombol Simpan -->
          <div class="row">
            <div class="col-sm-4">
              <button type="submit" class="btn btn-success">Simpan</button>
            </div>
          </div>
        </form>

        <hr />

        <!-- Table Tindakan -->
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

      <!-- Tab: Pengobatan -->
      <div class="card-body" v-if="currrentSubTabPlanning === 'pengobatan'">
        <form>
          <div class="my-4">
            <!-- Pilihan Puyer -->
            <div class="row mb-3 align-items-center">
              <div class="col-sm-2 text-end fw-bold">Puyer/Bukan puyer</div>
              <div class="col-sm-4">
                <select class="form-select" id="ketPuyer" onchange="getPuyer()">
                  <option value="0" selected>BUKAN PUYER</option>
                  <option value="1">PUYER</option>
                </select>
                <input type="hidden" name="nama_puyer" id="nama_puyer" value="puyer" />
              </div>
            </div>

            <!-- Jumlah Puyer -->
            <div class="row mb-3 align-items-center" id="jumlahpuyerdiv">
              <div class="col-sm-2 text-end fw-bold">Jumlah Puyer</div>
              <div class="col-sm-4">
                <input type="text" name="jumlah_puyer" id="jumlah_puyer" class="form-control" style="width:100px"
                  onkeypress="return hanyaAngka(event)" />
              </div>
            </div>

            <!-- Dosis Puyer -->
            <div class="row mb-3 align-items-center" id="dosispakaipuyerdiv">
              <div class="col-sm-2 text-end fw-bold">Dosis pakai puyer</div>
              <div class="col-sm-4 d-flex align-items-center">
                <input type="text" name="dosis_pakai_puyer" id="dosis_pakai_puyer" class="form-control me-2"
                  style="width:80px" onkeypress="return hanyaAngka(event)" /> x Sehari,
                setiap
                <input type="text" name="tiapJam" id="tiapJam" class="form-control ms-2" style="width:80px"
                  onkeypress="return hanyaAngka(event)" /> Jam sekali
              </div>
            </div>

            <!-- Tambahan: Waktu -->
            <div class="row mb-3 align-items-center" id="tambahanPuyerDiv">
              <div class="col-sm-2 text-end fw-bold">Waktu</div>
              <div class="col-sm-6">
                <div class="form-check form-check-inline">
                  <input type="checkbox" class="form-check-input" name="waktu[]" value="pagi" id="pagi">
                  <label class="form-check-label" for="pagi">Pagi</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="checkbox" class="form-check-input" name="waktu[]" value="siang" id="siang">
                  <label class="form-check-label" for="siang">Siang</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="checkbox" class="form-check-input" name="waktu[]" value="malam" id="malam">
                  <label class="form-check-label" for="malam">Malam</label>
                </div>
              </div>
            </div>

            <!-- Tambahan: Kondisi -->
            <div class="row mb-3 align-items-center">
              <div class="col-sm-2 text-end fw-bold">Kondisi</div>
              <div class="col-sm-6">
                <div class="form-check form-check-inline">
                  <input type="checkbox" class="form-check-input" name="kondisi[]" value="sebelum makan" id="sebelum">
                  <label class="form-check-label" for="sebelum">Sebelum makan</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="checkbox" class="form-check-input" name="kondisi[]" value="saat makan" id="saat">
                  <label class="form-check-label" for="saat">Saat makan</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="checkbox" class="form-check-input" name="kondisi[]" value="setelah makan" id="setelah">
                  <label class="form-check-label" for="setelah">Setelah makan</label>
                </div>
              </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="row">
              <div class="col-sm-12 text-end">
                <button type="button" class="btn btn-success" onclick="simpanResep()">
                  <i class="far fa-save me-2"></i>Simpan Resep
                </button>
              </div>
            </div>
          </div>
        </form>

        <hr />

        <!-- Table Pengobatan -->
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
              <!-- Data rows here -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
const currrentSubTabPlanning = ref("tindakan");
</script>
