<script setup lang="ts">
import { Head, usePage } from "@inertiajs/vue3";
import AppLayout2 from "@/layouts/AppLayout2.vue";
import { ref, reactive } from "vue";
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/pagination';
import { Pagination } from 'swiper/modules';

interface ServiceImage {
    id: number;
    url: string;
}

interface Service {
    id: number;
    type: string;
    title: string;
    summary: string;
    details: string;
    images: ServiceImage[];
    link?: string; // ✅ add video field
    is_visible: boolean;
}

const { services } = usePage().props as unknown as { services: Service[] };

const healthServices = services.filter((s) =>
    s.type.toLowerCase().includes("health")
);

// Track open state per service to avoid cross-triggering when one item toggles
const openDetailsMap = reactive<Record<number, boolean>>({});
const showVideo = ref<string | null>(null); // ✅ for modal video

function toggleDetails(serviceId: number) {
    // Toggle the given service's open state while closing other items
    const shouldOpen = !openDetailsMap[serviceId];
    // close all currently tracked items
    Object.keys(openDetailsMap).forEach((k) => (openDetailsMap[Number(k)] = false));
    // set the clicked one to the desired state
    openDetailsMap[serviceId] = shouldOpen;
}

const formatQuillContent = (html: string) => {
    if (!html) return "";
    return html
        .replace(/<ol>/g, '<ol style="list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem;">')
        .replace(/<ul>/g, '<ul style="list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem;">')
        .replace(/<p><br><\/p>/g, '');
};

</script>

<template>

    <Head title="Services (Health & Safety)" />

    <AppLayout2>
        <!-- Hero Section -->
        <section
            class="bg-gradient-to-r from-[#133762] to-[#2262AE] dark:bg-background dark:border-b py-12 text-white pt-32"
            data-aos="fade-down">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Health & Safety
                </h1>
                <p class="text-gray-200 text-lg">
                    Explore our professional Health & Safety solutions
                </p>
            </div>
        </section>

        <!-- Services Section -->
        <section class="py-16">
            <div class="container mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 md:px-0 items-start">
                    <div v-for="service in healthServices" :key="service.id"
                        class="bg-white dark:bg-background rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 flex flex-col h-auto"
                        data-aos="fade-up">

                        <!-- Image Gallery -->
                        <div class="relative">
                            <div v-if="service.images?.length" class="overflow-hidden rounded-t-2xl">
                                <Swiper :slides-per-view="1" :pagination="{ clickable: true }" :modules="[Pagination]"
                                    class="pb-6">
                                    <SwiperSlide v-for="(img, index) in service.images" :key="index">
                                        <img :src="img.url" :alt="`${service.title} image ${index + 1}`"
                                            class="w-full h-48 md:h-64 object-cover rounded-t-2xl" />
                                    </SwiperSlide>
                                </Swiper>
                            </div>
                            <div v-else
                                class="h-48 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-t-2xl">
                                <i class="bi bi-image text-4xl text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex flex-col flex-grow">
                            <h2 class="text-xl font-bold text-center text-primary mb-3 dark:text-blue-400">
                                {{ service.title }}
                            </h2>

                            <div class="text-gray-700 text-justify dark:text-gray-300 text-md mb-3"
                                v-html="formatQuillContent(service.summary)">
                            </div>

                            <!-- Toggle Details -->
                            <div v-if="service.details">
                                <button @click="toggleDetails(service.id)"
                                    class="mt-2 flex items-center gap-2 cursor-pointer text-blue-600 dark:text-blue-400 font-medium">
                                    <span>{{ openDetailsMap[service.id] ? 'Hide details' : 'More details' }}</span>
                                    <i class="bi bi-chevron-down transition-transform duration-300"
                                        :class="{ 'rotate-180': openDetailsMap[service.id] }"></i>
                                </button>

                                <!-- Animated Details Section (safe per-item animation using max-height + opacity) -->
                                <div class="mt-2 text-gray-600 text-justify dark:text-gray-300 text-md leading-relaxed overflow-hidden transition-all duration-500"
                                    :style="{
                                        maxHeight: openDetailsMap[service.id] ? '1000px' : '0px',
                                        opacity: openDetailsMap[service.id] ? 1 : 0,
                                    }">
                                    <div v-html="formatQuillContent(service.details)" class="px-0 py-2"></div>

                                    <!-- Watch Video Button -->
                                    <button v-if="service.link" @click="showVideo = service.link"
                                        class="mt-4 mb-3 w-full bg-white/80 backdrop-blur-sm border border-gray-200 text-[#2262ae] hover:bg-[#2262ae] hover:text-white font-medium py-3 px-4 rounded-xl transition-all duration-300 hover:shadow-lg flex items-center justify-center gap-3 group">
                                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                        <span>Watch Video</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Video Modal -->
        <teleport to="body">
            <div v-if="showVideo" data-aos="zoom-in"
                class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 sm:pt-24">
                <div class="relative w-11/12 md:w-3/4 lg:w-2/3">
                    <!-- YouTube -->
                    <template v-if="showVideo.includes('youtube')">
                        <iframe
                            :src="showVideo.replace(/(?:https?:\/\/)?(?:www\.)?youtube\.com\/.*[?&]v=([a-zA-Z0-9_-]+).*/, 'https://www.youtube.com/embed/$1')"
                            class="w-full h-64 md:h-96 rounded-lg shadow-lg" frameborder="0" allowfullscreen>
                        </iframe>
                    </template>

                    <!-- Vimeo -->
                    <template v-else-if="showVideo.includes('vimeo')">
                        <iframe :src="showVideo.replace('vimeo.com', 'player.vimeo.com/video')"
                            class="w-full h-64 md:h-96 rounded-lg shadow-lg" frameborder="0" allowfullscreen>
                        </iframe>
                    </template>

                    <!-- Google Drive -->
                    <template v-else-if="showVideo.includes('drive.google.com')">
                        <iframe :src="showVideo.replace('/view', '/preview')"
                            class="w-full h-64 md:h-[506px] rounded-lg shadow-lg" frameborder="0" allowfullscreen>
                        </iframe>
                    </template>

                    <!-- Fallback MP4 -->
                    <template v-else>
                        <video controls autoplay class="w-full rounded-lg shadow-lg">
                            <source :src="showVideo" type="video/mp4" />
                            Your browser does not support the video tag.
                        </video>
                    </template>

                    <!-- Close Button -->
                    <button @click="showVideo = null"
                        class="absolute -top-10 right-0 md:-top-12 text-white text-3xl font-bold">
                        ×
                    </button>
                </div>
            </div>
        </teleport>
    </AppLayout2>
</template>
