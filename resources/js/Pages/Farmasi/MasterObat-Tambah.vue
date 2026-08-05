<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Components/Layouts/AppLayouts.vue'

const form = useForm({
  KODE_OBAT: '',
  NAMA: '',
  SATUAN: '',
})

const submitForm = () => {
  form.post('/farmasi/master-obat', {
    preserveScroll: true,
  })
}
</script>

<template>
  <AppLayout title="Tambah Obat">
    <main class="tambah-obat-page">
      <div class="container py-4 py-lg-5">
        <Link href="/farmasi/master-obat" class="back-link">
          <i class="bi bi-arrow-left" aria-hidden="true"></i>
          Kembali ke Master Obat
        </Link>

        <div class="form-layout">
          <section class="form-intro" aria-labelledby="page-title">
            <span class="eyebrow"><i class="bi bi-capsule" aria-hidden="true"></i> Master Farmasi</span>
            <h1 id="page-title">Tambah Obat Baru</h1>
            <p>Lengkapi informasi dasar obat agar dapat digunakan dalam pelayanan dan pencatatan persediaan.</p>

            <div class="info-card">
              <i class="bi bi-info-circle" aria-hidden="true"></i>
              <p>Kode obat harus unik. Pastikan data sudah benar sebelum disimpan.</p>
            </div>
          </section>

          <section class="form-card" aria-labelledby="form-title">
            <div class="form-card__header">
              <div class="form-icon"><i class="bi bi-plus-lg" aria-hidden="true"></i></div>
              <div>
                <h2 id="form-title">Informasi Obat</h2>
                <p>Kolom bertanda <span aria-hidden="true">*</span> wajib diisi.</p>
              </div>
            </div>

            <form novalidate @submit.prevent="submitForm">
              <div class="mb-4">
                <label for="kode-obat" class="form-label">Kode Obat <span class="required">*</span></label>
                <div class="input-group" :class="{ 'has-error': form.errors.KODE_OBAT }">
                  <span class="input-group-text"><i class="bi bi-upc-scan" aria-hidden="true"></i></span>
                  <input
                    id="kode-obat"
                    v-model="form.KODE_OBAT"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.KODE_OBAT }"
                    placeholder="Contoh: OBT-001"
                    autocomplete="off"
                    required
                  >
                </div>
                <div v-if="form.errors.KODE_OBAT" class="invalid-feedback d-block">{{ form.errors.KODE_OBAT }}</div>
                <p v-else class="field-help">Gunakan kode yang mudah dikenali dan belum terdaftar.</p>
              </div>

              <div class="mb-4">
                <label for="nama-obat" class="form-label">Nama Obat <span class="required">*</span></label>
                <div class="input-group" :class="{ 'has-error': form.errors.NAMA }">
                  <span class="input-group-text"><i class="bi bi-capsule-pill" aria-hidden="true"></i></span>
                  <input
                    id="nama-obat"
                    v-model="form.NAMA"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.NAMA }"
                    placeholder="Contoh: Paracetamol 500 mg"
                    autocomplete="off"
                    required
                  >
                </div>
                <div v-if="form.errors.NAMA" class="invalid-feedback d-block">{{ form.errors.NAMA }}</div>
              </div>

              <div class="mb-4">
                <label for="satuan-obat" class="form-label">Satuan <span class="required">*</span></label>
                <div class="input-group" :class="{ 'has-error': form.errors.SATUAN }">
                  <span class="input-group-text"><i class="bi bi-box" aria-hidden="true"></i></span>
                  <input
                    id="satuan-obat"
                    v-model="form.SATUAN"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.SATUAN }"
                    placeholder="Contoh: Tablet, Botol, atau Strip"
                    autocomplete="off"
                    required
                  >
                </div>
                <div v-if="form.errors.SATUAN" class="invalid-feedback d-block">{{ form.errors.SATUAN }}</div>
              </div>

              <div class="form-actions">
                <Link href="/farmasi/master-obat" class="btn btn-cancel">Batal</Link>
                <button type="submit" class="btn btn-save" :disabled="form.processing">
                  <span v-if="form.processing" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                  <i v-else class="bi bi-check-lg" aria-hidden="true"></i>
                  {{ form.processing ? 'Menyimpan...' : 'Simpan Obat' }}
                </button>
              </div>
            </form>
          </section>
        </div>
      </div>
    </main>
  </AppLayout>
