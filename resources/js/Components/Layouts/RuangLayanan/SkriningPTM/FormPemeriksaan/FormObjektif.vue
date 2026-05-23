<template>
  <div class="p-3">
    <h5 class="mb-4 fw-semibold text-success">Objektif - Pemeriksaan dan Hasil Penunjang PTM</h5>

    <div class="row g-3">
      <div class="col-md-12">
        <div class="bg-light rounded-3 p-3">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="fw-semibold mb-0">Antropometri dan Tanda Vital</h6>
            <span class="badge bg-white text-success border">Observation</span>
          </div>

          <div class="row g-3 mt-2">
            <div class="col-md-3">
              <label class="form-label fw-semibold">Berat Badan</label>
              <div class="input-group">
                <input v-model.number="form.berat_badan" type="number" min="0" step="0.1" class="form-control" />
                <span class="input-group-text">kg</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Tinggi Badan</label>
              <div class="input-group">
                <input v-model.number="form.tinggi_badan" type="number" min="0" step="0.1" class="form-control" />
                <span class="input-group-text">cm</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">IMT</label>
              <input :value="form.imt" type="text" class="form-control bg-light" readonly />
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Interpretasi IMT</label>
              <input :value="form.interpretasi_imt" type="text" class="form-control bg-light" readonly />
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Lingkar Perut</label>
              <div class="input-group">
                <input v-model.number="form.lingkar_perut" type="number" min="0" step="0.1" class="form-control" />
                <span class="input-group-text">cm</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Sistolik</label>
              <div class="input-group">
                <input v-model.number="form.sistolik" type="number" min="0" class="form-control" />
                <span class="input-group-text">mmHg</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Diastolik</label>
              <div class="input-group">
                <input v-model.number="form.diastolik" type="number" min="0" class="form-control" />
                <span class="input-group-text">mmHg</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Interpretasi Tekanan Darah</label>
              <input :value="form.interpretasi_tekanan_darah" type="text" class="form-control bg-light" readonly />
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Nadi</label>
              <div class="input-group">
                <input v-model.number="form.nadi" type="number" min="0" class="form-control" />
                <span class="input-group-text">x/menit</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Pernapasan</label>
              <div class="input-group">
                <input v-model.number="form.pernapasan" type="number" min="0" class="form-control" />
                <span class="input-group-text">x/menit</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Suhu Tubuh</label>
              <div class="input-group">
                <input v-model.number="form.suhu" type="number" min="0" step="0.1" class="form-control" />
                <span class="input-group-text">C</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Saturasi Oksigen</label>
              <div class="input-group">
                <input v-model.number="form.saturasi_oksigen" type="number" min="0" max="100" class="form-control" />
                <span class="input-group-text">%</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="bg-light rounded-3 p-3">
          <h6 class="fw-semibold">Pemeriksaan Laboratorium / Penunjang</h6>
          <div class="row g-3 mt-2">
            <div class="col-md-3">
              <label class="form-label fw-semibold">Gula Darah Sewaktu</label>
              <div class="input-group">
                <input v-model.number="form.gula_darah_sewaktu" type="number" min="0" class="form-control" />
                <span class="input-group-text">mg/dL</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Gula Darah Puasa</label>
              <div class="input-group">
                <input v-model.number="form.gula_darah_puasa" type="number" min="0" class="form-control" />
                <span class="input-group-text">mg/dL</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">HbA1c</label>
              <div class="input-group">
                <input v-model.number="form.hba1c" type="number" min="0" step="0.1" class="form-control" />
                <span class="input-group-text">%</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Kolesterol Total</label>
              <div class="input-group">
                <input v-model.number="form.kolesterol_total" type="number" min="0" class="form-control" />
                <span class="input-group-text">mg/dL</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">HDL</label>
              <div class="input-group">
                <input v-model.number="form.hdl" type="number" min="0" class="form-control" />
                <span class="input-group-text">mg/dL</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">LDL</label>
              <div class="input-group">
                <input v-model.number="form.ldl" type="number" min="0" class="form-control" />
                <span class="input-group-text">mg/dL</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Trigliserida</label>
              <div class="input-group">
                <input v-model.number="form.trigliserida" type="number" min="0" class="form-control" />
                <span class="input-group-text">mg/dL</span>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Asam Urat</label>
              <div class="input-group">
                <input v-model.number="form.asam_urat" type="number" min="0" step="0.1" class="form-control" />
                <span class="input-group-text">mg/dL</span>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Interpretasi Gula Darah</label>
              <input :value="form.interpretasi_gula_darah" type="text" class="form-control bg-light" readonly />
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Interpretasi Lipid</label>
              <input :value="form.interpretasi_lipid" type="text" class="form-control bg-light" readonly />
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Hasil EKG / Jantung</label>
              <textarea
                v-model="form.hasil_ekg"
                class="form-control"
                rows="2"
                placeholder="Normal, aritmia, iskemia, atau temuan lain bila diperiksa"
              ></textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Temuan Fisik Lain</label>
              <textarea
                v-model="form.temuan_fisik_lain"
                class="form-control"
                rows="2"
                placeholder="Jantung, paru, abdomen, ekstremitas, edema, dll."
              ></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="bg-white rounded-3 p-3 border">
          <h6 class="fw-semibold">Kesimpulan Objektif</h6>
          <textarea
            v-model="form.kesimpulan_objektif"
            class="form-control mt-2"
            rows="3"
            placeholder="Ringkas hasil pemeriksaan objektif dan hasil penunjang"
          ></textarea>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  const props = defineProps({
    DataPasien: Object,
    formData: Object,
    tindakan: Array,
  });

  const form = props.formData?.objektif || {};
</script>
