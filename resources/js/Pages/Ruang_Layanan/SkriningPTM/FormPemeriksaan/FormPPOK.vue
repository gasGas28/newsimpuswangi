<template>
  <div class="risk-form">
    <section class="risk-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-lungs"></i> Gejala Pernapasan</h4>
          <p>Kuesioner PUMA (Q0021) linkId 1.5-1.8.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="respiratory-grid">
          <div class="form-field">
            <label for="napas_pendek" class="form-label"
              >Apakah peserta pernah merasa napas pendek ketika peserta berjalan lebih cepat pada
              jalan yang datar atau pada jalan yang sedikit menanjak?</label
            >
            <select
              id="napas_pendek"
              name="napas_pendek"
              class="form-select"
              v-model="form.napas_pendek"
            >
              <option value="tidak">Tidak</option>
              <option value="iya">Iya</option>
            </select>
          </div>

          <div class="form-field">
            <label for="dahak" class="form-label"
              >Apakah peserta biasanya mempunyai dahak yang berasal dari paru atau kesulitan
              mengeluarkan dahak saat peserta sedang tidak menderita selesma/flu?</label
            >
            <select id="dahak" name="dahak" class="form-select" v-model="form.dahak">
              <option value="tidak">Tidak</option>
              <option value="iya">Iya</option>
            </select>
          </div>

          <div class="form-field">
            <label for="batuk" class="form-label"
              >Apakah peserta biasanya batuk saat peserta sedang tidak menderita selesma/flu?</label
            >
            <select id="batuk" name="batuk" class="form-select" v-model="form.batuk">
              <option value="tidak">Tidak</option>
              <option value="iya">Iya</option>
            </select>
          </div>

          <div class="form-field">
            <label for="spirometri" class="form-label"
              >Apakah Dokter atau tenaga medis lainnya pernah meminta peserta untuk melakukan
              pemeriksaan spirometri atau peak flow meter (meniup ke dalam suatu alat) untuk
              mengetahui fungsi paru peserta?</label
            >
            <select id="spirometri" name="spirometri" class="form-select" v-model="form.spirometri">
              <option value="tidak">Tidak</option>
              <option value="iya">Iya</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <!-- ✅ Modal di dalam root element -->
    <ModalAlert
      :show="showSuccessModal"
      type="success"
      title="Data Berhasil Disimpan"
      message="Data kuesioner PUMA berhasil disimpan."
      button-text="Tutup"
      @close="showSuccessModal = false"
    />
    <ModalAlert
      :show="showValidationModal"
      type="warning"
      title="Data Belum Lengkap"
      message="Mohon lengkapi data berikut:"
      :items="validationMessages"
      @close="showValidationModal = false"
    />
  </div>
</template>
<script setup>
  import { ref, computed } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import ModalAlert from '../../../../Components/Layouts/Modal/ModalAlert.vue';

  // --- Props ---
  const props = defineProps({
    DataPasien: Object,
  });

  // --- Modal state ---
  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const validationMessages = ref([]);

  // --- Form ---
  const form = useForm({
    idSkrining: props.DataPasien?.idSkrining,
    idpelayanan: props.DataPasien?.idpelayanan,

    // Kuesioner PUMA (Q0021) linkId 1.5-1.9
    napas_pendek: 'tidak',
    dahak: 'tidak',
    batuk: 'tidak',
    spirometri: 'tidak',
    hasil_puma: '',

    // Anamnesa - Keluhan (Condition)
    keluhan_sesak_nafas: false,
    keluhan_batuk_kering: false,
    keluhan_batuk_berdahak_produktif: false,
    keluhan_batuk_berdahak_kronik: false,
    keluhan_dada_berat: false,

    // Wawancara - Faktor Risiko Lingkungan (Observation social-history)
    pajanan_cemaran_udara: 'tidak',
    pajanan_rumah_tangga: 'tidak',

    // Pemeriksaan Fisik - Inspeksi
    pink_puffer: 'tidak',
    pursed_lips_breathing: 'tidak',
    otot_bantu_napas: 'tidak',
    pelebaran_sela_iga: 'tidak',
    barrel_chest: 'tidak',

    // Pemeriksaan Fisik - Perkusi
    hipersonor: 'tidak',

    // Pemeriksaan Fisik - Auskultasi
    suara_vesikular: 'normal',
    ronki_mengi: 'tidak',
    ekspirasi_memanjang: 'tidak',

    // Pemeriksaan Penunjang Spirometri (jika hasil PUMA tinggi)
    spirometri_dilakukan: '',
    persen_kv: null,
    persen_kvp: null,
    persen_vep1: null,
  });

  // --- Options untuk v-for di template ---
  const keluhanOptions = [
    { model: 'keluhan_sesak_nafas', label: 'Sesak nafas yang bertambah berat jika beraktivitas' },
    { model: 'keluhan_batuk_kering', label: 'Batuk kering' },
    { model: 'keluhan_batuk_berdahak_produktif', label: 'Batuk berdahak yang produktif' },
    { model: 'keluhan_batuk_berdahak_kronik', label: 'Batuk berdahak kronik' },
    { model: 'keluhan_dada_berat', label: 'Rasa berat di dada' },
  ];

  // --- UI state ---
  const saveStatus = ref('idle');
  const saveError = ref('');

  const saveMessage = computed(() => {
    if (saveStatus.value === 'ready') return 'Data PUMA berhasil disimpan.';
    if (saveError.value) return saveError.value;
    return 'Simpan setelah kuesioner PUMA selesai diisi.';
  });

  // --- Helpers ---
  function extractMessage(errors) {
    return (
      Object.values(errors || {})
        .flat()
        .find(Boolean) || 'Terjadi kesalahan saat menyimpan data.'
    );
  }

  function saveFaktorRisiko() {
    saveStatus.value = 'idle';
    saveError.value = '';
    showSuccessModal.value = false;
    showValidationModal.value = false;
    validationMessages.value = [];

    form.post(route('pelayanan.simpan-risiko-ptm'), {
      preserveScroll: true,

      onSuccess: () => {
        saveStatus.value = 'ready';
        saveError.value = '';
        validationMessages.value = [];
        form.clearErrors();
        form.defaults(form.data());
        showSuccessModal.value = true;
      },

      onError: (errors) => {
        saveStatus.value = 'error';
        const message = extractMessage(errors);
        validationMessages.value = Object.values(errors).flat();
        saveError.value = message;
      },
    });
  }
</script>

<style scoped src="@/css/FormPemeriksaan.css"></style>
