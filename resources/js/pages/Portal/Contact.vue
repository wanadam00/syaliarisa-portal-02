<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout2 from '@/layouts/AppLayout2.vue';

interface ContactInfo { id: number; type: string; value: string; }

const { contactInfo } = usePage().props as unknown as { contactInfo: ContactInfo[] };
</script>

<template>

    <Head title="Contact Us" />
    <div class="flex flex-col min-h-screen bg-gradient-to-br from-white to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <AppLayout2>
            <main class="flex-1 py-10 px-4 md:px-12">
                <div class="max-w-5xl mx-auto">
                    <h1 class="text-3xl font-bold mb-8 text-blue-900 dark:text-white">Contact Us</h1>
                    <ul class="space-y-2">
                        <li v-for="info in contactInfo" :key="info.id" class="flex items-center gap-2">
                            <span class="font-semibold text-gray-700 dark:text-gray-200">{{ info.type }}:</span>
                            <span class="text-gray-600 dark:text-gray-300">{{ info.value }}</span>
                        </li>
                    </ul>
                </div>
            </main>
        </AppLayout2>
    </div>
</template>
