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
              <option value="Tidak Berlaku">Tidak berlaku</option>
              <option value="ambulan">Ambulans</option>
              <option value="kendaraan_pribadi">Kendaraan pribadi</option>
              <option value="ojek">Ojek/taksi</option>
            </select>
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

     <section class="planning-panel">
      <div class="panel-header compact">
        <div>
          <h4><i class="bi bi-list-check"></i> Data Status</h4>
          <p>Status tercatat untuk pasien ini.</p>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table planning-table mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Kondisi Pasien</th>
              <th>Jadwal Kontrol</th>
              <th>Nama Dokter</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!pasien.kondisi_pasien">
              <td colspan="7" class="empty-state">
                <i class="bi bi-inbox"></i>
                <span>Data status belum tersedia.</span>
              </td>
            </tr>
            <tr>
              <td>1</td>
              <td>
                <span class="code-pill">{{ pasien.kondisi_pasien }}</span>
              </td>
              <td class="fw-semibold">{{ pasien.jadwal_kontrol }}</td>
              <td>
                <span class="service-pill">{{ pasien.nmDokter }}</span>
              </td>
              <td class="text-center">
                <button
                  class="btn btn-outline-danger"
                  @click="hapusTindakan(item.idTindakan)"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>


    <ModalAlert
      :show="showSuccessModal"
      type="success"
      title="Status Pasien Berhasil Disimpan"
      message="Silakan lanjutkan pengiriman satu sehat."
      button-text="Tutup"
      secondary-button-text="Close"
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
   import { computed, ref, watchEffect } from 'vue';
  import ModalAlert from '../../../Components/Layouts/Modal/ModalAlert.vue';


   const props = defineProps({
     DataPasien: Object,
   });

   const pasien = computed(() => props.DataPasien || {});

   const emit = defineEmits(['save-status-pasien']);
   const isSaving = ref(false);
   const saveStatus = ref('idle');
   const saveError = ref('');
   const showSuccessModal = ref(false);
   const showValidationModal = ref(false);
   const validationMessages = ref([]);

   const form = useForm({
     skriningId: props.DataPasien?.idSkrining || '',
     kondisi_keluar: '',
     cara_keluar: '',
     jadwal_kontrol: '',
     rencana_rujuk: 'tidak',
     transport: 'Tidak Berlaku'
   });

    console.log('Form initialized with:', form);

   const saveMessage = computed(() => {
     if (saveStatus.value === 'ready') {
       return 'Data status pasien siap disimpan.';
     }
     return 'Simpan setelah status keluar selesai diisi.';
   });

  function saveStatusPasien() {
     console.log('Data yang akan dikirim:', form.data());

     isSaving.value = true;
     saveStatus.value = 'idle';
     saveError.value = '';

     showSuccessModal.value = false;
     showValidationModal.value = false;

     validationMessages.value = [];

     form.post(route('pelayanan.status-pasien-ptm'), {
       preserveScroll: true,

       onBefore: () => {
         console.log('Mulai request');
       },

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

       onFinish: () => {
         console.log('Request selesai');
         isSaving.value = false;
       },
     });
   }

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

   function labelize(value) {
     if (!value) return '-';
     return String(value)
       .replace(/_/g, ' ')
       .replace(/\b\w/g, (char) => char.toUpperCase());
   }
</script>

<style scoped src="./FormPemeriksaan/FormPemeriksaan.css"></style>
