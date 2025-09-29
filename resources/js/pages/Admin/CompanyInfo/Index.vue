<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { reactive, onMounted, onBeforeUnmount } from 'vue';
import Swal from 'sweetalert2';
import { Plus } from 'lucide-vue-next';

interface CompanyInfo {
    id: number;
    background: string;
    vision: string;
    mission: string;
    is_visible: boolean;
    showMenu?: boolean;
}

const { companyInfo } = usePage().props as unknown as { companyInfo: CompanyInfo[] };
companyInfo.forEach(info => (info.showMenu = false));

const dropdownPosition = reactive({ top: 0, left: 0 });
let activeCompanyInfo: CompanyInfo | null = null;

function toggleMenu(e: MouseEvent, info: CompanyInfo) {
    if (activeCompanyInfo && activeCompanyInfo !== info) {
        activeCompanyInfo.showMenu = false;
    }
    info.showMenu = !info.showMenu;
    activeCompanyInfo = info.showMenu ? info : null;

    if (info.showMenu) {
        const rect = (e.currentTarget as HTMLElement).getBoundingClientRect();
        dropdownPosition.top = rect.bottom + window.scrollY;
        dropdownPosition.left = rect.right - 144 + window.scrollX; // 144px ≈ w-36
    }
}

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

onMounted(() => document.addEventListener('click', handleClickOutside));
onBeforeUnmount(() => document.removeEventListener('click', handleClickOutside));

function deleteCompany(id: number) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.company-info.destroy', id), {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'The company info has been deleted.', 'success')
                    router.visit(route('admin.company-info.index'))
                },
            })
        }
    })
}
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header + Add Button -->
            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex sm:items-center justify-between gap-4">
                    <div class="truncate">
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            Company Info Management
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Manage vision, mission & background</p>
                    </div>
                    <Link :href="route('admin.company-info.create')"
                        class="group inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                    <Plus class="size-5 mr-0 sm:mr-2 transition-transform group-hover:rotate-90 duration-300" />
                    <span class="hidden sm:inline">New Company Info</span>
                    </Link>
                </div>
            </div>

            <!-- Table -->
            <div
                class="overflow-x-auto rounded-2xl border border-gray-200/50 dark:border-gray-700/50 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl shadow-lg">
                <table class="min-w-full text-sm text-left">
                    <thead
                        class="bg-gradient-to-r from-gray-100/70 to-gray-200/50 dark:from-gray-800/70 dark:to-gray-700/50 text-gray-700 dark:text-gray-200 uppercase tracking-wide text-xs">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Background</th>
                            <th class="px-4 py-3">Vision</th>
                            <th class="px-4 py-3">Mission</th>
                            <th class="px-4 py-3">Visible</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200/30 dark:divide-gray-700/30">
                        <tr v-for="(info, index) in companyInfo" :key="info.id"
                            class="hover:bg-gray-100/50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">{{ index + 1 }}</td>
                            <td class="px-4 py-3 w-64">
                                <div class="line-clamp-1" :title="info.background">{{ info.background }}</div>
                            </td>
                            <td class="px-4 py-3 w-64">
                                <div class="line-clamp-1" :title="info.vision">{{ info.vision }}</div>
                            </td>
                            <td class="px-4 py-3 w-64">
                                <div class="line-clamp-1" :title="info.mission">{{ info.mission }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    :class="info.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ info.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center relative">
                                <!-- Menu Button -->
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

                                <!-- Dropdown -->
                                <Teleport to="body">
                                    <div v-if="info.showMenu" :id="`dropdown-${info.id}`"
                                        class="absolute w-36 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border border-gray-200/40 dark:border-gray-700/40 rounded-xl shadow-lg z-50 overflow-hidden animate-fade-in"
                                        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }">
                                        <a :href="`/admin/company-info/${info.id}/edit`"
                                            class="block px-4 py-2 text-left text-sm hover:bg-gray-100/70 dark:hover:bg-gray-700/70">
                                            Edit
                                        </a>
                                        <button @click="deleteCompany(info.id)"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100/70 dark:hover:bg-gray-700/70">
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
