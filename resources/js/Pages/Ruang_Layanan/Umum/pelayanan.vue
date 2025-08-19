<template>
  <AppLayouts>
    <div class="card m-4">
      <div class="card-header d-flex justify-content-between">
        <h1 class="fs-5">BP Umum</h1>
        <Link :href="route('ruang-layanan.umum')" class="btn btn-primary">Kembali</Link>
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
                <span class="fw-bold text-dark"> DUSUN KRAJAN II, Genteng - Setail </span>
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
        <div class="mt-4" v-if="!isMelayani">
          <button class="btn btn-primary" @click.prevent="isMelayani = true">
            <i class="far fa-envelope me-2"></i>MULAI MELAYANI PEMERIKSAAN PASIEN
          </button>
        </div>

        <hr />
        <div class="card mt-4" v-if="isMelayani"> 
        <div class="card-header p-4 d-flex gap-4 align-items-center bg-info-subtle" >
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'subjective' }" @click.prevent="currentTab = 'subjective'">Subjective</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'objective' }" @click.prevent="currentTab = 'objective'">Objective</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'assesment' }" @click.prevent="currentTab = 'assesment'">Assessment</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'planning' }" @click.prevent="currentTab = 'planning'">Planning</a>
          <a href="#" class="text-decoration-none" :class="{ 'fw-bold text-primary': currentTab === 'status_pasien' }" @click.prevent="currentTab = 'status_pasien'">Status Pasien</a>
          <Link href="#" class="btn btn-success">Kirim RME v.1 ke SATU SEHAT</Link>
        </div>

          <div class="m-4 row gx-5">
            <div class="container" v-if="currentTab === 'subjective'">
              <!-- Judul -->
              <div class="text-decoration-underline fw-bold text-danger mb-4">
                <p>Anamnesa</p>
              </div>
              <form>
                <div class="row g-4">
                  <!-- PASANGAN INPUT KIRI - KANAN -->
                  <div class="col-12 col-md-6">
                    <div class="row mb-3">
                      <label class="col-sm-4 col-form-label fw-bold text-end">Tgl Anamnesa</label>
                      <div class="col-sm-8">
                        <input type="date" class="form-control" />
                      </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                      <label class="col-sm-4 col-form-label fw-bold text-end"
                        >Keluhan Utama/Keperluan</label
                      >
                      <div class="col-sm-8">
                        <textarea class="form-control" rows="2"></textarea>
                      </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                      <label class="col-sm-4 col-form-label fw-bold text-end"
                        >Keluhan Tambahan</label
                      >
                      <div class="col-sm-8">
                        <textarea class="form-control" rows="2"></textarea>
                      </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                      <label class="col-sm-4 col-form-label fw-bold text-end"
                        >Riwayat Penyakit Sekarang</label
                      >
                      <div class="col-sm-8">
                        <textarea class="form-control" rows="2"></textarea>
                      </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                      <label class="col-sm-4 col-form-label fw-bold text-end"
                        >Riwayat Penyakit Dahulu</label
                      >
                      <div class="col-sm-8">
                        <textarea class="form-control" rows="2"></textarea>
                      </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                      <label class="col-sm-4 col-form-label fw-bold text-end"
                        >Riwayat Penyakit Keluarga</label
                      >
                      <div class="col-sm-8">
                        <textarea class="form-control" rows="2"></textarea>
                      </div>
                    </div>
                  </div>

                  <!-- KOLOM KANAN -->
                  <div class="col-12 col-md-6">
                    <div class="row mb-3 align-items-center">
                      <label class="col-sm-4 col-form-label fw-bold text-end">Alergi Makanan</label>
                      <div class="col-sm-8">
                        <select class="form-select bg-warning">
                          <option>Tidak Ada Alergi</option>
                          <option>Alergi 1</option>
                          <option>Alergi 2</option>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                      <label class="col-sm-4 col-form-label fw-bold text-end">Alergi Obat</label>
                      <div class="col-sm-8">
                        <select class="form-select bg-warning">
                          <option>Tidak Ada Alergi</option>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                      <label class="col-sm-4 col-form-label fw-bold text-end"
                        >Keterangan Alergi</label
                      >
                      <div class="col-sm-8">
                        <textarea class="form-control bg-warning" rows="2"></textarea>
                      </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                      <label class="col-sm-4 col-form-label fw-bold text-end"
                        >Tindakan/Terapi</label
                      >
                      <div class="col-sm-8">
                        <textarea class="form-control" rows="2"></textarea>
                      </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                      <label class="col-sm-4 col-form-label fw-bold text-end"
                        >Obat yang Sering Digunakan</label
                      >
                      <div class="col-sm-8">
                        <textarea class="form-control" rows="2"></textarea>
                      </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                      <label class="col-sm-4 col-form-label fw-bold text-end"
                        >Obat yang Sering Dikonsumsi</label
                      >
                      <div class="col-sm-8">
                        <textarea class="form-control" rows="2"></textarea>
                      </div>
                    </div>
                    <div class="d-flex justify-content-end">
                      <button class="btn btn-success btn"><i class="bi bi-save"></i> SIMPAN</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="container" v-if="currentTab === 'objective'">
              <div class="bg-warning d-inline-block fw-bold mb-4">
                <span>Tombol "Simpan" ada di tab "Tenaga medis"</span>
              </div>
              <div class="card">
                <div class="card-header d-flex gap-4 p-3">
                  <a
                    href=""
                    class="text-decoration-none text-danger"
                    :class="{
                      'text-danger fw-bold': currrentSubTabObjective === 'pemeriksaan_fisik',
                    }"
                    @click.prevent="currrentSubTabObjective = 'pemeriksaan_fisik'"
                    >Pemeriksaan Fisik ></a
                  >
                  <a
                    href=""
                    class="text-decoration-none text-danger"
                    :class="{
                      'text-danger fw-bold': currrentSubTabObjective === 'status_generalis',
                    }"
                    @click.prevent="currrentSubTabObjective = 'status_generalis'"
                    >Status Generalis ></a
                  >
                  <a
                    href=""
                    class="text-decoration-none text-danger"
                    :class="{ 'text-danger fw-bold': currrentSubTabObjective === 'tenaga_medis' }"
                    @click.prevent="currrentSubTabObjective = 'tenaga_medis'"
                    >Tenaga Medis ></a
                  >
                </div>
                <div class="card-body row" v-if="currrentSubTabObjective === 'pemeriksaan_fisik'">
                  <div class="col-4">
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">Tanggal Anamnesa</label>
                      <input type="date" id="tanggal-anamnesa" class="form-control" />
                    </div>
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">Keadaan Umum</label>
                      <div class="d-flex gap-4">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            name="radioDefault"
                            id="radioDefault1"
                          />
                          <label class="form-check-label" for="radioDefault1"> Baik </label>
                        </div>
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            name="radioDefault"
                            id="radioDefault2"
                            checked
                          />
                          <label class="form-check-label" for="radioDefault2"> Sedang </label>
                        </div>
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            name="radioDefault"
                            id="radioDefault2"
                            checked
                          />
                          <label class="form-check-label" for="radioDefault2"> Lemah </label>
                        </div>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">Kesadaran</label>
                      <select type="text" id="tanggal-anamnesa" class="form-control"></select>
                    </div>
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">IMT</label>
                      <input type="text" id="tanggal-anamnesa" class="form-control bg-warning" />
                    </div>
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">Keterangan IMT</label>
                      <input type="text" id="tanggal-anamnesa" class="form-control bg-warning" />
                    </div>
                  </div>

                  <div class="col-4">
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">Tinggi Badan (cm)</label>
                      <input type="text" id="tanggal-anamnesa" class="form-control" />
                    </div>
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">Berat Badan (kg)</label>
                      <input type="text" id="tanggal-anamnesa" class="form-control" />
                    </div>
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">Lingkar Perut (cm)</label>
                      <input type="text" id="tanggal-anamnesa" class="form-control" />
                    </div>
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">Lingkar Lengan (cm)</label>
                      <input type="text" id="tanggal-anamnesa" class="form-control" />
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">- Sistole (mmHg)</label>
                      <input type="text" id="tanggal-anamnesa" class="form-control" />
                    </div>
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">- Diastole (mmHg)</label>
                      <input type="text" id="tanggal-anamnesa" class="form-control" />
                    </div>
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">Resp. Rate (bpm)</label>
                      <input type="text" id="tanggal-anamnesa" class="form-control" />
                    </div>
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">Heart Rate (bpm)</label>
                      <input type="text" id="tanggal-anamnesa" class="form-control" />
                    </div>
                    <div class="mb-3">
                      <label for="tanggal-anamnesa" class="form-label">Suhu (°C)</label>
                      <input type="text" id="tanggal-anamnesa" class="form-control" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="container" v-if="currrentSubTabObjective === 'status_generalis'">
              <div class="row my-4">
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
                      <input type="text" class="form-control bg-light" readonly />
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
                      <input type="text" class="form-control bg-light" readonly />
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
                      <input type="text" class="form-control bg-light" readonly />
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
                      <input type="text" class="form-control bg-light" readonly />
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
                      <input type="text" class="form-control bg-light" readonly />
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
                      <input type="text" class="form-control bg-light" readonly />
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
                      <input type="text" class="form-control bg-light" readonly />
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
                      <input type="text" class="form-control bg-light" readonly />
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
                      <input type="text" class="form-control bg-light" readonly />
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
                      <input type="text" class="form-control bg-light" readonly />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="container" v-if="currrentSubTabObjective === 'tenaga_medis'">
              <div class="row my-2">
                <div class="col-6">
                  <label for="" class="fw-bold">Tenaga Media Askep</label>
                  <select class="form-control my-3" name="" id="">
                    <option value="">-Pilih_</option>
                  </select>
                  <button class="btn btn-success">Simpan</button>
                </div>
              </div>
            </div>
            <div v-if="currentTab === 'assesment'">
              <div class="row">
                <div class="col-6">
                  <div class="text-decoration-underline fw-bold text-danger">
                    <p>Diagnosa Medis</p>
                  </div>
                  <div class="row mb-3">
                    <div class="col-3">
                      <input type="text" class="form-control" placeholder="" disabled />
                    </div>
                    <div class="col-9">
                      <input type="text" class="form-control" placeholder="" disabled />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-3 d-flex flex-column">
                      <label for=""> Alergi Makan</label>
                      <input class="form-control bg-warning" type="text" />
                    </div>
                    <div class="col-3 d-flex flex-column">
                      <label for=""> Alergi Obat</label>
                      <input class="form-control bg-warning" type="text" />
                    </div>
                    <div class="col-6 d-flex flex-column">
                      <label for=""> Keterangan Alergi</label>
                      <input class="form-control bg-warning" type="text" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-3 d-flex flex-column">
                      <label for="">Kunjungan Khusus</label>
                      <select class="form-control" name="" id="">
                        <option value="">Pilih</option>
                      </select>
                    </div>
                    <div class="col-9 d-flex flex-column">
                      <label for="">Keterangan</label>
                      <input class="form-control" type="text" />
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
                    <div class="col-7">
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
            <div class="container" v-if="currentTab === 'planning'">
              <div class="card-header d-flex gap-4 p-3 my-2">
                <a
                  href=""
                  class="text-decoration-none text-danger"
                  :class="{ 'text-danger fw-bold': currrentSubTabPlanning === 'tindakan' }"
                  @click.prevent="currrentSubTabPlanning = 'tindakan'"
                  >Tindakan ></a
                >
                <a
                  href=""
                  class="text-decoration-none text-danger"
                  :class="{ 'text-danger fw-bold': currrentSubTabPlanning === 'pengobatan' }"
                  @click.prevent="currrentSubTabPlanning = 'pengobatan'"
                  >Pengobatan ></a
                >
              </div>
              <div v-if="currrentSubTabPlanning === 'tindakan'">
                <form action="">
                  <!-- Kode Tindakan + Tombol -->
                  <div class="mb-3 row align-items-center">
                    <label class="col-sm-2 col-form-label text-end fw-bold">Kode Tindakan</label>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <input type="text" class="form-control bg-light" disabled />
                        <button type="button" class="btn btn-info">Cari</button>
                        <button type="button" class="btn btn-danger">Del</button>
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
                    <label class="col-sm-2 col-form-label text-end fw-bold"
                      >Nama Tindakan (Ind)</label
                    >
                    <div class="col-sm-4">
                      <input type="text" class="form-control bg-light" disabled />
                    </div>
                  </div>

                  <!-- Keterangan -->
                  <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label text-end fw-bold">Keterangan</label>
                    <div class="col-sm-4">
                      <textarea class="form-control" rows="2"></textarea>
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
                <div class="table-responsive mt-4">
                  <table class="table table-bordered table-sm">
                    <thead class="table-light">
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
              <div v-if="currrentSubTabPlanning === 'pengobatan'">
                <div class="mt-4">
                  <!-- Baris: Pilihan Puyer -->
                  <div class="row mb-3 align-items-center">
                    <div class="col-sm-2 text-end fw-bold">Puyer/Bukan puyer</div>
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
                  <div class="table-responsive">
                    <table class="table table-bordered text-center">
                      <thead class="table-secondary">
                        <tr>
                          <th>No</th>
                          <th>Nama Obat</th>
                          <th>Jumlah</th>
                          <th>Signa</th>
                          <th>Aturan Pakai</th>
                          <th>Keterangan</th>
                          <th>Aksi</th>
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
            <div class="container mt-4" v-if="currentTab === 'status_pasien'">
              <h5><u>Rujukan</u></h5>

              <!-- FORM -->
              <form>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label text-end fw-bold">Status Pulang</label>
                  <div class="col-sm-4">
                    <select class="form-select">
                      <option selected>Berobat Jalan</option>
                      <option>Rujuk</option>
                      <option>Pulang Paksa</option>
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label text-end fw-bold">Tenaga Medis</label>
                  <div class="col-sm-4">
                    <select class="form-select">
                      <option selected>- pilih -</option>
                      <option>dr. Agus</option>
                      <option>dr. Sari</option>
                    </select>
                  </div>
                </div>

        <div class="row mb-3">
          <div class=" col-sm-4">
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </div>
      </form>
      <hr>
      <!-- TABLE -->
      <div class="table-responsive mt-4">
        <table class="table table-bordered table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Asal Poli</th>
              <th>Keterangan</th>
              <th>Poli Tujuan</th>
              <th>Tenaga Medis</th>
              <th>Created By</th>
              <th>Mulai melayani</th>
              <th>Selesai melayani</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td><span class="badge bg-success">Umum</span></td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>user1</td>
              <td>2025-07-29 08:17:36</td>
              <td>-</td>
              <td><button class="btn btn-outline-secondary btn-sm">Poli awal</button></td>
            </tr>
          </tbody>
        </table>
      </div>
      </div>



  </div>
</div>

      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
  import { Link } from '@inertiajs/vue3';
  import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';
  import { ref } from 'vue';

  const currentTab = ref('subjective');
  const currrentSubTabObjective = ref('pemeriksaan_fisik');
  const currrentSubTabPlanning = ref('tindakan');
  const isMelayani = ref(false);
</script>
