<template>

    <Head title="Standards" />
    <AppLayout2>
        <!-- Hero Section -->
        <section
            class="bg-gradient-to-r from-[#2262ae] to-[#48b2e5] dark:bg-background dark:border-b py-12 text-white pt-32"
            data-aos="fade-down">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Standards Application</h1>
                <p class="text-lg opacity-90">Explore our recognized standards and guidelines</p>
            </div>
        </section>

        <!-- Standards List -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="space-y-6">
                    <div v-for="standard in standards" :key="standard.id"
                        class="bg-white dark:bg-background rounded-lg shadow-md hover:shadow-lg transition duration-300 p-6 items-center"
                        data-aos="fade-up">
                        <h2 class="text-xl text-center font-bold text-primary dark:text-white mb-6">
                            {{ standard.title }}
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <!-- Column 1: Logo -->
                            <div class="flex justify-center md:justify-center">
                                <div class="lg:w-1/2">
                                    <img v-if="standard.logo" :src="standard.logo" :alt="standard.title"
                                        class="rounded-lg w-full max-h-100 object-cover" />
                                    <i v-else class="bi bi-file-earmark-text text-3xl text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Column 3: Description + Link -->
                            <div class="text-justify md:text-justify">
                                <p class="text-gray-700 dark:text-gray-300 mb-3">{{ standard.description }}</p>
                                <a v-if="standard.link" :href="standard.link" target="_blank"
                                    class="inline-block px-4 py-2 bg-[#2262ae] text-white text-sm font-medium rounded-lg hover:bg-blue-700 dark:hover:bg-blue-500 transition">
                                    More Info
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

interface AboutUsStandard {
    id: number
    title: string
    description: string
    logo: string | null
    link: string | null
    is_visible: boolean
}

const { aboutUsStandards } = usePage().props as unknown as {
    aboutUsStandards: AboutUsStandard[]
}

const standards = aboutUsStandards;
const visibleStandards = standards.filter((standard) => standard.is_visible);
</script>
