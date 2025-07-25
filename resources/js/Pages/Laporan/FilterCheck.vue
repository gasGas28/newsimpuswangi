<template>
    <AppLayout>
        <div class="card">
            <div class="card-header fw-bold">Filter Laporan</div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Kolom 1 -->
                    <div class="col-md-3">
                        <!-- Contoh: Puskesmas -->
                        <div class="mb-2">
                            <div class="form-check mb-1">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="enablePuskesmas"
                                    v-model="enabledFields.puskesmas"
                                />
                                <label class="form-check-label" for="enablePuskesmas">
                                    Aktifkan Puskesmas
                                </label>
                            </div>
                            <label class="form-label fw-bold">Puskesmas</label>
                            <select
                                class="form-select"
                                :disabled="!enabledFields.puskesmas"
                            >
                                <option>WONGSOREJO</option>
                            </select>
                        </div>

                        <!-- Tgl Awal -->
                        <div class="mb-2">
                            <div class="form-check mb-1">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="enableTglAwal"
                                    v-model="enabledFields.tglAwal"
                                />
                                <label class="form-check-label" for="enableTglAwal">
                                    Aktifkan Tanggal Awal
                                </label>
                            </div>
                            <label class="form-label fw-bold">Tgl Awal</label>
                            <input
                                type="date"
                                class="form-control"
                                :disabled="!enabledFields.tglAwal"
                            />
                        </div>

                        <!-- Tgl Akhir -->
                        <div class="mb-2">
                            <div class="form-check mb-1">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="enableTglAkhir"
                                    v-model="enabledFields.tglAkhir"
                                />
                                <label class="form-check-label" for="enableTglAkhir">
                                    Aktifkan Tanggal Akhir
                                </label>
                            </div>
                            <label class="form-label fw-bold">Tgl Akhir</label>
                            <input
                                type="date"
                                class="form-control"
                                :disabled="!enabledFields.tglAkhir"
                            />
                        </div>

                        <!-- Tambahkan checkbox serupa untuk field lainnya -->
                    </div>

                    <!-- Kolom 2, 3, dan 4 mengikuti pola yang sama -->
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
    </AppLayout>
</template>
<script setup>
import { reactive } from "vue";
import AppLayout from "@/Components/Layouts/AppLayouts.vue";

// Reactive flags untuk mengaktifkan tiap field
const enabledFields = reactive({
    puskesmas: false,
    tglAwal: false,
    tglAkhir: false,
    // Tambahkan untuk field lainnya jika diperlukan
});
</script>
