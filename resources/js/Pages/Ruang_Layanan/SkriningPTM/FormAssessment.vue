<template>
  <div class="assessment-form animate-fade-in">
    <!-- Panel 1: Masalah Hasil Skrining -->
    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-check"></i> Masalah Hasil Skrining</h4>
          <p>
            Konfirmasi masalah atau faktor risiko PTM dari data subjektif, objektif, dan penunjang.
          </p>
        </div>
        <span class="status-pill complete">{{ form.masalah_hasil_skrining.length }} terkonfirmasi</span>
      </div>

      <div class="panel-body">
        <div class="diagnosis-grid">
          <label class="diagnosis-option" :class="{ checked: form.masalah_hasil_skrining.includes('obesitas') }" for="obesitas">
            <input id="obesitas" class="form-check-input" type="checkbox" value="obesitas" v-model="form.masalah_hasil_skrining" />
            <span>
              <strong>Obesitas / berat badan lebih</strong>
              <small>ICD-10 - E66</small>
              <small v-if="suggestedIssues.includes('obesitas')" class="suggestion-hint">Disarankan dari hasil skrining</small>
            </span>
          </label>

          <label class="diagnosis-option" :class="{ checked: form.masalah_hasil_skrining.includes('hipertensi') }" for="hipertensi">
            <input id="hipertensi" class="form-check-input" type="checkbox" value="hipertensi" v-model="form.masalah_hasil_skrining" />
            <span>
              <strong>Hipertensi / tekanan darah tinggi</strong>
              <small>ICD-10 - I10</small>
              <small v-if="suggestedIssues.includes('hipertensi')" class="suggestion-hint">Disarankan dari hasil skrining</small>
            </span>
          </label>

          <label class="diagnosis-option" :class="{ checked: form.masalah_hasil_skrining.includes('risiko_diabetes') }" for="risiko_diabetes">
            <input id="risiko_diabetes" class="form-check-input" type="checkbox" value="risiko_diabetes" v-model="form.masalah_hasil_skrining" />
            <span>
              <strong>Risiko prediabetes</strong>
              <small>ICD-10 - R73.0</small>
              <small v-if="suggestedIssues.includes('risiko_diabetes')" class="suggestion-hint">Disarankan dari hasil skrining</small>
            </span>
          </label>

          <label class="diagnosis-option" :class="{ checked: form.masalah_hasil_skrining.includes('diabetes_melitus') }" for="diabetes_melitus">
            <input id="diabetes_melitus" class="form-check-input" type="checkbox" value="diabetes_melitus" v-model="form.masalah_hasil_skrining" />
            <span>
              <strong>Diabetes melitus</strong>
              <small>ICD-10 - E11</small>
              <small v-if="suggestedIssues.includes('diabetes_melitus')" class="suggestion-hint">Disarankan dari hasil skrining</small>
            </span>
          </label>

          <label class="diagnosis-option" :class="{ checked: form.masalah_hasil_skrining.includes('dislipidemia') }" for="dislipidemia">
            <input id="dislipidemia" class="form-check-input" type="checkbox" value="dislipidemia" v-model="form.masalah_hasil_skrining" />
            <span>
              <strong>Dislipidemia</strong>
              <small>ICD-10 - E78.5</small>
              <small v-if="suggestedIssues.includes('dislipidemia')" class="suggestion-hint">Disarankan dari hasil skrining</small>
            </span>
          </label>

          <label class="diagnosis-option" :class="{ checked: form.masalah_hasil_skrining.includes('risiko_kardiovaskular') }" for="risiko_kardiovaskular">
            <input id="risiko_kardiovaskular" class="form-check-input" type="checkbox" value="risiko_kardiovaskular" v-model="form.masalah_hasil_skrining" />
            <span>
              <strong>Risiko penyakit kardiovaskular</strong>
              <small>SNOMED CT - 395112001</small>
              <small v-if="suggestedIssues.includes('risiko_kardiovaskular')" class="suggestion-hint">Disarankan dari hasil skrining</small>
            </span>
          </label>

          <label class="diagnosis-option" :class="{ checked: form.masalah_hasil_skrining.includes('perilaku_berisiko') }" for="perilaku_berisiko">
            <input id="perilaku_berisiko" class="form-check-input" type="checkbox" value="perilaku_berisiko" v-model="form.masalah_hasil_skrining" />
            <span>
              <strong>Perilaku berisiko PTM</strong>
              <small>SNOMED CT - 160573003</small>
              <small v-if="suggestedIssues.includes('perilaku_berisiko')" class="suggestion-hint">Disarankan dari hasil skrining</small>
            </span>
          </label>
        </div>
      </div>
    </section>

    <!-- Panel 2: Diagnosis Klinis -->
    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-clipboard2-pulse"></i> Diagnosis Klinis</h4>
          <p>Keputusan diagnosis oleh petugas berdasarkan masalah yang sudah dikonfirmasi.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="diagnosis-form-grid">
          <div class="form-field diagnosis-main-field">
            <label class="form-label" for="diagnosis_utama">Diagnosis Utama</label>
            <select id="diagnosis_utama" name="diagnosis_utama" class="form-select" v-model="form.diagnosis_utama">
              <option value="">Pilih Diagnosis Utama</option>
              <option value="Z13.6 - Skrining penyakit kardiovaskular">Z13.6 - Skrining penyakit kardiovaskular</option>
              <option value="E66 - Obesitas / berat badan lebih">E66 - Obesitas / berat badan lebih</option>
              <option value="I10 - Hipertensi esensial">I10 - Hipertensi esensial</option>
              <option value="R73.0 - Risiko prediabetes">R73.0 - Risiko prediabetes</option>
              <option value="E11 - Diabetes melitus tipe 2">E11 - Diabetes melitus tipe 2</option>
              <option value="E78.5 - Dislipidemia">E78.5 - Dislipidemia</option>
            </select>
            <span class="field-hint">Saran sistem hanya membantu pengisian. Diagnosis final tetap ditentukan petugas.</span>
          </div>

          <div class="form-field">
            <label class="form-label" for="status_klinis">Status Klinis</label>
            <select id="status_klinis" name="status_klinis" class="form-select" v-model="form.status_klinis">
              <option value="active">Aktif</option>
              <option value="recurrence">Berulang</option>
              <option value="remission">Remisi</option>
              <option value="resolved">Selesai / teratasi</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="verification_status">Status Verifikasi Diagnosis</label>
            <select id="verification_status" name="verification_status" class="form-select" :class="verificationStatusClass" v-model="form.verification_status">
              <option value="unconfirmed">Belum dikonfirmasi (Unconfirmed)</option>
              <option value="provisional">Diagnosis sementara (Provisional)</option>
              <option value="confirmed">Sudah dikonfirmasi (Confirmed)</option>
              <option value="refuted">Dibatalkan / Disangkal (Refuted)</option>
            </select>
            <span class="field-hint verification-hint" :class="verificationStatusClass">
              <i class="bi bi-clock" v-if="form.verification_status === 'provisional' || form.verification_status === 'unconfirmed'"></i>
              <i class="bi bi-check-circle" v-else-if="form.verification_status === 'confirmed'"></i>
              <i class="bi bi-x-circle" v-else></i>
              {{ verificationHintText }}
            </span>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="catatan_diagnosis">Catatan Diagnosis / Komorbid</label>
            <textarea
              id="catatan_diagnosis"
              name="catatan_diagnosis"
              class="form-control"
              rows="3"
              v-model="form.catatan_diagnosis"
              placeholder="Diagnosis tambahan, komorbid, atau pertimbangan singkat bila ada"
            ></textarea>
          </div>
        </div>
      </div>
    </section>

    <!-- Panel 3: Kesimpulan Assessment -->
    <section class="assessment-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-journal-check"></i> Kesimpulan Assessment</h4>
          <p>Kesimpulan akhir dari masalah hasil skrining dan diagnosis klinis.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="summary-grid">
          <div class="summary-item">
            <span>Diagnosis Final</span>
            <strong>{{ form.diagnosis_utama || '-' }}</strong>
          </div>

          <div class="summary-item">
            <span>Masalah Hasil Skrining</span>
            <strong>{{ formatSelectedIssues || '-' }}</strong>
          </div>

          <div class="summary-item">
            <span>Kategori Risiko</span>
            <select id="kategori_risiko" class="form-select border-0 bg-transparent fw-bold p-0 text-dark" v-model="form.kategori_risiko">
              <option value="">Pilih Kategori Risiko</option>
              <option value="Risiko Rendah">Risiko Rendah</option>
              <option value="Risiko Sedang">Risiko Sedang</option>
              <option value="Risiko Tinggi">Risiko Tinggi</option>
              <option value="Risiko Sangat Tinggi">Risiko Sangat Tinggi</option>
            </select>
          </div>

          <div class="summary-item">
            <span>Status Verifikasi</span>
            <strong class="verification-badge" :class="verificationStatusClass">
              <i class="bi bi-clock" v-if="form.verification_status === 'provisional' || form.verification_status === 'unconfirmed'"></i>
              <i class="bi bi-check-circle" v-else-if="form.verification_status === 'confirmed'"></i>
              <i class="bi bi-x-circle" v-else></i>
              {{ labelize(form.verification_status) }}
            </strong>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="ringkasan_klinis">Ringkasan Klinis</label>
            <textarea
              id="ringkasan_klinis"
              name="ringkasan_klinis"
              class="form-control"
              rows="3"
              v-model="form.ringkasan_klinis"
              placeholder="Kesimpulan hasil skrining, diagnosis kerja, dan pertimbangan klinis"
            ></textarea>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="catatan_assessment">Catatan Tambahan</label>
            <textarea
              id="catatan_assessment"
              name="catatan_assessment"
              class="form-control"
              rows="3"
              v-model="form.catatan_assessment"
              placeholder="Catatan tambahan bila ada"
            ></textarea>
          </div>
        </div>
      </div>
    </section>

    <!-- Save Status & Actions -->
    <div class="form-actions">
      <div class="save-status" :class="{ success: saveStatus === 'ready' }">
        {{ saveMessage }}
      </div>
      <button type="button" class="save-button" :disabled="form.processing" @click="saveAssessment">
        <i class="bi" :class="form.processing ? 'bi-arrow-repeat spin' : 'bi-save'"></i>
        <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Assessment' }}</span>
      </button>
    </div>

    <!-- Modals -->
    <ModalAlert
      :show="showSuccessModal"
      type="success"
      title="Assessment Berhasil Disimpan"
      message="Data assessment klinis telah tersimpan dan siap disinkronisasikan ke SatuSehat."
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
  import { ref, computed, onMounted, watch } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import ModalAlert from '../../../Components/Layouts/Modal/ModalAlert.vue';

  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const validationMessages = ref([]);
  const saveStatus = ref('idle');
  const saveError = ref('');

  const initialDiagnosisUtama = props.DataPasien?.diagnosis_utama || '';
  const initialStatusKlinis = props.DataPasien?.status_klinis || 'active';
  const initialVerificationStatus = props.DataPasien?.verification_status || 'provisional';
  const initialCatatanDiagnosis = props.DataPasien?.catatan_diagnosis || '';

  // Format Kategori Risiko
  const defaultKategoriRisiko = (() => {
    if (props.DataPasien?.kategori_risiko) return props.DataPasien.kategori_risiko;
    const rawRisk = props.DataPasien?.kat_risiko;
    if (rawRisk === 'rendah') return 'Risiko Rendah';
    if (rawRisk === 'sedang') return 'Risiko Sedang';
    if (rawRisk === 'tinggi') return 'Risiko Tinggi';
    if (rawRisk === 'sangat_tinggi') return 'Risiko Sangat Tinggi';
    return '';
  })();

  const initialRingkasanKlinis = props.DataPasien?.ringkasan_klinis || '';
  const initialCatatanAssessment = props.DataPasien?.catatan_assessment || '';

  // Parse existing array or default to empty
  const initialMasalahHasilSkrining = (() => {
    if (props.DataPasien?.masalah_hasil_skrining) {
      try {
        return Array.isArray(props.DataPasien.masalah_hasil_skrining)
          ? props.DataPasien.masalah_hasil_skrining
          : JSON.parse(props.DataPasien.masalah_hasil_skrining);
      } catch (e) {
        return [];
      }
    }
    return [];
  })();

  const form = useForm({
    skrining_ptm_id: props.DataPasien?.id || null, // Primary key of simpus_skrining_ptm table
    idpelayanan: props.DataPasien?.idpelayanan || '',
    masalah_hasil_skrining: initialMasalahHasilSkrining,
    ringkasan_temuan: [],
    diagnosis_utama: initialDiagnosisUtama,
    diagnosis_utama_saran: props.DataPasien?.diagnosis_utama_saran || '',
    status_klinis: initialStatusKlinis,
    verification_status: initialVerificationStatus,
    catatan_diagnosis: initialCatatanDiagnosis,
    kategori_risiko: defaultKategoriRisiko,
    ringkasan_klinis: initialRingkasanKlinis,
    catatan_assessment: initialCatatanAssessment,
  });

  // Calculate suggested risk factors / problems based on subjective/objective data
  const suggestedIssues = computed(() => {
    const list = [];
    const dp = props.DataPasien || {};

    if (dp.interpretasi_ptm === 'Gemuk' || dp.interpretasi_ptm === 'Obesitas' || dp.interpretasi_lp === 'Risiko meningkat') {
      list.push('obesitas');
    }
    if (dp.kategori_tekanan_darah && dp.kategori_tekanan_darah !== 'Normal' && dp.kategori_tekanan_darah !== 'Elevated') {
      list.push('hipertensi');
    }
    if (dp.kategori_gula_darah_puasa === 'Diabetes' || dp.kategori_gula_darah_sewaktu === 'Diabetes' || dp.kategori_hba1c === 'Diabetes' || dp.kategori_gula_darah_2_jam_pp === 'Diabetes') {
      list.push('diabetes_melitus');
    } else if (dp.kategori_gula_darah_puasa === 'Prediabetes' || dp.kategori_gula_darah_sewaktu === 'Prediabetes' || dp.kategori_hba1c === 'Prediabetes' || dp.kategori_gula_darah_2_jam_pp === 'Prediabetes') {
      list.push('risiko_diabetes');
    }
    if (dp.interpretasi_kolesterol_total === 'Borderline Tinggi' || dp.interpretasi_kolesterol_total === 'Tinggi' || dp.interpretasi_ldl === 'Tinggi' || dp.interpretasi_trigliserida === 'Tinggi') {
      list.push('dislipidemia');
    }
    if (dp.kat_risiko === 'tinggi' || dp.kat_risiko === 'sangat_tinggi') {
      list.push('risiko_kardiovaskular');
    }
    // 6. Perilaku berisiko PTM
    if (dp.merokok === 'ya' || dp.alkohol === 'ya' || dp.aktivitas === 'tidak') {
      list.push('perilaku_berisiko');
    }
    return list;
  });

  // Suggesting Diagnosis Utama based on suggested risk factors on initial load if not set
  onMounted(() => {
    if (!form.diagnosis_utama) {
      if (suggestedIssues.value.includes('diabetes_melitus')) {
        form.diagnosis_utama = 'E11 - Diabetes melitus tipe 2';
      } else if (suggestedIssues.value.includes('hipertensi')) {
        form.diagnosis_utama = 'I10 - Hipertensi esensial';
      } else if (suggestedIssues.value.includes('dislipidemia')) {
        form.diagnosis_utama = 'E78.5 - Dislipidemia';
      } else if (suggestedIssues.value.includes('risiko_diabetes')) {
        form.diagnosis_utama = 'R73.0 - Risiko prediabetes';
      } else if (suggestedIssues.value.includes('obesitas')) {
        form.diagnosis_utama = 'E66 - Obesitas / berat badan lebih';
      } else if (suggestedIssues.value.includes('risiko_kardiovaskular')) {
        form.diagnosis_utama = 'Z13.6 - Skrining penyakit kardiovaskular';
      }
    }

    // Pre-fill suggested problems into form if empty
    if (form.masalah_hasil_skrining.length === 0) {
      form.masalah_hasil_skrining = [...suggestedIssues.value];
    }

    // Pre-fill ringkasan klinis with a nice default template if empty
    if (!form.ringkasan_klinis) {
      const issuesDisplay = form.masalah_hasil_skrining.map(i => labelize(i)).join(', ');
      form.ringkasan_klinis = `Pasien dengan hasil skrining PTM menunjukkan masalah: ${issuesDisplay || '-'}. Kategori risiko: ${form.kategori_risiko || '-'}.`;
    }
  });

  // Format checked checkboxes mapping for UI display
  const formatSelectedIssues = computed(() => {
    return form.masalah_hasil_skrining.map(item => labelize(item)).join(', ');
  });

  const verificationStatusClass = computed(() => {
    return {
      'status-unconfirmed': form.verification_status === 'unconfirmed',
      'status-provisional': form.verification_status === 'provisional',
      'status-confirmed': form.verification_status === 'confirmed',
      'status-refuted': form.verification_status === 'refuted',
    };
  });

  const verificationHintText = computed(() => {
    switch (form.verification_status) {
      case 'unconfirmed':
        return 'Belum dikonfirmasi, perlu pemeriksaan lebih lanjut.';
      case 'provisional':
        return 'Diagnosis sementara, menunggu data penunjang tambahan.';
      case 'confirmed':
        return 'Diagnosis sudah dikonfirmasi secara klinis.';
      case 'refuted':
        return 'Diagnosis dibatalkan atau disangkal.';
      default:
        return '';
    }
  });

  const saveMessage = computed(() => {
    if (saveStatus.value === 'ready') return 'Data assessment berhasil disimpan.';
    if (saveError.value) return saveError.value;
    return 'Simpan setelah diagnosis dan kesimpulan assessment selesai diisi.';
  });

  function labelize(value) {
    if (!value) return '-';
    return String(value)
      .replace(/_/g, ' ')
      .replace(/\b\w/g, (char) => char.toUpperCase());
  }

  function extractMessage(errors) {
    return (
      Object.values(errors || {})
        .flat()
        .find(Boolean) || 'Terjadi kesalahan saat menyimpan data.'
    );
  }

  function saveAssessment() {
    saveStatus.value = 'idle';
    saveError.value = '';
    showSuccessModal.value = false;
    showValidationModal.value = false;
    validationMessages.value = [];

    // Automatically set the saran field to helper matching the suggestion
    form.diagnosis_utama_saran = suggestedIssues.value.map(i => labelize(i)).join(', ');

    form.post(route('pelayanan.simpan-assessment-ptm'), {
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
        validationMessages.value = Object.values(errors).flat();
        saveError.value = extractMessage(errors);
        showValidationModal.value = true;
      },
    });
  }
</script>

<style scoped src="./FormPemeriksaan/FormPemeriksaan.css"></style>

<style scoped>
  .verification-hint {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    margin-top: 0.35rem;
  }

  .verification-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.875rem;
  }

  .status-unconfirmed { color: #6c757d; }
  .status-provisional  { color: #fd7e14; }
  .status-confirmed    { color: #198754; }
  .status-refuted      { color: #dc3545; }

  .spin {
    animation: spin 0.8s linear infinite;
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  .animate-fade-in {
    animation: fadeIn 0.3s ease-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>