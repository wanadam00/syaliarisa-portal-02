<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import PortalHeader from '@/components/PortalHeader.vue';
import PortalFooter from '@/components/PortalFooter.vue';

interface AboutUsContent { id: number; title: string; content: string; }

const { aboutUsContents } = usePage().props as unknown as { aboutUsContents: AboutUsContent[] };
const legislationContents = aboutUsContents.filter(a => a.title.toLowerCase().includes('legislation'));
</script>

<template>

    <Head title="About Us (Legislation)" />
    <div class="flex flex-col min-h-screen bg-gradient-to-br from-white to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <PortalHeader />
        <main class="flex-1 py-10 px-4 md:px-12">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-3xl font-bold mb-8 text-blue-900 dark:text-white">About Us (Legislation)</h1>
                <div class="space-y-6">
                    <div v-for="item in legislationContents" :key="item.id">
                        <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-300">{{ item.title }}</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ item.content }}</p>
                    </div>
                </div>
            </div>
        </main>
        <PortalFooter />
    </div>
</template>
