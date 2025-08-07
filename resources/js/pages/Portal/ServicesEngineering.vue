<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import PortalHeader from '@/components/PortalHeader.vue';
import PortalFooter from '@/components/PortalFooter.vue';

interface Service { id: number; type: string; title: string; summary: string; details: string; image: string | null; is_visible: boolean; }

const { services } = usePage().props as unknown as { services: Service[] };
const engineeringServices = services.filter(s => s.type.toLowerCase().includes('engineering'));
</script>

<template>

    <Head title="Services (Engineering)" />
    <div class="flex flex-col min-h-screen bg-gradient-to-br from-white to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <PortalHeader />
        <main class="flex-1 py-10 px-4 md:px-12">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-3xl font-bold mb-8 text-blue-900 dark:text-white">Services (Engineering)</h1>
                <div class="grid md:grid-cols-2 gap-8">
                    <template v-for="service in engineeringServices" :key="service.id">
                        <div v-if="service.is_visible" class="border rounded-lg p-6 bg-blue-50 dark:bg-gray-800">
                            <h3 class="text-lg font-bold text-blue-700 dark:text-blue-300 mb-2">{{ service.title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Type: <span class="font-medium">{{
                                    service.type.replace('_', ' ') }}</span></p>
                            <p class="text-gray-700 dark:text-gray-200 mb-2">{{ service.summary }}</p>
                            <details class="mt-2">
                                <summary class="cursor-pointer text-blue-600 dark:text-blue-400">More details</summary>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">{{ service.details }}</p>
                            </details>
                            <img v-if="service.image" :src="service.image" alt="Service image"
                                class="mt-4 rounded-lg max-h-40 object-cover w-full" />
                        </div>
                    </template>
                </div>
            </div>
        </main>
        <PortalFooter />
    </div>
</template>
