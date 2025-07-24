<template>
  <footer class="bg-dark text-white py-4 mt-auto">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5 class="fw-bold mb-3">MyApp</h5>
          <p class="text-muted">A simple and elegant application built with Laravel, Vue, and Inertia.</p>
        </div>
        <div class="col-md-3">
          <h5 class="fw-bold mb-3">Links</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><Link href="/template" class="text-decoration-none text-muted">Template</Link></li>
            <li class="mb-2"><Link href="/admin" class="text-decoration-none text-muted">Admin</Link></li>
            <li class="mb-2"><Link href="/loket" class="text-decoration-none text-muted">Loket</Link></li>
          </ul>
        </div>
        <div class="col-md-3">
          <h5 class="fw-bold mb-3">Contact</h5>
          <ul class="list-unstyled text-muted">
            <li class="mb-2"><i class="bi bi-envelope me-2"></i> contact@myapp.com</li>
            <li class="mb-2"><i class="bi bi-phone me-2"></i> +123 456 7890</li>
          </ul>
        </div>
      </div>
      <hr class="my-4 bg-secondary">
      <div class="text-center text-muted">
        <small>&copy; {{ new Date().getFullYear() }} MyApp. All rights reserved.</small>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<style scoped>
footer a:hover {
  color: white !important;
  transition: color 0.3s ease;
}
</style>