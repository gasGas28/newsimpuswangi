<template>
    <AppLayout>
        <div class="ms-4 me-4">
            <div class="card">
                <h6 class="card-header bg-primary text-light text-start">Data Pasien</h6>
                <div class="card-body">
                    <div class="container">
                        <form>
                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="row mb-2 align-items-center">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >NIK</label
                                        >
                                    <div class="col-sm-8 d-flex">
                                        <input
                                        type="text"
                                        class="form-control me-2"
                                        />
                                        <button class="btn btn-info text-white">
                                            CEK..!
                                        </button>
                                    </div>
                                </div>

                                <div class="row mb-2 align-items-center">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Noka BPJS</label
                                    >
                                    <div class="col-sm-8 d-flex">
                                        <input
                                            type="text"
                                            class="form-control me-2"
                                        />
                                        <button class="btn btn-info text-white">
                                            CEK..!
                                        </button>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                    >kdProvider</label
                                    >
                                    <div class="col-sm-8">
                                        <input
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Nama Lengkap</label
                                    >
                                    <div class="col-sm-8">
                                        <input
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                                
                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Tempat, Tgl Lahir</label
                                    >
                                    <div class="col-sm-8 d-flex gap-1">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Tempat"
                                        />
                                        <select class="form-select">
                                            <option>24</option>
                                        </select>
                                        <select class="form-select">
                                            <option>Juli</option>
                                        </select>
                                        <select class="form-select">
                                            <option>2024</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Jenis Kelamin</label
                                    >
                                    <div class="col-sm-8">
                                        <select class="form-select">
                                            <option>Laki-laki</option>
                                            <option>Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Jenis Pekerjaan</label
                                    >
                                    <div class="col-sm-8">
                                        <input
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Agama</label
                                    >
                                    <div class="col-sm-8">
                                        <input
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Hub. Keluarga</label
                                    >
                                    <div class="col-sm-8">
                                        <input
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >IHS Pasien</label
                                    >
                                    <div class="col-sm-8">
                                        <input
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >No KK</label
                                    >
                                    <div class="col-sm-8">
                                        <input
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Nama KK</label
                                    >
                                    <div class="col-sm-8">
                                        <input
                                            type="text"
                                            class="form-control"
                                            style="background-color: yellow"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Provinsi</label
                                    >
                                    <div class="col-sm-8">
                                        <select class="form-select">
                                            <option>JAWA TIMUR</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Kabupaten</label
                                    >
                                    <div class="col-sm-8">
                                        <select class="form-select">
                                            <option>KAB. BANYUWANGI</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Kecamatan *</label
                                    >
                                    <div class="col-sm-8">
                                        <select class="form-select"></select>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Desa/Kelurahan *</label
                                        >
                                    <div class="col-sm-8">
                                        <select class="form-select"></select>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >Alamat</label
                                    >
                                    <div class="col-sm-8">
                                        <input
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >RT/RW</label
                                        >
                                    <div class="col-sm-8 d-flex gap-2">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="RT"
                                        />
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="RW"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label fw-bold"
                                        >HP</label
                                    >
                                    <div class="col-sm-8">
                                        <input
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">
                                SIMPAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div>
            
        </div>
    </div>
    </AppLayout>
</template>
<script setup>
import AppLayout from "../../Components/Layouts/AppLayouts.vue";
</script>
