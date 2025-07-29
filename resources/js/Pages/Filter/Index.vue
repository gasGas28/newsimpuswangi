<template>
    <AppLayout>
        <div class="container py-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-start">
                    <h5 class="text-white">Filter Laporan</h5>
                </div>
                <div class="card-body">
                    <div class="container">
                        <form>
                            <div class="row">
                                <!-- Kolom Pertama -->
                                <div class="col-md-4">
                                    <div class="row mb-2 align-items-center">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Puskesmas</label
                                        >
                                        <div class="col-sm-8 d-flex">
                                            <select class="form-select">
                                                <option>Wongsorejo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Tanggal Awal</label
                                        >
                                        <div class="col-sm-8">
                                            <input
                                                type="date"
                                                class="form-control"
                                            />
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Tanggal Akhir</label
                                        >
                                        <div class="col-sm-8">
                                            <input
                                                type="date"
                                                class="form-control"
                                            />
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Tempat Kunjungan</label
                                        >
                                        <div class="col-sm-8">
                                            <select class="form-select mb-2">
                                                <option></option>
                                                <option>PUSKESMAS</option>
                                                <option>PUSTU</option>
                                                <option>POLINDES</option>
                                                <option>POSYANDU</option>
                                                <option>POSKESDES</option>
                                                <option>PUSLING</option>
                                                <option>POSKESTREN</option>
                                                <option>PONKESDES</option>
                                            </select>
                                            <select class="form-select">
                                                <option>--Pilih--</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-2 align-items-center">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Kunjungan Kasus</label
                                        >
                                        <div class="col-sm-8">
                                            <select class="form-select mb-2">
                                                <option></option>
                                                <option>Kasus Lama</option>
                                                <option>Kasus Baru</option>
                                                <option>
                                                    Kunjungan Kasus Lama
                                                </option>
                                                <option>
                                                    Kunjungan Kasus Baru
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-2 align-items-center">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Kunjungan</label
                                        >
                                        <div class="col-sm-8">
                                            <select class="form-select mb-2">
                                                <option></option>
                                                <option>Lama</option>
                                                <option>Kasus Baru</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- KOlom Kedua -->
                                <div class="col-md-4">
                                    <div class="row mb-2">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Nama</label
                                        >
                                        <div class="col-sm-8">
                                            <input
                                                type="text"
                                                class="form-control"
                                            />
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Umur</label
                                        >
                                        <div class="col-auto">
                                            <input
                                                type="text"
                                                class="form-control text-center"
                                                placeholder="0"
                                                style="width: 60px"
                                            />
                                        </div>
                                        <div class="col-auto fw-bold">s/d</div>
                                        <div class="col-auto">
                                            <input
                                                type="text"
                                                class="form-control text-center"
                                                placeholder="0"
                                                style="width: 60px"
                                            />
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Jenis Kelamin</label
                                        >
                                        <div class="col-sm-8 mt-2">
                                            <select class="form-select">
                                                <option></option>
                                                <option>Laki-Laki</option>
                                                <option>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Asal</label
                                        >
                                        <div class="col-sm-8">
                                            <select class="form-select">
                                                <option></option>
                                                <option>Dalam Wilayah</option>
                                                <option>Luar Wilayah</option>
                                                <option>Luar Kabupaten</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Kecamatan</label
                                        >
                                        <div class="col-sm-8">
                                            <select class="form-select">
                                                <option></option>
                                                <option>Kecamatan</option>
                                                <option>Kecamatan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Desa</label
                                        >
                                        <div class="col-sm-8">
                                            <select class="form-select">
                                                <option></option>
                                                <option>Desa</option>
                                                <option>Desa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Kepesertaan</label
                                        >
                                        <div class="col-sm-8">
                                            <select class="form-select">
                                                <option></option>
                                                <option>BPJS</option>
                                                <option>Non BPJS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Kategori</label
                                        >
                                        <div class="col-sm-8">
                                            <select class="form-select">
                                                <option></option>
                                                <option>Non BPJS</option>
                                                <option>JKN PBI</option>
                                                <option>Non JKN PBI</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Kolom Ketiga -->
                                <div class="col-md-4">
                                    <div class="row mb-2">
                                        <label
                                            class="col-sm-4 col-form-label fw-bold"
                                            >Isi Diagnosa</label
                                        >
                                        <div class="col-sm-8">
                                            <select class="form-select">
                                                <option>--Pilih--</option>
                                                <option>Diagnosa Kosong</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label class="col-sm-4 col-form-label fw-bold">Diagnosa</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label class="col-sm-4 col-form-label fw-bold">Tindakan</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="d-flex justify-content-start gap-2 mt-4"
                            >
                                <button class="btn btn-primary">
                                    <i class="bi bi-eye"></i> Tampilkan Data
                                </button>
                                <button class="btn btn-info text-white">
                                    <i class="bi bi-filetype-html"></i>
                                    Tampilkan Data HTML
                                </button>
                                <button class="btn btn-success">
                                    <i class="bi bi-download"></i> Download
                                    Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Components/Layouts/AppLayouts.vue";
import { reactive } from "vue";
import Modal from "@/Components/Layouts/Modal.vue";
</script>
