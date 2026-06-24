<template>
  <div class="resume-form">
    <SendHipertensi
      :DataPasien="props.DataPasien"
      :TenagaMedis="props.TenagaMedis"
      :DataSkrining="props.DataSkrining"
    />
    <SendObesitas
      :DataPasien="props.DataPasien"
      :TenagaMedis="props.TenagaMedis"
      :DataSkrining="props.DataSkrining"
    />
    <SendDiabetes
      :DataPasien="props.DataPasien"
      :TenagaMedis="props.TenagaMedis"
      :DataSkrining="props.DataSkrining"
    />
    <SendAsamUrat
      :DataPasien="props.DataPasien"
      :TenagaMedis="props.TenagaMedis"
      :DataSkrining="props.DataSkrining"
    />
    <SendProfilLipid
      :DataPasien="props.DataPasien"
      :TenagaMedis="props.TenagaMedis"
      :DataSkrining="props.DataSkrining"
    />
    <SendPendengaran
      :DataPasien="props.DataPasien"
      :TenagaMedis="props.TenagaMedis"
      :DataSkrining="props.DataSkrining"
    />
    <SendPenglihatan
      :DataPasien="props.DataPasien"
      :TenagaMedis="props.TenagaMedis"
      :DataSkrining="props.DataSkrining"
    />
    <SendKankerParu
    :DataPasien="props.DataPasien"
    :TenagaMedis="props.TenagaMedis"
    :DataSkrining="props.DataSkrining"
    />
    <SendKolorektal
    :DataPasien="props.DataPasien"
    :TenagaMedis="props.TenagaMedis"
    :DataSkrining="props.DataSkrining"
    />
    <SendKankerServiks
    :DataPasien="props.DataPasien"
    :TenagaMedis="props.TenagaMedis"
    :DataSkrining="props.DataSkrining"
    />
    <SendEKG
      :DataPasien="props.DataPasien"
      :TenagaMedis="props.TenagaMedis"
      :DataSkrining="props.DataSkrining"
    />
  </div>
</template>

<script setup>
  import SendHipertensi from '../KirimSatuSehat/KirimHipertensi.vue';
  import SendObesitas from '../KirimSatuSehat/KirimObesitas.vue';
  import SendDiabetes from '../KirimSatuSehat/KirimDiabetes.vue';
  import SendAsamUrat from '../KirimSatuSehat/KirimAsamUrat.vue';
  import SendProfilLipid from '../KirimSatuSehat/KirimProfilLipid.vue';
  import SendPendengaran from '../KirimSatuSehat/KirimGangguanPendengaran.vue';
  import SendPenglihatan from '../KirimSatuSehat/KirimGangguanPenglihatan.vue';
  import SendEKG from '../KirimSatuSehat/KirimEKG.vue';
  import SendKankerParu from '../KirimSatuSehat/KirimKankerParu.vue';
  import SendKolorektal from '../KirimSatuSehat/KirimKolorektal.vue';
  import SendKankerServiks from '../KirimSatuSehat/KirimKankerServiks.vue';

  const props = defineProps({
    DataPasien: Object,
    TenagaMedis: Array,
    DataSkrining: Object,
  });
</script>

<style scoped src="./FormPemeriksaan.css"></style>
