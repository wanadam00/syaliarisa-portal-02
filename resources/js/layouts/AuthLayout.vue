<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';
import { onMounted } from 'vue';
import AOS from 'aos';
import 'aos/dist/aos.css';

defineProps<{
    title?: string;
    description?: string;
}>();

onMounted(() => {
    AOS.init({
        duration: 1000, // Animation duration in milliseconds
        easing: 'ease-in-out', // Animation easing
        once: true, // Whether animation should happen only once
    });
});
</script>

<template>
    <AuthLayout :title="title" :description="description">
        <slot />
    </AuthLayout>
</template>
