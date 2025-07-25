<template>
    <AppLayouts>
        <div class="m-4 row g-3">
            <div class="col-md-3" v-for="angka in 8" :key="angka">
            <div class="card shadow-sm p-3 rounded">
                <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary me-3 w-auto h-auto p-3">
                    <i class="fas fa-users fs-3"></i>
                </div>
                <Link href="/simpus/umum"  class="text-decoration-none">
                    <div>
                        <div class="text-muted small mb-1">UMUM</div>
                        <div class="fw-bold fs-6 text-dark">145</div>
                    </div>
                </Link>

                </div>
            </div>
            </div>
        </div>
    </AppLayouts>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayouts from '../../Components/Layouts/AppLayouts.vue';
</script>
