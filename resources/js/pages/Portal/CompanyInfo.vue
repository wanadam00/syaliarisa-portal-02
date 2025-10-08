<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import AppLayout2 from '@/layouts/AppLayout2.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

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

// Only employees that are visible
const visibleEmployees = computed(() => employees.filter(e => e.is_visible));

// Organization hierarchy based on position
const organization = computed(() => {
    const findByPosition = (pos: string) =>
        visibleEmployees.value.filter(e => e.position.toLowerCase() === pos.toLowerCase());

    return {
        manager: findByPosition("Manager"),
        assistantManager: findByPosition("Assistant Manager"),
        chemists: findByPosition("Chemist"),
        adminExecutives: findByPosition("Admin Executive"),
        environmentalExecutives: findByPosition("Environmental Executive"),
        fieldExecutives: findByPosition("Field Executive"),
        fieldTechnicians: findByPosition("Field Technician"),
    };
});

// Example branch data (you can fetch this from Laravel later)
const clients = [
    { id: 1, name: "HQ - Kuala Lumpur", lat: 3.139, lng: 101.6869 },
    { id: 2, name: "Branch - Penang", lat: 5.4141, lng: 100.3288 },
    { id: 3, name: "Branch - Johor Bahru", lat: 1.4927, lng: 103.7414 },
];

onMounted(() => {
    const map = L.map('client-map').setView([3.139, 101.6869], 6); // default Malaysia view

    // OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add markers for each branch
    clients.forEach(client => {
        L.marker([client.lat, client.lng])
            .addTo(map)
        // .bindPopup(`<b>${client.name}</b>`);
    });
});

// Fix default icon paths
delete (L.Icon.Default.prototype as any)._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl: new URL('leaflet/dist/images/marker-icon.png', import.meta.url).toString(),
    iconUrl: new URL('leaflet/dist/images/marker-icon.png', import.meta.url).toString(),
    shadowUrl: new URL('leaflet/dist/images/marker-shadow.png', import.meta.url).toString(),
});

</script>

