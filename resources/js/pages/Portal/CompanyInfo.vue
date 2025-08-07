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
        <!-- Page Header -->
        <section class="bg-[#2262ae] dark:bg-background dark:border-b py-12 text-white pt-32">
            <div class="container mx-auto px-4 text-center">
                <h1
                    class="text-4xl md:text-5xl font-bold mb-4  dark:text-white dark:drop-shadow-[0_0_8px_rgba(255,255,255,0.6)]">
                    Company Information</h1>
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
                    <div>
                        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Our Team Structure</h2>
                        <p class="mb-6 text-gray-700 dark:text-gray-300">Syaliarisa Services is built on a
                            foundation of
                            experienced professionals dedicated to environmental, health, and safety excellence.</p>

                        <!-- Leadership Team -->
                        <div class="mb-12" v-if="leadershipTeam.length > 0">
                            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Leadership Team</h3>
                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div v-for="member in leadershipTeam" :key="member.id"
                                    class="bg-white dark:bg-background p-6 rounded-lg shadow-md dark:shadow-white border border-gray-100 dark:border-white text-center">
                                    <div
                                        class="aspect-square bg-black dark:bg-white rounded-full w-32 mx-auto mb-4 overflow-hidden">
                                        <img v-if="member.photo" :src="member.photo" :alt="member.name"
                                            class="w-full h-full object-cover">
                                        <div v-else
                                            class="w-full h-full flex items-center justify-center text-gray-500 dark:text-gray-400">
                                            <svg class="size-24 text-gray-800 dark:text-white fill-white dark:fill-black"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <h4 class="text-xl font-medium mb-2 text-gray-900 dark:text-white">{{
                                        member.name }}
                                    </h4>
                                    <p class="text-primary dark:text-white font-medium mb-2">{{ member.position }} </p>
                                    <!-- <p class="text-gray-600 dark:text-gray-400 text-sm">Experienced professional in
                                            their field</p> -->
                                </div>
                            </div>
                        </div>

                        <!-- Departments -->
                        <div class="mb-8" v-if="departments.size > 0">
                            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Departments</h3>
                            <div class="space-y-4">
                                <div v-for="[dept, members] in departments" :key="dept"
                                    class="border-l-4 border-[#2262ae] dark:border-white pl-4 py-2 bg-white dark:bg-background rounded-r-lg shadow-md dark:shadow-white">
                                    <h4 class="text-xl font-medium text-gray-900 dark:text-white">{{ dept }}</h4>
                                    <p class="text-gray-600 dark:text-gray-400 mb-3">Our {{ dept.toLowerCase() }}
                                        team
                                        specializes in their respective fields.</p>

                                    <div class="grid sm:grid-cols-2 gap-4 mt-3">
                                        <div v-for="member in members" :key="member.id" class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden flex-shrink-0">
                                                <img v-if="member.photo" :src="member.photo" :alt="member.name"
                                                    class="w-full h-full object-cover">
                                                <div v-else
                                                    class="w-full h-full flex items-center justify-center text-gray-500 dark:text-gray-400">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ member.name
                                                    }}
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{
                                                    member.position
                                                    }}</p>
                                            </div>
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
