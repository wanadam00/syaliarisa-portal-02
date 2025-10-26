<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { reactive, onMounted, onBeforeUnmount } from 'vue';
import { Plus } from 'lucide-vue-next';
import Swal from 'sweetalert2'

interface HomeSection {
    id: number;
    title: string;
    top_details: string;
    top_image: string | null;
    bottom_details: string | null;
    bottom_image: string | null;
    // is_visible: boolean;
    showMenu?: boolean; // dropdown toggle
}

const dropdownPosition = reactive({ top: 0, left: 0 });
let activeSection: HomeSection | null = null;

function toggleMenu(e: MouseEvent, section: HomeSection) {
    if (activeSection && activeSection !== section) {
        activeSection.showMenu = false; // close previous
    }
    section.showMenu = !section.showMenu;
    activeSection = section.showMenu ? section : null;

    if (section.showMenu) {
        const rect = (e.currentTarget as HTMLElement).getBoundingClientRect();
        dropdownPosition.top = rect.bottom + window.scrollY;
        dropdownPosition.left = rect.right - 128 + window.scrollX; // dropdown aligned right-to-left
    }
}

// close when clicking outside
function handleClickOutside(event: MouseEvent) {
    if (activeSection && activeSection.showMenu) {
        const menuEl = document.getElementById(`dropdown-${activeSection.id}`);
        const btnEl = document.getElementById(`btn-${activeSection.id}`);

        if (
            menuEl &&
            !menuEl.contains(event.target as Node) &&
            btnEl &&
            !btnEl.contains(event.target as Node)
        ) {
            activeSection.showMenu = false;
            activeSection = null;
        }
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

function deleteSection(id: number) {
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
            router.delete(route('admin.home-sections.destroy', id), {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'The section has been deleted.', 'success')
                    router.visit(route('admin.home-sections.index'))
                },
            })
        }
    })
}

const { sections } = usePage().props as unknown as { sections: HomeSection[] };
sections.forEach(s => (s.showMenu = false));
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <!-- Add Button -->
            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex sm:items-center justify-between gap-4">
                    <div class="truncate">
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            Home Management
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Create and manage homepage sections</p>
                    </div>
                    <Link :href="route('admin.home-sections.create')"
                        class="group inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                    <Plus class="size-5 mr-0 sm:mr-2 transition-transform group-hover:rotate-90 duration-300" />
                    <span class="hidden sm:inline">New Section</span>
                    </Link>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-2xl border border-gray-200/50 dark:border-gray-700/50
         bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl shadow-lg">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-200/70 dark:from-gray-800/70 dark:to-gray-700/50
             text-gray-700 dark:text-gray-200 uppercase tracking-wide text-xs">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Top Details</th>
                            <th class="px-4 py-3">Top Background</th>
                            <th class="px-4 py-3">Bottom Details</th>
                            <th class="px-4 py-3">Bottom Image</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200/30 dark:divide-gray-700/30">
                        <tr v-for="(section, index) in sections" :key="section.id"
                            class="hover:bg-gray-100/50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">{{ index + 1 }}</td>
                            <td class="px-4 py-3 w-64">
                                <div class="line-clamp-1" :title="section.title">
                                    {{ section.title }}
                                </div>
                            </td>
                            <td class="px-4 py-3 w-64">
                                <div class="line-clamp-1" :title="section.top_details">
                                    {{ section.top_details }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <img v-if="section.top_image" :src="section.top_image" alt="Top Image"
                                    class="h-10 w-16 rounded-lg object-cover shadow" />
                                <span v-else class="italic text-gray-400">No image</span>
                            </td>
                            <td class="px-4 py-3 w-64">
                                <div class="line-clamp-1" :top_details="section.bottom_details">
                                    {{ section.bottom_details }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <img v-if="section.bottom_image" :src="section.bottom_image" alt="Bottom Image"
                                    class="h-10 w-16 rounded-lg object-cover shadow" />
                                <span v-else class="italic text-gray-400">No image</span>
                            </td>
                            <td class="px-4 py-3 text-center relative">
                                <!-- Vertical Dot Button -->
                                <button :id="`btn-${section.id}`" @click="toggleMenu($event, section)"
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
                                    <div v-if="section.showMenu" :id="`dropdown-${section.id}`" class="absolute w-36 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md
                     border border-gray-200/40 dark:border-gray-700/40 rounded-xl shadow-lg z-50
                     overflow-hidden animate-fade-in"
                                        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }">
                                        <a :href="`/admin/home/${section.id}/edit`"
                                            class="block px-4 py-2 text-left text-sm hover:bg-gray-100/70 dark:hover:bg-gray-700/70 transition">
                                            Edit
                                        </a>
                                        <button @click="deleteSection(section.id)"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100/70 dark:hover:bg-gray-700/70 transition">
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
