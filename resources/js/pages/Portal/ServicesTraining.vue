<script setup lang="ts">
import { Head, usePage } from "@inertiajs/vue3";
import AppLayout2 from "@/layouts/AppLayout2.vue";
import { ref } from "vue";
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/pagination';
import { Pagination } from 'swiper/modules';

// No need to use Swiper.use for modern versions

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

const trainingServices = services.filter((s) =>
    s.type.toLowerCase().includes("training")
);

// track which service is expanded
const openDetails = ref<number | null>(null);
const showVideo = ref<string | null>(null); // ✅ for modal video

function toggleDetails(serviceId: number) {
    openDetails.value = openDetails.value === serviceId ? null : serviceId;
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

    <Head title="Services (Training)" />

    <AppLayout2>
        <!-- Hero Section -->
        <section class="bg-[#2262ae] dark:bg-background py-12 pt-32 text-white">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Training
                </h1>
                <p class="text-gray-200 text-lg">
                    Explore our professional Training solutions
                </p>
            </div>
        </section>

        <!-- Services Section -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 md:px-0">
                    <div v-for="service in trainingServices" :key="service.id"
                        class="bg-white dark:bg-background rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 flex flex-col h-auto">
                        <!-- Image Gallery -->
                        <div class="relative">
                            <div v-if="service.images?.length" class="overflow-hidden rounded-t-2xl">
                                <!-- Image Slider -->
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
                            <!-- <div v-if="service.images && service.images.length > 1"
                                class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded-md">
                                +{{ service.images.length - 1 }} more
                            </div> -->
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex flex-col flex-grow">
                            <h2 class="text-xl font-bold text-center text-primary mb-3 dark:text-blue-400">
                                {{ service.title }}
                            </h2>

                            <div class="text-gray-700 text-justify dark:text-gray-300 text-md mb-3"
                                v-html="formatQuillContent(service.summary)">
                            </div>

                            <!-- Toggle -->
                            <button @click="toggleDetails(service.id)"
                                class="mt-2 flex items-center gap-2 cursor-pointer text-blue-600 dark:text-blue-400 font-medium">
                                <span>More details</span>
                                <i class="bi bi-chevron-down transition-transform duration-300"
                                    :class="{ 'rotate-180': openDetails === service.id }"></i>
                            </button>

                            <!-- Details -->
                            <div v-show="openDetails === service.id"
                                class="mt-2 text-gray-600 dark:text-gray-300 text-md leading-relaxed transition-all duration-300">
                                <div v-html="formatQuillContent(service.details)"></div>
                                <!-- Watch Video Button -->
                                <button v-if="service.link" @click="showVideo = service.link"
                                    class="mt-2 mb-3 w-full bg-[#2262ae] hover:bg-[#1d4f8a] text-white font-medium py-2 px-4 rounded-lg transition">
                                    Watch Video
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Video Modal -->
        <teleport to="body">
            <div v-if="showVideo"
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
