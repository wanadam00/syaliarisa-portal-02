<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import PortalHeader from '@/components/PortalHeader.vue';
import PortalFooter from '@/components/PortalFooter.vue';

interface CompanyInfo {
    background: string;
    vision: string;
    mission: string;
    organization_structure: { position: string; name: string }[];
    is_visible: boolean;
}

const { companyInfo } = usePage().props as { companyInfo: CompanyInfo };
</script>

<template>

    <Head title="Company Info" />
    <div class="flex flex-col min-h-screen bg-gradient-to-br from-white to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <PortalHeader />

        <main class="flex-1 py-10 px-4 md:px-12">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-3xl font-bold mb-8 text-blue-900 dark:text-white">Company Information</h1>

                <!-- Background -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-primary dark:text-secondary mb-2">Background</h2>
                    <p class="text-gray-700 dark:text-gray-200">{{ companyInfo.background }}</p>
                </div>

                <!-- Vision -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-primary dark:text-secondary mb-2">Vision</h2>
                    <p class="text-gray-700 dark:text-gray-200">{{ companyInfo.vision }}</p>
                </div>

                <!-- Mission -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-primary dark:text-secondary mb-2">Mission</h2>
                    <p class="text-gray-700 dark:text-gray-200">{{ companyInfo.mission }}</p>
                </div>

                <!-- Organization Structure -->
                <div v-if="companyInfo.organization_structure?.length" class="mb-12">
                    <h2 class="text-xl font-semibold text-primary dark:text-secondary mb-4">Organization Structure</h2>
                    <ul class="space-y-4">
                        <li v-for="(member, index) in companyInfo.organization_structure" :key="index"
                            class="flex justify-between bg-white dark:bg-gray-800 p-4 rounded shadow-sm">
                            <span class="font-medium text-gray-800 dark:text-gray-100">{{ member.position }}</span>
                            <span class="text-gray-600 dark:text-gray-300">{{ member.name }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </main>

        <PortalFooter />
    </div>
</template>
