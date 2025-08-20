<template>
    <button @click="$emit('halo', 'Selamat pagi dari child!')">
        Klik Aku
    </button>
</template>

<script setup>
defineEmits(['halo']) 
</script>
