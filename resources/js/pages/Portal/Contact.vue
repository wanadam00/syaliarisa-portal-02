<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout2 from '@/layouts/AppLayout2.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

interface ContactInfo {
    id: number;
    address: string;
    phone: string;
    email: string;
    business_hours: string | null;
    is_visible: boolean;
}

const { contactInfo } = usePage().props as unknown as { contactInfo: ContactInfo[] };

// Form state
const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

function submitForm() {
    form.post('/customers', {
        onSuccess: () => {
            alert('Your message has been sent successfully!');
            form.reset();
        },
        onError: () => {
            alert('There was an error sending your message. Please try again.');
        },
    });
}

// Function to parse and format business hours
const formatBusinessHours = (hoursHtml: string | null) => {
    if (!hoursHtml) return [];

    try {
        // Remove HTML tags and split by line breaks
        const plainText = hoursHtml.replace(/<br\s*\/?>/gi, '\n').replace(/<[^>]*>/g, '');
        const lines = plainText.split('\n').filter(line => line.trim());

        return lines.map(line => {
            const parts = line.split(':');
            if (parts.length >= 2) {
                return {
                    day: parts[0].trim(),
                    time: parts.slice(1).join(':').trim()
                };
            }
            return { day: line.trim(), time: '' };
        });
    } catch {
        return [];
    }
};
</script>

<template>

    <Head title="Contact Us" />
    <div class="flex flex-col min-h-screen bg-gradient-to-br from-white to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <AppLayout2>
            <!-- Page Header -->
            <section class="bg-[#2262ae] dark:bg-background dark:border-b py-12 text-white pt-32">
                <div class="container mx-auto px-4 text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Get In Touch</h1>
                    <p class="text-lg text-blue-100 dark:text-gray-300">
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
                                                class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900 dark:text-white">Address</h4>
                                                <p class="text-gray-600 dark:text-gray-300">{{ info.address }}</p>
                                            </div>
                                        </div>

                                        <!-- Phone -->
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white">
                                                <i class="bi bi-telephone"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900 dark:text-white">Phone</h4>
                                                <a :href="`tel:${info.phone}`"
                                                    class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors">{{
                                                    info.phone }}</a>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white">
                                                <i class="bi bi-envelope"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900 dark:text-white">Email</h4>
                                                <a :href="`mailto:${info.email}`"
                                                    class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors">{{
                                                    info.email }}</a>
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
                                    <div v-for="info in contactInfo" :key="info.id">
                                        <div v-if="info.business_hours" class="space-y-3">
                                            <div v-for="(slot, index) in formatBusinessHours(info.business_hours)"
                                                :key="index"
                                                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                                <span class="font-medium text-gray-900 dark:text-white">{{ slot.day
                                                    }}</span>
                                                <span class="text-gray-600 dark:text-gray-300">{{ slot.time }}</span>
                                            </div>
                                        </div>
                                        <div v-else class="text-gray-500 dark:text-gray-400 italic">
                                            Business hours not specified
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
                                <form @submit.prevent="submitForm" class="space-y-6">
                                    <div>
                                        <label
                                            class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Name</label>
                                        <input v-model="form.name" type="text" required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Email</label>
                                        <input v-model="form.email" type="email" required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Subject</label>
                                        <input v-model="form.subject" type="text" required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Message</label>
                                        <textarea v-model="form.message" rows="4" required
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
                            <div class="aspect-video">
                                <!-- Responsive Google Maps iframe -->
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.8841502568707!2d101.46685459999999!3d3.125323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc512f9d5fa83b%3A0xcada717e2450a0cf!2sSyaliarisa%20Services%20Sdn%20Bhd%20%2F%20Syaliarisa%20Consultants!5e0!3m2!1sen!2smy!4v1755865606387!5m2!1sen!2smy"
                                    width="100%" height="100%" style="border:0;" allowfullscreen="true" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade" class="min-h-[400px]"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </AppLayout2>
    </div>
</template>
