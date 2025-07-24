<template>
  <AppLayout title="Template Components">
    <div class="container py-4">
      <div class="row mb-4">
        <div class="col">
          <h1 class="fw-bold">Template Components</h1>
          <p class="text-muted">Reusable components for your application</p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title fw-bold">Buttons</h5>
              <p class="card-text text-muted">Various button styles and variants.</p>
              <Link href="/button" class="btn btn-outline-primary">View Buttons</Link>
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title fw-bold">Forms</h5>
              <p class="card-text text-muted">Form elements and validation examples.</p>
              <Link href="/form" class="btn btn-outline-primary">View Forms</Link>
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title fw-bold">Tables</h5>
              <p class="card-text text-muted">Data table examples with sorting and pagination.</p>
              <Link href="/table" class="btn btn-outline-primary">View Tables</Link>
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title fw-bold">Cards</h5>
              <p class="card-text text-muted">Card components with different styles.</p>
              <Link href="/card" class="btn btn-outline-primary">View Cards</Link>
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title fw-bold">Pagination</h5>
              <p class="card-text text-muted">Pagination components and examples.</p>
              <Link href="/pagination" class="btn btn-outline-primary">View Pagination</Link>
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