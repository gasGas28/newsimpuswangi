<template>
  <div class="resume-form">

    <!-- ── Tab Navigation ── -->
    <div class="send-tabs-wrapper">
      <div class="send-tabs-grid">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          type="button"
          class="send-tab"
          :class="{ 'send-tab--active': selectedForm === tab.value }"
          @click="selectedForm = tab.value"
        >
          <i class="bi" :class="tab.icon"></i>
          <span>{{ tab.label }}</span>
        </button>
      </div>
    </div>

    <!-- ── Panel Konten ── -->
    <div class="send-tab-content">
      <div v-if="!selectedForm" class="send-tab-empty">
        <i class="bi bi-arrow-up-circle send-tab-empty-icon"></i>
        <p>Pilih jenis data yang ingin dikirim ke SATUSEHAT.</p>
      </div>

      <SendHipertensi   v-if="selectedForm === 'Hipertensi'"    :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining" />
      <SendObesitas     v-if="selectedForm === 'Obesitas'"       :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining" />
      <SendDiabetes     v-if="selectedForm === 'Diabetes'"       :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining" />
      <SendAsamUrat     v-if="selectedForm === 'AsamUrat'"       :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining" />
      <SendProfilLipid  v-if="selectedForm === 'ProfilLipid'"    :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining" />
      <SendPendengaran  v-if="selectedForm === 'Pendengaran'"    :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining" />
      <SendPenglihatan  v-if="selectedForm === 'Penglihatan'"    :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining" />
      <SendKankerParu   v-if="selectedForm === 'KankerParu'"     :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining" />
      <SendKolorektal   v-if="selectedForm === 'Kolorektal'"     :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining" />
      <SendKankerServiks v-if="selectedForm === 'KankerServiks'" :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining" />
      <SendEKG          v-if="selectedForm === 'EKG'"            :DataPasien="props.DataPasien" :TenagaMedis="props.TenagaMedis" :DataSkrining="props.DataSkrining" />
    </div>

  </div>
</template>

<script setup>
  import { ref } from 'vue';
  import SendHipertensi    from '../KirimSatuSehat/KirimHipertensi.vue';
  import SendObesitas      from '../KirimSatuSehat/KirimObesitas.vue';
  import SendDiabetes      from '../KirimSatuSehat/KirimDiabetes.vue';
  import SendAsamUrat      from '../KirimSatuSehat/KirimAsamUrat.vue';
  import SendProfilLipid   from '../KirimSatuSehat/KirimProfilLipid.vue';
  import SendPendengaran   from '../KirimSatuSehat/KirimGangguanPendengaran.vue';
  import SendPenglihatan   from '../KirimSatuSehat/KirimGangguanPenglihatan.vue';
  import SendKankerParu    from '../KirimSatuSehat/KirimKankerParu.vue';
  import SendKolorektal    from '../KirimSatuSehat/KirimKolorektal.vue';
  import SendKankerServiks from '../KirimSatuSehat/KirimKankerServiks.vue';
  import SendEKG           from '../KirimSatuSehat/KirimEKG.vue';

  const props = defineProps({
    DataPasien: Object,
    TenagaMedis: Array,
    DataSkrining: Object,
  });

  const selectedForm = ref('Obesitas');

  const tabs = [
    { value: 'Obesitas',      label: 'Obesitas',       icon: 'bi-speedometer2'     },
    { value: 'Hipertensi',    label: 'Hipertensi',    icon: 'bi-heart-pulse'      },
    { value: 'Diabetes',      label: 'Diabetes',       icon: 'bi-droplet-half'     },
    { value: 'AsamUrat',      label: 'Asam Urat',      icon: 'bi-activity'         },
    { value: 'ProfilLipid',   label: 'Profil Lipid',   icon: 'bi-clipboard2-pulse' },
    { value: 'Pendengaran',   label: 'Pendengaran',    icon: 'bi-ear'              },
    { value: 'Penglihatan',   label: 'Penglihatan',    icon: 'bi-eye'              },
    { value: 'KankerParu',    label: 'Kanker Paru',    icon: 'bi-lungs'            },
    { value: 'Kolorektal',    label: 'Kolorektal',     icon: 'bi-virus'            },
    { value: 'KankerServiks', label: 'Kanker Serviks', icon: 'bi-gender-female'    },
    { value: 'EKG',           label: 'EKG',            icon: 'bi-graph-up'         },
  ];
</script>

<style scoped src="@/css/FormPemeriksaan.css"></style>

<style scoped>
.send-tabs-wrapper {
  margin-bottom: 20px;
}

.send-tabs-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  border-bottom: 2px solid #e2e8f0;
  row-gap: 0;
}

/* Baris pertama tiap tab punya garis bawah tipis pemisah antar baris */
.send-tab:nth-child(-n+6) {
  border-bottom: 2px solid #e2e8f0;
  margin-bottom: -2px;
}

/* ── Tiap tab: sama persis dengan versi sebelumnya ── */
.send-tab {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 10px 16px;
  border: none;
  border-bottom: 3px solid transparent;
  background: transparent;
  color: #080808;
  font-size: 0.84rem;
  font-weight: 600;
  white-space: nowrap;
  cursor: pointer;
  border-radius: 6px 6px 0 0;
  transition: color 0.15s ease, border-color 0.15s ease, background 0.15s ease;
  margin-bottom: -2px;
}

.send-tab i {
  font-size: 0.95rem;
}

.send-tab:hover {
  color: #fdfdfd;
  background: #05c283;
}

/* ── Tab aktif ── */
.send-tab--active {
  color: #f4f7f5;
  border-bottom-color: #05c283;
  background: #05c283;
}

.send-tab--active i {
  color: #fdfffe;
}

/* ── Empty state ── */
.send-tab-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  padding: 60px 20px;
  color: #94a3b8;
  text-align: center;
}

.send-tab-empty-icon {
  font-size: 2.8rem;
  color: #cbd5e1;
}

.send-tab-empty p {
  margin: 0;
  font-size: 0.95rem;
  color: #64748b;
}

/* ── Responsive: layar kecil kembali ke 3 kolom ── */
@media (max-width: 640px) {
  .send-tabs-grid {
    grid-template-columns: repeat(3, 1fr);
  }

  .send-tab:nth-child(-n+6) {
    border-bottom: 2px solid #e2e8f0;
  }

  .send-tab:nth-child(-n+3) {
    border-bottom: 2px solid #e2e8f0;
  }
}
</style>
