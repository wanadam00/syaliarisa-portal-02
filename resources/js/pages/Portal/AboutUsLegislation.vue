<template>

    <Head title="Legislation" />
    <AppLayout2>
        <!-- Hero Section -->
        <section
            class="bg-gradient-to-r from-[#2262ae] to-[#48b2e5] dark:bg-background dark:border-b py-12 text-white pt-32"
            data-aos="fade-down">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Legislations</h1>
                <p class="text-lg opacity-90">Explore our legislative references and resources</p>
            </div>
        </section>

        <!-- Legislations List -->
        <section class="py-16">
            <div class="container mx-auto px-4 space-y-12">
                <!-- Group by type -->
                <div v-for="(laws, type) in groupedLegislations" :key="type" class="space-y-6" data-aos="fade-up">

                    <!-- Type Header -->
                    <div class="text-center md:text-left mb-6">
                        <h2 class="text-2xl font-bold text-primary text-center dark:text-white">{{ type }}</h2>
                        <p v-if="laws[0].details" class="text-gray-600 text-justify dark:text-gray-400 mt-2">
                            {{ laws[0].details }}
                        </p>
                    </div>

                    <!-- Shared container for all laws of this type -->
                    <div
                        class="bg-white dark:bg-background rounded-lg shadow-md hover:shadow-lg transition duration-300 p-6 flex flex-col md:flex-row gap-6">

                        <!-- One Image for the whole type -->
                        <div class="md:w-1/3 flex justify-center">
                            <img v-if="laws[0].image" :src="laws[0].image" :alt="type"
                                class="rounded-lg w-full object-cover" />
                            <i v-else class="bi bi-file-text text-4xl text-gray-400"></i>
                        </div>

                        <!-- All Titles under this type -->
                        <div class="flex-1 space-y-6">
                            <div v-for="law in laws" :key="law.id" class="border-b pb-4 last:border-0 last:pb-0"
                                data-aos="fade-up">
                                <h3 class="text-lg font-semibold text-primary dark:text-white mb-2">
                                    {{ law.title }}
                                </h3>
                                <p class="text-gray-700 dark:text-gray-300 text-justify mb-3">
                                    {{ law.description }}
                                </p>
                                <div class="flex pt-4 justify-end">
                                    <a v-if="law.link" :href="law.link" target="_blank"
                                        class="group inline-flex items-center gap-2 bg-[#2262ae] text-white border-2 border-[#2262ae] hover:bg-white hover:text-[#2262ae] font-medium px-4 py-2 rounded-lg transition-all duration-300 hover:shadow-md">
                                        <span>Learn More</span>
                                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
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

// Group by type
const groupedLegislations = legislations.reduce((groups: Record<string, AboutUsLegislation[]>, law) => {
    if (!groups[law.type]) {
        groups[law.type] = []
    }
    groups[law.type].push(law)
    return groups
}, {})
</script>
