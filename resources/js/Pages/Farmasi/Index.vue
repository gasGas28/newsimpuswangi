<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const layanan = [
  {
    title: 'Master Obat',
    route: '/farmasi/master',
    icon: 'fas fa-capsules',
  },
  {
    title: 'Resep Langsung',
    route: '/farmasi/resep-langsung',
    icon: 'fas fa-notes-medical',
  },
  {
    title: 'Pelayanan Resep dari Poli',
    route: '/farmasi/pelayanan-resep',
    icon: 'fas fa-hospital-user',
  },
  {
    title: 'Laporan Farmasi',
    route: '/farmasi/laporan',
    icon: 'fas fa-file-medical-alt',
  },
]
</script>

<template>
    <div class="container py-4">
      <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">Halaman Farmasi</h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6 col-lg-3" v-for="menu in layanan" :key="menu.title">
              <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                  <i :class="menu.icon" class="mb-2" style="font-size: 2rem;"></i>
                  <h6 class="card-title">{{ menu.title }}</h6>
                  <Link :href="menu.route" class="btn btn-sm btn-outline-success mt-2">Buka</Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>


