<template>
  <div class="indera-form">
    <section class="indera-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-eye"></i> Gangguan Penglihatan</h4>
          <p>Observation untuk mata kanan dan mata kiri.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="section-title">
          <h5>Tajam Penglihatan</h5>
          <span>Snellen chart - uncorrected visual acuity</span>
        </div>

        <div class="side-grid">
          <div class="side-card">
            <div class="side-head kanan">Mata Kanan (OD)</div>
            <div class="side-body">
              <div class="form-field">
                <label class="form-label" for="visus_od">Visus</label>
                <input
                  id="visus_od"
                  class="form-control"
                  type="text"
                  v-model="form.penglihatan.visus_od"
                  placeholder="6/6"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="pinhole_od">Pinhole</label>
                <input
                  id="pinhole_od"
                  class="form-control"
                  type="text"
                  v-model="form.penglihatan.pinhole_od"
                  placeholder="6/6"
                />
              </div>
            </div>
          </div>

          <div class="side-card">
            <div class="side-head kiri">Mata Kiri (OS)</div>
            <div class="side-body">
              <div class="form-field">
                <label class="form-label" for="visus_os">Visus</label>
                <input
                  id="visus_os"
                  class="form-control"
                  type="text"
                  v-model="form.penglihatan.visus_os"
                  placeholder="6/6"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="pinhole_os">Pinhole</label>
                <input
                  id="pinhole_os"
                  class="form-control"
                  type="text"
                  v-model="form.penglihatan.pinhole_os"
                  placeholder="6/6"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="section-title with-gap">
          <h5>Segmen Anterior &amp; Katarak</h5>
          <span>Pupil, refleks fundus, shadow test, TIO</span>
        </div>

        <div class="side-grid">
          <div class="side-card">
            <div class="side-head kanan">Mata Kanan</div>
            <div class="side-body compact-grid">
              <div class="form-field">
                <label class="form-label" for="sa_od">Segmen Anterior</label>
                <select id="sa_od" class="form-select" v-model="form.penglihatan.sa_od">
                  <option value="">Pilih hasil</option>
                  <option value="normal">Normal</option>
                  <option value="tidak_normal">Tidak Normal</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="rf_od">Refleks Fundus</label>
                <select id="rf_od" class="form-select" v-model="form.penglihatan.rf_od">
                  <option value="">Pilih hasil</option>
                  <option value="normal">Normal</option>
                  <option value="tidak_normal">Tidak Normal</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="st_od">Shadow Test</label>
                <select id="st_od" class="form-select" v-model="form.penglihatan.st_od">
                  <option value="">Pilih hasil</option>
                  <option value="negatif">Negatif</option>
                  <option value="positif">Positif</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="gio_od">Curiga Glaukoma</label>
                <div class="input-with-addon">
                  <input
                    id="gio_od"
                    class="form-control"
                    type="text"
                    v-model ="form.penglihatan.gio_od"
                    placeholder="0"
                  />
                  <span>mmHg</span>
                </div>
              </div>
            </div>
          </div>

          <div class="side-card">
            <div class="side-head kiri">Mata Kiri</div>
            <div class="side-body compact-grid">
              <div class="form-field">
                <label class="form-label" for="sa_os">Segmen Anterior</label>
                <select id="sa_os" class="form-select" v-model="form.penglihatan.sa_os">
                  <option value="">Pilih hasil</option>
                  <option value="normal">Normal</option>
                  <option value="tidak_normal">Tidak Normal</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="rf_os">Refleks Fundus</label>
                <select id="rf_os" class="form-select" v-model="form.penglihatan.rf_os">
                  <option value="">Pilih hasil</option>
                  <option value="normal">Normal</option>
                  <option value="tidak_normal">Tidak Normal</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="st_os">Shadow Test</label>
                <select id="st_os" class="form-select" v-model="form.penglihatan.st_os">
                  <option value="">Pilih hasil</option>
                  <option value="negatif">Negatif</option>
                  <option value="positif">Positif</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="gio_os">Curiga Glaukoma</label>
                <div class="input-with-addon">
                  <input
                    id="gio_os"
                    class="form-control"
                    type="text"
                    v-model = "form.penglihatan.gio_os"
                    placeholder="0"
                  />
                  <span>mmHg</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="result-grid">
          <div class="form-field">
            <label class="form-label" for="retino_od">Curiga Retinopati Mata Kanan</label>
            <select id="retino_od" class="form-select" v-model="form.penglihatan.retino_od">
              <option value="false">Tidak</option>
              <option value="true">Ya</option>
            </select>
          </div>
          <div class="form-field">
            <label class="form-label" for="retino_os">Curiga Retinopati Mata Kiri</label>
            <select id="retino_os" class="form-select" v-model="form.penglihatan.retino_os">
              <option value="false">Tidak</option>
              <option value="true">Ya</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <section class="indera-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-ear"></i> Gangguan Pendengaran</h4>
          <p>Observation untuk telinga kanan dan telinga kiri.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="side-grid">
          <div class="side-card">
            <div class="side-head kanan">Telinga Kanan</div>
            <div class="side-body compact-grid">
              <div class="form-field">
                <label class="form-label" for="tuli_kanan">Curiga Tuli Kongenital</label>
                <select id="tuli_kanan" class="form-select" v-model="form.pendengaran.tuli_kanan">
                  <option value="false">Tidak</option>
                  <option value="true">Ya</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="omsk_kanan">OMSK / Congek</label>
                <select id="omsk_kanan" class="form-select" v-model="form.pendengaran.omsk_kanan">
                  <option value="false">Tidak</option>
                  <option value="true">Ya</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="serumen_kanan">Serumen Impaksi</label>
                <select id="serumen_kanan" class="form-select" v-model="form.pendengaran.serumen_kanan">
                  <option value="false">Tidak</option>
                  <option value="true">Ya</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="presbi_kanan">Curiga Presbikusis</label>
                <select id="presbi_kanan" class="form-select" v-model="form.pendengaran.presbi_kanan">
                  <option value="false">Tidak</option>
                  <option value="true">Ya</option>
                </select>
              </div>
              <div class="form-field span-full">
                <label class="form-label" for="bisik_kanan">Tes Berbisik</label>
                <select id="bisik_kanan" class="form-select" v-model="form.pendengaran.bisik_kanan">
                  <option value="">Pilih hasil</option>
                  <option value="normal">Normal (&gt;=6 meter)</option>
                  <option value="ringan">Ringan (4-5 m)</option>
                  <option value="sedang">Sedang (1-3 m)</option>
                  <option value="berat">Berat (&lt;1 m)</option>
                </select>
              </div>
            </div>
          </div>

          <div class="side-card">
            <div class="side-head kiri">Telinga Kiri</div>
            <div class="side-body compact-grid">
              <div class="form-field">
                <label class="form-label" for="tuli_kiri">Curiga Tuli Kongenital</label>
                <select id="tuli_kiri" class="form-select" v-model="form.pendengaran.tuli_kiri">
                  <option value="false">Tidak</option>
                  <option value="true">Ya</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="omsk_kiri">OMSK / Congek</label>
                <select id="omsk_kiri" class="form-select" v-model="form.pendengaran.omsk_kiri">
                  <option value="false">Tidak</option>
                  <option value="true">Ya</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="serumen_kiri">Serumen Impaksi</label>
                <select id="serumen_kiri" class="form-select" v-model="form.pendengaran.serumen_kiri">
                  <option value="false">Tidak</option>
                  <option value="true">Ya</option>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="presbi_kiri">Curiga Presbikusis</label>
                <select id="presbi_kiri" class="form-select" v-model="form.pendengaran.presbi_kiri">
                  <option value="false">Tidak</option>
                  <option value="true">Ya</option>
                </select>
              </div>
              <div class="form-field span-full">
                <label class="form-label" for="bisik_kiri">Tes Berbisik</label>
                <select id="bisik_kiri" class="form-select" v-model="form.pendengaran.bisik_kiri">
                  <option value="">Pilih hasil</option>
                  <option value="normal">Normal (&gt;=6 meter)</option>
                  <option value="berat">Berat (&lt;1 m)</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-actions">
        <div class="save-status" :class="{ success: saveStatus === 'ready' }">
          {{ saveMessage }}
        </div>
        <button type="button" class="save-button" :disabled="isSaving" @click="saveGagguanIndera">
          <i class="bi" :class="isSaving ? 'bi-arrow-repeat' : 'bi-save'"></i>
          <span>{{ isSaving ? 'Menyimpan...' : 'Simpan Objective' }}</span>
        </button>
      </div>
    </section>
    <ModalAlert
      :show="showSuccessModal"
      type="success"
      title="Kunjungan Berhasil Disimpan"
      message="Silakan lanjutkan pengisian Faktor Risiko."
      button-text="Tutup"
      secondary-button-text="Lanjut Faktor Risiko"
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

  const props = defineProps({
    DataPasien: Object,
    formData: Object,
  });


  const form = useForm({
    skriningId: props.DataPasien?.idSkrining || null,

    penglihatan : {
      // Tajam Penglihatan
      visus_od: '',
      pinhole_od: '',
      visus_os: '',
      pinhole_os: '',

    // Segmen Anterior & Katarak - Mata Kanan
      sa_od: '',
      rf_od: '',
      st_od: '',
      gio_od: '',

    // Segmen Anterior & Katarak - Mata Kiri
      sa_os: '',
      rf_os: '',
      st_os: '',
      gio_os: '',

    // Retinopati
      retino_od: 'false',
      retino_os: 'false',
    },

    pendengaran: {
      // Pendengaran - Telinga Kanan
      tuli_kanan: 'false',
      omsk_kanan: 'false',
      serumen_kanan: 'false',
      presbi_kanan: 'false',
      bisik_kanan: '',

    // Pendengaran - Telinga Kiri
      tuli_kiri: 'false',
      omsk_kiri: 'false',
      serumen_kiri: 'false',
      presbi_kiri: 'false',
      bisik_kiri: '',
    },

  });

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const validationMessages = ref([]);

  console.log('Form initialized with:', form);

  // --- UI state ---

  const isSaving = ref(false);
  const saveStatus = ref('idle');
  const saveError = ref('');

  // --- Computed ---

  const saveMessage = computed(() => {
    if (saveStatus.value === 'ready') return 'Data subjektif berhasil disimpan.';
    if (saveError.value) return saveError.value;
    return 'Simpan setelah data kunjungan selesai diisi.';
  });

  // --- Actions ---

  console.log('Component loaded');

  function saveGagguanIndera() {
    console.log('Data yang akan dikirim:', form.data());

    isSaving.value = true;
    saveStatus.value = 'idle';
    saveError.value = '';

    showSuccessModal.value = false;
    showValidationModal.value = false;

    validationMessages.value = [];

    form.post(route('pelayanan.simpan-gangguan-indera'), {
      preserveScroll: true,

      onBefore: () => {
        console.log('Mulai request');
      },

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

        validationMessages.value = Object.values(errors).flat();

        saveError.value = extractMessage(errors);

        showValidationModal.value = true;
      },

      onFinish: () => {
        console.log('Request selesai');
        isSaving.value = false;
      },
    });
  }

  function closeDuplicateModal() {
    showDuplicateModal.value = false;
  }

  // --- Error helpers ---

  function extractMessage(errors) {
    return (
      Object.values(errors || {})
        .flat()
        .find(Boolean) || 'Terjadi kesalahan saat menyimpan data.'
    );
  }

  function isDuplicateError(message) {
    const lower = message.toLowerCase();
    return ['sudah', 'tersimpan', 'duplikat', 'duplicate', 'already', 'exists'].some((kw) =>
      lower.includes(kw)
    );
  }
</script>

<style scoped src="./FormPemeriksaan.css"></style>
