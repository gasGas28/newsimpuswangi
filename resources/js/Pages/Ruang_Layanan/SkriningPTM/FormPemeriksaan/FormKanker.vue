<template>
  <div class="genetik-form">
    <!-- ==================== KANKER ==================== -->
    <section class="genetik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-shield-plus"></i> Kanker</h4>
          <p>Procedure dan Observation untuk skrining serviks, payudara, paru, dan kolorektal.</p>
        </div>
      </div>

      <div class="panel-body">
        <!-- Serviks & Payudara -->
        <div class="section-title">
          <h5>Serviks &amp; Payudara</h5>
          <span>Khusus perempuan - IVA, SADANIS, HPV-DNA</span>
        </div>

        <div class="cancer-grid">
          <div class="form-field">
            <label class="form-label" for="inspekulo">Pemeriksaan Inspekulo</label>
            <select
              id="inspekulo"
              name="inspekulo"
              class="form-select"
              v-model="formServiks.inspekulo"
            >
              <option value="Tidak Dilakukan">Tidak dilakukan</option>
              <option value="Suspected cervical cancer">Curiga Kanker</option>
              <option value="No evidence of cancer found">Tidak curiga kanker</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="iva">Hasil IVA</label>
            <select id="iva" name="iva" class="form-select" v-model="formServiks.iva">
              <option value="Tidak Dilakukan">Tidak dilakukan</option>
              <option value="negatif">IVA Negatif</option>
              <option value="positif">IVA Positif</option>
              <option value="curiga_kanker">Curiga Kanker Serviks</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="hpv">HPV-DNA</label>
            <select id="hpv" name="hpv" class="form-select" v-model="formServiks.hpv">
              <option value="Tidak Dilakukan">Tidak dilakukan</option>
              <option value="negatif">Negatif</option>
              <option value="positif">Positif</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="sadanis">SADANIS (Payudara)</label>
            <select id="sadanis" name="sadanis" class="form-select" v-model="formServiks.sadanis">
              <option value="Tidak Dilakukan">Tidak dilakukan</option>
              <option value="normal">Normal</option>
              <option value="curiga">Curiga kelainan</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="usg_py">USG Payudara</label>
            <select id="usg_py" name="usg_py" class="form-select" v-model="formServiks.usg_py">
              <option value="Tidak Dilakukan">Tidak dilakukan</option>
              <option value="normal">Normal</option>
              <option value="curiga">Curiga kelainan</option>
            </select>
          </div>
        </div>

        <div v-if="formServiks.iva === 'positif'" class="follow-up-panel">
          <div class="follow-up-alert">
            <i class="bi bi-exclamation-circle"></i>
            <span>IVA positif, pilih tindak lanjut yang sesuai.</span>
          </div>
          <div class="check-grid">
            <label class="check-option">
              <input v-model="formServiks.krioterapi" type="checkbox" />
              <span>Krioterapi</span>
            </label>
            <label class="check-option">
              <input v-model="formServiks.thermal" type="checkbox" />
              <span>Thermal ablation</span>
            </label>
            <label class="check-option">
              <input v-model="formServiks.tca" type="checkbox" />
              <span>Trichloroacetic acid</span>
            </label>
            <label class="check-option">
              <input v-model="formServiks.rujuk_serviks" type="checkbox" />
              <span>Rujuk faskes lanjut</span>
            </label>
          </div>
        </div>

        <div class="form-actions">
          <div class="save-status" :class="{ success: saveStatus.serviks === 'ready' }">
            {{ saveMessage.serviks }}
          </div>
          <button
            type="button"
            class="save-button"
            :disabled="isSaving.serviks"
            @click="saveServiks"
          >
            <i class="bi" :class="isSaving.serviks ? 'bi-arrow-repeat' : 'bi-save'"></i>
            <span>{{ isSaving.serviks ? 'Menyimpan...' : 'Simpan Serviks & Payudara' }}</span>
          </button>
        </div>

        <!-- Kanker Paru -->
        <div class="section-title with-gap">
          <h5>Kanker Paru</h5>
          <span>QuestionnaireResponse Q0019</span>
        </div>

        <div class="question-grid">
          <div class="form-field">
            <label class="form-label" for="kp1">Pernah didiagnosis/menderita kanker?</label>
            <select id="kp1" name="kp1" class="form-select" v-model="formParu.kp1">
              <option value="">Tidak dilakukan</option>
              <option value="Memiliki diagnosis kanker >5 tahun yang lalu">
                Ya, pernah lebih 5 tahun yang lalu
              </option>
              <option value="Memiliki diagnosis kanker <5 tahun yang lalu">
                Ya, pernah kurang dari 5 tahun yang lalu
              </option>
              <option value="Tidak pernah didiagnosis menderita kanker">Tidak</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp2">Ada keluarga yang didiagnosis kanker?</label>
            <select id="kp2" name="kp2" class="form-select" v-model="formParu.kp2">
              <option value="">Tidak dilakukan</option>
              <option value="Family history of malignant neoplasm of lung">
                Memiliki keluarga yang terdiagnosis kanker paru
              </option>
              <option value="Memiliki keluarga yang terdiagnosis kanker lain">
                Memiliki keluarga yang terdiagnosis kanker lain
              </option>
              <option value="Tidak ada keluarga yang terdiagnosis kanker">
                Tidak ada keluarga yang terdiagnosis kanker
              </option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp3">Riwayat merokok/paparan asap rokok?</label>
            <select id="kp3" name="kp3" class="form-select" v-model="formParu.kp3">
              <option value="">Tidak dilakukan</option>
              <option value="Perokok aktif (dalam 1 tahun ini masih merokok)">
                Perokok aktif (dalam 1 tahun ini masih merokok)
              </option>
              <option value="Perokok/bekas perokok berhenti <10 tahun lalu">
                Perokok/bekas perokok berhenti kurang dari 10 tahun lalu
              </option>
              <option value="Occupational exposure to environmental tobacco smoke">
                Perokok pasif dari lingkungan rumah/tempat kerja
              </option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp4">Tempat kerja mengandung zat karsinogenik?</label>
            <select id="kp4" name="kp4" class="form-select" v-model="formParu.kp4">
              <option value="">Tidak dilakukan</option>
              <option value="Memiliki tempat kerja mengandung zat karsinogenik">
                Memiliki tempat kerja mengandung zat karsinogenik
              </option>
              <option value="Tidak yakin memiliki tempat kerja mengandung zat karsinogenik">
                Tidak memiliki tempat kerja mengandung zat karsinogenik
              </option>
              <option value="Tidak memiliki tempat kerja mengandung zat karsinogenik">
                Tidak yakin memiliki tempat kerja mengandung zat karsinogenik
              </option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp5">Lingkungan dekat pabrik/pertambangan?</label>
            <select id="kp5" name="kp5" class="form-select" v-model="formParu.kp5">
              <option value="">Tidak dilakukan</option>
              <option value="Memiliki tempat tinggal berpotensi tinggi">
                Memiliki tempat tinggal berpotensi tinggi
              </option>
              <option value="Tidak yakin memiliki tempat tinggal berpotensi tinggi">
                Tidak yakin memiliki tempat tinggal berpotensi tinggi
              </option>
              <option value="Tidak memiliki tempat tinggal berpotensi tinggi">
                Tidak memiliki tempat tinggal berpotensi tinggi
              </option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp6">Lingkungan dalam rumah tidak sehat?</label>
            <select id="kp6" name="kp6" class="form-select" v-model="formParu.kp6">
              <option value="">Tidak dilakukan</option>
              <option value="Memiliki lingkungan dalam rumah yang sehat">
                Memiliki lingkungan dalam rumah yang sehat
              </option>
              <option value="Memiliki lingkungan dalam rumah yang tidak sehat">
                Memiliki lingkungan dalam rumah yang tidak sehat
              </option>
              <option value="Tidak yakin memiliki lingkungan dalam rumah yang tidak sehat">
                Tidak yakin memiliki lingkungan dalam rumah yang tidak sehat
              </option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kp7">Pernah didiagnosis penyakit paru kronik?</label>
            <select id="kp7" name="kp7" class="form-select" v-model="formParu.kp7">
              <option value="">Tidak dilakukan</option>
              <option value="History of tuberculosis (situation)">
                Pernah didiagnosis tuberkulosis (TBC)
              </option>
              <option value="History of chronic lung disease (situation)">
                Pernah didiagnosis penyakit kronis lain (PPOK, ILD, dll)
              </option>
              <option value="pernah_not_tbc">Tidak pernah didiagnosis penyakit paru kronik</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="hasil_kkp">Hasil Kuesioner</label>
            <select
              id="hasil_kkp"
              name="hasil_kkp"
              class="form-select"
              v-model="formParu.hasil_kkp"
            >
              <option value="">Pilih jawaban</option>
              <option value="Low Risk">Risiko Ringan Kanker Paru</option>
              <option value="Moderate Risk Of">Risiko Sedang Kanker Paru</option>
              <option value="High Risk">Risiko Berat Kanker Paru</option>
            </select>
          </div>
        </div>

        <div class="form-actions">
          <div class="save-status" :class="{ success: saveStatus.paru === 'ready' }">
            {{ saveMessage.paru }}
          </div>
          <button type="button" class="save-button" :disabled="isSaving.paru" @click="saveParu">
            <i class="bi" :class="isSaving.paru ? 'bi-arrow-repeat' : 'bi-save'"></i>
            <span>{{ isSaving.paru ? 'Menyimpan...' : 'Simpan Kanker Paru' }}</span>
          </button>
        </div>

        <!-- Kanker Kolorektal -->
        <div class="section-title with-gap">
          <h5>Kanker Kolorektal</h5>
          <span>QuestionnaireResponse Q0020 - diutamakan usia &gt;50 tahun</span>
        </div>

        <div class="kolorektal-grid">
          <div class="form-field">
            <label class="form-label" for="kkr1"
              >Riwayat keluarga generasi pertama kanker kolorektal?</label
            >
            <select id="kkr1" name="kkr1" class="form-select" v-model="formKolorektal.kkr1">
              <option value="">Pilih jawaban</option>
              <option value="false">
                Tidak memiliki riwayat keluarga kanker kolorektal generasi pertama
              </option>
              <option value="true">
                Memiliki riwayat keluarga kanker kolorektal generasi pertama
              </option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="kkr2">Apakah peserta merokok?</label>
            <select id="kkr2" name="kkr2" class="form-select" v-model="formKolorektal.kkr2">
              <option value="">Pilih jawaban</option>
              <option value="false">Tidak</option>
              <option value="true">Ya</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="hasil_kkr">Hasil Skoring Kolorektal</label>
            <select
              id="hasil_kkr"
              name="hasil_kkr"
              class="form-select"
              v-model="formKolorektal.hasil_kkr"
              disabled
            >
              <option value="">Belum ada hasil</option>
              <option value="Low">Risiko Ringan (Skor 0-1)</option>
              <option value="Moderate">Risiko Sedang (Skor 2-3)</option>
              <option value="High">Risiko Tinggi (Skor 4-7)</option>
            </select>
            <small class="form-hint"
              >Skor saat ini: {{ skorKolorektal }} — {{ interpretasiKolorektal.label }}</small
            >
          </div>

          <div v-if="interpretasiKolorektal.value === 'tinggi'">
            <div class="form-field">
              <label class="form-label" for="colok_dubur">Pemeriksaan Colok Dubur</label>
              <select
                id="colok_dubur"
                name="colok_dubur"
                class="form-select"
                v-model="formKolorektal.colok_dubur"
              >
                <option value="">Tidak dilakukan</option>
                <option value="normal">Normal</option>
                <option value="curiga">Curiga</option>
              </select>
            </div>

            <div class="form-field">
              <label class="form-label" for="darah_samar">Darah Samar Feses</label>
              <select
                id="darah_samar"
                name="darah_samar"
                class="form-select"
                v-model="formKolorektal.darah_samar"
              >
                <option value="">Tidak dilakukan</option>
                <option value="negatif">Negatif</option>
                <option value="positif">Positif</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-actions">
          <div class="save-status" :class="{ success: saveStatus.kolorektal === 'ready' }">
            {{ saveMessage.kolorektal }}
          </div>
          <button
            type="button"
            class="save-button"
            :disabled="isSaving.kolorektal"
            @click="saveKolorektal"
          >
            <i class="bi" :class="isSaving.kolorektal ? 'bi-arrow-repeat' : 'bi-save'"></i>
            <span>{{ isSaving.kolorektal ? 'Menyimpan...' : 'Simpan Kanker Kolorektal' }}</span>
          </button>
        </div>
      </div>
    </section>

    <!-- ==================== THALASEMIA ==================== -->
    <section class="genetik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-droplet-half"></i> Thalasemia</h4>
          <p>Observation hematology analyzer untuk deteksi dini thalasemia.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="hematologi-grid">
          <div class="form-field">
            <label class="form-label" for="hb">Hemoglobin (Hb)</label>
            <div class="input-with-addon">
              <input
                id="hb"
                name="hb"
                type="number"
                step="0.1"
                class="form-control"
                v-model.number="formThalasemia.hb"
                placeholder="0.0"
              />
              <span>g/dL</span>
            </div>
            <small>L: 13-17, P: 12-16</small>
          </div>

          <div class="form-field">
            <label class="form-label" for="mcv">MCV</label>
            <div class="input-with-addon">
              <input
                id="mcv"
                name="mcv"
                type="number"
                step="0.1"
                class="form-control"
                v-model.number="formThalasemia.mcv"
                placeholder="0.0"
              />
              <span>fL</span>
            </div>
            <small>Normal 80-100 fL</small>
          </div>

          <div class="form-field">
            <label class="form-label" for="mch">MCH</label>
            <div class="input-with-addon">
              <input
                id="mch"
                name="mch"
                type="number"
                step="0.1"
                class="form-control"
                v-model.number="formThalasemia.mch"
                placeholder="0.0"
              />
              <span>pg</span>
            </div>
            <small>Normal 27-33 pg</small>
          </div>

          <div class="form-field">
            <label class="form-label" for="rbc">Eritrosit / RBC Count</label>
            <div class="input-with-addon">
              <input
                id="rbc"
                name="rbc"
                type="number"
                step="0.01"
                class="form-control"
                v-model.number="formThalasemia.rbc"
                placeholder="0.00"
              />
              <span>juta/uL</span>
            </div>
            <small>Normal 4.5-5.5</small>
          </div>

          <div class="form-field">
            <label class="form-label" for="rdw">RDW</label>
            <div class="input-with-addon">
              <input
                id="rdw"
                name="rdw"
                type="number"
                step="0.1"
                class="form-control"
                v-model.number="formThalasemia.rdw"
                placeholder="0.0"
              />
              <span>%</span>
            </div>
            <small>Normal 11.5-14.5%</small>
          </div>
        </div>

        <div class="form-actions">
          <div class="save-status" :class="{ success: saveStatus.thalasemia === 'ready' }">
            {{ saveMessage.thalasemia }}
          </div>
          <button
            type="button"
            class="save-button"
            :disabled="isSaving.thalasemia"
            @click="saveThalasemia"
          >
            <i class="bi" :class="isSaving.thalasemia ? 'bi-arrow-repeat' : 'bi-save'"></i>
            <span>{{ isSaving.thalasemia ? 'Menyimpan...' : 'Simpan Thalasemia' }}</span>
          </button>
        </div>
      </div>
    </section>

    <!-- ==================== JANTUNG / EKG ==================== -->
    <section class="genetik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-heart-pulse"></i> Jantung</h4>
          <p>Procedure dan Observation pemeriksaan EKG untuk deteksi dini penyakit jantung.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="follow-up-panel">
          <div class="follow-up-alert">
            <i class="bi bi-exclamation-circle"></i>
            <span>
              Pemeriksaan EKG hanya dilakukan pada peserta dengan riwayat
              <strong>Diabetes Melitus</strong> dan/atau <strong>Hipertensi</strong>.
            </span>
          </div>
        </div>

        <div class="section-title with-gap">
          <h5>Pemeriksaan EKG</h5>
          <span>Procedure &amp; Observation Elektrokardiografi</span>
        </div>

        <div class="hematologi-grid">
          <div class="form-field">
            <label class="form-label" for="ekg_hr">Heart Rate</label>
            <div class="input-with-addon">
              <input
                id="ekg_hr"
                name="ekg_hr"
                type="number"
                step="1"
                class="form-control"
                v-model.number="formEkg.hr"
                placeholder="0"
              />
              <span>bpm</span>
            </div>
            <small>Normal 60-100 bpm</small>
          </div>

          <div class="form-field">
            <label class="form-label" for="ekg_irama">Irama</label>
            <select id="ekg_irama" name="ekg_irama" class="form-select" v-model="formEkg.irama">
              <option value="">Tidak dilakukan</option>
              <option value="sinus">Sinus Rhythm</option>
              <option value="aritmia">Aritmia</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="ekg_axis">Axis</label>
            <select id="ekg_axis" name="ekg_axis" class="form-select" v-model="formEkg.axis">
              <option value="">Tidak dilakukan</option>
              <option value="normal">Normal</option>
              <option value="lad">Left Axis Deviation</option>
              <option value="rad">Right Axis Deviation</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="ekg_st">Segmen ST</label>
            <select id="ekg_st" name="ekg_st" class="form-select" v-model="formEkg.st">
              <option value="">Tidak dilakukan</option>
              <option value="normal">Normal</option>
              <option value="elevasi">ST Elevasi</option>
              <option value="depresi">ST Depresi</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="ekg_qrs">Kompleks QRS</label>
            <select id="ekg_qrs" name="ekg_qrs" class="form-select" v-model="formEkg.qrs">
              <option value="">Tidak dilakukan</option>
              <option value="normal">Normal</option>
              <option value="abnormal">Abnormal</option>
            </select>
          </div>

          <div class="form-field result-field">
            <label class="form-label" for="hasil_ekg">Kesimpulan EKG</label>
            <select id="hasil_ekg" name="hasil_ekg" class="form-select" v-model="formEkg.hasil_ekg">
              <option value="">Pilih hasil</option>
              <option value="Electrocardiogram normal">Normal</option>
              <option value="Electrocardiogram abnormal">Abnormal</option>
            </select>
          </div>
        </div>

        <div class="form-actions">
          <div class="save-status" :class="{ success: saveStatus.ekg === 'ready' }">
            {{ saveMessage.ekg }}
          </div>
          <button type="button" class="save-button" :disabled="isSaving.ekg" @click="saveEkg">
            <i class="bi" :class="isSaving.ekg ? 'bi-arrow-repeat' : 'bi-save'"></i>
            <span>{{ isSaving.ekg ? 'Menyimpan...' : 'Simpan EKG' }}</span>
          </button>
        </div>
      </div>
    </section>

    <!-- ==================== MODALS ==================== -->
    <ModalAlert
      :show="showSuccessModal"
      type="success"
      title="Kunjungan Berhasil Disimpan"
      message="Silakan lanjutkan pengisian Selanjutnya."
      button-text="Close"
      secondary-button-text="Lanjut Pemeriksaan Berikutnya"
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
  import { useForm } from '@inertiajs/vue3';
  import { ref, computed, watch } from 'vue';
  import { route } from 'ziggy-js';
  import ModalAlert from '../../../../Components/Layouts/Modal/ModalAlert.vue';

  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });

  const skriningId = props.DataPasien?.idSkrining || null;

  const formServiks = useForm({
    skriningId,
    inspekulo: '',
    iva: '',
    hpv: '',
    sadanis: '',
    usg_py: '',
    krioterapi: false,
    thermal: false,
    tca: false,
    rujuk_serviks: false,
  });

  const formParu = useForm({
    skriningId,
    kp1: '',
    kp2: '',
    kp3: '',
    kp4: '',
    kp5: '',
    kp6: '',
    kp7: '',
    hasil_kkp: '',
  });

  const formKolorektal = useForm({
    skriningId,
    kkr1: '',
    kkr2: '',
    hasil_kkr: '',
    colok_dubur: 'Tidak Dilakukan',
    darah_samar: 'Tidak Dilakukan',
  });

  const formThalasemia = useForm({
    skriningId,
    hb: '',
    mcv: '',
    mch: '',
    rbc: '',
    rdw: '',
  });

  const formEkg = useForm({
    skriningId,
    hr: '',
    irama: '',
    axis: '',
    st: '',
    qrs: '',
    hasil_ekg: '',
  });

  // Skoring Kuesioner Kanker Kolorektal (APCS Score)
  const skorKolorektal = computed(() => {
    let skor = 0;

    const umur = props.DataPasien?.umur ?? 0;
    if (umur >= 70) {
      skor += 3;
    } else if (umur >= 50) {
      skor += 2;
    }

    if (props.DataPasien?.jenis_klmin === 1) {
      skor += 1;
    }

    if (formKolorektal.kkr1 === 'true') {
      skor += 2;
    }

    if (formKolorektal.kkr2 === 'true') {
      skor += 1;
    }

    return skor;
  });

  const interpretasiKolorektal = computed(() => {
    const skor = skorKolorektal.value;
    if (skor <= 1) {
      return { value: 'Low', label: 'Risiko Ringan', kelas: 'risk-low' };
    } else if (skor <= 3) {
      return { value: 'Moderate', label: 'Risiko Sedang', kelas: 'risk-medium' };
    } else {
      return { value: 'High', label: 'Risiko Tinggi', kelas: 'risk-high' };
    }
  });

  watch(
    () => [formKolorektal.kkr1, formKolorektal.kkr2],
    () => {
      formKolorektal.hasil_kkr = interpretasiKolorektal.value.value;
    },
    { immediate: true }
  );

  // ── UI State ──────────────────────────────────────────────────────────────────

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const validationMessages = ref([]);

  const isSaving = ref({
    serviks: false,
    paru: false,
    kolorektal: false,
    thalasemia: false,
    ekg: false,
  });

  const saveStatus = ref({
    serviks: 'idle',
    paru: 'idle',
    kolorektal: 'idle',
    thalasemia: 'idle',
    ekg: 'idle',
  });

  const saveError = ref({
    serviks: '',
    paru: '',
    kolorektal: '',
    thalasemia: '',
    ekg: '',
  });

  // ── Computed messages ─────────────────────────────────────────────────────────

  const saveMessage = computed(() => ({
    serviks: msgFor('serviks'),
    paru: msgFor('paru'),
    kolorektal: msgFor('kolorektal'),
    thalasemia: msgFor('thalasemia'),
    ekg: msgFor('ekg'),
  }));

  function msgFor(key) {
    if (saveStatus.value[key] === 'ready') return 'Data berhasil disimpan.';
    if (saveError.value[key]) return saveError.value[key];
    return 'Simpan setelah data selesai diisi.';
  }

  // ── Generic save helper ───────────────────────────────────────────────────────

  function saveSection(key, form, routeName) {
    isSaving.value[key] = true;
    saveStatus.value[key] = 'idle';
    saveError.value[key] = '';
    showSuccessModal.value = false;
    showValidationModal.value = false;
    validationMessages.value = [];

    form.post(route(routeName), {
      preserveScroll: true,

      onSuccess: () => {
        saveStatus.value[key] = 'ready';
        saveError.value[key] = '';
        validationMessages.value = [];
        form.clearErrors();
        form.defaults(form.data());
        showSuccessModal.value = true;
      },

      onError: (errors) => {
        saveStatus.value[key] = 'error';
        validationMessages.value = Object.values(errors).flat();
        saveError.value[key] = extractMessage(errors);
        showValidationModal.value = true;
      },

      onFinish: () => {
        isSaving.value[key] = false;
      },
    });
  }

  // ── Save actions ──────────────────────────────────────────────────────────────

  const saveServiks = () => saveSection('serviks', formServiks, 'pelayanan.simpan-serviks');
  const saveParu = () => saveSection('paru', formParu, 'pelayanan.simpan-paru');
  const saveKolorektal = () =>
    saveSection('kolorektal', formKolorektal, 'pelayanan.simpan-kolorektal');
  const saveThalasemia = () =>
    saveSection('thalasemia', formThalasemia, 'pelayanan.simpan-thalasemia');
  const saveEkg = () => saveSection('ekg', formEkg, 'pelayanan.simpan-ekg');

  // ── Error helpers ─────────────────────────────────────────────────────────────

  function extractMessage(errors) {
    return (
      Object.values(errors || {})
        .flat()
        .find(Boolean) || 'Terjadi kesalahan saat menyimpan data.'
    );
  }
</script>

<style scoped src="./FormPemeriksaan.css"></style>
