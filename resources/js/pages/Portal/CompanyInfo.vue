<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout2 from '@/layouts/AppLayout2.vue';

interface CompanyInfo {
    background: string;
    vision: string;
    mission: string;
    is_visible: boolean;
}
interface Employee {
    id: number;
    name: string;
    position: string;
    photo: string | null;
    department: string;
    is_visible: boolean;
}

const { companyInfo, employees } = usePage().props as unknown as {
    companyInfo: CompanyInfo;
    employees: Employee[];
};

// Group employees by department
const departments = computed(() => {
    const deptMap = new Map<string, Employee[]>();
    employees.forEach(employee => {
        if (!deptMap.has(employee.department)) {
            deptMap.set(employee.department, []);
        }
        deptMap.get(employee.department)?.push(employee);
    });
    return deptMap;
});

// Filter leadership team (you might need to adjust this based on your data)
const leadershipTeam = computed(() =>
    employees.filter(e => e.position.toLowerCase().includes('chief') ||
        e.position.toLowerCase().includes('director'))
);
</script>

<template>

    <Head title="Company Info" />
    <AppLayout2>
        <!-- Hero Section -->
        <section class="bg-[#2262ae] dark:bg-background dark:border-b py-12 text-white pt-32">
            <div class="container mx-auto px-4 text-center">
                <h1
                    class="text-4xl md:text-5xl font-bold mb-4 dark:text-white dark:drop-shadow-[0_0_8px_rgba(255,255,255,0.6)]">
                    Company Information
                </h1>
                <p class="text-lg opacity-90">
                    Discover our vision, mission, and organizational values.
                </p>
            </div>
        </section>

        <!-- Content Section -->
        <section class="py-16 ">
            <div class="container mx-auto px-4">
                <div class="flex flex-col">
                    <!-- Company Background -->
                    <div class="mb-12">
                        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">About Our Company</h2>
                        <p class="mb-6 text-gray-700 dark:text-gray-300">{{ companyInfo.background }}</p>

                        <div class="grid md:grid-cols-2 gap-6 mb-8">
                            <div
                                class="bg-white dark:bg-background p-6 rounded-lg shadow-md dark:shadow-white border border-gray-100 dark:border-white">
                                <h3 class="text-xl font-medium mb-4 text-primary dark:text-white">Our Mission
                                </h3>
                                <p class="text-gray-700 dark:text-gray-300">{{ companyInfo.mission }}</p>
                            </div>
                            <div
                                class="bg-white dark:bg-background p-6 rounded-lg shadow-md dark:shadow-white border border-gray-100 dark:border-white">
                                <h3 class="text-xl font-medium mb-4 text-primary dark:text-white">Our Vision
                                </h3>
                                <p class="text-gray-700 dark:text-gray-300">{{ companyInfo.vision }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Team Structure -->
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Our Team Structure</h2>

                        <!-- Leadership Team -->
                        <div class="flex justify-center gap-8 mb-12">
                            <div v-for="member in leadershipTeam" :key="member.id"
                                class="flex flex-col items-center relative">
                                <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-blue-600">
                                    <img v-if="member.photo" :src="member.photo" :alt="member.name"
                                        class="w-full h-full object-cover">
                                    <div v-else
                                        class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                        <i class="bi bi-person text-3xl"></i>
                                    </div>
                                </div>
                                <h4 class="mt-2 font-medium text-gray-900 dark:text-white">{{ member.name }}</h4>
                                <p class="text-sm text-primary dark:text-white">{{ member.position }}</p>
                            </div>
                        </div>

                        <!-- Departments -->
                        <div class="flex justify-center gap-12 flex-wrap">
                            <div v-for="[dept, members] in departments" :key="dept"
                                class="flex flex-col items-center relative">
                                <!-- Connector Line -->
                                <div class="w-px h-8 bg-blue-600 mb-4"></div>

                                <!-- Department Card -->
                                <div
                                    class="bg-white dark:bg-background rounded-lg p-4 shadow-md dark:shadow-white text-center min-w-[200px]">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">{{ dept }}</h4>
                                    <div class="space-y-2">
                                        <div v-for="member in members" :key="member.id"
                                            class="flex flex-col items-center gap-1">
                                            <div
                                                class="w-12 h-12 rounded-full overflow-hidden border border-gray-200 dark:border-gray-600">
                                                <img v-if="member.photo" :src="member.photo" :alt="member.name"
                                                    class="w-full h-full object-cover">
                                                <div v-else
                                                    class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            </div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ member.name
                                            }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ member.position }}
                                            </p>
                                        </div>
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
