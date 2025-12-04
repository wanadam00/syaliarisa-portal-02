<template>

    <Head title="Standards" />
    <AppLayout2>
        <!-- Hero Section -->
        <section
            class="bg-gradient-to-r from-[#133762] to-[#2262AE] dark:bg-background dark:border-b py-12 text-white pt-32"
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
                        <h2 class="text-xl text-center sm:hidden block font-bold text-primary dark:text-white mb-6">
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
                            <div class="">
                                <h2 class="text-xl text-center hidden sm:block sm:text-left font-bold text-primary dark:text-white mb-6">
                                    {{ standard.title }}
                                </h2>
                                <p class="text-gray-700 dark:text-gray-300 mb-3"
                                    v-html="formatQuillContent(standard.description)"></p>
                                <div class="flex justify-end pt-4">
                                    <a v-if="standard.link" :href="standard.link" target="_blank"
                                        class="group inline-flex items-center gap-2 bg-[#2262ae] text-white border-2 border-[#2262ae] hover:bg-white hover:text-[#2262ae] font-medium px-4 py-2 rounded-lg transition-all duration-300 hover:shadow-md">
                                        <span>More Info</span>
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
