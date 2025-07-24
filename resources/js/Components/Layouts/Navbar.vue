<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
      <Link href="/" class="navbar-brand fw-bold">MyApp</Link>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <Link href="/" class="nav-link" :class="{ 'active': $page.url.startsWith('/template') }">
              Template
            </Link>
          </li>
          <li class="nav-item">
            <Link href="/admin" class="nav-link" :class="{ 'active': $page.url.startsWith('/admin') }">
              Admin
            </Link>
          </li>
          <li class="nav-item">
            <Link href="/loket" class="nav-link" :class="{ 'active': $page.url.startsWith('/loket') }">
              Loket
            </Link>
          </li>
          <!-- <li class="nav-item">
            <Link href="/pasien" class="nav-link" :class="{ 'active': $page.url.startsWith('/pasien') }">
              Pasien
            </Link>
          </li> -->
        </ul>
        <div class="d-flex align-items-center">
          <div class="dropdown">
            <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i> User
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><Link class="dropdown-item" href="/profile">Profile</Link></li>
              <li><hr class="dropdown-divider"></li>
              <li><Link class="dropdown-item" href="/logout">Logout</Link></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
</script>

<style scoped>
.navbar {
  padding: 0.8rem 1rem;
}
.nav-link {
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
}
.nav-link:hover, .nav-link.active {
  background-color: rgba(255, 255, 255, 0.1);
}
</style>