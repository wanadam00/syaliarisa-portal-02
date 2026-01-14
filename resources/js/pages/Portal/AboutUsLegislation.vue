<template>

    <Head title="Guidelines" />
    <AppLayout2>
        <!-- Hero Section -->
        <section
            class="bg-gradient-to-r from-[#133762] to-[#2262AE] dark:bg-background dark:border-b py-12 text-white pt-32"
            data-aos="fade-down">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Guidelines & References</h1>
                <p class="text-lg opacity-90">Standardized guidance for completing various HSE assessments effectively
                </p>
            </div>
        </section>

        <!-- Legislations List -->
        <section class="py-16">
            <div class="container mx-auto px-4 space-y-6">
                <div v-for="law in legislations" :key="law.id"
                    class="bg-white dark:bg-background rounded-lg shadow-md hover:shadow-lg transition duration-300 p-6 flex flex-col md:flex-row gap-6"
                    data-aos="fade-up">
                    <div class="md:w-1/3 flex justify-center">
                        <img v-if="law.image" :src="law.image" :alt="law.title"
                            class="rounded-lg w-full object-cover" />
                        <i v-else class="bi bi-file-text text-4xl text-gray-400"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-primary dark:text-white mb-2">
                            {{ law.title }}
                        </h3>
                        <div class="text-gray-700 dark:text-gray-300 text-justify mb-3"
                            v-html="formatQuillContent(law.description)">
                        </div>
                        <div class="flex justify-end">
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
    display_mode?: 'group' | 'individual'
}

const { aboutUsLegislations } = usePage().props as unknown as {
    aboutUsLegislations: AboutUsLegislation[]
}

const legislations = aboutUsLegislations

const formatQuillContent = (html: string) => {
    if (!html) return "";
    return html
        .replace(/<ol>/g, '<ol style="list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem;">')
        .replace(/<ul>/g, '<ul style="list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem;">')
        .replace(/class="ql-align-center"/g, 'style="text-align: center;"')
        .replace(/class="ql-align-right"/g, 'style="text-align: right;"')
        .replace(/class="ql-align-justify"/g, 'style="text-align: justify;"')
        .replace(/<p><br><\/p>/g, '');
};
</script>
