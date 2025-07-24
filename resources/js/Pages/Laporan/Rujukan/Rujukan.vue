<template>
  <AppLayout title="Template Components">
    <div class="container py-4">
      <div class="row mb-4">
        <div class="col">
          <h1 class="fw-bold">Halaman Rujukan</h1>
          <p class="text-muted">Reusable components for your application</p>
        </div>
      </div>
<div class="card mb-4">
  <div class="card-body">
    <h5 class="mb-3 fw-bold">Data Pasien Rujukan</h5>
    <form class="row g-3 align-items-center">
      <div class="col-md-2 fw-bold">Puskesmas</div>
      <div class="col-md-4">
        <select class="form-select">
          <option>WONGSOREJO</option>
        </select>
      </div>
      <div class="w-100"></div>
      <div class="col-md-2 fw-bold">Tanggal Rujukan</div>
      <div class="col-md-4">
        <input type="date" class="form-control" value="2025-07-24"/>
      </div>
      <div class="w-100"></div>
      <div class="col-md-2 fw-bold">Jenis Kepesertaan</div>
      <div class="col-md-4">
        <select class="form-select">
          <option>- Pilih -</option>
        </select>
      </div>
      <div class="w-100"></div>
      <div class="col-md-2 fw-bold">Unit</div>
      <div class="col-md-4">
        <select class="form-select">
          <option>- Pilih -</option>
        </select>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="row mb-2">
      <div class="col-md-6 d-flex align-items-center">
        <label class="me-2">Show</label>
        <select class="form-select d-inline-block" style="width: 70px;">
          <option>10</option>
          <option>25</option>
          <option>50</option>
        </select>
        <label class="ms-2">entries</label>
      </div>
      <div class="col-md-6 text-end">
        <label class="me-2">Search:</label>
        <input type="text" class="form-control d-inline-block" style="width: 200px; display: inline-block;">
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-sm">
        <thead>
          <tr style="background: #8ff88f;">
            <th>NO</th>
            <th>TGL RUJUKAN</th>
            <th>NIK</th>
            <th>NAMA</th>
            <th>NAMA RUMAH SAKIT</th>
            <th>POLI TUJUAN</th>
            <th>KEPESERTAAN</th>
            <th>STATUS</th>
            <th>DILAYANI</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="9" class="text-center">No matching records found</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-2">
      <div>Showing 0 to 0 of 0 entries</div>
      <div>
        <button class="btn btn-outline-secondary btn-sm me-2" disabled>Previous</button>
        <button class="btn btn-outline-secondary btn-sm" disabled>Next</button>
      </div>
    </div>
  </div>
</div>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title fw-bold">Buttons</h5>
              <p class="card-text text-muted">Various button styles and variants.</p>
              <Link href="/template/button" class="btn btn-outline-primary">View Buttons</Link>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { Link } from '@inertiajs/vue3'
</script>