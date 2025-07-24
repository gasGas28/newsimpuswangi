<template>
  <AppLayout title="Manajemen User">
    <div class="p-4 bg-white rounded-lg shadow">
      <h1 class="text-2xl font-bold mb-4">Daftar User</h1>
      
      <table class="min-w-full divide-y divide-gray-200">
        <thead>
          <tr>
            <th class="px-6 py-3 text-left">Nama</th>
            <th class="px-6 py-3 text-left">Email</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users.data" :key="user.id">
            <td class="px-6 py-4">{{ user.name }}</td>
            <td class="px-6 py-4">{{ user.email }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Components/Layout/AppLayout.vue';

defineProps({
  users: Object,
});
</script>