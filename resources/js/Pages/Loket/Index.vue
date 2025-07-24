<template>
    <AppLayout>
      <div class="card">
        <div class="card-body d-flex gap-2 justify-content-end">
          <a href="/cari" class="btn btn-outline-primary">Cari Pasien</a>
          <a href="/pasien" class="btn btn-primary">Tambah Pasien</a>
          <a href="/filter" class="btn btn-success">Filter Laporan</a>
        </div>
      </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Components/Layouts/AppLayouts.vue";

defineProps({
    users: Object,
});
</script>
