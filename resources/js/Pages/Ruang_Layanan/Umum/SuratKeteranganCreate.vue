<template>
    <AppLayouts>
        <SuratKeteranganCreate :jenis-surat="jenisSurat" :data-anamnesa="dataAnamnesa"
            :tenaga-medis-askep="tenagaMedisAskep" :id-pelayanan="idPelayanan" :id-poli="idPoli">

        </SuratKeteranganCreate>
    </AppLayouts>

</template>


<script setup>
import { usePage } from '@inertiajs/vue3';
import SuratKeteranganCreate from '../../../Components/Layouts/RuangLayanan/SuratKeteranganCreate.vue';
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';

const page = usePage();
const jenisSurat = page.props.jenisSurat
const dataAnamnesa = page.props.dataAnamnesa
const tenagaMedisAskep = page.props.tenagaMedisAskep
const idPelayanan = page.props.idPelayanan
const idPoli = page.props.idPoli
console.log('id pelayanannya', idPelayanan)
</script>