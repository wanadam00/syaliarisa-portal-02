<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { reactive, onMounted, onBeforeUnmount } from 'vue';

interface HomeSection {
    id: number;
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
    if (confirm('Are you sure you want to delete this section?')) {
        router.delete(route('admin.home-sections.destroy', id), {
            onSuccess: () => {
                router.visit(route('admin.home-sections.index'));
            },
        });
    }
}

const { sections } = usePage().props as unknown as { sections: HomeSection[] };
sections.forEach(s => (s.showMenu = false));
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Home Management</h1>

            <!-- Create Button -->
            <div class="flex justify-end mb-4">
                <Link :href="route('admin.home-sections.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                + Create
                </Link>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Top Details</th>
                            <th class="px-4 py-3">Top Background</th>
                            <th class="px-4 py-3">Bottom Details</th>
                            <th class="px-4 py-3">Bottom Image</th>
                            <!-- <th class="px-4 py-3">Visible</th> -->
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(section, index) in sections" :key="section.id">
                            <td class="px-4 py-2">{{ index + 1 }}</td>
                            <td class="px-4 py-2 truncate">{{ section.top_details }}</td>
                            <td class="px-4 py-2">
                                <img v-if="section.top_image" :src="section.top_image" alt="Top Image"
                                    class="h-10 w-16 rounded object-cover" />
                                <span v-else class="italic text-gray-400">No image</span>
                            </td>
                            <td class="px-4 py-2 truncate">{{ section.bottom_details }}</td>
                            <td class="px-4 py-2">
                                <img v-if="section.bottom_image" :src="section.bottom_image" alt="Bottom Image"
                                    class="h-10 w-16 rounded object-cover" />
                                <span v-else class="italic text-gray-400">No image</span>
                            </td>
                            <!-- <td class="px-4 py-2">
                                <span
                                    :class="section.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ section.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td> -->
                            <td class="px-4 py-2 text-center relative">
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
                                    <div v-if="section.showMenu" :id="`dropdown-${section.id}`"
                                        class="absolute w-32 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50"
                                        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }">
                                        <a :href="`/admin/home/${section.id}/edit`"
                                            class="block px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Edit
                                        </a>
                                        <button @click="deleteSection(section.id)"
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
