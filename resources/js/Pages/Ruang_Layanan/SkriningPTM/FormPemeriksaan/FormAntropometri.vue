<template>
  <div class="metabolik-form">
    <section class="metabolik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-speedometer2"></i>Deteksi Dini Obesitas</h4>
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
                v-model.number="formObesitas.berat_badan"
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
                v-model.number="formObesitas.tinggi_badan"
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
                v-model="formObesitas.imt"
                readonly
                placeholder="-"
              />
              <span>kg/m2</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="imt_interp">Kategori IMT</label>
            <input
              id="imt_interp"
              class="form-control"
              type="text"
              v-model="formObesitas.interpretasi_imt"
              readonly
              placeholder="-"
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="lp">Lingkar Perut</label>
            <div class="input-with-addon">
              <input
                id="lp"
                class="form-control"
                type="number"
                min="0"
                step="0.1"
                v-model.number="formObesitas.lingkar_perut"
                placeholder="0.0"
                @input="interpretLP"
              />
              <span>cm</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="lp_interp">Kategori Lingkar Perut</label>
            <input
              id="lp_interp"
              class="form-control"
              type="text"
              v-model="formObesitas.interpretasi_lp"
              readonly
              placeholder="-"
            />
          </div>
        </div>
        <div class="form-actions">
          <div class="save-status">
            {{ saveMessage.obesitas }}
          </div>
          <button
            type="button"
            class="save-button"
            :disabled="isSaving.obesitas"
            @click="saveObesitas"
          >
            <i class="bi" :class="isSaving.obesitas ? 'bi-arrow-repeat' : 'bi-save'"></i>
            <span>{{ isSaving.obesitas ? 'Menyimpan...' : 'Simpan Obesitas' }}</span>
          </button>
        </div>
      </div>
    </section>

    <section class="metabolik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-heart-pulse"></i>Deteksi Dini Hipertensi</h4>
          <p>Tekanan darah, nadi, frekuensi napas, dan suhu tubuh.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="metric-grid">
          <div class="form-field">
            <label class="form-label" for="td_s">Sistolik</label>
            <div class="input-with-addon">
              <input
                id="td_s"
                class="form-control"
                type="number"
                min="0"
                v-model.number="formHipertensi.sistolik"
                placeholder="120"
                @input="interpretTD"
              />
              <span>mmHg</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="td_d">Diastolik</label>
            <div class="input-with-addon">
              <input
                id="td_d"
                class="form-control"
                type="number"
                min="0"
                v-model.number="formHipertensi.diastolik"
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
              v-model="formHipertensi.kategori_hipertensi"
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
                v-model.number="formHipertensi.nadi"
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
                v-model.number="formHipertensi.pernapasan"
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
                v-model.number="formHipertensi.suhu"
                placeholder="36.5"
              />
              <span>C</span>
            </div>
          </div>
        </div>
        <div class="form-actions">
          <div class="save-status">
            {{ saveMessage.hipertensi }}
          </div>
          <button
            type="button"
            class="save-button"
            :disabled="isSaving.hipertensi"
            @click="saveHipertensi"
          >
            <i class="bi" :class="isSaving.hipertensi ? 'bi-arrow-repeat' : 'bi-save'"></i>
            <span>{{ isSaving.hipertensi ? 'Menyimpan...' : 'Simpan Hipertensi' }}</span>
          </button>
        </div>
      </div>
    </section>

    <section class="metabolik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-droplet-half"></i>Deteksi Dini Diabetes Melitus</h4>
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
                v-model.number="formDiabetes.gdp"
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
              v-model="formDiabetes.interpretasi_gdp"
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
                v-model.number="formDiabetes.gds"
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
              v-model="formDiabetes.interpretasi_gds"
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
                v-model.number="formDiabetes.hba1c"
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
              v-model="formDiabetes.interpretasi_hba1c"
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
                v-model.number="formDiabetes.gd2pp"
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
              v-model="formDiabetes.interpretasi_gd2pp"
              readonly
              placeholder="-"
            />
          </div>
        </div>
        <div class="form-actions">
          <div class="save-status">
            {{ saveMessage.diabetes }}
          </div>
          <button
            type="button"
            class="save-button"
            :disabled="isSaving.diabetes"
            @click="saveDiabetes"
          >
            <i class="bi" :class="isSaving.diabetes ? 'bi-arrow-repeat' : 'bi-save'"></i>
            <span>{{ isSaving.diabetes ? 'Menyimpan...' : 'Simpan Diabetes' }}</span>
          </button>
        </div>
      </div>
    </section>

    <section class="metabolik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-droplet"></i> Deteksi Dini Asam Urat</h4>
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
                v-model.number="formAsamUrat.asam_urat"
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
              v-model="formAsamUrat.interpretasi_asam_urat"
              readonly
              placeholder="-"
            />
          </div>
        </div>
        <div class="form-actions">
          <div class="save-status">
            {{ saveMessage.asamUrat }}
          </div>
          <button
            type="button"
            class="save-button"
            :disabled="isSaving.asamUrat"
            @click="saveAsamUrat"
          >
            <i class="bi" :class="isSaving.asamUrat ? 'bi-arrow-repeat' : 'bi-save'"></i>
            <span>{{ isSaving.asamUrat ? 'Menyimpan...' : 'Simpan Asam Urat' }}</span>
          </button>
        </div>
      </div>
    </section>

    <section class="metabolik-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-capsule"></i> Deteksi Dini Profil Lipid</h4>
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
                v-model.number="formProfilLipid.kolesterol_total"
                placeholder="0"
                @input="interpretLipid"
              />
              <span>mg/dL</span>
            </div>
            <input
              class="form-control interpretation-control"
              type="text"
              v-model="formProfilLipid.interpretasi_kolesterol_total"
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
                v-model.number="formProfilLipid.hdl"
                placeholder="0"
                @input="interpretLipid"
              />
              <span>mg/dL</span>
            </div>
            <input
              class="form-control interpretation-control"
              type="text"
              v-model="formProfilLipid.interpretasi_hdl"
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
                v-model.number="formProfilLipid.ldl"
                placeholder="0"
                @input="interpretLipid"
              />
              <span>mg/dL</span>
            </div>
            <input
              class="form-control interpretation-control"
              type="text"
              v-model="formProfilLipid.interpretasi_ldl"
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
                v-model.number="formProfilLipid.trigliserida"
                placeholder="0"
                @input="interpretLipid"
              />
              <span>mg/dL</span>
            </div>
            <input
              class="form-control interpretation-control"
              type="text"
              v-model="formProfilLipid.interpretasi_trigliserida"
              readonly
              placeholder="Interpretasi"
            />
            <small>LOINC 2571-8</small>
          </div>
        </div>
        <div class="form-actions">
          <div class="save-status">
            {{ saveMessage.profilLipid }}
          </div>
          <button
            type="button"
            class="save-button"
            :disabled="isSaving.profilLipid"
            @click="saveProfilLipid"
          >
            <i class="bi" :class="isSaving.profilLipid ? 'bi-arrow-repeat' : 'bi-save'"></i>
            <span>{{ isSaving.profilLipid ? 'Menyimpan...' : 'Simpan Profil Lipid' }}</span>
          </button>
        </div>
      </div>
    </section>

    <ModalAlert
      :show="showSuccessModal"
      type="success"
      title="Pelayanan Berhasil Disimpan"
      message="Data Pelayanan telah tersimpan."
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
  import { ref, computed, watch } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import ModalAlert from '../../../../Components/Layouts/Modal/ModalAlert.vue';

  // --- Props ---
  const props = defineProps({
    DataPasien: Object,
  });

  const skriningId = props.DataPasien?.idSkrining || null;

  const formHipertensi = useForm({
    skriningId,
    sistolik: props.DataPasien?.sistolik || '',
    diastolik: props.DataPasien?.tekanan_diastolik || '',
    kategori_hipertensi: props.DataPasien?.kategori_tekanan_darah || '',
    suhu: props.DataPasien?.suhu || '',
    nadi: props.DataPasien?.nadi || '',
    pernapasan: props.DataPasien?.pernapasan || '',
  });

  const formDiabetes = useForm({
    skriningId,
    gdp: '',
    interpretasi_gdp: '',
    gds: '',
    interpretasi_gds: '',
    hba1c: '',
    interpretasi_hba1c: '',
    gd2pp: '',
    interpretasi_gd2pp: '',
  });

  const formObesitas = useForm({
    skriningId,
    berat_badan: '',
    tinggi_badan: '',
    imt: '',
    interpretasi_imt: '',
    lingkar_perut: '',
    interpretasi_lp: '',
  });

  const formProfilLipid = useForm({
    skriningId,
    kolesterol_total: '',
    interpretasi_kolesterol_total: '',
    ldl: '',
    interpretasi_ldl: '',
    hdl: '',
    interpretasi_hdl: '',
    trigliserida: '',
    interpretasi_trigliserida: '',
  });

  const formAsamUrat = useForm({
    skriningId,
    asam_urat: null,
    interpretasi_asam_urat: '',
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
    const bb = toNumber(formObesitas.berat_badan);
    const tb = toNumber(formObesitas.tinggi_badan);

    if (!bb || !tb) {
      formObesitas.imt = '';
      formObesitas.interpretasi_imt = '';
      return;
    }

    const imt = bb / (tb / 100) ** 2;
    formObesitas.imt = imt.toFixed(1);

    if (imt < 18.5) formObesitas.interpretasi_imt = 'Kurus';
    else if (imt < 23) formObesitas.interpretasi_imt = 'Normal';
    else if (imt < 27) formObesitas.interpretasi_imt = 'Gemuk';
    else formObesitas.interpretasi_imt = 'Obesitas';
  }

  function interpretLP() {
    const lp = toNumber(formObesitas.lingkar_perut);

    if (!lp) {
      formObesitas.interpretasi_lp = '';
      return;
    }

    formObesitas.interpretasi_lp = lp >= 90 ? 'Risiko meningkat' : 'Normal';
  }

  function interpretTD() {
    const s = toNumber(formHipertensi.sistolik);
    const d = toNumber(formHipertensi.diastolik);

    if (!s || !d) {
      formHipertensi.kategori_hipertensi = '';
      return;
    }

    if (s < 120 && d < 80) formHipertensi.kategori_hipertensi = 'Normal';
    else if (s < 130 && d < 80) formHipertensi.kategori_hipertensi = 'Elevated';
    else if (s < 140 || d < 90) formHipertensi.kategori_hipertensi = 'Hipertensi Grade 1';
    else if (s < 180 || d < 110) formHipertensi.kategori_hipertensi = 'Hipertensi Grade 2';
    else formHipertensi.kategori_hipertensi = 'Krisis Hipertensi';
  }

  function interpretDM() {
    const gdp = toNumber(formDiabetes.gdp);
    const gds = toNumber(formDiabetes.gds);
    const hba1c = toNumber(formDiabetes.hba1c);
    const gd2pp = toNumber(formDiabetes.gd2pp);

    // --- GDP ---
    if (!gdp) {
      formDiabetes.interpretasi_gdp = '';
    } else if (gdp >= 126) {
      formDiabetes.interpretasi_gdp = 'Diabetes';
    } else if (gdp >= 100) {
      formDiabetes.interpretasi_gdp = 'Prediabetes';
    } else {
      formDiabetes.interpretasi_gdp = 'Normal';
    }

    // --- GDS ---
    if (!gds) {
      formDiabetes.interpretasi_gds = '';
    } else if (gds >= 200) {
      formDiabetes.interpretasi_gds = 'Diabetes';
    } else if (gds >= 140) {
      formDiabetes.interpretasi_gds = 'Prediabetes';
    } else {
      formDiabetes.interpretasi_gds = 'Normal';
    }

    // --- GD2PP ---
    if (!gd2pp) {
      formDiabetes.interpretasi_gd2pp = '';
    } else if (gd2pp >= 200) {
      formDiabetes.interpretasi_gd2pp = 'Diabetes';
    } else if (gd2pp >= 140) {
      formDiabetes.interpretasi_gd2pp = 'Prediabetes';
    } else {
      formDiabetes.interpretasi_gd2pp = 'Normal';
    }

    // --- HbA1c ---
    if (!hba1c) {
      formDiabetes.interpretasi_hba1c = '';
    } else if (hba1c >= 6.5) {
      formDiabetes.interpretasi_hba1c = 'Diabetes';
    } else if (hba1c >= 5.7) {
      formDiabetes.interpretasi_hba1c = 'Prediabetes';
    } else {
      formDiabetes.interpretasi_hba1c = 'Normal';
    }
  }

  function interpretUrat() {
    const urat = toNumber(formAsamUrat.asam_urat);

    if (!urat) {
      formAsamUrat.interpretasi_asam_urat = '';
      return;
    }

    formAsamUrat.interpretasi_asam_urat = urat > 7 ? 'Hiperurisemia' : 'Normal';
  }

  function interpretLipid() {
    // Kolesterol Total
    const kolesterol = toNumber(formProfilLipid.kolesterol_total);
    const hdl = toNumber(formProfilLipid.hdl);
    const ldl = toNumber(formProfilLipid.ldl);
    const trigliserida = toNumber(formProfilLipid.trigliserida);

    if (kolesterol < 200) {
      formProfilLipid.interpretasi_kolesterol_total = 'Normal';
    } else if (kolesterol >= 200 && kolesterol < 240) {
      formProfilLipid.interpretasi_kolesterol_total = 'Borderline Tinggi';
    } else if (kolesterol >= 240) {
      formProfilLipid.interpretasi_kolesterol_total = 'Tinggi';
    } else {
      formProfilLipid.interpretasi_kolesterol_total = 'Data Tidak Tersedia';
    }

    // HDL
    if (hdl < 40) {
      formProfilLipid.interpretasi_hdl = 'Rendah';
    } else if (hdl >= 40 && hdl < 59) {
      formProfilLipid.interpretasi_hdl = 'Sedang';
    } else if (hdl >= 60) {
      formProfilLipid.interpretasi_hdl = 'Protektif';
    } else {
      formProfilLipid.interpretasi_hdl = 'Data Tidak Tersedia';
    }

    // LDL
    if (ldl < 100) {
      formProfilLipid.interpretasi_ldl = 'Optimal';
    } else if (ldl >= 100 && ldl < 160) {
      formProfilLipid.interpretasi_ldl = 'Borderline Tinggi';
    } else if (ldl >= 160) {
      formProfilLipid.interpretasi_ldl = 'Tinggi';
    } else {
      formProfilLipid.interpretasi_ldl = 'Data Tidak Tersedia';
    }

    // Trigliserida
    if (trigliserida < 150) {
      formProfilLipid.interpretasi_trigliserida = 'Normal';
    } else if (trigliserida >= 160 && trigliserida < 199) {
      formProfilLipid.interpretasi_trigliserida = 'Borderline Tinggi';
    } else if (trigliserida >= 200) {
      formProfilLipid.interpretasi_trigliserida = 'Tinggi';
    } else {
      formProfilLipid.interpretasi_trigliserida = 'Data Tidak Tersedia';
    }
  }

  const showSuccessModal = ref(false);
  const showValidationModal = ref(false);
  const validationMessages = ref([]);

  const isSaving = ref({
    obesitas: false,
    hipertensi: false,
    asamUrat: false,
    diabetes: false,
    profilLipid: false,
  });

  const saveStatus = ref({
    obesitas: 'idle',
    hipertensi: 'idle',
    diabetes: 'idle',
    asamUrat: 'idle',
    profilLipid: 'idle',
  });

  const saveError = ref({
    diabetes: '',
    obesitas: '',
    hipertensi: '',
    asamuUrat: '',
    profilLipid: '',
  });

  // ── Computed messages ─────────────────────────────────────────────────────────

  const saveMessage = computed(() => ({
    obesitas: msgFor('obesitas'),
    hipertensi: msgFor('hipertensi'),
    diabetes: msgFor('diabetes'),
    asamUrat: msgFor('asamUrat'),
    profilLipid: msgFor('profilLipid'),
  }));

  function msgFor(key) {
    if (saveStatus.value[key] === 'ready') return 'Data berhasil disimpan.';
    if (saveError.value[key]) return saveError.value[key];
    return 'Simpan setelah data selesai diisi.';
  }

  function saveSection(key, form, routeName) {
    isSaving.value[key] = true;
    saveStatus.value[key] = 'idle';
    saveError.value[key] = '';
    showSuccessModal.value = false;
    showValidationModal.value = false;
    validationMessages.value = [];

    form.post(route(routeName), {
      preserveScroll: true,
      showGlobalLoader: false,
      only: ['DataPasien'],

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

  const saveObesitas = () => saveSection('obesitas', formObesitas, 'pelayanan.simpan-obesitas');
  const saveHipertensi = () =>
    saveSection('hipertensi', formHipertensi, 'pelayanan.simpan-hipertensi');
  const saveAsamUrat = () => saveSection('asamUrat', formAsamUrat, 'pelayanan.simpan-asamUrat');
  const saveDiabetes = () => saveSection('diabetes', formDiabetes, 'pelayanan.simpan-diabetes');
  const saveProfilLipid = () =>
    saveSection('profilLipid', formProfilLipid, 'pelayanan.simpan-profilLipid');

  function extractMessage(errors) {
    return (
      Object.values(errors || {})
        .flat()
        .find(Boolean) || 'Terjadi kesalahan saat menyimpan data.'
    );
  }
</script>

<style scoped src="@/css/FormPemeriksaan.css"></style>
