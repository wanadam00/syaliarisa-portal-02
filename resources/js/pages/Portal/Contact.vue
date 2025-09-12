<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout2 from '@/layouts/AppLayout2.vue';

interface ContactInfo {
    id: number;
    address: string;
    phone: string;
    email: string;
    map_embed: Text;
}

const { contactInfo } = usePage().props as unknown as { contactInfo: ContactInfo[] };
</script>

<template>

    <Head title="Contact Us" />
    <div class="flex flex-col min-h-screen bg-gradient-to-br from-white to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <AppLayout2>
            <!-- Page Header -->
            <section class="bg-[#2262ae] dark:bg-background dark:border-b py-12 text-white pt-32">
                <div class="container mx-auto px-4 text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Get In Touch</h1>
                    <p class="text-xl text-blue-100 dark:text-gray-300 max-w-2xl mx-auto">
                        We'd love to hear from you. Reach out to us through any of the channels below.
                    </p>
                </div>
            </section>

            <!-- Contact Content -->
            <main class="flex-1 py-16 px-4 md:px-12">
                <div class="max-w-6xl mx-auto">
                    <div class="grid lg:grid-cols-2 gap-12">
                        <!-- Contact Information -->
                        <div class="space-y-8">
                            <div>
                                <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Contact Information
                                </h2>
                                <div class="space-y-6">
                                    <div v-for="info in contactInfo" :key="info.id" class="space-y-4">
                                        <!-- Address -->
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="bi bi-geo-alt text-blue-600 dark:text-blue-400 text-xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Address</h3>
                                                <p class="text-gray-600 dark:text-gray-300">{{ info.address }}</p>
                                            </div>
                                        </div>

                                        <!-- Phone -->
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="bi bi-telephone text-blue-600 dark:text-blue-400 text-xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Phone</h3>
                                                <a :href="`tel:${info.phone}`"
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                                    {{ info.phone }}
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="bi bi-envelope text-blue-600 dark:text-blue-400 text-xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Email</h3>
                                                <a :href="`mailto:${info.email}`"
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                                    {{ info.email }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Business Hours -->
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Business Hours</h3>
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-300">Monday - Friday</span>
                                            <span class="font-medium text-gray-900 dark:text-white">8:00 AM - 6:00
                                                PM</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-300">Saturday</span>
                                            <span class="font-medium text-gray-900 dark:text-white">9:00 AM - 2:00
                                                PM</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-300">Sunday</span>
                                            <span class="font-medium text-gray-900 dark:text-white">Closed</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Form -->
                        <div>
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Send us a Message</h2>
                                <form class="space-y-6">
                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div>
                                            <label
                                                class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">First
                                                Name</label>
                                            <input type="text"
                                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Last
                                                Name</label>
                                            <input type="text"
                                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Email</label>
                                        <input type="email"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Subject</label>
                                        <input type="text"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Message</label>
                                        <textarea rows="4"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                                        Send Message
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="mt-16">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Our Location</h2>
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                            <div class="aspect-video bg-gray-200 dark:bg-gray-700">
                                <!-- Safely render Google Maps iframe from DB -->
                                <div v-if="contactInfo[0]?.map_embed" class="w-full h-full">
                                    <div v-html="contactInfo[0].map_embed"></div>
                                </div>
                                <div v-else class="flex items-center justify-center h-full">
                                    <i class="bi bi-map text-4xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </AppLayout2>
    </div>
</template>
