<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';
import { reactive, onMounted, onBeforeUnmount } from 'vue';

interface CompanyInfo {
    id: number;
    background: string;
    vision: string;
    mission: string;
    is_visible: boolean;
    showMenu?: boolean; // Add showMenu property
}

// Get companyInfo from props
const { companyInfo } = usePage().props as unknown as { companyInfo: CompanyInfo[] };

// Initialize showMenu for each company info item
companyInfo.forEach(info => (info.showMenu = false));

const dropdownPosition = reactive({ top: 0, left: 0 });
let activeCompanyInfo: CompanyInfo | null = null;

function toggleMenu(e: MouseEvent, info: CompanyInfo) {
    if (activeCompanyInfo && activeCompanyInfo !== info) {
        activeCompanyInfo.showMenu = false; // close previous
    }
    info.showMenu = !info.showMenu;
    activeCompanyInfo = info.showMenu ? info : null;

    if (info.showMenu) {
        const rect = (e.currentTarget as HTMLElement).getBoundingClientRect();
        dropdownPosition.top = rect.bottom + window.scrollY;
        dropdownPosition.left = rect.right - 128 + window.scrollX; // 128px = dropdown width (w-32)
    }
}

// close when clicking outside
function handleClickOutside(event: MouseEvent) {
    if (activeCompanyInfo && activeCompanyInfo.showMenu) {
        const menuEl = document.getElementById(`dropdown-${activeCompanyInfo.id}`);
        const btnEl = document.getElementById(`btn-${activeCompanyInfo.id}`);

        if (
            menuEl &&
            !menuEl.contains(event.target as Node) &&
            btnEl &&
            !btnEl.contains(event.target as Node)
        ) {
            activeCompanyInfo.showMenu = false;
            activeCompanyInfo = null;
        }
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

function deleteCompany(id: number) {
    if (confirm('Are you sure you want to delete this company info?')) {
        router.delete(route('admin.company-info.destroy', id), {
            onSuccess: () => {
                router.visit(route('admin.company-info.index'));
            },
        });
    }
}
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Company Info Management</h1>

            <div class="flex justify-end mb-4">
                <a href="/admin/company-info/create"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    + Create
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Background</th>
                            <th class="px-4 py-3">Vision</th>
                            <th class="px-4 py-3">Mission</th>
                            <th class="px-4 py-3">Visible</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(info, index) in companyInfo" :key="info.id">
                            <td class="px-4 py-2">{{ index + 1 }}</td>
                            <td class="px-4 py-2 truncate">{{ info.background }}</td>
                            <td class="px-4 py-2 truncate">{{ info.vision }}</td>
                            <td class="px-4 py-2 truncate">{{ info.mission }}</td>
                            <td class="px-4 py-2">
                                <span
                                    :class="info.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ info.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center relative">
                                <!-- Vertical Dot Button -->
                                <button :id="`btn-${info.id}`" @click="toggleMenu($event, info)"
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
                                    <div v-if="info.showMenu" :id="`dropdown-${info.id}`"
                                        class="absolute w-32 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50"
                                        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }">
                                        <a :href="`/admin/company-info/${info.id}/edit`"
                                            class="block px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Edit
                                        </a>
                                        <button @click="deleteCompany(info.id)"
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
