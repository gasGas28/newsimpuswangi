<template>
    <div> 
        <div class="row">
          <div class="col-6">
            <div class="text-decoration-underline fw-bold text-danger">
              <p>Diagnosa Medis</p>
            </div>
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
                <input class="form-control bg-warning"  type="text">
              </div>
              <div class="col-3  d-flex flex-column">
                <label for=""> Alergi Obat</label>
                <input  class="form-control bg-warning" type="text">
              </div>
              <div class="col-6 d-flex flex-column">
                <label for=""> Keterangan Alergi</label>
                <input  class="form-control bg-warning" type="text">
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
                <div class="text-decoration-underline fw-bold text-danger">
              <p>Pemeriksaan Penunjang</p>
            </div>
                <Link href="/surat-keterangan" class="btn btn-info">
                <i class="fas fa-bars me-2"></i>Laboratorium
              </Link>
              </div>
              <div class="col-7 ">
                <div class="text-decoration-underline fw-bold text-danger">
              <p>Form Lanjutan</p>
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
               <div class="text-decoration-underline fw-bold text-danger">
              <p>Diagnosa Keperawatan</p>
            </div>
               
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