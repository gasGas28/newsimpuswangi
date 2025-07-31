<template>
  <AppLayout title="Loket">
    <div class="container py-4">
      <!-- Tombol Aksi -->
      <div class="d-flex justify-content-end mb-3 gap-2">
        <Link :href="route('loket.search')" class="btn btn-success text-white">
          <i class="bi bi-search me-1"></i> CARI PASIEN
        </Link>
        <Link :href="route('loket.pasien')" class="btn btn-primary text-white">
          <i class="bi bi-plus-lg me-1"></i> TAMBAH PASIEN
        </Link>
      </div>

      <!-- Form Loket -->
      <div class="card border-success mb-4">
        <div class="card-header bg-info text-white fw-bold">Form Loket</div>
        <div class="card-body">
          <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
              <div
                class="mb-2"
                v-for="(label, index) in ['NO. MR', 'NIK', 'NO. BPJS']"
                :key="index"
              >
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">{{ label }}</label>
                  </div>
                  <div class="col-8">
                    <div class="input-group">
                      <input type="text" class="form-control form-control-sm" />
                      <button class="btn btn-info text-white btn-sm">CEK ..!</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">NAMA</label>
                  </div>
                  <div class="col-8">
                    <div class="input-group">
                      <input type="text" class="form-control form-control-sm" disabled />
                      <button class="btn btn-danger text-white btn-sm">Cari.....</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Tanggal Kunjungan</label>
                  </div>
                  <div class="col-8">
                    <input type="date" class="form-control form-control-sm" />
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Kategori Unit</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm">
                      <option selected disabled>- Pilih Kategori Unit -</option>
                      <option>PUSKESMAS</option>
                      <option>KLINIK</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Unit</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm">
                      <option selected disabled>- Pilih Unit -</option>
                      <option>PUSKESMAS WONGSOREJO</option>
                      <option>PUSKESMAS PESANGGARAN</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Status Kartu</label>
                  </div>
                  <div class="col-8">
                    <div class="input-group">
                      <input type="text" class="form-control form-control-sm" disabled />
                      <button class="btn btn-info text-white btn-sm">CEK ..!</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Provider Kartu</label>
                  </div>
                  <div class="col-8">
                    <input type="text" class="form-control form-control-sm" disabled />
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Jenis Peserta</label>
                  </div>
                  <div class="col-8">
                    <input type="text" class="form-control form-control-sm" disabled />
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">No HP</label>
                  </div>
                  <div class="col-8">
                    <input type="text" class="form-control form-control-sm" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Alamat *</label>
                  </div>
                  <div class="col-8">
                    <textarea class="form-control form-control-sm" rows="2"></textarea>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Wilayah *</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm bg-warning">
                      <option selected>- Pilih -</option>
                    </select>
                    <small class="text-muted"
                      >*) Pemilihan wilayah, sesuaikan dengan alamat pasien !!</small
                    >
                  </div>
                </div>
              </div>

              <div
                class="mb-2"
                v-for="item in [
                  { label: 'Jenis Pengunjung *', value: 'Pengunjung Lama' },
                  { label: 'Kategori *', value: 'NON BPJS' },
                  { label: 'Jenis Kunjungan', value: 'Kunjungan Sakit' },
                  { label: 'Poli Tujuan', value: 'Umum' },
                  { label: 'Kode TKP', value: 'RJTP (RAWAT JALAN)' },
                ]"
                :key="item.label"
              >
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">{{ item.label }}</label>
                  </div>
                  <div class="col-8">
                    <select
                      class="form-select form-select-sm"
                      :class="item.label.includes('*') ? 'bg-warning' : ''"
                    >
                      <option selected>{{ item.value }}</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="row">
                  <div class="col-4">
                    <label class="form-label form-label-sm fw-bold">Sub Kegiatan</label>
                  </div>
                  <div class="col-8">
                    <select class="form-select form-select-sm" disabled>
                      <option selected>RJTP (RAWAT JALAN)</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tombol Bawah -->
          <div class="d-flex justify-content-end mt-4 gap-2">
            <button class="btn btn-success">RIWAYAT KUNJUNGAN</button>
            <button class="btn btn-primary">SIMPAN</button>
          </div>
        </div>
      </div>

      <!-- Daftar Loket -->
      <div class="card border-success">
        <div
          class="card-header bg-info text-white d-flex justify-content-between align-items-center"
        >
          <span class="fw-bold">Daftar Loket</span>
          <button class="btn btn-success btn-sm">Cek Pendaftaran Pcare</button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead class="text-center" style="background-color: #90ee90">
                <tr>
                  <th>TANGGAL<br />NO. URUT<br />(pcare)</th>
                  <th>NO ANTRIAN<br />POLI</th>
                  <th>NO. MR<br />NAMA (UMUR)<br />NIK</th>
                  <th>ALAMAT<br />KECAMATAN-DESA</th>
                  <th>NO. BPJS<br />STATUS</th>
                  <th>POLI</th>
                  <th>UNIT<br />SUB UNIT</th>
                  <th>ACTION<br />PCARE<br />KATEGORI</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="9" class="text-center">No matching records found</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div>Showing 0 to 0 of 0 entries</div>
            <div>
              <button class="btn btn-sm btn-outline-secondary me-1" disabled>Previous</button>
              <button class="btn btn-sm btn-outline-secondary" disabled>Next</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
  import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
  .form-label {
    font-weight: 500;
  }
  .table th,
  .table td {
    vertical-align: middle;
  }
</style>
