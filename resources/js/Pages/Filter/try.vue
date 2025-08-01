<template>
    <AppLayout>
        <div class="ms-4 me-4">
            <div class="row mb-4">
                <div class="col">
                    <h3 class="fw-bold">Filter Laporan</h3>
                    <p class="text-muted">Reusable components for your application</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white fw-bold">Filter Laporan</div>

                <div class="card-body">
                    <div class="row g-3">
                        <!-- Kolom 1 -->
                        <div class="col-md-3">
                            <div class="mb-2" v-for="field in kolom1" :key="field.key">
                                <div class="form-check mb-1">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        :id="field.key"
                                        v-model="form[field.key].enabled"
                                    />
                                    <label class="form-check-label" :for="field.key">{{ field.label }}</label>
                                </div>

                                <template v-if="field.type === 'select'">
                                    <select
                                        class="form-select"
                                        v-model="form[field.key].value"
                                        :disabled="!form[field.key].enabled"
                                    >
                                        <option v-for="option in field.options" :key="option" :value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </template>

                                <template v-else-if="field.type === 'date' || field.type === 'text'">
                                    <input
                                        :type="field.type"
                                        class="form-control"
                                        v-model="form[field.key].value"
                                        :disabled="!form[field.key].enabled"
                                    />
                                </template>
                            </div>
                        </div>

                        <!-- Kolom 2 -->
                        <div class="col-md-3" v-for="field in kolom2" :key="field.key">
                            <div class="form-check mb-1">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    :id="field.key"
                                    v-model="form[field.key].enabled"
                                />
                                <label class="form-check-label" :for="field.key">{{ field.label }}</label>
                            </div>

                            <template v-if="field.type === 'text'">
                                <input
                                    type="text"
                                    class="form-control mb-2"
                                    v-model="form[field.key].value"
                                    :disabled="!form[field.key].enabled"
                                />
                            </template>

                            <template v-else-if="field.type === 'select'">
                                <select
                                    class="form-select mb-2"
                                    v-model="form[field.key].value"
                                    :disabled="!form[field.key].enabled"
                                >
                                    <option v-for="option in field.options" :key="option" :value="option">
                                        {{ option }}
                                    </option>
                                </select>
                            </template>
                        </div>

                        <!-- Kolom 3 dan 4 dapat Anda lanjutkan dengan pola serupa -->
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
import { reactive } from "vue";

const form = reactive({
    puskesmas: { enabled: false, value: "WONGSOREJO" },
    tgl_awal: { enabled: false, value: "" },
    tgl_akhir: { enabled: false, value: "" },
    tempat_kunjungan: { enabled: false, value: "" },
    asal: { enabled: false, value: "" },
    rujuk_lanjut: { enabled: false, value: "" },
    unit: { enabled: false, value: "" },
    umur_awal: { enabled: false, value: "" },
    umur_akhir: { enabled: false, value: "" },
    diagnosa: { enabled: false, value: "" },
    tindakan: { enabled: false, value: "" },
    kepesertaan: { enabled: false, value: "" },
    kategori: { enabled: false, value: "" },
});

const kolom1 = [
    { key: "puskesmas", label: "Puskesmas", type: "select", options: ["WONGSOREJO"] },
    { key: "tgl_awal", label: "Tgl Awal", type: "date" },
    { key: "tgl_akhir", label: "Tgl Akhir", type: "date" },
    {
        key: "tempat_kunjungan",
        label: "Tempat Kunjungan",
        type: "select",
        options: [
            "- Pilih -", "SEMUA UNIT", "PUSKESMAS", "PUSTU", "POSYANDU",
            "POLINDES", "POSKESDES", "PUSLING", "POSKESTREN", "PONKESDES"
        ],
    },
    { key: "asal", label: "Asal", type: "select", options: ["- Pilih -"] },
    { key: "rujuk_lanjut", label: "Rujuk Lanjut", type: "select", options: ["- Pilih -"] },
];

const kolom2 = [
    { key: "unit", label: "Unit", type: "select", options: ["- Pilih -"] },
    { key: "umur_awal", label: "Umur Awal", type: "text" },
    { key: "umur_akhir", label: "Umur Akhir", type: "text" },
    { key: "diagnosa", label: "Diagnosa", type: "text" },
    { key: "tindakan", label: "Tindakan", type: "select", options: ["- Pilih -"] },
    { key: "kepesertaan", label: "Kepesertaan", type: "select", options: ["- Pilih -"] },
    { key: "kategori", label: "Kategori", type: "select", options: ["- Pilih -"] },
];
</script>
