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
                                <div class="col-md-6">
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
                                                <option>PUSKESMAS</option>
                                                <option>PUSTU</option>
                                                <option>POLINDES</option>
                                                <option>POSYANDU</option>
                                                <option>POSKESDES</option>
                                                <option>PUSLING</option>
                                                <option>POSKESTREN</option>
                                                <option>PONKESDES</option>
                                            </select>
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
</script>