<template>

    <Head title="Company Info" />
    <AppLayout2>
        <!-- Hero Section -->
        <section
            class="bg-gradient-to-r from-[#2262ae] to-[#48b2e5] dark:bg-background dark:border-b py-12 text-white pt-32"
            data-aos="fade-down">
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
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="flex flex-col">
                    <!-- Company Background -->
                    <div class="mb-12" data-aos="fade-up">
                        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">About Us</h2>
                        <p class="mb-6 text-gray-700 text-justify dark:text-gray-300">{{ companyInfo.background }}</p>

                        <div class="grid md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-white dark:bg-background p-6 rounded-lg shadow-md dark:shadow-white border border-gray-100 dark:border-white"
                                data-aos="zoom-in">
                                <h3 class="text-xl font-medium mb-4 text-primary dark:text-white">Mission
                                </h3>
                                <p class="text-gray-700 dark:text-gray-300 text-justify">{{ companyInfo.mission }}</p>
                            </div>
                            <div class="bg-white dark:bg-background p-6 rounded-lg shadow-md dark:shadow-white border border-gray-100 dark:border-white"
                                data-aos="zoom-in">
                                <h3 class="text-xl font-medium mb-4 text-primary dark:text-white">Vision
                                </h3>
                                <p class="text-gray-700 dark:text-gray-300 text-justify">{{ companyInfo.vision }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Organization Structure -->
                    <div class="text-center mb-12" data-aos="fade-up">
                        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Team Structure</h2>

                        <!-- Manager -->
                        <div v-for="manager in organization.manager" :key="manager.id"
                            class="flex flex-col items-center" data-aos="fade-up">
                            <!-- Manager -->
                            <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-blue-600">
                                <img v-if="manager.photo" :src="manager.photo" :alt="manager.name"
                                    class="w-full h-full object-cover" />
                                <div v-else
                                    class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                                    <i class="bi bi-person text-3xl"></i>
                                </div>
                            </div>
                            <h4 class="mt-2 font-medium text-gray-900 dark:text-white">{{ manager.name }}</h4>
                            <p class="text-sm text-primary dark:text-white">{{ manager.position }}</p>

                            <!-- Line down -->
                            <div class="w-px h-8 bg-blue-600 my-4"></div>

                            <!-- Assistant Manager -->
                            <div v-for="am in organization.assistantManager" :key="am.id"
                                class="flex flex-col items-center" data-aos="fade-up">
                                <div class="w-28 h-28 rounded-full overflow-hidden border-2 border-green-600">
                                    <img v-if="am.photo" :src="am.photo" :alt="am.name"
                                        class="w-full h-full object-cover" />
                                    <div v-else
                                        class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                                        <i class="bi bi-person text-2xl"></i>
                                    </div>
                                </div>
                                <h4 class="mt-2 font-medium text-gray-900 dark:text-white">{{ am.name }}</h4>
                                <p class="text-sm text-primary dark:text-white">{{ am.position }}</p>

                                <!-- Under Assistant Manager -->
                                <div class="flex justify-center gap-8 mt-6 flex-wrap">
                                    <!-- Chemist -->
                                    <div v-for="c in organization.chemists" :key="c.id"
                                        class="flex flex-col items-center" data-aos="fade-up">
                                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-purple-600">
                                            <img v-if="c.photo" :src="c.photo" class="w-full h-full object-cover" />
                                            <div v-else
                                                class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                        <p class="text-sm mt-1 text-gray-900 dark:text-white">{{ c.name }}</p>
                                        <p class="text-xs text-gray-500">{{ c.position }}</p>
                                    </div>

                                    <!-- Admin Executive -->
                                    <div v-for="ae in organization.adminExecutives" :key="ae.id"
                                        class="flex flex-col items-center" data-aos="fade-up">
                                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-orange-600">
                                            <img v-if="ae.photo" :src="ae.photo" class="w-full h-full object-cover" />
                                            <div v-else
                                                class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                        <p class="text-sm mt-1 text-gray-900 dark:text-white">{{ ae.name }}</p>
                                        <p class="text-xs text-gray-500">{{ ae.position }}</p>
                                    </div>

                                    <!-- Environmental Executive + Field Techs -->
                                    <div v-for="ee in organization.environmentalExecutives" :key="ee.id"
                                        class="flex flex-col items-center" data-aos="fade-up">
                                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-teal-600">
                                            <img v-if="ee.photo" :src="ee.photo" class="w-full h-full object-cover" />
                                            <div v-else
                                                class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                        <p class="text-sm mt-1 text-gray-900 dark:text-white">{{ ee.name }}</p>
                                        <p class="text-xs text-gray-500">{{ ee.position }}</p>

                                        <!-- Under Environmental Executive -->
                                        <div class="mt-3 flex gap-4">
                                            <div v-for="ft in organization.fieldTechnicians" :key="ft.id"
                                                class="flex flex-col items-center">
                                                <div
                                                    class="w-16 h-16 rounded-full overflow-hidden border border-blue-400">
                                                    <img v-if="ft.photo" :src="ft.photo"
                                                        class="w-full h-full object-cover" />
                                                    <div v-else
                                                        class="w-full h-full flex items-center justify-center bg-gray-200">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                                <p class="text-xs mt-1 text-gray-900 dark:text-white">{{ ft.name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Field Executives + Field Techs -->
                                    <div v-for="fe in organization.fieldExecutives" :key="fe.id"
                                        class="flex flex-col items-center" data-aos="fade-up">
                                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-pink-600">
                                            <img v-if="fe.photo" :src="fe.photo" class="w-full h-full object-cover" />
                                            <div v-else
                                                class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                        <p class="text-sm mt-1 text-gray-900 dark:text-white">{{ fe.name }}</p>
                                        <p class="text-xs text-gray-500">{{ fe.position }}</p>

                                        <!-- Under Field Executive -->
                                        <div class="mt-3 flex gap-4">
                                            <div v-for="ft in organization.fieldTechnicians" :key="'fe-' + ft.id"
                                                class="flex flex-col items-center">
                                                <div
                                                    class="w-16 h-16 rounded-full overflow-hidden border border-blue-400">
                                                    <img v-if="ft.photo" :src="ft.photo"
                                                        class="w-full h-full object-cover" />
                                                    <div v-else
                                                        class="w-full h-full flex items-center justify-center bg-gray-200">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                                <p class="text-xs mt-1 text-gray-900 dark:text-white">{{ ft.name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mx-auto px-4" data-aos="fade-in">
                    <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white text-center">
                        Client Locations
                    </h2>
                    <div id="client-map" class="leaflet-container"></div>
                </div>
            </div>
        </section>
    </AppLayout2>
</template>
