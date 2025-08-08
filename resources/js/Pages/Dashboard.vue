<script setup>
  import { ref } from 'vue';
  import { usePage, router, Link } from '@inertiajs/vue3';

  const products = usePage().props.products;
  const userRole = usePage().props.userRole;
  const name = ref('');

  function submit() {
    console.log('Submitting:', name.value);
    router.post(
      '/products',
      { name: name.value },
      {
        onSuccess: () => (name.value = ''),
      }
    );
  }

  function destroy(id) {
    if (confirm('Yakin mau hapus produk ini?')) {
      router.delete(`/products/${id}`, {
        onSuccess: () => {
          // hapus produk dari array frontend
          const index = products.findIndex((product) => product.id === id);
          if (index > -1) {
            products.splice(index, 1);
          }
        },
        onError: (error) => {
          console.error(error);
        },
      });
    }
  }

  const logout = () => {
    router.post('/logout');
  };
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard - List Produk</h1>
    <p>Role kamu: {{ userRole }}</p>

    <Link href="/" class="text-blue-500 underline">Ke Home</Link>
    <button @click="logout" class="ml-4 bg-gray-700 text-white px-3 py-1 rounded">Logout</button>

    <form @submit.prevent="submit" class="my-6">
      <input v-model="name" type="text" placeholder="Nama Produk" class="border rounded p-2 mr-2" />
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah</button>
    </form>

    <table class="min-w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="py-2 px-4 border">ID</th>
          <th class="py-2 px-4 border">Nama Produk</th>
          <th class="py-2 px-4 border">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td class="py-2 px-4 border">{{ product.id }}</td>
          <td class="py-2 px-4 border">{{ product.name }}</td>
          <td class="py-2 px-4 border">
            <button @click="destroy(product.id)" class="bg-red-500 text-white px-2 py-1 rounded">
              Hapus
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
