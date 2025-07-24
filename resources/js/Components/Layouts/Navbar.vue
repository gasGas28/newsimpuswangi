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
            <Link href="/template" class="nav-link" :class="{ 'active': $page.url.startsWith('/template') }">
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

          <li class="nav-item dropdown">
            <button class="nav-link dropdown-toggle btn btn-link"
              id="laporanDropdown"
              data-bs-toggle="dropdown"
              aria-expanded="false"
              style="color:inherit;text-decoration:none;"
              type="button"
            >
              Laporan
            </button>
            <ul class="dropdown-menu" aria-labelledby="laporanDropdown">


              <li>
                <Link class="dropdown-item" href="/laporan/loket"
                  :class="{ active: $page.url.startsWith('/laporan/loket') }"
                >Loket</Link>
              </li>
              <li>
                <Link class="dropdown-item" href="/laporan/rujukan"
                  :class="{ active: $page.url.startsWith('/laporan/rujukan') }"
                >Rujukan</Link>
              </li>
              <li>
                <Link class="dropdown-item" href="/laporan/gigi"
                  :class="{ active: $page.url.startsWith('/laporan/gigi') }"
                >Gigi</Link>
              </li>
                            <li>
                <Link class="dropdown-item" href="/laporan/kia"
                  :class="{ active: $page.url.startsWith('/laporan/rujukan') }"
                >Kia</Link>
              </li>

              <li>
                <Link class="dropdown-item" href="/laporan/umum"
                  :class="{ active: $page.url.startsWith('/laporan/umum') }"
                >Umum</Link>
              </li>

              <li>
                <Link class="dropdown-item" href="/laporan/lab"
                  :class="{ active: $page.url.startsWith('/laporan/lab') }"
                >Lab</Link>
              </li>

              <li>
                <Link class="dropdown-item" href="/laporan/kb"
                  :class="{ active: $page.url.startsWith('/laporan/kb') }"
                  >KB</Link>
              </li>

              <li>
                <Link class="dropdown-item" href="/laporan/ugd"
                  :class="{ active: $page.url.startsWith('/laporan/ugd') }"
                  >UGD</Link>
              </li>

              <li>
                <Link class="dropdown-item" href="/laporan/Rawat-inap"
                  :class="{ active: $page.url.startsWith('/laporan/Rawat-inap') }"
                  >Rawat Inap</Link>
              </li>

              <li>
                <Link class="dropdown-item" href="/laporan/sanitasi"
                  :class="{ active: $page.url.startsWith('/laporan/Sanitasi') }"
                  >Sanitasi</Link>
              </li>

              <li>
                <Link class="dropdown-item" href="/laporan/kunjungan-sehat"
                  :class="{ active: $page.url.startsWith('/laporan/kunjungan-sehat') }"
                  >Kunjungan sehat</Link>
              </li>
              
            </ul>
          </li>


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