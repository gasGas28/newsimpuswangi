<template>
  <div class="status-form">
    <section class="status-panel">
      <div class="panel-header">
        <div>
          <h4><i class="bi bi-door-open"></i> Status Keluar Pasien</h4>
          <p>Encounter discharge disposition, cara keluar, rujukan, dan jadwal kontrol.</p>
        </div>
      </div>

      <div class="panel-body">
        <div class="status-grid">
          <div class="form-field">
            <label class="form-label" for="kondisi_keluar"
              >Kondisi Saat Meninggalkan Fasyankes</label
            >
            <select
              id="kondisi_keluar"
              v-model="form.kondisi_keluar"
              name="kondisi_keluar"
              class="form-select"
            >
              <option value="">Pilih kondisi</option>
              <option value="stabil">Stabil</option>
              <option value="membaik">Membaik</option>
              <option value="dirujuk">Dirujuk</option>
              <option value="observasi">Perlu observasi</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="cara_keluar">Cara Keluar Fasyankes</label>
            <select
              id="cara_keluar"
              v-model="form.cara_keluar"
              name="cara_keluar"
              class="form-select"
            >
              <option value="">Pilih cara keluar</option>
              <option value="pulang">Pulang sendiri</option>
              <option value="rujuk">Dirujuk</option>
              <option value="diantar">Diantar keluarga</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="jadwal_kontrol">Jadwal Kontrol Berikutnya</label>
            <input
              id="jadwal_kontrol"
              v-model="form.jadwal_kontrol"
              name="jadwal_kontrol"
              type="date"
              class="form-control"
            />
          </div>

          <div class="form-field">
            <label class="form-label" for="rencana_rujuk">Rujukan / Konsultasi</label>
            <select
              id="rencana_rujuk"
              v-model="form.rencana_rujuk"
              name="rencana_rujuk"
              class="form-select"
            >
              <option value="tidak">Tidak</option>
              <option value="internal">Konsultasi internal puskesmas</option>
              <option value="fkrtl">Rujuk FKRTL</option>
              <option value="igd">Rujuk segera / IGD</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="transport">Transportasi Rujukan</label>
            <select
              id="transport"
              v-model="form.transport"
              name="transport"
              class="form-select"
              :disabled="form.rencana_rujuk === 'tidak'"
            >
              <option value="">Tidak berlaku</option>
              <option value="ambulan">Ambulans</option>
              <option value="kendaraan_pribadi">Kendaraan pribadi</option>
              <option value="ojek">Ojek/taksi</option>
            </select>
          </div>

          <div class="form-field">
            <label class="form-label" for="saran_tindak_lanjut">Saran Tindak Lanjut</label>
            <input
              id="saran_tindak_lanjut"
              :value="saranTindakLanjut"
              name="saran_tindak_lanjut"
              type="text"
              class="form-control readonly-field"
              readonly
            />
          </div>
        </div>
      </div>
    </section>

    <div class="form-actions">
      <div class="save-status" :class="{ success: saveStatus === 'ready' }">
        {{ saveMessage }}
      </div>
      <button type="button" class="save-button" :disabled="isSaving" @click="saveStatusPasien">
        <i class="bi" :class="isSaving ? 'bi-arrow-repeat' : 'bi-save'"></i>
        <span>{{ isSaving ? 'Menyimpan...' : 'Simpan Status Pasien' }}</span>
      </button>
    </div>
  </div>
</template>

<script setup>
  import { computed, ref, watchEffect } from 'vue';

  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });

  const emit = defineEmits(['save-status-pasien']);
  const isSaving = ref(false);
  const saveStatus = ref('idle');
  const form = props.formData?.status_pasien || (props.formData.status_pasien = {});
  const assessment = computed(() => props.formData?.assessment || {});

  form.kondisi_keluar = form.kondisi_keluar || '';
  form.cara_keluar = form.cara_keluar || '';
  form.jadwal_kontrol = form.jadwal_kontrol || '';
  form.rencana_rujuk = form.rencana_rujuk || 'tidak';
  form.transport = form.transport || '';

  const saranTindakLanjut = computed(() => {
    if (form.rencana_rujuk && form.rencana_rujuk !== 'tidak') return labelize(form.rencana_rujuk);
    if (assessment.value.diabetes_melitus || assessment.value.risiko_kardiovaskular) {
      return 'Konsultasi internal puskesmas';
    }
    if (
      assessment.value.hipertensi ||
      assessment.value.dislipidemia ||
      assessment.value.risiko_diabetes
    ) {
      return 'Edukasi dan kontrol berkala';
    }
    if (assessment.value.obesitas || assessment.value.perilaku_berisiko) {
      return 'Edukasi perubahan gaya hidup';
    }
    return 'Tidak ada tindak lanjut khusus';
  });

  watchEffect(() => {
    if (form.rencana_rujuk === 'tidak') form.transport = '';
    form.saran_tindak_lanjut = saranTindakLanjut.value;
  });

  const saveMessage = computed(() => {
    if (saveStatus.value === 'ready') {
      return 'Data status pasien siap disimpan.';
    }

    return 'Simpan setelah status keluar selesai diisi.';
  });

  const saveStatusPasien = () => {
    isSaving.value = true;

    emit('save-status-pasien', {
      DataPasien: props.DataPasien,
      status_pasien: props.formData?.status_pasien || {},
    });

    window.setTimeout(() => {
      isSaving.value = false;
      saveStatus.value = 'ready';
    }, 400);
  };

  function labelize(value) {
    if (!value) return '-';
    return String(value)
      .replace(/_/g, ' ')
      .replace(/\b\w/g, (char) => char.toUpperCase());
  }
</script>

<style scoped src="./FormPemeriksaan.css"></style>
