<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout2 from '@/layouts/AppLayout2.vue';

interface Service { id: number; type: string; title: string; summary: string; details: string; image: string | null; is_visible: boolean; }

const { services } = usePage().props as unknown as { services: Service[] };
const environmentalServices = services.filter(s => s.type.toLowerCase().includes('environment'));
</script>

<template>

    <Head title="Services (Environmental)" />
    <AppLayout2>
        <section class="bg-[#2262ae] dark:bg-background dark:border-b py-12 text-white pt-32">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 dark:text-white">Services (Environmental)</h1>
            </div>
        </section>

        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-2 gap-6">
                    <div v-for="service in environmentalServices" :key="service.id"
                        class="bg-white dark:bg-background p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold mb-4 text-primary">{{ service.title }}</h2>
                        <p class="text-gray-700 dark:text-gray-300">{{ service.summary }}</p>
                        <details class="mt-2">
                            <summary class="cursor-pointer text-blue-600 dark:text-blue-400">More details</summary>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">{{ service.details }}</p>
                        </details>
                        <img v-if="service.image" :src="service.image" alt="Service image"
                            class="mt-4 rounded-lg max-h-40 object-cover w-full" />
                    </div>
                </div>
            </div>
        </section>
    </AppLayout2>
</template>
