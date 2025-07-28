<template>
    <AppLayout>
        <div class="ms-4 me-4">

            <div class="card">
                <div class="card-header fw-bold">Filter Laporan</div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Kolom 1 -->
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label class="form-label fw-bold">Puskesmas</label>
                            <select class="form-select">
                                <option>WONGSOREJO</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-bold">Tgl Awal</label>
                            <input type="date" class="form-control" />
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-bold">Tgl Akhir</label>
                            <input type="date" class="form-control" />
                        </div>

                        <label class="form-label mt-2 fw-bold">Tempat Kunjungan</label>
                        <select class="form-select mb-2">
                            <option>- Pilih -</option>
                            <option>SEMUA UNIT</option>
                            <option>PUSKESMAS</option>
                            <option>PUSTU</option>
                            <option>POSYANDU</option>
                            <option>POLINDES</option>
                            <option>POSKESDES</option>
                            <option>PUSLING</option>
                            <option>POSKESTREN</option>
                            <option>PONKESDES</option>
                        </select>
                        
                        <label class="form-label mt-2 fw-bold">Asal</label>
                        <select class="form-select mb-2">
                            <option>- Pilih -</option>
                        </select>
                        
                        <label class="form-label mt-2 fw-bold">Rujuk Lanjut</label>
                        <select class="form-select mb-2">
                            <option>- Pilih -</option>
                        </select>
                    </div>

                    <!-- Kolom 2 -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Unit</label>
                        <select class="form-select mb-2">
                            <option>- Pilih -</option>
                        </select>

                        <label class="form-label mt-2 fw-bold">Umur</label>
                        <div class="input-group mb-2">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="0"
                            />
                            <span class="input-group-text">s/d</span>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="0"
                                />
                            </div>
                            
                        <label class="form-label mt-2 fw-bold">Diagnosa</label>
                        <input
                            type="text"
                            class="form-control mb-2"
                            placeholder="0"
                            />
                            
                            <label class="form-label mt-2 fw-bold">Tindakan</label>
                            <select class="form-select mb-2">
                                <option>- Pilih -</option>
                        </select>
                        
                        <label class="form-label mt-2 fw-bold">Kepesertaan</label>
                        <select class="form-select mb-2">
                            <option>- Pilih -</option>
                        </select>
                        
                        <label class="form-label mt-2 fw-bold">Kategori</label>
                        <select class="form-select mb-2">
                            <option>- Pilih -</option>
                        </select>
                    </div>
                    
                    <!-- Kolom 3 -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Kecamatan</label>
                        <select class="form-select mb-2">
                            <option>- Pilih -</option>
                        </select>
                        
                        <label class="form-label mt-2 fw-bold">Desa</label>
                        <select class="form-select mb-2">
                            <option>- Pilih -</option>
                        </select>

                        <label class="form-label mt-2 fw-bold">Kunjungan</label>
                        <select class="form-select mb-2">
                            <option>- Pilih -</option>
                        </select>

                        <label class="form-label mt-2 fw-bold">Jenis Kelamin</label>
                        <select class="form-select mb-2">
                            <option>Laki-laki</option>
                            <option>Perempuan</option>
                        </select>
                        
                        <label class="form-label mt-2 fw-bold">Nama</label>
                        <input type="text" class="form-control" />
                    </div>
                    
                    <!-- Kolom 4 -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Kode</label>
                        <input
                        type="text"
                            class="form-control mb-2"
                            placeholder="0"
                            />
                            
                            <label class="form-label mt-2 fw-bold">Poli</label>
                            <select class="form-select mb-2">
                            <option>- Pilih -</option>
                        </select>

                        <label class="form-label mt-2 fw-bold">Jenis Layanan</label>
                        <select class="form-select mb-2">
                            <option>- pilih -</option>
                        </select>

                        <label class="form-label mt-2 fw-bold">Kunj Kasus</label>
                        <select class="form-select mb-2">
                            <option>- Pilih -</option>
                        </select>
                        
                        <label class="form-label mt-2 fw-bold">Isi Diagnosa</label>
                        <input
                        type="text"
                            class="form-control mb-2"
                            placeholder="Diagnosa"
                        />
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-start gap-2 mt-4">
                    <button class="btn btn-primary">
                        <i class="bi bi-eye"></i> Tampilkan Data
                    </button>
                    <button class="btn btn-info text-white">
                        <i class="bi bi-filetype-html"></i> Tampilkan Data HTML
                    </button>
                    <button class="btn btn-success">
                        <i class="bi bi-download"></i> Download Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Components/Layouts/AppLayouts.vue";
</script>
