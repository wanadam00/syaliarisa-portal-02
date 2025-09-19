<template>

    <Head title="Legislation" />
    <AppLayout2>
        <!-- Hero Section -->
        <section class="bg-[#2262ae] dark:bg-background py-12 text-white pt-32">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Legislations</h1>
                <p class="text-lg opacity-90">Explore our legislative references and resources</p>
            </div>
        </section>

        <!-- Legislations List -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="space-y-6">
                    <div v-for="law in legislations" :key="law.id"
                        class="bg-white dark:bg-background rounded-lg shadow-md hover:shadow-lg transition duration-300 p-6 items-center">
                        <!-- Title -->
                        <h2 class="text-xl text-center font-bold text-primary dark:text-white mb-6">
                            {{ law.title }}
                        </h2>

                        <!-- Content Columns -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Column 1: Image -->
                            <div class="flex justify-center md:justify-center">
                                <div
                                    class="w-28 h-28 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                    <img v-if="law.image" :src="law.image" :alt="law.title"
                                        class="w-24 h-24 object-contain" />
                                    <i v-else class="bi bi-file-text text-4xl text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Column 2: Details -->
                            <div class="text-center md:text-left">
                                <p class="text-gray-700 dark:text-gray-300 mb-3">
                                    {{ law.description }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    Type: {{ law.type }}
                                </p>
                                <p v-if="law.details" class="text-gray-600 dark:text-gray-400 mb-3">
                                    {{ law.details }}
                                </p>

                                <a v-if="law.link" :href="law.link" target="_blank"
                                    class="inline-block px-4 py-2 bg-[#2262ae] text-white text-sm font-medium rounded-lg hover:bg-blue-700 dark:hover:bg-blue-500 transition">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout2>
</template>

<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3'
import AppLayout2 from '@/layouts/AppLayout2.vue'

interface AboutUsLegislation {
    id: number
    title: string
    description: string
    type: string
    details: string
    image: string | null
    link: string | null
}

const { aboutUsLegislations } = usePage().props as unknown as {
    aboutUsLegislations: AboutUsLegislation[]
}

const legislations = aboutUsLegislations
</script>
