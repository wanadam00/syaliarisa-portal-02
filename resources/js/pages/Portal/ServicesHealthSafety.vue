<script setup lang="ts">
import { Head, usePage } from "@inertiajs/vue3";
import AppLayout2 from "@/layouts/AppLayout2.vue";
import { ref } from "vue";

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
    is_visible: boolean;
}

const { services } = usePage().props as unknown as { services: Service[] };

const healthServices = services.filter((s) =>
    s.type.toLowerCase().includes("health")
);

// track which service is expanded
const openDetails = ref<number | null>(null);

function toggleDetails(serviceId: number) {
    openDetails.value = openDetails.value === serviceId ? null : serviceId;
}
</script>

<template>

    <Head title="Services (Health & Safety)" />

    <AppLayout2>
        <!-- Hero Section -->
        <section class="bg-[#2262ae] dark:bg-background py-12 pt-32 text-white">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Services (Health & Safety)
                </h1>
                <p class="text-gray-200 text-lg">
                    Explore our professional Health & Safety solutions
                </p>
            </div>
        </section>

        <!-- Services Section -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="service in healthServices" :key="service.id"
                        class="bg-white dark:bg-background rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 flex flex-col h-auto">
                        <!-- Image Gallery -->
                        <div class="relative">
                            <div v-if="service.images?.length" class="overflow-hidden rounded-t-2xl">
                                <img :src="service.images[0].url" :alt="service.title"
                                    class="h-48 w-full object-cover" />
                            </div>
                            <div v-else
                                class="h-48 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-t-2xl">
                                <i class="bi bi-image text-4xl text-gray-400"></i>
                            </div>
                            <div v-if="service.images && service.images.length > 1"
                                class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded-md">
                                +{{ service.images.length - 1 }} more
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex flex-col flex-grow">
                            <h2 class="text-xl font-bold text-center text-primary mb-3 dark:text-blue-400">
                                {{ service.title }}
                            </h2>

                            <p class="text-gray-700 dark:text-gray-300 text-sm mb-3">
                                {{ service.summary }}
                            </p>

                            <!-- Toggle -->
                            <button @click="toggleDetails(service.id)"
                                class="mt-2 flex items-center gap-2 cursor-pointer text-blue-600 dark:text-blue-400 font-medium">
                                <span>More details</span>
                                <i class="bi bi-chevron-down transition-transform duration-300"
                                    :class="{ 'rotate-180': openDetails === service.id }"></i>
                            </button>

                            <!-- Details -->
                            <div v-show="openDetails === service.id"
                                class="mt-2 text-gray-600 dark:text-gray-300 text-sm leading-relaxed transition-all duration-300"
                                style="display: none;">
                                <p>{{ service.details }}</p>

                                <!-- Show all images in details -->
                                <div v-if="service.images && service.images.length > 1"
                                    class="mt-4 grid grid-cols-2 gap-3">
                                    <div v-for="(img, i) in service.images.slice(1)" :key="i"
                                        class="rounded-lg overflow-hidden">
                                        <img :src="img.url" :alt="`${service.title} image ${i + 2}`"
                                            class="w-full h-32 object-cover" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout2>
</template>
