<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import PortalHeader from '@/components/PortalHeader.vue';
import PortalFooter from '@/components/PortalFooter.vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination } from 'swiper/modules';
import AppLayout2 from '@/layouts/AppLayout2.vue';

interface CompanyInfo {
    background: string;
    vision: string;
    mission: string;
    is_visible: boolean;
}

interface Service {
    type: string;
    title: string;
    summary: string;
    details: string;
    image: string | null;
    is_visible: boolean;
}

interface HomeSection {
    top_details: string;
    top_image: string;
    bottom_details: string;
    bottom_image: string;
}

const {
    companyInfo,
    services,
    homeSections,
} = usePage().props as unknown as {
    companyInfo: CompanyInfo[];
    services: Service[];
    homeSections: HomeSection[];
};
</script>

<template>

    <Head title="Syaliarisa - Home" />
    <AppLayout2>

        <!-- Hero -->
        <section class="min-h-[80vh] flex items-center relative">
            <!-- Background image with dark overlay -->
            <div class="absolute inset-0 bg-cover bg-center z-0 after:absolute after:inset-0 after:bg-black/60"
                style="background-image: url('/images/banner-company.jpg')"></div>

            <!-- Content container -->
            <div class="container mx-auto px-4 py-20 relative z-10 text-white">
                <div class="max-w-2xl p-8 rounded-lg">
                    <h1 class="text-5xl font-bold mb-6">Expert Environmental & Safety Consulting</h1>
                    <p class="text-xl mb-8">
                        {{ homeSections[0]?.top_details }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#contact"
                            class="bg-secondary hover:bg-primary text-white font-medium px-6 py-3 rounded transition">
                            Get a Consultation
                        </a>
                        <a href="#services"
                            class="border-2 border-white hover:bg-white hover:text-primary text-white font-medium px-6 py-3 rounded transition">
                            Our Services
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section id="services" class="py-16 bg-gray-50 dark:bg-gray-800">
            <div class="container mx-auto px-4">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <h2 class="text-3xl font-bold mb-4 text-primary dark:text-secondary">Our Comprehensive Services
                    </h2>
                    <p class="text-xl">Tailored solutions to meet your environmental, health, and safety needs</p>
                </div>

                <div class="overflow-x-auto">
                    <div class="flex space-x-6 pb-4">
                        <div v-for="service in services.filter(s => s.is_visible)" :key="service.title"
                            class="min-w-[300px] bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg shadow-md overflow-hidden p-6 text-center flex flex-col">
                            <div
                                class="w-20 h-20 bg-primary rounded-full flex items-center justify-center mx-auto mb-5 text-white text-3xl">
                                <i class="bi bi-gear"></i>
                            </div>
                            <h3 class="text-xl font-medium mb-3">{{ service.title }}</h3>
                            <p class="mb-5 flex-grow">{{ service.summary }}</p>
                            <a :href="'/services/' + service.type"
                                class="inline-block border border-primary text-primary hover:bg-primary hover:text-white px-4 py-2 rounded transition">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About -->
        <section id="about" class="py-16 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4 flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2">
                    <img src="/images/banner-company.jpg" alt="About" class="rounded-lg shadow-md w-full" />
                </div>
                <div class="lg:w-1/2">
                    <h2 class="text-3xl font-bold mb-6 text-primary dark:text-secondary">About Syaliarisa Services
                    </h2>
                    <p class="text-xl mb-4">{{ homeSections[0]?.bottom_details }}</p>
                    <!-- <p class="mb-4">{{ companyInfo[0]?.mission }}</p> -->
                    <a href="/company-info"
                        class="inline-block bg-primary hover:bg-secondary text-white font-medium px-6 py-3 rounded transition">
                        Learn More About Us
                    </a>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <!-- <section id="contact" class="py-16 bg-primary text-white dark:bg-blue-800">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row justify-between items-center">
                    <div class="lg:w-2/3 mb-8 lg:mb-0">
                        <h2 class="text-3xl font-bold mb-4">Ready to Enhance Your Workplace Safety?</h2>
                        <p class="text-xl">
                            Contact us today for a free consultation on how we can help your organization
                            achieve compliance and create a safer work environment.
                        </p>
                    </div>
                    <div class="lg:w-1/3 text-center lg:text-right">
                        <a href="#contact"
                            class="inline-block bg-white hover:bg-gray-100 text-primary font-medium px-8 py-3 rounded transition">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </section> -->

    </AppLayout2>
</template>
