<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';
import { reactive, onMounted, onBeforeUnmount } from 'vue';

interface Legislation {
    id: number;
    title: string;
    type: string;
    is_visible: boolean;
    showMenu?: boolean; // added for dropdown state
}

function deleteLegislation(id: number) {
    if (confirm('Are you sure you want to delete this legislation?')) {
        router.delete(route('admin.legislations.destroy', id), {
            onSuccess: () => {
                router.visit(route('admin.legislations.index'));
            },
        });
    } else {
        router.visit(route('admin.legislations.index'));
    }
}

const { legislations } = usePage().props as unknown as { legislations: Legislation[] };
legislations.forEach(e => (e.showMenu = false));

const dropdownPosition = reactive({ top: 0, left: 0 });
let activeLegislation: Legislation | null = null;

function toggleMenu(e: MouseEvent, legislation: Legislation) {
    if (activeLegislation && activeLegislation !== legislation) {
        activeLegislation.showMenu = false; // close previous
    }
    legislation.showMenu = !legislation.showMenu;
    activeLegislation = legislation.showMenu ? legislation : null;

    if (legislation.showMenu) {
        const rect = (e.currentTarget as HTMLElement).getBoundingClientRect();
        dropdownPosition.top = rect.bottom + window.scrollY;
        dropdownPosition.left = rect.right - 128 + window.scrollX; // 128px = dropdown width (w-32)
    }
}

// close when clicking outside
function handleClickOutside(event: MouseEvent) {
    if (activeLegislation && activeLegislation.showMenu) {
        const menuEl = document.getElementById(`dropdown-${activeLegislation.id}`);
        const btnEl = document.getElementById(`btn-${activeLegislation.id}`);

        if (
            menuEl &&
            !menuEl.contains(event.target as Node) &&
            btnEl &&
            !btnEl.contains(event.target as Node)
        ) {
            activeLegislation.showMenu = false;
            activeLegislation = null;
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
            <h1 class="text-2xl font-bold mb-6">Legislation Management</h1>
            <div class="flex justify-end mb-4">
                <a href="/admin/legislations/create"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    + Add
                </a>
            </div>
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Visible</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(legislation, index) in legislations" :key="legislation.id">
                            <td class="px-4 py-2">{{ index + 1 }}</td>
                            <td class="px-4 py-2">{{ legislation.title }}</td>
                            <td class="px-4 py-2">{{ legislation.type }}</td>
                            <td class="px-4 py-2">
                                <span
                                    :class="legislation.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ legislation.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center relative">
                                <!-- Vertical Dot Button -->
                                <button :id="`btn-${legislation.id}`" @click="toggleMenu($event, legislation)"
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
                                    <div v-if="legislation.showMenu" :id="`dropdown-${legislation.id}`"
                                        class="absolute w-32 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50"
                                        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }">
                                        <a :href="`/admin/legislations/${legislation.id}/edit`"
                                            class="block px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Edit
                                        </a>
                                        <button @click="deleteLegislation(legislation.id)"
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
