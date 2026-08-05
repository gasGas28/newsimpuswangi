<template>
  <AppLayout title="Farmasi">
    <main class="farmasi-page">
      <div class="container py-4 py-lg-5">
        <section class="farmasi-hero mb-4 mb-lg-5" aria-labelledby="farmasi-title">
          <div class="farmasi-hero__content">
            <span class="eyebrow"><i class="bi bi-capsule-pill" aria-hidden="true"></i> Modul Farmasi</span>
            <h1 id="farmasi-title">Kelola layanan farmasi dengan lebih cepat.</h1>
            <p>Kelola stok obat, resep pasien, dan laporan pelayanan dari satu tempat.</p>
          </div>
          <div class="farmasi-hero__date" aria-label="Tanggal hari ini">
            <i class="bi bi-calendar3" aria-hidden="true"></i>
            <span>{{ today }}</span>
          </div>
        </section>

        <section aria-labelledby="menu-title">
          <div class="section-heading">
            <div>
              <span class="section-kicker">Akses cepat</span>
              <h2 id="menu-title">Menu Farmasi</h2>
            </div>
            <span class="menu-count">{{ menus.length }} menu tersedia</span>
          </div>

          <div class="row g-3 g-lg-4">
            <div v-for="menu in menus" :key="menu.title" class="col-12 col-md-6 col-xl-3">
              <Link :href="menu.link" class="menu-card" :aria-label="`Buka ${menu.title}`">
                <div class="menu-card__icon" :class="menu.theme">
                  <i :class="menu.icon" aria-hidden="true"></i>
                </div>
                <div class="menu-card__body">
                  <h3>{{ menu.title }}</h3>
                  <p>{{ menu.description }}</p>
                </div>
                <span class="menu-card__arrow" aria-hidden="true"><i class="bi bi-arrow-up-right"></i></span>
              </Link>
            </div>
          </div>
        </section>

        <aside class="farmasi-note mt-4 mt-lg-5">
          <i class="bi bi-info-circle" aria-hidden="true"></i>
          <p><strong>Tips:</strong> Perbarui stok obat setelah setiap pengeluaran agar data persediaan tetap akurat.</p>
        </aside>
      </div>
    </main>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { Link } from '@inertiajs/vue3'

const today = new Intl.DateTimeFormat('id-ID', {
  weekday: 'long',
  day: 'numeric',
  month: 'long',
  year: 'numeric',
}).format(new Date())

const menus = [
  {
    title: 'Master Obat',
    description: 'Tambah, ubah, dan pantau data stok obat.',
    link: '/farmasi/master-obat',
    icon: 'bi bi-capsule',
    theme: 'theme-teal',
  },
  {
    title: 'Resep Langsung',
    description: 'Catat pengeluaran obat untuk pasien langsung.',
    link: '/farmasi/pengeluaran-langsung',
    icon: 'bi bi-prescription2',
    theme: 'theme-blue',
  },
  {
    title: 'Resep dari Poli',
    description: 'Proses resep yang masuk dari ruang pelayanan.',
    link: '/farmasi/pelayanan-resep',
    icon: 'bi bi-hospital',
    theme: 'theme-violet',
  },
  {
    title: 'Laporan Farmasi',
    description: 'Tinjau ringkasan dan laporan layanan farmasi.',
    link: '/farmasi/laporan',
    icon: 'bi bi-file-earmark-bar-graph',
    theme: 'theme-amber',
  },
]
</script>

<style scoped>
.farmasi-page {
  min-height: 100%;
  background: #f5f8fa;
}

