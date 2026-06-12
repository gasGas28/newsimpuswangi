<template>
  <div class="metabolik-form">
    <section class="metabolik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-speedometer2"></i> Obesitas</h4>
          <p>Antropometri, IMT, dan lingkar pinggang.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="metric-grid">
          <div class="form-field">
            <label class="form-label" for="bb">Berat Badan</label>
            <div class="input-with-addon">
              <input
                id="bb"
                class="form-control"
                type="number"
                min="0"
                step="0.1"
                v-model.number="form.obesitas.berat_badan"
                placeholder="0.0"
                @input="hitungIMT"
              />
              <span>kg</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="tb">Tinggi Badan</label>
            <div class="input-with-addon">
              <input
                id="tb"
                class="form-control"
                type="number"
                min="0"
                step="0.1"
                v-model.number="form.obesitas.tinggi_badan"
                placeholder="0.0"
                @input="hitungIMT"
              />
              <span>cm</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="imt">IMT</label>
            <div class="input-with-addon">
              <input
                id="imt"
                class="form-control"
                type="text"
                v-model="form.obesitas.imt"
                readonly
                placeholder="-"
              />
              <span>kg/m2</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="imt_interp">Interpretasi IMT</label>
            <input
              id="imt_interp"
              class="form-control"
              type="text"
              v-model="form.obesitas.interpretasi_imt"
              readonly
              placeholder="-"
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="lp">Lingkar Pinggang</label>
            <div class="input-with-addon">
              <input
                id="lp"
                class="form-control"
                type="number"
                min="0"
                step="0.1"
                v-model.number="form.obesitas.lingkar_pinggang"
                placeholder="0.0"
                @input="interpretLP"
              />
              <span>cm</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="lp_interp">Interpretasi Lingkar Pinggang</label>
            <input
              id="lp_interp"
              class="form-control"
              type="text"
              v-model="form.obesitas.interpretasi_lingkar_pinggang"
              readonly
              placeholder="-"
            />
          </div>
        </div>
      </div>
    </section>

    <section class="metabolik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-heart-pulse"></i> Hipertensi</h4>
          <p>Tekanan darah, nadi, frekuensi napas, dan suhu tubuh.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="metric-grid">
          <div class="form-field">
            <label class="form-label" for="td_s">Tekanan Darah Sistolik</label>
            <div class="input-with-addon">
              <input
                id="td_s"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.hipertensi.sistolik"
                placeholder="120"
                @input="interpretTD"
              />
              <span>mmHg</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="td_d">Tekanan Darah Diastolik</label>
            <div class="input-with-addon">
              <input
                id="td_d"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.hipertensi.tekanan_diastolik"
                placeholder="80"
                @input="interpretTD"
              />
              <span>mmHg</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="td_interp">Interpretasi Hipertensi</label>
            <input
              id="td_interp"
              class="form-control"
              type="text"
              v-model="form.hipertensi.kategori_tekanan_darah"
              readonly
              placeholder="-"
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="nadi">Frekuensi Nadi</label>
            <div class="input-with-addon">
              <input
                id="nadi"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.hipertensi.nadi"
                placeholder="80"
              />
              <span>x/mnt</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="napas">Frekuensi Napas</label>
            <div class="input-with-addon">
              <input
                id="napas"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.hipertensi.pernapasan"
                placeholder="18"
              />
              <span>x/mnt</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="suhu">Suhu Tubuh</label>
            <div class="input-with-addon">
              <input
                id="suhu"
                class="form-control"
                type="number"
                min="0"
                step="0.1"
                v-model.number="form.hipertensi.suhu"
                placeholder="36.5"
              />
              <span>C</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="metabolik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-droplet-half"></i> Diabetes Melitus</h4>
          <p>Gula darah puasa, gula darah sewaktu, HbA1c, dan interpretasi diabetes.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="metric-grid">
          <div class="form-field">
            <label class="form-label" for="gd_puasa">Gula Darah Puasa</label>
            <div class="input-with-addon">
              <input
                id="gd_puasa"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.diabetes.gdp"
                placeholder="0"
                @input="interpretDM"
              />
              <span>mg/dL</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="dm_interp">Interpretasi GDP</label>
            <input
              id="dm_interp"
              class="form-control"
              type="text"
              v-model="form.diabetes.interpretasi_gdp"
              readonly
              placeholder="-"
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="gd_sewaktu">Gula Darah Sewaktu</label>
            <div class="input-with-addon">
              <input
                id="gd_sewaktu"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.diabetes.gds"
                placeholder="0"
                @input="interpretDM"
              />
              <span>mg/dL</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="dm_interp">Interpretasi GDS</label>
            <input
              id="dm_interp"
              class="form-control"
              type="text"
              v-model="form.diabetes.interpretasi_gds"
              readonly
              placeholder="-"
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="hba1c">HbA1c</label>
            <div class="input-with-addon">
              <input
                id="hba1c"
                class="form-control"
                type="number"
                min="0"
                step="0.1"
                v-model.number="form.diabetes.hba1c"
                placeholder="0.0"
                @input="interpretDM"
              />
              <span>%</span>
            </div>
          </div>
          <div class="form-field">
            <label class="form-label" for="dm_interp">Interpretasi hbA1C</label>
            <input
              id="dm_interp"
              class="form-control"
              type="text"
              v-model="form.diabetes.interpretasi_hba1c"
              readonly
              placeholder="-"
            />
          </div>
          <div class="form-field">
            <label class="form-label" for="gd2pp">Gula Darah 2 Jam Pasca Puasa</label>
            <div class="input-with-addon">
              <input
                id="gd2pp"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.diabetes.gd2pp"
                placeholder="0.0"
                @input="interpretDM"
              />
              <span>mg/dL</span>
            </div>
          </div>
          <div class="form-field">
            <label class="form-label" for="dm_interp">Interpretasi GD2PP</label>
            <input
              id="dm_interp"
              class="form-control"
              type="text"
              v-model="form.diabetes.interpretasi_gd2pp"
              readonly
              placeholder="-"
            />
          </div>
        </div>
      </div>
    </section>

    <section class="metabolik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-droplet"></i> Asam Urat</h4>
          <p>Asam urat serum dan interpretasi risiko hiperurisemia.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="metric-grid">
          <div class="form-field">
            <label class="form-label" for="asam_urat">Asam Urat</label>
            <div class="input-with-addon">
              <input
                id="asam_urat"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.asam_urat.asam_urat"
                placeholder="0"
                @input="interpretUrat"
              />
              <span>mg/dL</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="urat_interp">Interpretasi Asam Urat</label>
            <input
              id="urat_interp"
              class="form-control"
              type="text"
              v-model="form.asam_urat.interpretasi_asam_urat"
              readonly
              placeholder="-"
            />
          </div>
        </div>
      </div>
    </section>

    <section class="metabolik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-capsule"></i> Profil Lipid</h4>
          <p>Kolesterol total, HDL, LDL, trigliserida, dan prediksi risiko PTM.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="lab-grid">
          <div class="form-field lab-field">
            <label class="form-label" for="koltot">Kolesterol Total</label>
            <div class="input-with-addon">
              <input
                id="koltot"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.profil_lipid.kolesterol_total"
                placeholder="0"
                @input="interpretLipid"
              />
              <span>mg/dL</span>
            </div>
            <input
              class="form-control interpretation-control"
              type="text"
              v-model="form.profil_lipid.interpretasi_kolesterol_total"
              readonly
              placeholder="Interpretasi"
            />
            <small>LOINC 2093-3</small>
          </div>

          <div class="form-field lab-field">
            <label class="form-label" for="hdl">HDL</label>
            <div class="input-with-addon">
              <input
                id="hdl"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.profil_lipid.hdl"
                placeholder="0"
                @input="interpretLipid"
              />
              <span>mg/dL</span>
            </div>
            <input
              class="form-control interpretation-control"
              type="text"
              v-model="form.profil_lipid.interpretasi_hdl"
              readonly
              placeholder="Interpretasi"
            />
            <small>LOINC 2085-9</small>
          </div>

          <div class="form-field lab-field">
            <label class="form-label" for="ldl">LDL</label>
            <div class="input-with-addon">
              <input
                id="ldl"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.profil_lipid.ldl"
                placeholder="0"
                @input="interpretLipid"
              />
              <span>mg/dL</span>
            </div>
            <input
              class="form-control interpretation-control"
              type="text"
              v-model="form.profil_lipid.interpretasi_ldl"
              readonly
              placeholder="Interpretasi"
            />
            <small>LOINC 2089-1</small>
          </div>

          <div class="form-field lab-field">
            <label class="form-label" for="tg">Trigliserida</label>
            <div class="input-with-addon">
              <input
                id="tg"
                class="form-control"
                type="number"
                min="0"
                v-model.number="form.profil_lipid.trigliserida"
                placeholder="0"
                @input="interpretLipid"
              />
              <span>mg/dL</span>
            </div>
            <input
              class="form-control interpretation-control"
              type="text"
              v-model="form.profil_lipid.interpretasi_trigliserida"
              readonly
              placeholder="Interpretasi"
            />
            <small>LOINC 2571-8</small>
          </div>
        </div>

        <div class="section-title with-gap">
          <h5>Prediksi Risiko PTM</h5>
          <span>WHO/ISH risk chart</span>
        </div>

        <div class="risk-grid">
          <div class="form-field">
            <label class="form-label" for="risiko_lab">Risiko dengan Lab</label>
            <div class="input-with-addon">
              <input
                id="risiko_lab"
                class="form-control"
                type="number"
                min="0"
                max="100"
                v-model.number="form.risiko_lab"
                placeholder="0"
              />
              <span>%</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="risiko_nolab">Risiko tanpa Lab</label>
            <div class="input-with-addon">
              <input
                id="risiko_nolab"
                class="form-control"
                type="number"
                min="0"
                max="100"
                v-model.number="form.risiko_nolab"
                placeholder="0"
              />
              <span>%</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="kat_risiko">Interpretasi Risiko</label>
            <select id="kat_risiko" class="form-select" v-model="form.kat_risiko">
              <option value="">Pilih kategori</option>
              <option value="rendah">&lt;10% (Rendah)</option>
              <option value="sedang">10-20% (Sedang)</option>
              <option value="tinggi">20-30% (Tinggi)</option>
              <option value="sangat_tinggi">&gt;30% (Sangat Tinggi)</option>
            </select>
          </div>
        </div>
      </div>
    </section>
    <div class="form-actions">
      <div class="save-status" :class="{ success: saveStatus === 'ready' }">
        {{ saveMessage }}
      </div>
      <button
        type="button"
        class="save-button"
        :disabled="form.processing"
        @click="savePemeriksaan"
      >
        <i class="bi" :class="form.processing ? 'bi-arrow-repeat' : 'bi-save'"></i>
        <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Pemeriksaan Metabolik' }}</span>
      </button>
    </div>
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
  import ModalAlert from '../../../Modal/ModalAlert.vue';

  // --- Props ---
  const props = defineProps({
    DataPasien: Object,
    tindakan: Array,
  });

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const showDuplicateModal = ref(false); // ← tambahkan jika memang dipakai
  const validationMessages = ref([]);

  const pasien = computed(() => props.DataPasien || {});

  // --- Form ---
  const form = useForm({
    skriningId: props.DataPasien?.idSkrining || null,

    hipertensi: {
      sistolik: null,
      tekanan_diastolik: null,
      kategori_tekanan_darah: '',
      suhu: null,
      nadi: null,
      pernapasan: null,
    },

    diabetes: {
      gdp: null,
      interpretasi_gdp: '',
      gds: null,
      interpretasi_gds: '',
      hba1c: null,
      interpretasi_hba1c: '',
      gd2pp: null,
      interpretasi_gd2pp: '',
    },

    obesitas: {
      berat_badan: null,
      tinggi_badan: null,
      imt: null,
      interpretasi_imt: '',
      lingkar_pinggang: null,
      interpretasi_lingkar_pinggang: '',
    },

    profil_lipid: {
      kolesterol_total: null,
      interpretasi_kolesterol_total: '',
      ldl: null,
      interpretasi_ldl: '',
      hdl: null,
      interpretasi_hdl: '',
      trigliserida: null,
      interpretasi_trigliserida: '',
    },

    asam_urat: {
      asam_urat: null,
      interpretasi_asam_urat: '',
    },
    risiko_lab: null,
    risiko_nolab: null,
    kat_risiko: '',
  });
  // ============================================================
  // HELPERS
  // ============================================================
  function toNumber(value) {
    const number = parseFloat(value);
    return Number.isFinite(number) ? number : 0;
  }

  function interpretRange(value, threshold, normalLabel, highLabel) {
    if (value === '' || value === null || value === undefined) return '';
    return toNumber(value) >= threshold ? highLabel : normalLabel;
  }

  // ============================================================
  // KALKULASI & INTERPRETASI
  // ============================================================
  function hitungIMT() {
    const bb = toNumber(form.obesitas.berat_badan);
    const tb = toNumber(form.obesitas.tinggi_badan);

    if (!bb || !tb) {
      form.obesitas.imt = '';
      form.obesitas.interpretasi_imt = '';
      return;
    }

    const imt = bb / (tb / 100) ** 2;
    form.obesitas.imt = imt.toFixed(1);

    if (imt < 18.5) form.obesitas.interpretasi_imt = 'Kurus';
    else if (imt < 23) form.obesitas.interpretasi_imt = 'Normal';
    else if (imt < 27) form.obesitas.interpretasi_imt = 'Gemuk';
    else form.obesitas.interpretasi_imt = 'Obesitas';
  }

  function interpretLP() {
    const lp = toNumber(form.obesitas.lingkar_pinggang);

    if (!lp) {
      form.obesitas.interpretasi_lingkar_pinggang = '';
      return;
    }

    form.obesitas.interpretasi_lingkar_pinggang = lp >= 90 ? 'Risiko meningkat' : 'Normal';
  }

  function interpretTD() {
    const s = toNumber(form.hipertensi.sistolik);
    const d = toNumber(form.hipertensi.tekanan_diastolik);

    if (!s || !d) {
      form.hipertensi.kategori_tekanan_darah = '';
      return;
    }

    if (s < 120 && d < 80) form.hipertensi.kategori_tekanan_darah = 'Normal';
    else if (s < 130 && d < 80) form.hipertensi.kategori_tekanan_darah = 'Elevated';
    else if (s < 140 || d < 90) form.hipertensi.kategori_tekanan_darah = 'Hipertensi Grade 1';
    else if (s < 180 || d < 110) form.hipertensi.kategori_tekanan_darah = 'Hipertensi Grade 2';
    else form.hipertensi.kategori_tekanan_darah = 'Krisis Hipertensi';
  }

  function interpretDM() {
  const gdp   = toNumber(form.diabetes.gdp)
  const gds   = toNumber(form.diabetes.gds)
  const hba1c = toNumber(form.diabetes.hba1c)
  const gd2pp = toNumber(form.diabetes.gd2pp)

  // --- GDP ---
  if (!gdp) {
    form.diabetes.interpretasi_gdp = ''
  } else if (gdp >= 126) {
    form.diabetes.interpretasi_gdp = 'Diabetes'
  } else if (gdp >= 100) {
    form.diabetes.interpretasi_gdp = 'Prediabetes'
  } else {
    form.diabetes.interpretasi_gdp = 'Normal'
  }

  // --- GDS ---
  if (!gds) {
    form.diabetes.interpretasi_gds = ''
  } else if (gds >= 200) {
    form.diabetes.interpretasi_gds = 'Diabetes'
  } else if (gds >= 140) {
    form.diabetes.interpretasi_gds = 'Prediabetes'
  } else {
    form.diabetes.interpretasi_gds = 'Normal'
  }

  // --- GD2PP ---
  if (!gd2pp) {
    form.diabetes.interpretasi_gd2pp = ''
  } else if (gd2pp >= 200) {
    form.diabetes.interpretasi_gd2pp = 'Diabetes'
  } else if (gd2pp >= 140) {
    form.diabetes.interpretasi_gd2pp = 'Prediabetes'
  } else {
    form.diabetes.interpretasi_gd2pp = 'Normal'
  }

  // --- HbA1c ---
  if (!hba1c) {
    form.diabetes.interpretasi_hba1c = ''
  } else if (hba1c >= 6.5) {
    form.diabetes.interpretasi_hba1c = 'Diabetes'
  } else if (hba1c >= 5.7) {
    form.diabetes.interpretasi_hba1c = 'Prediabetes'
  } else {
    form.diabetes.interpretasi_hba1c = 'Normal'
  }
}

  function interpretUrat() {
    const urat = toNumber(form.asam_urat.asam_urat);

    if (!urat) {
      form.asam_urat.interpretasi_asam_urat = '';
      return;
    }

    form.asam_urat.interpretasi_asam_urat = urat > 7 ? 'Hiperurisemia' : 'Normal';
  }

  function interpretLipid() {
    form.profil_lipid.interpretasi_kolesterol_total = interpretRange(
      form.profil_lipid.kolesterol_total,
      200,
      'Normal',
      'Tinggi'
    );
    form.profil_lipid.interpretasi_hdl = form.profil_lipid.hdl
      ? toNumber(form.profil_lipid.hdl) >= 40
        ? 'Baik'
        : 'Rendah'
      : '';
    form.profil_lipid.interpretasi_ldl = interpretRange(
      form.profil_lipid.ldl,
      130,
      'Optimal',
      'Tinggi'
    );
    form.profil_lipid.interpretasi_trigliserida = interpretRange(
      form.profil_lipid.trigliserida,
      150,
      'Normal',
      'Tinggi'
    );
  }

  const saveStatus = ref('idle');
  const saveError = ref('');

  const saveMessage = computed(() => {
    if (saveStatus.value === 'ready') return 'Data subjektif berhasil disimpan.';
    if (saveError.value) return saveError.value;
    return 'Simpan setelah data kunjungan selesai diisi.';
  });

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

  function closeDuplicateModal() {
    showDuplicateModal.value = false;
  }

  function savePemeriksaan() {
    saveStatus.value = 'idle';
    saveError.value = '';
    showSuccessModal.value = false;
    showValidationModal.value = false;
    validationMessages.value = [];

    form.post(route('pelayanan.simpan-pemeriksaan-metabolik'), {
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

        if (isDuplicateError(message)) {
          showDuplicateModal.value = true;
        } else {
          showValidationModal.value = true;
        }
      },
    });
  }
</script>

<style scoped src="./FormPemeriksaan.css"></style>
