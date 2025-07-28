<template>
    <AppLayout title="Loket">
        <div class="container py-4">
            <div class="card">
                <div class="card-body d-flex gap-2 justify-content-end">
                    <Link
                        :href="route('loket.search')"
                        class="btn btn-outline-primary"
                        >Cari Pasien</Link
                    >
                    <Link
                        :href="route('loket.pasien')"
                        class="btn btn-primary"
                        >Tambah Pasien</Link
                    >
                    <a href="/filter" class="btn btn-success">Filter Laporan</a>
                </div>
            </div>
            <!-- Form Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Form Loket</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">NO. MR</label>
                            <input
                                type="text"
                                class="form-control"
                                value="CER..I"
                                readonly
                            />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">NIK</label>
                            <input
                                type="text"
                                class="form-control"
                                value="CER..J"
                                readonly
                            />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">NO. BPJS</label>
                            <input
                                type="text"
                                class="form-control"
                                value="CER..J"
                                readonly
                            />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">NAMA</label>
                            <input
                                type="text"
                                class="form-control"
                                value="Ceri..."
                                readonly
                            />
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tanggal Kunjungan</label>
                            <input
                                type="text"
                                class="form-control"
                                value="28-07-2025"
                                readonly
                            />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori Unit</label>
                            <input
                                type="text"
                                class="form-control"
                                value="PUSKESMAS"
                                readonly
                            />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Unit</label>
                            <input
                                type="text"
                                class="form-control"
                                value="PUSKESMAS WONGSOREJO"
                                readonly
                            />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status Kartu</label>
                            <input
                                type="text"
                                class="form-control"
                                value="CER..J"
                                readonly
                            />
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat *</label>
                            <input
                                type="text"
                                class="form-control"
                                value="-"
                                readonly
                            />
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Wilayah *</label>
                            <select class="form-select">
                                <option selected>Pillin -</option>
                            </select>
                            <small class="text-muted"
                                >*) Pemilihan wilayah, sesuahkan dengan alamat
                                pasien II</small
                            >
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jenis Pengunjung *</label>
                            <input
                                type="text"
                                class="form-control"
                                value="Pengunjung Lama"
                                readonly
                            />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori *</label>
                            <input
                                type="text"
                                class="form-control"
                                value="NON BPJS"
                                readonly
                            />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jenis Kunjungan</label>
                            <input
                                type="text"
                                class="form-control"
                                value="Kunjungan Sakit"
                                readonly
                            />
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Poli Tujuan</label>
                            <input
                                type="text"
                                class="form-control"
                                value="Umum"
                                readonly
                            />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sub Kegiatan</label>
                            <input
                                type="text"
                                class="form-control"
                                value="RJTP (RAWAT JALAN)"
                                readonly
                            />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kode TKP</label>
                            <input
                                type="text"
                                class="form-control"
                                value=""
                                readonly
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div
                        class="d-flex justify-content-between align-items-center"
                    >
                        <h5 class="card-title mb-0">Data: Loket</h5>
                        <div class="d-flex align-items-center">
                            <span class="me-3">Show</span>
                            <select class="form-select form-select-sm w-auto">
                                <option>10 entries</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>TANGGAL NO. URUT (peare)</th>
                                    <th>NO ANTRIAN POLI</th>
                                    <th>NO. MR NAMA (UMUR) NIK</th>
                                    <th>ALAMAT KECAMATAN-DESA</th>
                                    <th>NO. BPJS STATUS</th>
                                    <th>POLI</th>
                                    <th>UNIT SUB UNIT</th>
                                    <th>ACTION PCARE KATEGORI</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>28-07-2025</td>
                                    <td>Ceti Nomor Antrian Poli</td>
                                    <td>
                                        0189163 DENIS HIDAYAT (37)
                                        3511010100286001
                                    </td>
                                    <td>
                                        PERUM, GRYA PESONA KARANGREJO BLOK DE-15
                                        RT 1 RW 4 Banyuwangi-Karangrejo No TLP :
                                        -
                                    </td>
                                    <td>Umum</td>
                                    <td>PUSKESMAS WONGSOREJO</td>
                                    <td>Bukan BPJS</td>
                                    <td>NON_BPJS</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            Action
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        class="d-flex justify-content-between align-items-center mt-3"
                    >
                        <div>Showing 1 to 1 of 1 entries</div>
                        <div>
                            <button
                                class="btn btn-sm btn-outline-secondary me-1"
                                disabled
                            >
                                Previous
                            </button>
                            <button
                                class="btn btn-sm btn-outline-secondary"
                                disabled
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Components/Layouts/AppLayouts.vue"
import { Link } from '@inertiajs/vue3'
</script>

<style scoped>
.card-header {
    font-weight: bold;
}
.form-label {
    font-weight: 500;
}
.table th {
    white-space: nowrap;
    background-color: #f8f9fa;
}
</style>