.farmasi-hero {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.5rem;
  overflow: hidden;
  padding: clamp(1.5rem, 3vw, 2.75rem);
  color: #fff;
  background: linear-gradient(125deg, #087e8b 0%, #1296a3 52%, #36b9c7 100%);
  border-radius: 1.25rem;
  box-shadow: 0 1rem 2.5rem rgba(8, 126, 139, 0.2);
}

.farmasi-hero::after {
  position: absolute;
  right: -3.75rem;
  bottom: -5rem;
  width: 15rem;
  height: 15rem;
  content: '';
  background: rgba(255, 255, 255, 0.12);
  border-radius: 50%;
}

.farmasi-hero__content,
.farmasi-hero__date {
  position: relative;
  z-index: 1;
}

.eyebrow,
.section-kicker {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.eyebrow {
  color: rgba(255, 255, 255, 0.85);
}

.farmasi-hero h1 {
  max-width: 44rem;
  margin: 0.55rem 0 0.65rem;
  font-size: clamp(1.65rem, 3vw, 2.35rem);
  font-weight: 750;
  line-height: 1.2;
}

.farmasi-hero p {
  max-width: 37rem;
  margin: 0;
  color: rgba(255, 255, 255, 0.86);
}

.farmasi-hero__date {
  display: flex;
  flex: 0 0 auto;
  align-items: center;
  gap: 0.65rem;
  max-width: 15rem;
  padding: 0.8rem 1rem;
  font-size: 0.875rem;
  font-weight: 600;
  line-height: 1.35;
  background: rgba(255, 255, 255, 0.17);
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: 0.875rem;
  backdrop-filter: blur(4px);
}

.farmasi-hero__date i {
  font-size: 1.2rem;
}

.section-heading {
  display: flex;
  align-items: end;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 1.25rem;
}

.section-kicker {
  color: #16838e;
}

.section-heading h2 {
  margin: 0.2rem 0 0;
  color: #1d2b35;
  font-size: 1.35rem;
  font-weight: 700;
}

.menu-count {
  padding: 0.38rem 0.7rem;
  color: #60717d;
  font-size: 0.8rem;
  font-weight: 600;
  background: #e9f0f3;
  border-radius: 999px;
}

.menu-card {
  position: relative;
  display: flex;
  flex-direction: column;
  height: 100%;
  min-height: 14rem;
  padding: 1.4rem;
  overflow: hidden;
  color: inherit;
  text-decoration: none;
  background: #fff;
  border: 1px solid #e7edef;
  border-radius: 1rem;
  box-shadow: 0 0.3rem 1rem rgba(25, 55, 70, 0.035);
  transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}

.menu-card:hover,
.menu-card:focus-visible {
  color: inherit;
  border-color: #92d7dd;
  box-shadow: 0 0.85rem 1.75rem rgba(23, 107, 117, 0.13);
  outline: none;
  transform: translateY(-0.3rem);
}

.menu-card:focus-visible {
  box-shadow: 0 0 0 0.25rem rgba(18, 150, 163, 0.22), 0 0.85rem 1.75rem rgba(23, 107, 117, 0.13);
}

.menu-card__icon {
  display: grid;
  width: 3.1rem;
  height: 3.1rem;
  margin-bottom: 1.25rem;
  font-size: 1.35rem;
  place-items: center;
  border-radius: 0.85rem;
}

.theme-teal { color: #087e8b; background: #e1f6f7; }
.theme-blue { color: #2b6cb0; background: #e8f1fc; }
.theme-violet { color: #7356b6; background: #f0ebfb; }
.theme-amber { color: #b7791f; background: #fff5df; }

.menu-card__body h3 {
  margin: 0 0 0.45rem;
  color: #26343e;
  font-size: 1.05rem;
  font-weight: 700;
}

.menu-card__body p {
  margin: 0;
  color: #6b7b86;
  font-size: 0.875rem;
  line-height: 1.5;
}

.menu-card__arrow {
  position: absolute;
  right: 1.2rem;
  bottom: 1.1rem;
  color: #8aa2ac;
  transition: color 0.2s ease, transform 0.2s ease;
}

.menu-card:hover .menu-card__arrow,
.menu-card:focus-visible .menu-card__arrow {
  color: #087e8b;
  transform: translate(0.18rem, -0.18rem);
}

.farmasi-note {
  display: flex;
  align-items: flex-start;
  gap: 0.7rem;
  max-width: 48rem;
  padding: 0.9rem 1rem;
  color: #526671;
  font-size: 0.875rem;
  background: #ecf7f7;
  border: 1px solid #d2eeee;
  border-radius: 0.8rem;
}

.farmasi-note i {
  color: #087e8b;
  font-size: 1.05rem;
}

.farmasi-note p {
  margin: 0;
}

@media (max-width: 575.98px) {
  .farmasi-hero {
    align-items: flex-start;
    flex-direction: column;
  }

  .farmasi-hero__date {
    max-width: none;
  }

  .section-heading {
    align-items: flex-start;
    flex-direction: column;
  }

  .menu-card {
    min-height: auto;
  }
}

@media (prefers-reduced-motion: reduce) {
  .menu-card,
  .menu-card__arrow {
    transition: none;
  }
}
</style>