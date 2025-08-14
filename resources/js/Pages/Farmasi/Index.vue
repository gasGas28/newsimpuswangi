<template>
  <AppLayout title="Farmasi">
    <div class="container py-4">
     <div class="mb-3">
        <Link href="/dashboard" class="btn btn-secondary">
          <i class=""></i> Kembali 
        </Link>
      </div>
      <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0"><i class=""></i> Menu Farmasi</h4>
        </div>
        <div class="card-body">
            <div class="row g-4">
            <div class="col-md-6 col-lg-3" v-for="menu in menus" :key="menu.title">
              <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                  <div>
                    <h5 class="card-title">
                      <i :class="menu.icon" class="me-2"></i> {{ menu.title }}
                    </h5>
                    <p class="card-text small text-muted">{{ menu.description }}</p>
                  </div>
                  <Link :href="menu.link" class="btn btn-outline-primary mt-3">Masuk</Link>
                </div>
              </div>
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

const menus = [
  {
    title: 'Master Obat',
    description: 'Kelola data obat yang tersedia di apotek.',
    link: '/farmasi/master',
    icon: 'bi bi-capsule'
  },
  {
    title: 'Resep Langsung',
    description: 'Input resep langsung tanpa rujukan.',
    link: '/farmasi/resep-langsung',
    icon: 'bi bi-prescription2'
  },
  {
    title: 'Pelayanan dari Poli',
    description: 'Pelayanan resep hasil kunjungan poli.',
    link: '/farmasi/pelayanan-resep',
    icon: 'bi bi-hospital'
  },
  {
    title: 'Laporan Farmasi',
    description: 'Lihat laporan pengeluaran dan stok obat.',
    link: '/farmasi/laporan',
    icon: 'bi bi-file-earmark-bar-graph'
  }
]
</script>
