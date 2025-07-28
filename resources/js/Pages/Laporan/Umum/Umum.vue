<template>
  <AppLayout title="Template Components">
    <div class="container py-4">
      <div class="row mb-4">
        <div class="col">
          <h1 class="fw-bold">Halaman UMUM</h1>
          <p class="text-muted">Reusable components for your application</p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title fw-bold">Buttons</h5>
              <p class="card-text text-muted">Various button styles and variants.</p>
              <Link href="/template/button" class="btn btn-outline-primary">View Buttons</Link>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { Link } from '@inertiajs/vue3'
</script>