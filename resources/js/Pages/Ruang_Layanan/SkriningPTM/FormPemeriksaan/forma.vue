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
            <select
              id="spirometri"
              name="spirometri"
              class="form-select"
              v-model="form.spirometri"
            >
              <option value="tidak">Tidak</option>
              <option value="iya">Iya</option>
            </select>
          </div>