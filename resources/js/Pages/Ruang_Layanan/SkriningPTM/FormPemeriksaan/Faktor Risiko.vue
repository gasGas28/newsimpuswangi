<template>
  <div class="risk-form">
    <section class="risk-score-panel" :class="scoreClass">
      <div class="risk-score-main">
        <div class="risk-score-value">
          <span class="risk-score-number"
            >{{ riskScore.total }}<small>/{{ riskScore.maxScore }}</small></span
          >
          <span class="risk-score-category">{{ riskScore.category }}</span>
        </div>
        <p class="risk-score-note">{{ riskScore.recommendation }}</p>
      </div>

      <div class="risk-score-disclaimer">
        <i class="bi bi-info-circle"></i>
        <span
          >Indikator internal untuk triase, bukan instrumen skoring baku Kemenkes/SATUSEHAT.</span
        >
      </div>
    </section>
    <section class="risk-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-exclamation-triangle"></i> Status Merokok</h4>
          <p>Observation LOINC 72166-2 dan kuesioner Q0013 linkId 1.1-1.5.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="smoking-grid">
          <div class="form-field">
            <label class="form-label">Apakah Pernah Merokok?</label>
            <div class="radio-group">
              <label class="radio-option" :class="{ checked: form.merokok === 'tidak' }">
                <input v-model="form.merokok" type="radio" name="merokok" value="tidak" />
                <span>Tidak</span>
              </label>
              <label class="radio-option" :class="{ checked: form.merokok === 'ya' }">
                <input v-model="form.merokok" type="radio" name="merokok" value="ya" />
                <span>Ya</span>
              </label>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="status_merokok">Status Merokok Saat Ini</label>
            <select
              id="status_merokok"
              name="status_merokok"
              class="form-select"
              v-model="form.status_merokok"
            >
              <option value="">Pilih status merokok</option>
              <option value="tidak_pernah">Tidak pernah merokok</option>
              <option value="perokok_aktif">Merokok aktif</option>
              <option value="mantan_perokok">Mantan perokok</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="btg_rokok">Rata-rata Batang Rokok/Hari</label>
            <input
              id="btg_rokok"
              name="btg_rokok"
              type="number"
              min="0"
              class="form-control"
              v-model.number="form.btg_rokok"
              placeholder="0"
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="lama_rokok">Lama Merokok</label>
            <div class="input-with-addon">
              <input
                id="lama_rokok"
                name="lama_rokok"
                type="number"
                min="0"
                class="form-control"
                v-model.number="form.lama_rokok"
                placeholder="0"
              />
              <span>tahun</span>
            </div>
          </div>

          <div class="form-field">
            <label class="form-label" for="paparan_rokok">
              Terpapar Asap Rokok Orang Lain (1 Bulan)
            </label>
            <select
              id="paparan_rokok"
              name="paparan_rokok"
              class="form-select"
              v-model="form.paparan_rokok"
            >
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <section v-if="form.merokok === 'ya'" class="risk-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-lungs"></i> Gejala Pernapasan</h4>
          <p>Kuesioner PUMA (Q0021) linkId 1.5-1.8.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="smoking-grid">
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

    <section class="risk-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-activity"></i> Pola Makan &amp; Aktivitas</h4>
          <p>Faktor risiko perilaku Q0013 linkId 1.6-1.11.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="habit-grid">
          <div class="form-field">
            <label class="form-label" for="gula">Konsumsi Gula &gt;4 sdm/hari?</label>
            <select id="gula" name="gula" class="form-select" v-model="form.gula">
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="garam">Konsumsi Garam &gt;1 sdt/hari?</label>
            <select id="garam" name="garam" class="form-select" v-model="form.garam">
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="minyak">Konsumsi Minyak &gt;5 sdm/hari?</label>
            <select id="minyak" name="minyak" class="form-select" v-model="form.minyak">
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="sayur">Kurang Sayur/Buah (&lt;5 porsi/hari)?</label>
            <select id="sayur" name="sayur" class="form-select" v-model="form.sayur">
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="aktivitas">Aktivitas Fisik Kurang?</label>
            <select id="aktivitas" name="aktivitas" class="form-select" v-model="form.aktivitas">
              <option value="tidak">Tidak</option>
              <option value="kadang">Ya, tidak setiap hari</option>
              <option value="setiap_hari">Ya, setiap hari</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="alkohol">Konsumsi Alkohol 1 Bulan Terakhir?</label>
            <select id="alkohol" name="alkohol" class="form-select" v-model="form.alkohol">
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <section class="risk-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-journal-medical"></i> Riwayat PTM</h4>
          <p>Condition FHIR, FamilyMemberHistory, dan MedicationStatement.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="history-grid">
          <div class="form-field history-check-field">
            <label class="form-label">Riwayat PTM Pribadi</label>
            <div class="check-grid history-check-grid">
              <label class="check-option" v-for="item in riwayatPribadiOptions" :key="item.model">
                <input
                  type="checkbox"
                  :name="item.model"
                  :checked="form[item.model]"
                  @change="form[item.model] = $event.target.checked"
                />
                <span>{{ item.label }}</span>
              </label>
            </div>
          </div>

          <div class="form-field history-check-field">
            <label class="form-label">Riwayat PTM Keluarga</label>
            <div class="check-grid history-check-grid">
              <label class="check-option" v-for="item in riwayatKeluargaOptions" :key="item.model">
                <input
                  type="checkbox"
                  :name="item.model"
                  :checked="form[item.model]"
                  @change="form[item.model] = $event.target.checked"
                />
                <span>{{ item.label }}</span>
              </label>
            </div>
          </div>

          <div class="form-field note-field">
            <label class="form-label" for="obat">Obat yang Sedang Dikonsumsi</label>
            <textarea
              id="obat"
              name="obat"
              class="form-control"
              rows="3"
              v-model="form.obat"
              placeholder="Antihipertensi, antidiabetes, statin, dll."
            ></textarea>
          </div>

          <div class="form-field">
            <label class="form-label" for="kesiapan">Kesiapan Berubah</label>
            <select id="kesiapan" name="kesiapan" class="form-select" v-model="form.kesiapan">
              <option value="tidak_siap">Tidak siap</option>
              <option value="ragu">Ragu</option>
              <option value="siap">Siap</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="dukung">Dukungan Keluarga</label>
            <select id="dukung" name="dukung" class="form-select" v-model="form.dukung">
              <option value="kurang">Kurang</option>
              <option value="cukup">Cukup</option>
              <option value="baik">Baik</option>
            </select>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <div class="save-status" :class="{ success: saveStatus === 'ready' }">
          {{ saveMessage }}
        </div>
        <button
          type="button"
          class="save-button"
          :disabled="form.processing"
          @click="saveFaktorRisiko"
        >
          <i class="bi" :class="form.processing ? 'bi-arrow-repeat' : 'bi-save'"></i>
          <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Faktor Risiko' }}</span>
        </button>
      </div>
    </section>

    <!-- ✅ Modal di dalam root element -->
    <ModalAlert
      :show="showSuccessModal"
      type="success"
      title="Data Berhasil Disimpan"
      message="Data faktor risiko berhasil disimpan."
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
  import { ref, computed, watchEffect } from 'vue';
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

    merokok: '',
    status_merokok: '',
    btg_rokok: 0,
    lama_rokok: 0,
    paparan_rokok: 'tidak',

    napas_pendek: 'tidak',
    dahak: 'tidak',
    batuk: 'tidak',
    spirometri: 'tidak',
    hasil_puma: '',

    gula: 'tidak',
    garam: 'tidak',
    minyak: 'tidak',
    sayur: 'tidak',
    aktivitas: 'tidak',
    alkohol: 'tidak',
    obat: '',

    kesiapan: 'tidak_siap',
    dukung: 'kurang',

    r_pribadi_htn: false,
    r_pribadi_dm: false,
    r_pribadi_stroke: false,
    r_pribadi_jantung: false,

    r_keluarga_htn: false,
    r_keluarga_dm: false,
    r_keluarga_stroke: false,
    r_keluarga_jantung: false,

    skor_faktor_risiko: 0,
    kategori_faktor_risiko: '',
    detail_faktor_risiko: [],
  });

  console.log('Form initialized with:', form);

  // Options untuk v-for di template
  const riwayatPribadiOptions = [
    { model: 'r_pribadi_htn', label: 'Hipertensi' },
    { model: 'r_pribadi_dm', label: 'Diabetes Melitus' },
    { model: 'r_pribadi_stroke', label: 'Stroke' },
    { model: 'r_pribadi_jantung', label: 'Penyakit Jantung' },
  ];

  const riwayatKeluargaOptions = [
    { model: 'r_keluarga_htn', label: 'Hipertensi' },
    { model: 'r_keluarga_dm', label: 'Diabetes Melitus' },
    { model: 'r_keluarga_stroke', label: 'Stroke' },
    { model: 'r_keluarga_jantung', label: 'Penyakit Jantung' },
  ];

  // UI state 
  const saveStatus = ref('idle');
  const saveError = ref('');

  const saveMessage = computed(() => {
    if (saveStatus.value === 'ready') return 'Data subjektif berhasil disimpan.';
    if (saveError.value) return saveError.value;
    return 'Simpan setelah data kunjungan selesai diisi.';
  });

  // --- Helpers ---                               
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

  const toNumber = (value) => Number(value || 0);

  const addRisk = (items, condition, key, label, score) => {
    if (condition) items.push({ key, label, score });
  };

  // rules
  const riskRuleGroups = computed(() => [
    {
      rules: [{ test: form.merokok === 'ya', key: 'merokok', label: 'Pernah merokok', score: 2 }],
    },
    {
      rules: [
        {
          test: form.status_merokok === 'perokok_aktif',
          key: 'status_merokok_aktif',
          label: 'Merokok aktif',
          score: 2,
        },
        {
          test: form.status_merokok === 'mantan_perokok',
          key: 'status_merokok_mantan',
          label: 'Mantan perokok',
          score: 1,
        },
      ],
    },
    {
      rules: [
        {
          test: toNumber(form.btg_rokok) >= 10,
          key: 'btg_rokok',
          label: 'Rokok >=10 batang/hari',
          score: 1,
        },
      ],
    },
    {
      rules: [
        {
          test: toNumber(form.lama_rokok) >= 10,
          key: 'lama_rokok',
          label: 'Lama merokok >=10 tahun',
          score: 1,
        },
      ],
    },
    {
      rules: [
        {
          test: form.paparan_rokok === 'kadang',
          key: 'paparan_kadang',
          label: 'Paparan asap rokok kadang',
          score: 1,
        },
        {
          test: form.paparan_rokok === 'setiap_hari',
          key: 'paparan_harian',
          label: 'Paparan asap rokok harian',
          score: 2,
        },
      ],
    },
    {
      rules: [
        {
          test: form.gula === 'kadang',
          key: 'gula_kadang',
          label: 'Gula berlebih kadang',
          score: 1,
        },
        {
          test: form.gula === 'setiap_hari',
          key: 'gula_harian',
          label: 'Gula berlebih harian',
          score: 2,
        },
      ],
    },
    {
      rules: [
        {
          test: form.garam === 'kadang',
          key: 'garam_kadang',
          label: 'Garam berlebih kadang',
          score: 1,
        },
        {
          test: form.garam === 'setiap_hari',
          key: 'garam_harian',
          label: 'Garam berlebih harian',
          score: 2,
        },
      ],
    },
    {
      rules: [
        {
          test: form.minyak === 'kadang',
          key: 'minyak_kadang',
          label: 'Minyak berlebih kadang',
          score: 1,
        },
        {
          test: form.minyak === 'setiap_hari',
          key: 'minyak_harian',
          label: 'Minyak berlebih harian',
          score: 2,
        },
      ],
    },
    {
      rules: [
        {
          test: form.sayur === 'kadang',
          key: 'sayur_kadang',
          label: 'Kurang sayur/buah kadang',
          score: 1,
        },
        {
          test: form.sayur === 'setiap_hari',
          key: 'sayur_harian',
          label: 'Kurang sayur/buah harian',
          score: 2,
        },
      ],
    },
    {
      rules: [
        {
          test: form.aktivitas === 'kadang',
          key: 'aktivitas_kadang',
          label: 'Aktivitas fisik kurang kadang',
          score: 1,
        },
        {
          test: form.aktivitas === 'setiap_hari',
          key: 'aktivitas_harian',
          label: 'Aktivitas fisik kurang harian',
          score: 2,
        },
      ],
    },
    {
      rules: [{ test: form.alkohol === 'ya', key: 'alkohol', label: 'Konsumsi alkohol', score: 1 }],
    },
    {
      rules: [
        {
          test: form.r_pribadi_htn,
          key: 'pribadi_htn',
          label: 'Riwayat pribadi hipertensi',
          score: 2,
        },
      ],
    },
    {
      rules: [
        { test: form.r_pribadi_dm, key: 'pribadi_dm', label: 'Riwayat pribadi diabetes', score: 2 },
      ],
    },
    {
      rules: [
        {
          test: form.r_pribadi_stroke,
          key: 'pribadi_stroke',
          label: 'Riwayat pribadi stroke',
          score: 3,
        },
      ],
    },
    {
      rules: [
        {
          test: form.r_pribadi_jantung,
          key: 'pribadi_jantung',
          label: 'Riwayat pribadi penyakit jantung',
          score: 3,
        },
      ],
    },
    {
      rules: [
        {
          test: form.r_keluarga_htn,
          key: 'keluarga_htn',
          label: 'Riwayat keluarga hipertensi',
          score: 1,
        },
      ],
    },
    {
      rules: [
        {
          test: form.r_keluarga_dm,
          key: 'keluarga_dm',
          label: 'Riwayat keluarga diabetes',
          score: 1,
        },
      ],
    },
    {
      rules: [
        {
          test: form.r_keluarga_stroke,
          key: 'keluarga_stroke',
          label: 'Riwayat keluarga stroke',
          score: 1,
        },
      ],
    },
    {
      rules: [
        {
          test: form.r_keluarga_jantung,
          key: 'keluarga_jantung',
          label: 'Riwayat keluarga penyakit jantung',
          score: 1,
        },
      ],
    },
    {
      rules: [
        {
          test: form.kesiapan === 'tidak_siap',
          key: 'tidak_siap',
          label: 'Belum siap berubah',
          score: 1,
        },
        { test: form.kesiapan === 'ragu', key: 'ragu', label: 'Masih ragu berubah', score: 1 },
      ],
    },
    {
      rules: [
        {
          test: form.dukung === 'kurang',
          key: 'dukungan_kurang',
          label: 'Dukungan keluarga kurang',
          score: 1,
        },
      ],
    },
  ]);

  // ============================================================
  // KALKULASI SKOR RISIKO (computed)
  // ============================================================
  const riskScore = computed(() => {
    const items = [];
    let maxScore = 0;

    for (const { rules } of riskRuleGroups.value) {
      maxScore += Math.max(...rules.map((r) => r.score));
      for (const rule of rules) {
        if (rule.test) items.push({ key: rule.key, label: rule.label, score: rule.score });
      }
    }

    const total = items.reduce((sum, i) => sum + i.score, 0);
    const percentage = Math.min(100, Math.round((total / maxScore) * 100));

    if (total >= 12)
      return {
        total,
        maxScore,
        items,
        percentage,
        level: 'high',
        category: 'Risiko Tinggi',
        recommendation: 'Prioritaskan konseling intensif dan evaluasi klinis.',
        summary:
          'Ada kombinasi faktor perilaku, riwayat PTM, atau hambatan perubahan yang cukup kuat.',
      };

    if (total >= 6)
      return {
        total,
        maxScore,
        items,
        percentage,
        level: 'medium',
        category: 'Risiko Sedang',
        recommendation: 'Berikan edukasi terarah dan buat target perubahan kecil.',
        summary: 'Beberapa faktor risiko sudah muncul dan perlu dipantau saat kontrol berikutnya.',
      };

    return {
      total,
      maxScore,
      items,
      percentage,
      level: 'low',
      category: 'Risiko Rendah',
      recommendation: 'Pertahankan perilaku sehat dan lakukan edukasi pencegahan.',
      summary:
        total === 0
          ? 'Belum ada faktor risiko bermakna dari isian saat ini.'
          : 'Faktor risiko masih terbatas.',
    };
  });

  const scoreClass = computed(() => `risk-score-${riskScore.value.level}`);


  watchEffect(() => {
    form.skor_faktor_risiko = riskScore.value.total;
    form.kategori_faktor_risiko = riskScore.value.category;
    form.detail_faktor_risiko = riskScore.value.items;
  });

  function saveFaktorRisiko() {
    saveStatus.value = 'idle';
    saveError.value = '';
    showSuccessModal.value = false;
    showValidationModal.value = false;
    validationMessages.value = [];

    form.post(route('pelayanan.simpan-risiko-ptm'), {
      preserveScroll: true,
      showGlobalLoader: false,
      only: ['DataPasien'],

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
