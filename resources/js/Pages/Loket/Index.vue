<template>
    <AppLayout>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
              <a href="/pasien">  
                <button class="btn btn-outline-primary">Add Pasien</button>
              </a>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Components/Layouts/AppLayouts.vue";

defineProps({
    users: Object,
});
</script>
