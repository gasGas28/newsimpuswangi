<template>
    <AppLayouts>
        <div class="card mx-auto" style="max-width: 1000px">
            <div class="card-header bg-primary">
                <h5 class="mb-0 text-white fw-bold">Pencarian Pasien</h5>
            </div>

            <div class="card-body">
                <form @submit.prevent="searchPasien">
                    <div class="row">
                        <!-- Kolom 1 -->
                        <div class="col-md-4">
                            <div
                                class="form-check mb-2"
                                v-for="field in kolom1"
                                :key="field.key"
                            >
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    :id="field.key"
                                    v-model="form[field.key].enabled"
                                />
                                <label class="form-check-label fw-bold" :for="field.key">
                                    {{ field.label }}
                                </label>

                                <input
                                    v-if="field.type === 'text'"
                                    type="text"
                                    class="form-control mt-1"
                                    v-model="form[field.key].value"
                                    :disabled="!form[field.key].enabled"
                                />
                            </div>
                        </div>

                        <!-- Kolom 2 -->
                        <div class="col-md-4">
                            <div
                                class="form-check mb-2"
                                v-for="field in kolom2"
                                :key="field.key"
                            >
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    :id="field.key"
                                    v-model="form[field.key].enabled"
                                />
                                <label class="form-check-label fw-bold" :for="field.key">

                                    {{ field.label }}
                                </label>

                                <input
                                    v-if="field.type === 'text'"
                                    type="text"
                                    class="form-control mt-1"
                                    v-model="form[field.key].value"
                                    :disabled="!form[field.key].enabled"
                                />

                                <select
                                    v-else-if="field.type === 'select'"
                                    class="form-select mt-1"
                                    v-model="form[field.key].value"
                                    :disabled="!form[field.key].enabled"
                                >
                                    <option value="">- Pilih -</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kolom 3 -->
                        <div class="col-md-4">
                            <div
                                class="form-check mb-2"
                                v-for="field in kolom3"
                                :key="field.key"
                            >
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    :id="field.key"
                                    v-model="form[field.key].enabled"
                                />
                                <label class="form-check-label fw-bold" :for="field.key">
                                    {{ field.label }}
                                </label>

                                <input
                                    v-if="field.type === 'text'"
                                    type="text"
                                    class="form-control mt-1"
                                    v-model="form[field.key].value"
                                    :disabled="!form[field.key].enabled"
                                />

                                <select
                                    v-else-if="field.type === 'select'"
                                    class="form-select mt-1"
                                    v-model="form[field.key].value"
                                    :disabled="!form[field.key].enabled"
                                >
                                    <option value="">- Pilih -</option>
                                    <option value="Kecamatan A">Kecamatan A</option>
                                    <option value="Kecamatan B">Kecamatan B</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="#" class="btn btn-primary bi bi-search-heart-fill">
                            CARI PASIEN
                        </a>
                        <a href="#" class="btn btn-success bi bi-plus-circle">
                            TAMBAH PASIEN BARU
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </AppLayouts>
</template>

<script setup>
import AppLayouts from "@/Components/Layouts/AppLayouts.vue";
import { reactive } from "vue";

// Form data dengan flag enabled dan nilai
const form = reactive({
    nama: { enabled: false, value: "" },
    nik: { enabled: false, value: "" },
    no_mr: { enabled: false, value: "" },
    no_bpjs: { enabled: false, value: "" },
    jenis_kelamin: { enabled: false, value: "" },
    kecamatan: { enabled: false, value: "" },
    alamat: { enabled: false, value: "" },
});

// Grup field berdasarkan kolom
const kolom1 = [
    { key: "nama", label: "Nama", type: "text" },
    { key: "nik", label: "NIK", type: "text" },
    { key: "no_mr", label: "NO MR", type: "text" },
];
const kolom2 = [
    { key: "no_bpjs", label: "NO BPJS", type: "text" },
    { key: "jenis_kelamin", label: "Jenis Kelamin", type: "select" },
];
const kolom3 = [
    { key: "kecamatan", label: "Kecamatan", type: "select" },
    { key: "alamat", label: "Alamat", type: "text" },
];

// Fungsi submit pencarian
function searchPasien() {
    const filtered = Object.fromEntries(
        Object.entries(form).filter(([_, f]) => f.enabled)
    );
    console.log("Data yang dikirim:", filtered);
}
</script>