</template>

<style scoped>
.tambah-obat-page { min-height: 100%; background: #f5f8fa; }
.back-link { display: inline-flex; align-items: center; gap: .45rem; margin-bottom: 1.5rem; color: #55707a; font-size: .9rem; font-weight: 600; text-decoration: none; }
.back-link:hover, .back-link:focus-visible { color: #087e8b; text-decoration: underline; }
.form-layout { display: grid; grid-template-columns: minmax(0, .8fr) minmax(0, 1fr); gap: clamp(1.5rem, 4vw, 4rem); max-width: 65rem; margin: 0 auto; }
.form-intro { padding: 2rem 0; }
.eyebrow { display: inline-flex; align-items: center; gap: .45rem; color: #16838e; font-size: .75rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
.form-intro h1 { margin: .65rem 0 .8rem; color: #263640; font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 750; line-height: 1.15; }
.form-intro > p { max-width: 28rem; margin: 0; color: #667a85; line-height: 1.65; }
.info-card { display: flex; gap: .7rem; margin-top: 2rem; padding: 1rem; color: #526972; font-size: .875rem; background: #e8f7f8; border: 1px solid #d1edef; border-radius: .8rem; }
.info-card i { flex: 0 0 auto; color: #087e8b; font-size: 1.1rem; }
.info-card p { margin: 0; }
.form-card { padding: clamp(1.35rem, 3vw, 2rem); background: #fff; border: 1px solid #e4ebee; border-radius: 1rem; box-shadow: 0 .8rem 2rem rgba(25,55,70,.07); }
.form-card__header { display: flex; align-items: center; gap: .9rem; padding-bottom: 1.4rem; margin-bottom: 1.5rem; border-bottom: 1px solid #edf1f3; }
.form-icon { display: grid; width: 2.8rem; height: 2.8rem; color: #087e8b; font-size: 1.2rem; background: #e5f7f8; border-radius: .75rem; place-items: center; }
.form-card h2 { margin: 0 0 .2rem; color: #2d3d46; font-size: 1.2rem; font-weight: 700; }
.form-card__header p { margin: 0; color: #7b8d96; font-size: .82rem; }
.required { color: #cf3e45; }
.form-label { margin-bottom: .45rem; color: #43555f; font-size: .9rem; font-weight: 700; }
.input-group-text { min-width: 2.8rem; justify-content: center; color: #6a818b; background: #f5f8f9; border-color: #dce6e9; }
.form-control { min-height: 2.75rem; border-color: #dce6e9; }
.form-control:focus { border-color: #38abb6; box-shadow: 0 0 0 .2rem rgba(18,150,163,.14); }
.input-group:focus-within .input-group-text { color: #087e8b; background: #eafafb; border-color: #38abb6; }
.input-group.has-error .input-group-text { color: #dc3545; border-color: #dc3545; }
.field-help { margin: .4rem 0 0; color: #81919a; font-size: .78rem; }
.form-actions { display: flex; justify-content: flex-end; gap: .7rem; padding-top: 1.25rem; border-top: 1px solid #edf1f3; }
.btn-cancel, .btn-save { display: inline-flex; align-items: center; justify-content: center; gap: .45rem; min-height: 2.6rem; padding: .5rem 1rem; font-weight: 600; border-radius: .65rem; }
.btn-cancel { color: #5c6d76; background: #fff; border: 1px solid #d8e1e5; }
.btn-cancel:hover { color: #344852; background: #f4f7f8; border-color: #c9d5da; }
.btn-save { color: #fff; background: #087e8b; border-color: #087e8b; }
.btn-save:hover, .btn-save:focus-visible { color: #fff; background: #066d78; border-color: #066d78; }
.btn-save:disabled { cursor: wait; opacity: .7; }
@media (max-width: 767.98px) { .form-layout { grid-template-columns: 1fr; gap: 0; } .form-intro { padding: 0 0 1.75rem; } }
@media (max-width: 420px) { .form-actions { flex-direction: column-reverse; } .form-actions > * { width: 100%; } }
</style>