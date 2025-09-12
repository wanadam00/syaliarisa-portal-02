<template>

    <Head title="Contact Us" />
    <AppLayout2>
        <section class="bg-[#2262ae] dark:bg-background dark:border-b py-12 text-white pt-32">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 dark:text-white">Contact Us</h1>
            </div>
        </section>

        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="bg-white dark:bg-background p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold mb-4 text-primary">Get in Touch</h2>
                    <p class="text-gray-700 dark:text-gray-300">Address: {{ contactInfo.address }}</p>
                    <p class="text-gray-700 dark:text-gray-300">Phone: {{ contactInfo.phone }}</p>
                    <p class="text-gray-700 dark:text-gray-300">Email: {{ contactInfo.email }}</p>
                </div>
            </div>
        </section>
    </AppLayout2>
</template>

<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout2 from '@/layouts/AppLayout2.vue';

interface ContactInfo {
    address: string;
    phone: string;
    email: string;
}

const { contactInfo } = usePage().props as unknown as {
    contactInfo: ContactInfo;
};
</script>
