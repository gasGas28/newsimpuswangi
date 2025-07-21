<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-6 rounded shadow">
      <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block text-gray-700 mb-1">Name</label>
          <input type="text" v-model="form.name" class="w-full p-2 border rounded" required />
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 mb-1">Email</label>
          <input type="email" v-model="form.email" class="w-full p-2 border rounded" required />
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 mb-1">Password</label>
          <input type="password" v-model="form.password" class="w-full p-2 border rounded" required />
        </div>

        <div class="mb-6">
          <label class="block text-gray-700 mb-1">Confirm Password</label>
          <input type="password" v-model="form.password_confirmation" class="w-full p-2 border rounded" required />
        </div>

        <button type="submit"
          class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">
          Register
        </button>
      </form>

      <p v-if="errors" class="text-red-600 mt-4">{{ errors }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const errors = ref(null)

function submit() {
  errors.value = null
  router.post('/register', form.value, {
    onError: (err) => {
      errors.value = err.message || 'Register gagal'
    }
  })
}
</script>
