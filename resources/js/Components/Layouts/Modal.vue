<template>
  <div class="modal fade" :id="modalId" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ title }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered" :id="tableId">
            <thead>
              <tr>
                <th>KODE</th>
                <th>NAMA</th>
                <th>KETERANGAN</th>
                <th>ACTION</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in items" :key="item.kode">
                <td>{{ item.kode }}</td>
                <td>{{ item.nama }}</td>
                <td>{{ item.keterangan }}</td>
                <td>
                  <button class="btn btn-sm btn-primary" @click="pilih(item)">Pilih</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

const props = defineProps({
  title: String,
  items: Array,
  modalId: String,
  tableId: String
})

const emit = defineEmits(['terpilih'])

function pilih(item) {
  emit('terpilih', item)
  const modal = bootstrap.Modal.getInstance(document.getElementById(props.modalId))
  modal.hide()
}

onMounted(() => {
  new DataTable(`#${props.tableId}`)
})
</script>
