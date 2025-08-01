<template>
  <AppLayout title="Template Components">
    <div class="container px-2 py-2">
      <div class="card">
        <div class="card-body py-3 px-3">
          <h6 class="fw-bold mb-3">Data Pasien Rujukan</h6>
          <form class="row gx-2 gy-2 align-items-center">
            <div class="col-auto fw-bold">Puskesmas</div>
            <div class="col-auto">
              <select class="form-select form-select-sm">
                <option>WONGSOREJO</option>
              </select>
            </div>

            <div class="col-12"></div>

            <div class="col-auto fw-bold">Tanggal Rujukan</div>
            <div class="col-auto">
              <input type="date" class="form-control form-control-sm" value="2025-07-24" />
            </div>

            <div class="col-12"></div>

            <div class="col-auto fw-bold">Jenis Kepesertaan</div>
            <div class="col-auto">
              <select class="form-select form-select-sm">
                <option>- Pilih -</option>
              </select>
            </div>

            <div class="col-12"></div>

            <div class="col-auto fw-bold">Unit</div>
            <div class="col-auto">
              <select class="form-select form-select-sm">
                <option>- Pilih -</option>
              </select>
            </div>
          </form>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-body py-3 px-3">
          <div class="row gx-2 gy-2 mb-2">
            <div class="col-md-6 d-flex align-items-center">
              <label class="me-1 small">Show</label>
              <select class="form-select form-select-sm me-1" style="width: 60px">
                <option>10</option>
                <option>25</option>
                <option>50</option>
              </select>
              <label class="small">entries</label>
            </div>
            <div class="col-md-6 text-start text-md-end">
              <label class="me-1 small">Search:</label>
              <input
                type="text"
                class="form-control form-control-sm d-inline-block"
                style="width: 180px"
              />
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead class="table-success">
                <tr>
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
                  <td colspan="9" class="text-center small">No matching records found</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-2 small">
            <div>Showing 0 to 0 of 0 entries</div>
            <div>
              <button class="btn btn-outline-secondary btn-sm me-2" disabled>Previous</button>
              <button class="btn btn-outline-secondary btn-sm" disabled>Next</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
</script>
