<template>
  <div class="container mt-4">
    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-header bg-light">
        <h6 class="fw-bold text-primary mb-0">Surat Keterangan - {{ no_mr }}</h6>
      </div>
      <div class="card-body">
        <p>Ini halaman surat keterangan untuk pasien dengan No MR: <strong>{{ no_mr }}</strong></p>
        <!-- isi form/logic tambahan disini -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SuratKeterangan",
  props: {
    no_mr: {
      type: String,
      required: true
    }
  }
}
</script>
