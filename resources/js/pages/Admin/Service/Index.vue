<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';
import { reactive, onMounted, onBeforeUnmount } from 'vue';

interface Service {
    id: number;
    type: string;
    title: string;
    summary: string;
    details: string;
    image: string | null;
    is_visible: boolean;
    showMenu?: boolean; // added for dropdown state
}

const { services } = usePage().props as unknown as { services: Service[] };
services.forEach(e => (e.showMenu = false));

function deleteService(id: number) {
    if (confirm('Are you sure you want to delete this service?')) {
        router.delete(route('admin.services.destroy', id), {
            onSuccess: () => {
                router.visit(route('admin.services.index'));
            },
        });
    }
}

const dropdownPosition = reactive({ top: 0, left: 0 });
let activeService: Service | null = null;

function toggleMenu(e: MouseEvent, service: Service) {
    if (activeService && activeService !== service) {
        activeService.showMenu = false; // close previous
    }
    service.showMenu = !service.showMenu;
    activeService = service.showMenu ? service : null;

    if (service.showMenu) {
        const rect = (e.currentTarget as HTMLElement).getBoundingClientRect();
        dropdownPosition.top = rect.bottom + window.scrollY;
        dropdownPosition.left = rect.right - 128 + window.scrollX; // 128px = dropdown width (w-32)
    }
}

// close when clicking outside
function handleClickOutside(event: MouseEvent) {
    if (activeService && activeService.showMenu) {
        const menuEl = document.getElementById(`dropdown-${activeService.id}`);
        const btnEl = document.getElementById(`btn-${activeService.id}`);

        if (
            menuEl &&
            !menuEl.contains(event.target as Node) &&
            btnEl &&
            !btnEl.contains(event.target as Node)
        ) {
            activeService.showMenu = false;
            activeService = null;
        }
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

</script>

<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Services Management</h1>
            <div class="flex justify-end mb-4">
                <a href="/admin/services/create"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    + Add
                </a>
            </div>
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Title</th>
                            <!-- <th class="px-4 py-3">Photo</th> -->
                            <th class="px-4 py-3">Visible</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(service, index) in services" :key="service.id">
                            <td class="px-4 py-2">{{ index + 1 }}</td>
                            <td class="px-4 py-2 truncate">{{ service.type }}</td>
                            <td class="px-4 py-2 truncate">{{ service.title }}</td>
                            <!-- <td class="px-4 py-2">
                                <img v-if="service.image" :src="service.image" alt="Photo"
                                    class="h-10 w-10 rounded-full object-cover" />
                                <span v-else class="italic text-gray-400">No image</span>
                            </td> -->
                            <td class="px-4 py-2">
                                <span
                                    :class="service.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ service.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center relative">
                                <!-- Vertical Dot Button -->
                                <button :id="`btn-${service.id}`" @click="toggleMenu($event, service)"
                                    class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                    </svg>
                                </button>

                                <!-- Dropdown rendered outside -->
                                <Teleport to="body">
                                    <div v-if="service.showMenu" :id="`dropdown-${service.id}`"
                                        class="absolute w-32 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50"
                                        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }">
                                        <a :href="`/admin/services/${service.id}/edit`"
                                            class="block px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Edit
                                        </a>
                                        <button @click="deleteService(service.id)"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-gray-700">
                                            Delete
                                        </button>
                                    </div>
                                </Teleport>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
