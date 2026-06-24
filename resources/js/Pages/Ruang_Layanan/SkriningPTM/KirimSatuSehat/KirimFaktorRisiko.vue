<template>
  <section class="resume-panel">
    <div class="panel-header">
      <div>
        <h4><i class="bi bi-clipboard2-check"></i> Faktor Risiko</h4>
        <p>Status pengisian dan rangkuman data utama sebelum dikirim ke SATUSEHAT.</p>
      </div>
    </div>

    <div class="panel-body">
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Apakah Pernah Merokok ?</div>
          <div class="summary-value">{{ merokok }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Status Merokok</div>
          <div class="summary-value">{{ statusMerokok }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Berapa Batang Rokok Per Hari</div>
          <div class="summary-value">{{ btgRokok }} Batang / Hari</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Berapa Lama Merokok Dalam Tahun</div>
          <div class="summary-value">{{ lamaRokok }} Tahun</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Terpapar Asap Rokok Orang Lain (1 Bulan)</div>
          <div class="summary-value">{{ paparanRokok }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Konsumsi Gula Kurang Dari 4 sdm/hari?</div>
          <div class="summary-value">{{ gula }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Konsumsi Garam Kurang Dari 1 sdt/hari?</div>
          <div class="summary-value">{{ garam }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Kurang Sayur/Buah Lebih (5 porsi/hari)</div>
          <div class="summary-value">{{ sayur }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Aktivitas Fisik Kurang</div>
          <div class="summary-value">{{ fisik }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Konsumsi Alkohol 1 Bulan Terakhir?</div>
          <div class="summary-value">{{ alkohol }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Riwayat Pribadi Hipertensi</div>
          <div class="summary-value">{{ getRiwayatLabel('r_pribadi_htn') }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Riwayat Pribadi Diabetes Melitus</div>
          <div class="summary-value">{{ getRiwayatLabel('r_pribadi_dm') }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Riwayat Pribadi Stroke</div>
          <div class="summary-value">{{ getRiwayatLabel('r_pribadi_stroke') }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Riwayat Pribadi Jantung</div>
          <div class="summary-value">{{ getRiwayatLabel('r_pribadi_jantung') }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Riwayat Keluarga Hipertensi</div>
          <div class="summary-value">{{ getRiwayatLabel('r_keluarga_htn') }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Riwayat Keluarga Diabetes Melitus</div>
          <div class="summary-value">{{ getRiwayatLabel('r_keluarga_dm') }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Riwayat Keluarga Stroke</div>
          <div class="summary-value">{{ getRiwayatLabel('r_keluarga_stroke') }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Riwayat Keluarga Jantung</div>
          <div class="summary-value">{{ getRiwayatLabel('r_keluarga_jantung') }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Risiko PTM</div>
          <div class="summary-value">{{ risiko }}</div>
        </div>
      </div>
    </div>
    <div class="form-actions">
      <div class="save-status"></div>
      <button type="button" class="save-button" @click="createObservation">
        <i class="bi bi-save"></i>
        <span>Kirim Satu Sehat</span>
      </button>
    </div>
  </section>
</template>

<script setup>
  import { ref, watchEffect, computed, watch } from 'vue';
  import { useForm, router, usePage } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';
  import ModalAlert from '../../../../Components/Layouts/Modal/ModalAlert.vue';

  const props = defineProps({
    DataPasien: Object,
    TenagaMedis: Array,
  });

  const page = usePage();
  const flash = computed(() => page.props.flash);
  const data = computed(() => props.DataSkrining || {});
  const patient = computed(() => props.DataPasien || {});

  const merokok = computed(() => {
    if (patient.value.merokok === 'ya') {
      return 'Iya Pernah';
    } else if (patient.value.merokok === 'tidak') {
      return 'Tidak Pernah';
    } else {
      return 'Data belum tersedia';
    }
  });

  const statusMerokok = computed(() => {
    if (patient.value.status_merokok === 'tidak_pernah') {
      return 'Pasien Tidak Pernah Merokok';
    } else if (patient.value.status_merokok === 'mantan_perokok') {
      return 'Pasien Mantan Perokok';
    } else if (patient.value.status_merokok === 'perokok_aktif') {
      return 'Pasien Perokok Aktif';
    } else {
      return 'Data belum tersedia';
    }
  });

  const paparanRokok = computed(() => {
    if (patient.value.paparan_rokok === 'setiap_hari') {
      return 'Iya Terpapar';
    } else if (patient.value.paparan_rokok === 'kadang') {
      return 'Kadang Terpapar';
    } else if (patient.value.paparan_rokok === 'tidak') {
      return 'Tidak Terpapar';
    } else {
      return 'Data belum tersedia';
    }
  });

  const getFrekuensiLabel = (value) => {
    return (
      {
        setiap_hari: 'Iya Setiap Hari',
        kadang: 'Kadang',
        tidak: 'Tidak',
      }[value] ?? 'Data belum tersedia'
    );
  };

  const alkohol = computed(() => {
    if (patient.value.alkohol === 'ya') {
      return 'Iya Mengonsumsi';
    } else if (patient.value.alkohol === 'tidak') {
      return 'Tidak Mengonsumsi';
    } else {
      return 'Data belum tersedia';
    }
  });

  const btgRokok = computed(() => patient.value.btg_rokok || 'Data Belum Tersedia');
  const lamaRokok = computed(() => patient.value.lama_rokok || 'Data Belum Tersedia');
  const gula = computed(() => getFrekuensiLabel(patient.value.gula));
  const garam = computed(() => getFrekuensiLabel(patient.value.garam));
  const sayur = computed(() => getFrekuensiLabel(patient.value.sayur));
  const fisik = computed(() => getFrekuensiLabel(patient.value.aktivitas));
  const risiko = computed(() => patient.value.kategori_faktor_risiko || 'Data Belum Tersedia');

  const getRiwayatLabel = (field) => {
    const value = patient.value[field];

    return (
      {
        0: 'Inactive',
        1: 'Active',
      }[value] ?? 'Data belum tersedia'
    );
  };

  const createObservation = () => {
    console.log('props.DataSkrining:', props.DataSkrining);
    console.log('idSkrining yang dikirim:', props.DataPasien?.idSkrining);
    router.post(
      route('satusehat.observation', props.DataPasien?.idSkrining),
      {},
      {
        preserveScroll: true,
        onSuccess: () => {
          console.log('Observation berhasil dikirim');
        },
        onError: (errors) => {
          console.error(errors);
        },
      }
    );
  };

  watch(flash, (val) => {
    if (val.success) {
      console.log('Berhasil:', val.message);
      console.log('Data PTM:', val.data?.riwayat_ptm);
    } else {
      console.error('Gagal:', val.message);
    }
  });
</script>

<style scoped src="@/css/FormPemeriksaan.css"></style>
